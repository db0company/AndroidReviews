  <body>
    <?php viewModals() ?>
    <header class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
	  <h1>
	    <a href="/">
	      <span>A</span>ndroid <span>R</span>eviews <span>M</span>anager
	    </a>
            <small>&beta;eta</small>
	  </h1>
        </div> <!-- navbar-header -->
        <div class="navbar-collapse collapse">
	  <form method="get" id="f_search" action="<?= getUrl('search') ?>" class="search navbar-form navbar-left" role="search">
            <div class="form-group">
	      <input class="form-control" type="text" name="q" placeholder="Search...">
            </div>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
          </form>
	  <div class="email nav navbar-nav navbar-right">
	    <?= $email ?>
	    -
	    <a href="<?= getUrl('logout') ?>">Sign out</a>
	  </div> <!-- email -->
        </div><!-- navbar-collapse -->
      </div> <!-- container -->
    </header>
    <div class="row divider">
      <div class="col-sm-1">
	<nav class="navbar-default">
	  <a href="<?= getUrl('apps') ?>"<?= $page == 'apps' ? ' class="active"' : ''?>>
	    <i class="fa fa-android"></i></a>
	  <a href="<?= getUrl('search') ?>"<?= $page == 'search' ? ' class="active"' : ''?>>
	    <i class="fa fa-plus"></i></a>
	  <a href="<?= getUrl('help') ?>"<?= $page == 'help' ? ' class="active"' : ''?>>
	    <i class="fa fa-question"></i></a>
	  <a href="<?= getUrl('logout') ?>">
	    <i class="fa fa-power-off"></i></a>
	  <?php foreach ($tracked as $trackedApp) { ?>
	  <a href="<?= getUrl('reviews'),
		   $trackedApp['id'] ?>"<?= $page == 'app' && $trackedApp['id'] == $app['id'] ?
					    ' class="active"' : ''?>>
	    <img src="<?= $trackedApp['icon'] ?>" alt="<?= $trackedApp['title'] ?>"></a>
	  <?php } ?>
	</nav>
      </div> <!-- col -->
      <div class="col-sm-11">
	<main>
	  <div class="row"><div class="col-md-12">
	      <?php if (!empty($errors)) { ?>
	      <?php   foreach ($errors as $error) { ?>
	      <?php     viewAlert('danger', $error); ?>
	      <?php   } ?>
	      <?php } ?>
