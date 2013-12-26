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

}
