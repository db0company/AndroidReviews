
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
