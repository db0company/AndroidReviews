<?php

class Apps_Model extends TinyMVC_Model {

  var $lastError;

  private function setError($msg) {
    $this->lastError = $msg;
    return false;
  }

  private function flatten($array) {
    $finalArray = array();
    foreach ($array as $subArray) {
      foreach ($subArray as $value) {
	$finalArray[] = $value;
      }
    }
    return $finalArray;
  }

  private function reviewId($appId, $review) {
    return reviewId($appId, $review);
  }

  private function trackedToId($trackedApps) {
    $ids = array();
    foreach ($trackedApps as $app)
      $ids[] = $app['id'];
    return $ids;
  }

  function searchApps($market, $query, $trackedApps, $index = 0, $limit = 9) {
    if (!($apps = $market->searchApps($query, false, $index, $limit, $this->trackedToId($trackedApps))))
      return $this->setError('Couldn\'t get apps from the Android Market API.');
    return $apps;
  }

  private function getTotalUnread($appId, $email) {
    $c = $this->db->query_one('SELECT count(*) AS unread FROM reviews_tracker AS t JOIN reviews AS r WHERE r.id=t.review_id AND t.user=? AND t.`read`=false AND r.app_id=?',
			      array($email, $appId));
    return $c['unread'];
  }

  function getTracked($email, $filter = 'view_all') {
    try {
      $tracked = $this->db->query_all('SELECT a.*, true AS tracked FROM apps_tracker AS t JOIN apps AS a WHERE a.id=t.app_id AND t.user=?',
				      array($email));
      foreach ($tracked as $idx => $app)
	$tracked[$idx]['unread'] = $this->getTotalUnread($app['id'], $email);
      usort($tracked, function($app1, $app2) { return $app2['unread'] - $app1['unread']; });
      if ($filter == 'view_unread')
	$ttracked = array_filter($tracked, function($app) { return $app['unread'] != 0; });
      elseif ($filter == 'view_read')
	$ttracked = array_filter($tracked, function($app) { return $app['unread'] == 0; });
      else
	$ttracked = $tracked;
      $tracked = $ttracked;
    } catch (Exception $e) { return $this->setError($e); }
    return $tracked;
  }

  function isTracking($email, $appId) {
    try {
      $track = $this->db->query_one('SELECT * FROM apps_tracker WHERE user=? AND app_id=?',
				    array($email, $appId));
    } catch (Exception $e) { return $this->setError($e); }
    return !empty($track);
  }

  function updateTracking($market, $email, $appId, $continue = true) {
    if (is_string($err = updateTracking($this->db->pdo, $market, $email, $appId, $continue)))
      return $this->setError($err);
    return true;
  }

  private function addApp($app) {
    if (!$app)
      return $this->setError('Couldn\'t get the app on the Android Market.');
    $qApp = $this->db->pdo->prepare('INSERT INTO apps(id, packageName, title, icon, creator, rating, ratingsCount, description, contactEmail) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE packageName=?, title=?, icon=?, creator=?, rating=?, ratingsCount=?, description=?, contactEmail=?');
    try { $qApp->execute(array(
			       $app['id'],
			       $app['packageName'],
			       $app['title'],
			       $app['icon'],
			       $app['creator'],
			       $app['rating'],
			       $app['ratingsCount'],
			       $app['description'],
			       $app['contactEmail'],
			       $app['packageName'],
			       $app['title'],
			       $app['icon'],
			       $app['creator'],
			       $app['rating'],
			       $app['ratingsCount'],
			       $app['description'],
			       $app['contactEmail'],
			       ));
    } catch (PDOException $e) { return $this->setError(PdoGetMessage($qApp)); }
    return true;
  }

  public function getApp($market, $email, $appId, $tracked, $update = false) {
    if (!$update) {
      try { // Get from DB
	$app = $this->db->query_one('SELECT *, ? AS tracked FROM apps WHERE id=?',
				    array($tracked, $appId));
	if ($tracked)
	  $app['unread'] = $this->getTotalUnread($appId, $email);
      } catch (Exception $e) { return $this->setError($e); }
      if ($app)
	return $app;
    }
    if (!($app = $market->getApp($appId, true, $tracked)))
      return $this->setError('Couldn\'t get the app on the Android Market.');
    return $app;
  }

