<?php

class Login_Controller extends TinyMVC_Controller
{
  function index()
  {
    $this->view->assign('is_error', false);
    if (isset($_SESSION['AndroidMarket']))
      header('location: /index.php/apps/');

    if (isset($_POST['f_login_submit'])) {
      try {
	$market = new AndroidMarket($_POST['f_login_email'],
				    $_POST['f_login_password']);
      } catch (Exception $_) {
	$error = true;
	$this->view->assign('is_error', true);
      }
      if (!isset($error)) {
	$_SESSION['AndroidMarket'] = $market;
	header('location: /index.php/apps/');
      }
    }

    $this->view->display('template_header');
    $this->view->display('login_view');
    $this->view->display('template_footer');
  }
}

