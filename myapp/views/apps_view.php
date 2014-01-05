
<div class="view_app">
  <div class="big_title">
    <div class="buttons btn-group">
      <a class="btn btn-default active" id="view_all">
	<i class="fa fa-th"></i></a>
      <a class="btn btn-default" id="view_unread">
	<i class="fa fa-envelope"></i></a>
      <a class="btn btn-default" id="view_read">
	<i class="fa fa-envelope-o"></i></a>
    </div> <!-- pull-right -->
    <h3>Track your favorite apps: never miss a review again!</h3>
  </div> <!-- text-right -->
  <?php viewTrackedApps($tracked); ?>

</div> <!-- view_app -->
