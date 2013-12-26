
<div class="row">
  <div class="col-md-3">
    <div class="single_app">
      <h3 class="single_app_header">
	<img src="/img/appsicons/<?= $app['icon'] ?>" alt="<?= $app['title'] ?>">
	<span class="visible-xs visible-sm pull-right">
	  <?= $app['title'] ?>
	</span>
      </h3> <!-- single_app_header -->
      <div class="padded">
	<form method="post" id="f_track">
	  <div class="counter <?= $app['unread'] ? 'unread' : 'read' ?> pull-right">
	    <a href="<?= getUrl('reviews'), $app['id'] ?>">
	      <div class="hexagon">
		<?= $app['unread'] ?
		    $app['unread']
		    : '<i class="fa fa-envelope-o"></i>'
		    ?>
	      </div>
	    </a>
	    <?php if ($app['unread']) { ?>
	    <span class="fa-stack bubble">
	      <i class="fa fa-comment fa-stack-2x"></i>
	      <i class="fa fa-envelope fa-stack-1x"></i>
	    </span>
	    <?php } ?>
	  </div> <!-- read/unread -->
	  <button type="submit" name="f_track_submit" class="btn btn-android btn-lg">
	    <i class="fa fa-<?= $isTracked ? 'stop' : 'play' ?>"></i>
	    <?= $isTracked ? 'Stop' : 'Start' ?> Tracking
	  </button>
	  <input type="submit" name="f_mark_all_read" class="btn btn-default btn-xs btn-lg"
		 value="Mark all as read">
	</form>
	<ul class="fa-ul">
	  <li class="hidden-xs hidden-sm"><i class="fa-li fa fa-file-text-o"></i><b>Description</b></li>
	</ul>
	<details class="hidden-xs hidden-sm">
	  <summary><?= summary($app['description'], 100) ?></summary>
	  <p>
	    <?= $app['description'] ?>
	  </p>
	</details>
	<ul class="fa-ul">
	  <li class="hidden-xs hidden-sm"><i class="fa-li fa fa-inbox"></i>
	    <b>Package</b>
	    <?= $app['packageName'] ?>
	  </li>
	  <li><i class="fa-li fa fa-trophy"></i>
	    <b>Rating</b>
	    <?= $app['rating'] ?>
	    <br>
	    <br>
	    <?php viewRatingStars($app['rating']); ?>
	  </li>
	  <li class="hidden-xs hidden-sm"><i class="fa-li fa fa-hand-o-right"></i>
	    <b>Total votes</b>
	    <?= $app['ratingsCount'] ?>
	  </li>
	  <li class="hidden-xs hidden-sm"><i class="fa-li fa fa-user"></i>
	    <b>Creator</b>
	    <?= $app['creator'] ?>
	  </li>
	  <li class="hidden-xs hidden-sm"><i class="fa-li fa fa-envelope"></i>
	    <b>Contact Email</b>
	    <a href="mailto:<?= $app['contactEmail'] ?>">
	      <?= $app['contactEmail'] ?>
	    </a>
	  </li>
	</ul>
      </div> <!-- padded -->
    </div> <!-- single_app -->
  </div> <!-- col -->
  <div class="col-md-9">
    <h3 class="single_app_header hidden-xs hidden-sm">
      <div class="row">
	<div class="col-md-10">
	  <?= $app['title'] ?>
	</div> <!-- col -->
	<div class="col-md-2">
	  <button class="btn btn-default<?= $isTracked ? ' active' : '' ?>"
              <?= $isTracked ? '' : 'disabled="disabled"' ?> id="one_view">
	    <i class="fa fa-youtube-play"></i></button>
	  <button class="btn btn-default<?= $isTracked ? '' : ' active' ?>" id="list_view">
	    <i class="fa fa-th"></i></button>
	</div> <!-- col -->
      </div> <!-- row -->
    </h3> <!-- single_app_header -->
    <div class="reviews <?= $isTracked ? 'one_view' : 'list_view' ?>">
      <?php
    if (!empty($errorsReviews)) {
      foreach ($errorsReviews as $error) {
	viewAlert('danger', $error);
      }
    } elseif (empty($reviews) || !count($reviews)) {
      viewAlert('android', 'No reviews found');
    } else { ?>

      <?php function viewReview($review, $array, $idx) {
      $isTracked = $array[0];
      $app = $array[1];
      $reviews = $array[2];
       ?>
      <div class="review panel panel-default" id="<?= idToValid($review['id']) ?>">
	<div class="panel-body">
	  <div>
	    <div class="pull-right date">
	      <?= date('d M Y H:i', $review['creationTime'] / 1000) ?>
	    </div> <!-- pull-right -->
	    <p class="text-android"><?= $review['author'] ?></p>
	  </div>
	  <?php if (preg_match('/^([^\t]+)\t(.+)$/', $review['text'], $reviewContent)) { ?>
	  <p><strong><?= $reviewContent[1] ?></strong></p>
	  <p><?= $reviewContent[2] ?></p>
	  <? } else { ?>
	  <p><?= $review['text'] ?></p>
	  <? } ?>
	  <div class="pull-right rating">
	    <?php viewRatingStars($review['rating']); ?>
	  </div>
	</div> <!-- panel-body -->
	<div class="panel-footer">
	  <?php if ($isTracked && isset($review['read'])) { ?>
	  <form method="post" class="f_read">
	    <input type="hidden" name="f_read_id" value="<?= $review['id'] ?>">
	    <div class="pull-right">
	      <button type="submit" name="f_read_<?= $review['read'] ? 'unread' : 'read' ?>"
		      class="btn btn-default <?= $review['read'] ? 'read' : 'unread' ?>">
		<i class="fa fa-<?= $review['read'] ? 'square' : 'check-square' ?>-o"></i>
		<span>Mark as <?= $review['read'] ? 'unread' : 'read' ?></span>
	      </button>
	      <a href="https://play.google.com/apps/publish#ReviewsPlace:p=<?= $app['packageName'] ?>"
		 target="_blank" class="btn btn-default">
		<i class="fa fa-reply"></i>
		Reply
	      </a>
	      <button class="btn btn-android next">
		<span class="hidden id_next"><?= idToValid($reviews[$idx + 1]['id']) ?></span>
		<i class="fa fa-chevron-right"></i>
	      </button>
	    </div> <!-- text-right -->
	    <button class="btn btn-default<?= $idx ? '' : ' invisible' ?> prev">
	      <span class="hidden id_prev"><?= idToValid($reviews[$idx - 1]['id']) ?></span>
	      <i class="fa fa-chevron-left"></i>
	    </button>
	  </form>
	  <?php } ?>
	</div> <!-- panel-footer -->
      </div> <!-- panel -->
      <?php } ?>
      <?php if ($isTracked) { ?>
      <div class="start_reading panel panel-android">
	<div class="panel-body">
	  <div class="play js_start">
	    <i class="fa fa-youtube-play"></i>
	  </div>
	</div> <!-- panel-body -->
	<div class="panel-footer">
	  <div class="text-right">
	    <button class="btn btn-android js_start">
	      <i class="fa fa-play-circle"></i>
	      Start Reading Reviews
	    </button>
	  </div> <!-- text-right -->
	</div> <!-- panel-footer -->
      </div> <!-- panel -->
      <?php } ?>
      <?php viewGrid(2, $reviews, viewReview, array($isTracked, $app, $reviews)); ?>
      <?php } ?>
    </div> <!-- reviews -->
  </div> <!-- col -->
</div> <!-- row -->
