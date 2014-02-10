
<div class="row">
  <div class="col-md-3">
    <div class="single_app">
      <h3 class="single_app_header">
	<img src="<?= $app['icon'] ?>" alt="<?= $app['title'] ?>">
	<span class="visible-xs visible-sm pull-right">
	  <?= $app['title'] ?>
	</span>
      </h3> <!-- single_app_header -->
      <div class="padded">
	<form method="post" id="f_track">
	  <?php if ($isTracked) { ?>
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
	  <?php } ?>
	  <button type="submit" name="f_track_submit" class="btn btn-android <?= $isTracked ? 'stop' : 'start' ?>">
	    <i class="fa fa-<?= $isTracked ? 'stop' : 'play' ?>"></i>
	    <?= $isTracked ? 'Stop' : 'Start' ?> Tracking
	  </button>
	  <br>
	  <?php if ($isTracked) { ?>
	  <input type="submit" name="f_mark_all_read" class="btn btn-default btn-xs btn-lg"
		 value="Mark all as read">
	  <?php } ?>
	  <br>
	  <?php foreach ($countries as $country) { ?>
	  <img src="/img/countries/<?= $country ?>.png" alt="<?= $country ?>" />
	  <?php } ?>
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
	    <span id="packageName"><?= $app['packageName'] ?></span>
	  </li>
	  <li><i class="fa-li fa fa-trophy"></i>
	    <b>Rating</b>
	    <?= round($app['rating'], 1) ?>
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
      <?= $app['title'] ?>
      <div class="btn-toolbar pull-right">
	<div class="btn-group">
	  <button class="btn btn-default active" id="list_view">
	    <i class="fa fa-th"></i></button>
	  <button class="btn btn-default"
		  <?= $isTracked ? '' : 'disabled="disabled"' ?> id="one_view">
	    <i class="fa fa-youtube-play"></i></button>
	</div> <!-- btn-group -->
	<?php if ($isTracked) { ?>
	<div class="btn-group filter">
	  <button class="btn btn-default active" id="view_unread">
	    <i class="fa fa-envelope"></i></button>
	  <button class="btn btn-default" id="view_all">
	    <i class="fa fa-th-large"></i></button>
	  <button class="btn btn-default" id="view_read">
	    <i class="fa fa-envelope-o"></i></button>
	</div> <!-- btn-group -->
	<?php } ?>
      </div> <!-- btn-toolbar -->
    </h3> <!-- single_app_header -->
    <?php viewReviews($reviews, $isTracked, $errorsReviews, $app['packageName']); ?>
  </div> <!-- col -->
</div> <!-- row -->
