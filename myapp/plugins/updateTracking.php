<?php

function reviewId($appId, $review) {
  return $appId . $review->getAuthorId() . $review->getCreationTime();
}

function updateTracking($db, $market, $email, $appId, $continue = true) {
  if (!($reviews = $market->getReviews($appId)))
    return 'Couldn\'t get reviews from the Android Market.';
  // todo continue get reviews pages
  $qReview = $db->prepare('INSERT INTO reviews(id, app_id, creationTime, author, text, rating) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE author=?, text=?, rating=?, creationTime=?');
  $qReviewTracker = $db->prepare('INSERT INTO reviews_tracker(review_id, `user`, `read`) VALUES(?, ?, false) ON DUPLICATE KEY UPDATE `read`=`read`');
  $newReviews = array();
  foreach ($reviews as $review) {
    $id = reviewId($appId, $review);
    try {
      $qReview->execute(array($id, $appId,
			      $review->getCreationTime(),
			      $review->getAuthorName(),
			      $review->getText(),
			      $review->getRating(),
			      $review->getAuthorName(),
			      $review->getText(),
			      $review->getRating(),
			      $review->getCreationTime(),
			      ));
      if ($qReview->rowCount() === 1)
	$newReviews[] = $review;
      $qReviewTracker->execute(array($id, $email));
    } catch (PDOException $e) { return $e; }
  }
  return $newReviews;
}

