<?php

class Settings_Controller extends TinyMVC_Controller
{
  function index()
  {
    checkLogin();
    $market = getMarket();
    $this->load->model('Apps_Model', 'appmodel');
    $this->load->model('Users_Model', 'usermodel');
    $email = $_SESSION['email'];

    // Delete account
    if (isset($_POST['f_delete'])) {
      if ($this->usermodel->deleteAccount($email)) {
	session_destroy();
	header('location: /login/');
      }
    }

    // Get tracked Apps
    if (($tracked = $this->appmodel->getTracked($email)) === false)
      $errors[] = $this->appmodel->lastError;

    $this->view->assign('page', 'settings');
    $this->view->assign('errors', $errors);
    $this->view->assign('tracked', $tracked);
    $this->view->display('template_header');
    $this->view->assign('email', $email);
    $this->view->display('template_menu');
    $this->view->display('settings_view');
    $this->view->display('modals_view');
    $this->view->display('template_footer');
  }
}
