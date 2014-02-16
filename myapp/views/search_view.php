
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
	       <input type="text" value="<?= isset($searchQuery) ? $searchQuery : '' ?>" name="q" placeholder="Search..." class="form-control input-android">
	       <div class="pull-right">
		 <input type="submit" class="btn btn-android" value="Search">
	       </div>
	       <div class="subform">
		 <small>Play Store</small>
		 <select class="form-control" name="country">
		   <?php global $config; foreach ($config['api']['countries'] as $country_url => $country_name) { ?>
		   <option value="<?= $country_url ?>" style="background-image: url('/img/countries/<?= $country_url ?>.png');"
			   <?php if ((isset($searchCountry) && $country_url == $searchCountry)
				 || (!(isset($searchCountry)) && $country_url == $config['api']['default_country'])) {
				 echo ' selected';
				 } ?>><?= $country_name ?></option>
		   <?php } ?>
		 </select>
	       </div>
	     </form>
	   </div> <!-- col -->
	   <div class="col-md-8" id="searchResults">
	     <?= viewSearchApps($searchApps, $errorSearch) ?>
	   </div> <!-- col -->
	 </div> <!-- row -->
       </div><!-- panel-body -->
     </div> <!-- panel -->
