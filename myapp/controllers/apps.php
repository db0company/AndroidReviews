<?php

class Apps_Controller extends TinyMVC_Controller {

  function index() {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $query = protect($_GET['q']);
    $iconPath = getcwd() . '/img/appsicons/';

    // Start/Stop Tracking
    if (isset($_POST['f_track_submit'])
        && !empty($_POST['f_track_id'])
	&& !($this->appmodel->switchTracking($market, $market->getEmail(),
					     protect($_POST['f_track_id']),
					     $iconPath,
					     redirectsApp)))
      $errors[] = $this->appmodel->lastError;

    // Get tracked Apps
    if (!($tracked = $this->appmodel->getTracked($market->getEmail())))
      $errors[] = $this->appmodel->lastError;

    // Get Search results
    if (!empty($query)
	&& !($searchApps = $this->appmodel->searchApps($market,
						       $query,
						       $tracked,
						       $iconPath)))
      $errors[] = $this->appmodel->lastError;

    // View
    $this->view->assign('errors', $errors);
    $this->view->assign('trackedApps', $tracked);
    $this->view->display('template_header');
    $this->view->display('template_menu');
    if (isset($searchApps)) {
      $this->view->assign('searchQuery', $query);
      $this->view->assign('searchApps', $searchApps);
      $this->view->display('apps_search_view');
    }
    $this->view->display('apps_view');
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
    $email = $market->getEmail();
    $this->load->model('Apps_Model', 'appmodel');
    $iconPath = getcwd() . '/img/appsicons/';

    // Start/Stop Tracking
    if (isset($_POST['f_track_submit'])
	&& !($this->appmodel->switchTracking($market, $email, $appId, $iconPath,
					     null, redirectsApps)))
	$errors[] = $this->appmodel->lastError;

    $tracked = $this->appmodel->isTracking($email, $appId);

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
    if (!($app = $this->appmodel->getApp($market, $appId, $tracked, $iconPath)))
      $errors[] = $this->appmodel->lastError;

    // Get Reviews
    if (!($reviews = $this->appmodel->getReviews($market, $appId, $email)))
      $errors[] = $this->appmodel->lastError;

    // CanReply
    $canReply = true; // todo$app && $app['contactEmail'] == $email;

    // View
    $this->view->assign('errors', $errors);
    $this->view->assign('app', $app);
    $this->view->assign('tracked', $tracked);
    $this->view->assign('canReply', $canReply);
    $this->view->assign('reviews', $reviews);
    $this->view->display('template_header');
    $this->view->display('template_menu');
    $this->view->display('reviews_view');
    $this->view->display('template_footer');
  }
}

