<?php

class Ajax_Controller extends TinyMVC_Controller {

  function search() {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $query = protect($_GET['q']);
    $index = intval($_GET['index']);

    // Get tracked Apps
    if (!($tracked = $this->appmodel->getTracked($market->getEmail())))
      $errors[] = $this->appmodel->lastError;

    // Get Search results
    if (!empty($query)
	&& !($searchApps = $this->appmodel->searchApps($market,
						       $query,
						       $tracked,
						       getIconPath(),
						       $index)))
      $errorSearch[] = $this->appmodel->lastError;

    viewSearchApps($searchApps, $errorSearch, $index);
  }

  function markread() {
    $reviewId = protect($_GET['r']);
    checkLogin();
    $market = getMarket();
    $email = $market->getEmail();
    $this->load->model('Apps_Model', 'appmodel');    
    echo ($this->appmodel->markReviewAsRead($reviewId, $email) ?
	  'true' : 'false');
  }
  
  function markunread() {
    $reviewId = protect($_GET['r']);
    checkLogin();
    $market = getMarket();
    $email = $market->getEmail();
    $this->load->model('Apps_Model', 'appmodel');    
    echo ($this->appmodel->markReviewAsUnread($reviewId, $email) ?
	  'true' : 'false');
  }

  function trackedapps() {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $filter = protect($_GET['filter']);

    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($market->getEmail(),
						$filter)) === false)
      $errors[] = $this->appmodel->lastError;

    viewTrackedApps($tracked);
  }

  function reviews() {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $appId = protect($_GET['appId']);
    $filter = protect($_GET['filter']);
    $packageName = protect($_GET['packageName']);
    $viewStyle = protect($_GET['viewStyle']);

    // Get Reviews
    if (!($reviews = $this->appmodel->getReviews($market, $appId, $market->getEmail(), $filter)))
      $errors[] = $this->appmodel->lastError;

    // Is tracked?
    $isTracked = $this->appmodel->isTracking($market->getEmail(), $appId);

    viewReviews($reviews, $isTracked, $errors, $packageName, $viewStyle);
  }

  function switchtrack() {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $appId = protect($_GET['appId']);

    global $switchTrackResult;

    // Start/Stop Tracking
    if (!($this->appmodel->switchTracking($market, $market->getEmail(),
					  $appId, getIconPath(),
					  function() {
					    global $switchTrackResult;
					    $switchTrackResult = 'start';
					  }, function() {
					    global $switchTrackResult;
					    $switchTrackResult = 'stop';
					  })))
      $switchTrackResult = 'error';

    // Get App
    if (!($app = $this->appmodel->getApp($market, $market->getEmail(), $appId,
					 $switchTrackResult == 'start' ? true : false,
					 getIconPath())))
      $switchTrackResult = 'error';
    
    echo json_encode(array('status' => $switchTrackResult,
			   'app' => $app));
  }

}

$switchTrackResult = '';
