<?php

function reviewId($appId, $review) {
  return idToValid($appId . $review['authorId'] . $review['creationTime']);
}

function getCountries($db, $appId) {
  try {
    $dbCountries = $db->prepare('SELECT * FROM apps_countries WHERE app_id=?');
    $dbCountries->execute(array($appId));
    $countries = array();
    while ($country = $dbCountries->fetch())
      $countries[] = $country['country'];
  } catch (Exception $e) { return false; }
  return $countries;
}

function updateTracking($db, $market, $email, $appId, $continue = true, $all = false) {
  if (!($countries = getCountries($db, $appId)))
    return 'Couldn\'t get app countries';

  $newReviews = array();
  $qReview = $db->prepare('INSERT INTO reviews(id, app_id, creationTime, author, text, rating) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE author=?, text=?, rating=?, creationTime=?');
  $qReviewCheck = $db->prepare('SELECT review_id FROM reviews_tracker WHERE review_id=? AND `user`=?');
  $qReviewTracker = $db->prepare('INSERT INTO reviews_tracker(review_id, `user`, `read`) VALUES(?, ?, false) ON DUPLICATE KEY UPDATE `read`=`read`');

  $old_country = $market->country;
  foreach ($countries as $country) {
    $market->country = $country;

    if (($reviews = $market->getReviews($appId)) === false)
      continue;

    foreach ($reviews as $review) {
      $id = reviewId($appId, $review);
      try {
	$qReview->execute(array($id, $appId,
				$review['creationTime'],
				$review['author'],
				$review['text'],
				$review['rating'],
				$review['author'],
				$review['text'],
				$review['rating'],
				$review['creationTime'],
				));
	$qReviewCheck->execute(array($id, $email));
	if ($all || !$qReviewCheck->rowCount()) { // review not tracked
	  $newReviews[] = $review;
	  $qReviewTracker->execute(array($id, $email));
	}
      } catch (PDOException $e) { return $e; }
    }
  }
  $market->country = $old_country;
  return $newReviews;
}
