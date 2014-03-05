<?php

class Apps_Controller extends TinyMVC_Controller {

  function index() {
    checkLogin();
    $market = getMarket();
    $email = $_SESSION['email'];
    $this->load->model('Apps_Model', 'appmodel');

    // Start/Stop Tracking
    if (isset($_POST['f_track_submit'])
        && !empty($_POST['f_track_id'])
	&& !($this->appmodel->switchTracking($market, $email,
					     protect($_POST['f_track_id']),
					     redirectsApp)))
      $errors[] = $this->appmodel->lastError;


    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($email)) === false)
      $errors[] = $this->appmodel->lastError;

    // Get tracked Apps
    if (($tracked_to_display = $this->appmodel->getTracked($email, 'view_unread')) === false)
      $errors[] = $this->appmodel->lastError;

    // View
    $this->view->assign('page', 'apps');
    $this->view->assign('errors', $errors);
    $this->view->assign('tracked', $tracked);
    $this->view->assign('tracked_to_display', $tracked_to_display);
    $this->view->display('template_header');
    $this->view->assign('email', $email);
    $this->view->display('template_menu');
    $this->view->display('apps_view');
    $this->view->assign('js', 'apps');
    $this->view->display('modals_view');
    $this->view->display('template_footer');
  }

  ///////////////////////////////////////////////

  function search() {
    checkLogin();
    $market = getMarket();
    $email = $_SESSION['email'];
    $this->load->model('Apps_Model', 'appmodel');
    $query = protect($_GET['q']);
    $country = protect($_GET['country']);
    $market->country = $country;

    // Start/Stop Tracking
    if (isset($_POST['f_track_submit'])
        && !empty($_POST['f_track_id'])
	&& !($this->appmodel->switchTracking($market, $email,
					     protect($_POST['f_track_id']),
					     redirectsApp)))
      $errors[] = $this->appmodel->lastError;

    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($email)) === false)
      $errors[] = $this->appmodel->lastError;

    // Get Search results
    if (!empty($query)
	&& !($searchApps = $this->appmodel->searchApps($market,
						       $query,
						       $tracked)))
      $errorSearch[] = $this->appmodel->lastError;

    // View
    $this->view->assign('page', 'search');
    $this->view->assign('errors', $errors);
    $this->view->assign('tracked', $tracked);
    $this->view->display('template_header');
    $this->view->assign('email', $email);
    $this->view->display('template_menu');
    if (isset($searchApps)) {
      $this->view->assign('searchCountry', $country);
      $this->view->assign('searchQuery', $query);
      $this->view->assign('searchApps', $searchApps);
      $this->view->assign('errorSearch', $errorSearch);
    }
    $this->view->display('search_view');
    $this->view->assign('js', 'apps');
    $this->view->assign('js', 'search');
    $this->view->display('modals_view');
    $this->view->display('template_footer');
  }

  ///////////////////////////////////////////////

  function reviews() {
    checkLogin();
    if (empty($_GET['id']))
      redirectsApps();

    // Load market, model, data
    $appId = $_GET['id'];
    $market = getMarket();
    $email = $_SESSION['email'];
    $this->load->model('Apps_Model', 'appmodel');

    // Mark all as read
    if (isset($_POST['f_mark_all_read'])
	&& !($this->appmodel->markAllRead($appId, $email)))
      $errors[] = $this->appmodel->lastError;

    // Start/Stop Tracking
    if (isset($_POST['f_track_submit'])
	&& !($this->appmodel->switchTracking($market, $email, $appId,
					     null, redirectsApps)))
      $errors[] = $this->appmodel->lastError;

    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($email)) === false)
      $errors[] = $this->appmodel->lastError;

    // Is tracked?
    $isTracked = $this->appmodel->isTracking($email, $appId);

    // Mark Review as read / unread
    if (isset($_POST['f_read_id'])) {
      $id = protect($_POST['f_read_id']);
      if ((isset($_POST['f_read_read'])
	   && !($this->appmodel->markReviewAsRead($id, $email)))
	  || (isset($_POST['f_read_unread'])
	      && !($this->appmodel->markReviewAsUnread($id, $email))))
	$errors[] = $this->appmodel->lastError;
    }

    // Get App
    if (!($app = $this->appmodel->getApp($market, $email, $appId, $isTracked))) {
      $errors[] = $this->appmodel->lastError;
      $noapp = true;
    }

    else {

      // Get countries
      if (($countries = $this->appmodel->getCountries($appId)) === false)
	$errors[] = $this->appmodel->lastError;

      // Get Reviews
      if (($reviews = $this->appmodel->getReviews($market, $appId, $email, 'view_unread')) === false)
	$errorsReviews[] = $this->appmodel->lastError;

      // CanReply
      $canReply = true; // todo$app && $app['contactEmail'] == $email;
    }

    // View
    $this->view->assign('page', 'app');
    $this->view->assign('errors', $errors);
    $this->view->assign('errorsReviews', $errorsReviews);
    $this->view->assign('app', $app);
    $this->view->assign('isTracked', $isTracked);
    $this->view->assign('tracked', $tracked);
    $this->view->assign('canReply', $canReply);
    $this->view->assign('countries', $countries);
    $this->view->assign('reviews', $reviews);
    $this->view->assign('email', $email);
    $this->view->display('template_header');
    $this->view->display('template_menu');
    if (!$noapp)
      $this->view->display('reviews_view');
    $this->view->assign('js', 'reviews');
    $this->view->display('modals_view');
    $this->view->display('template_footer');
  }
}

