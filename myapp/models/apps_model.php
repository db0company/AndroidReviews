<?php

class Apps_Model extends TinyMVC_Model {

  var $lastError;

  private function flatten($array) {
    $finalArray = array();
    foreach ($array as $subArray) {
      foreach ($subArray as $value) {
	$finalArray[] = $value;
      }
    }
    return $finalArray;
  }

  private function reviewToDbLike($appId, $review) {
    return array('app_id' => $appId,
		 'creationTime' => $review->getCreationTime(),
		 'author' => $review->getAuthorName(),
		 'text' => $review->getText(),
		 'rating' => $review->getRating());
  }

  private function setError($msg) {
    $this->lastError = $msg;
    return false;
  }

  private function reviewsToDbLike($appId, $reviewsObjects) {
    if (!$reviewsObjects)
      return $this->setError('Could\'nt get reviews on the Android Market.');
    $reviews = array();
    foreach ($reviewsObjects as $reviewObject)
      $reviews[] = $this->reviewToDbLike($appId, $reviewObject);
    return $reviews;
  }

  private function appToDbLike_isTracked($tracked, $app) {
    foreach ($tracked as $trackedApp) {
      if ($trackedApp['id'] == $app->getId())
	return true;
    }
    return false;
  }

  private function appToDbLike($app, $tracked = false) {
    $ext = $app->getExtendedInfo();
    return array('id' => $app->getId(),
		 'title' => $app->getTitle(),
		 'icon' => $app->icon,
		 'creator' => $app->getCreator(),
		 'rating' => $app->getRating(),
		 'ratingsCount' => $app->getRatingsCount(),
		 'description' => ($ext ? $ext->getDescription() : null),
		 'contactEmail' => ($ext ? $ext->getContactEmail() : null),
		 'packageName' => $app->getPackageName(),
		 'tracked' => $tracked,
		 );
  }

  private function appsToDbLike($appsObjects, $trackedApps) {
    if (!$appsObjects)
      return $this->setError('Could\'nt get apps from the Android Market.');
    $apps = array();
    foreach ($appsObjects as $appObject)
      $apps[] = $this->appToDbLike($appObject,
				   $this->appToDbLike_isTracked($trackedApps, $appObject));
    return $apps;
  }


  private function reviewId($appId, $review) {
    return reviewId($appId, $review);
  }

  function searchApps($market, $query, $trackedApps, $iconPath = false, $index = 0, $limit = 9) {
    return $this->appsToDbLike($market->searchApps($query, false, $iconPath, $index, $limit),
			       $trackedApps);
  }

  private function getTotalUnread($appId, $email) {
    $c = $this->db->query_one('SELECT count(*) AS unread FROM reviews_tracker AS t JOIN reviews AS r WHERE r.id=t.review_id AND t.user=? AND t.`read`=false AND r.app_id=?',
			      array($email, $appId));
    return $c['unread'];
  }

  function getTracked($email) {
    try {
      $tracked = $this->db->query_all('SELECT a.*, true AS tracked FROM apps_tracker AS t JOIN apps AS a WHERE a.id=t.app_id AND t.user=?',
				      array($email));
      foreach ($tracked as $idx => $app) {
	$tracked[$idx]['unread'] = $this->getTotalUnread($app['id'], $email);
	usort($tracked, function($app1, $app2) { return $app2['unread'] - $app1['unread']; });
      }
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
			       $app->getId(),
			       $app->getPackageName(),
			       $app->getTitle(),
			       $app->icon,
			       $app->getCreator(),
			       $app->getRating(),
			       $app->getRatingsCount(),
			       $app->getExtendedInfo()->getDescription(),
			       $app->getExtendedInfo()->getContactEmail(),
			       $app->getPackageName(),
			       $app->getTitle(),
			       $app->icon,
			       $app->getCreator(),
			       $app->getRating(),
			       $app->getRatingsCount(),
			       $app->getExtendedInfo()->getDescription(),
			       $app->getExtendedInfo()->getContactEmail(),
			       ));
    } catch (PDOException $e) { return $this->setError($e->getMessage); }
    return true;
  }

  public function getApp($market, $email, $appId, $tracked, $iconPath = false, $update = false) {
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
    if (!($app = $market->getApp($appId, true, $iconPath)))
      return $this->setError('Couldn\'t get the app on the Android Market.');
    return $this->appToDbLike($app, $tracked);
  }

  public function updateApp($market, $appId, $iconPath = false) {
    return $this->addApp($market->getApp($appId, true, $iconPath));
  }

  private function startTracking($market, $email, $appId, $iconPath = false) {
    if (!($this->updateApp($market, $appId, $iconPath)))
      return false;
    try {
      $qTracking = $this->db->pdo->prepare('INSERT INTO apps_tracker(user, app_id) VALUES(?, ?)');
      $qTracking->execute(array($email, $appId));
    } catch (PDOException $e) { return $this->setError($e); }
    if (!($this->updateTracking($market, $email, $appId, false)))
      return false;
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

  function switchTracking($market, $email, $appId, $iconPath = false,
			  $callbackOnStart = null, $callbackOnStop = null) {
    if (!($this->isTracking($email, $appId))) {
      if (!($this->startTracking($market, $email, $appId, $iconPath)))
	return false;
      if ($callbackOnStart)
	$callbackOnStart($appId);
      return true;
    }
    if (!($this->stopTracking($email, $appId)))
      return false;
    if ($callbackOnStop)
      $callbackOnStop();
    return true;
  }

  function getReviews($market, $appId, $email) {
    if ($this->isTracking($email, $appId)) {
      if (!$this->updateTracking($market, $email, $appId))
	return false;
      try {
	$reviews = $this->db->query_all('SELECT r.*, t.read FROM reviews AS r JOIN reviews_tracker AS t WHERE r.app_id=? AND r.id=t.review_id AND t.user=? ORDER BY t.read, r.creationTime',
					array($appId, $email));
      } catch (Exception $e) { return $this->setError($e); }
      return $reviews;
    }
    return $this->reviewsToDbLike($appId, $market->getReviews($appId));
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
