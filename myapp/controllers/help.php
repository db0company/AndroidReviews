<?php

class Help_Controller extends TinyMVC_Controller
{
  function index()
  {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $email = $_SESSION['email'];

    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($email)) === false)
      $errors[] = $this->appmodel->lastError;

    $this->view->assign('page', 'help');
    $this->view->assign('errors', $errors);
    $this->view->assign('tracked', $tracked);
    $this->view->display('template_header');
    $this->view->assign('email', $email);
    $this->view->display('template_menu');
    $this->view->display('help_view');
    $this->view->display('template_footer');
  }
}
