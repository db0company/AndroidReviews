<?php

class Apps_Controller extends TinyMVC_Controller
{

  function index()
  {
    if (!isset($_SESSION['AndroidMarket'])) {
      header('location: /index.php/login/');
      return ;
    }
    $market = $_SESSION['AndroidMarket'];

    if (!empty($_GET['q'])) {
      $search_results = $market->searchApps($_GET['q'], false,
					    getcwd() . '/img/appsicons/');
    }

    $this->view->display('template_header');
    $this->view->display('apps_view');
    if (isset($search_results)) {
      $this->view->assign('apps', $search_results);
      $this->view->display('apps_search_view');
    }
    $this->view->display('template_footer');
  }
}

