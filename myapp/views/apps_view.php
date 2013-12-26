
<div class="view_app">
  <?php function viewApp($app) { ?>
  <?php if (empty($app)) { ?>
  <div class="panel panel-default app">
    <div class="panel-heading">
      <h3>Add a new App to track here</h3>
    </div> <!-- panel-heading -->
    <div class="panel-body">
      <a href="#add-app" class="addapp">+</a>
    </div> <!-- panel-body -->
  </div> <!-- panel -->
  <?php } else { ?>
  <div class="panel panel-android app">
    <div class="panel-heading">
      <h3>
	<img src="/img/appsicons/<?= $app['icon'] ?>" alt="<?= $app['title'] ?>">
	<?= $app['title'] ?>
      </h3>
    </div> <!-- panel-heading -->
    <div class="panel-body">
      <details>
	<summary><?= summary($app['description'], 100) ?></summary>
	<p>
	  <?= $app['description'] ?>
      </details>
      <!-- <span class="label label-default"><?= $app['creator'] ?></span> -->
      <!-- <span class="label label-warning"><?= $app['rating'] ?></span> -->
      <form method="post" id="f_track" class="buttons">
	<div class="<?= $app['unread'] ? 'unread' : 'read' ?> pull-right">
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
	</div>
	<input type="hidden" name="f_track_id" value="<?= $app['id'] ?>">
	<button type="submit" name="f_track_submit" class="btn btn-android">
	  <i class="fa fa-<?= $app['tracked'] ? 'stop' : 'play' ?>"></i>
	  <?= $app['tracked'] ? 'Stop' : 'Start' ?> Tracking
	</button>
	<a href="/index.php/apps/reviews?id=<?= $app['id'] ?>" class="btn btn-android">Browse reviews</a>
      </form>
    </div> <!-- panel-body -->
  </div> <!-- panel -->
  <?php } ?>
  <?php } ?>
  <?php
     $per_line = 3;
     $extra = $per_line - (count($tracked) % $per_line);
     for ($i = 0; $i < $extra; $i++) {
		       $tracked[] = array();
		       }
		       ?>
     <?php viewGrid($per_line, $tracked, viewApp); ?>
     
     <span id="add-app" href="#" class="anchor"></span>
     <div class="panel panel-android">
       <div class="panel-heading">
	 <h3>
	   <i class="fa fa-android fa-lg"></i>
	   Search for Apps
	   <?= isset($searchQuery) ? ': <span id="searchQuery">'.$searchQuery.'</span>' : '' ?>
	 </h3>
       </div> <!-- panel-heading -->
       <div class="panel-body">
	 <div class="row">
	   <div class="col-md-4">
	     <form method="get" id="f_search" class="big_search form-group" action="#add-app">
	       <?php if (empty($tracked) || !count($tracked)) { ?>
	       <p>
		 You're not tracking any app yet. Start tracking your favorite apps today by searching for them!
	       </p>
	       <?php } ?>
	       <input type="text" value="" name="q" placeholder="Search..." class="form-control input-android">
	       <div class="pull-right">
		 <input type="submit" class="btn btn-android" value="Search">
	       </div>
	     </form>
	   </div> <!-- col -->
	   <div class="col-md-8" id="searchResults">
	     <?= viewSearchApps($searchApps, $errorSearch) ?>
	   </div> <!-- col -->
	 </div> <!-- row -->
       </div><!-- panel-body -->
     </div> <!-- panel -->
</div> <!-- view_app -->