  public function updateApp($market, $appId) {
    global $config;
    $old_country = $market->country;
    foreach ($config['api']['countries'] as $country) {
      $market->country = $country;
      if ($this->addApp($market->getApp($appId, true), $country) === true) {
	// add in valid countries
	try {
	  $q = $this->db->pdo->prepare('INSERT INTO apps_countries(app_id, country) VALUES(?, ?) ON DUPLICATE KEY UPDATE country=country');
	  $q->execute(array($appId, $country));
	} catch (PDOException $e) { return $this->setError($e); }
      }
    }
    $market->country = $old_country;
    return true;
  }

  private function startTracking($market, $email, $appId) {
    $this->updateApp($market, $appId);
    try {
      $qTracking = $this->db->pdo->prepare('INSERT INTO apps_tracker(user, app_id) VALUES(?, ?)');
      $qTracking->execute(array($email, $appId));
    } catch (PDOException $e) { return $this->setError($e); }
    $this->updateTracking($market, $email, $appId, false);
    return true;
  }

  private function stopTracking($email, $appId) {
    try { // Get reviews of this app
      $reviews = $this->db->query_all('SELECT id FROM reviews WHERE app_id=?',
				      array($appId));
    } catch (Exception $e) { return $this->setError($e); }
    foreach ($reviews as $review) {
      try { // Remove review tracking
	$this->db->where('review_id=? AND user=?', array($review['id'], $email));
	$this->db->delete('reviews_tracker');
      } catch (Exception $e) { return $this->setError($e); }
    }
    try { // Remove app tracking
      $this->db->where('user=? AND app_id=?', array($email, $appId));
      $this->db->delete('apps_tracker');
    } catch (Exception $e) { return $this->setError($e); }
    return true;
  }

  function switchTracking($market, $email, $appId,
			  $callbackOnStart = null, $callbackOnStop = null) {
    if (!($this->isTracking($email, $appId))) {
      if (!($this->startTracking($market, $email, $appId)))
	return false;
      if ($callbackOnStart)
	$callbackOnStart($appId);
      return true;
    }
    if (!($this->stopTracking($email, $appId)))
      return false;
    if ($callbackOnStop)
      $callbackOnStop($appId);
    return true;
  }

  function getCountries($appId) {
    try { // Get from DB
      $dbCountries = $this->db->query_all('SELECT * FROM apps_countries WHERE app_id=?',
					  array($appId));
    } catch (Exception $e) { return $this->setError($e); }
    $countries = array();
    foreach ($dbCountries as $country) {
      $countries[] = $country['country'];
    }
    return $countries;
  }

  function getReviews($market, $appId, $email, $filter = 'view_all') {
    if ($this->isTracking($email, $appId)) {
      if (!$this->updateTracking($market, $email, $appId))
	return false;
      try {
	$reviews = $this->db->query_all('SELECT r.*, t.read FROM reviews AS r JOIN reviews_tracker AS t WHERE r.app_id=? AND r.id=t.review_id AND t.user=? ORDER BY t.read, r.creationTime',
					array($appId, $email));
      if ($filter == 'view_unread')
	$reviews = array_filter($reviews, function($review) { return !$review['read']; });
      elseif ($filter == 'view_read')
	$reviews = array_filter($reviews, function($review) { return $review['read']; });
      } catch (Exception $e) { return $this->setError($e); }
      return $reviews;
    }
    return $market->getReviews($appId);
  }

  private function markReview($reviewId, $email, $status) {
    $qMark = $this->db->pdo->prepare('UPDATE reviews_tracker SET `read`=? WHERE review_id=? AND user=?');
    try { $qMark->execute(array($status, $reviewId, $email));
    } catch (PDOException $e) { return $this->setError($e); }
    if (!$qMark->rowCount())
      return false;
    return true;
  }

  function markReviewAsRead($reviewId, $email) {
    return $this->markReview($reviewId, $email, true);
  }

  function markReviewAsUnread($reviewId, $email) {
    return $this->markReview($reviewId, $email, false);
  }

  function markAllRead($appId, $email) {
    try {
      $reviews = $this->db->query_all('SELECT * from reviews WHERE app_id=?',
				      array($appId));
      foreach ($reviews as $review) {
	$qMark = $this->db->pdo->prepare('UPDATE reviews_tracker SET `read`=true WHERE review_id=? AND user=?');
	$qMark->execute(array($review['id'], $email));
      }
    } catch (PDOException $e) { return $this->setError($e); }
    return true;
  }
}
