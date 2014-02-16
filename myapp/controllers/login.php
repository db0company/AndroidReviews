<?php

class Login_Controller extends TinyMVC_Controller
{

  private function login($email) {
    $_SESSION['email'] = $email;
    $_SESSION['AndroidMarket'] = new AndroidMarket();
    redirectsApps();
  }

  function index() {
    $this->load->model('Users_Model', 'usermodel');

    // Already logged in?
    if (isset($_SESSION['email'])
	&& isset($_SESSION['AndroidMarket']))
      redirectsApps();

    // Forms validation
    if (isset($_POST['f_create_account_submit'])
	|| isset($_POST['f_login_submit'])) {
      $email1 = $this->usermodel->createAccountForm();
      $email2 = $email = $this->usermodel->loginForm();
      if ($email1 === false || $email2 === false)
	$errors[] = $this->usermodel->lastError;
      else {
	$email = is_string($email1) ? $email1 : $email2;
	// Set session
	if (!isset($error))
	  $this->login($email);
      }
    }

    // Forgot password forms
    if (isset($_POST['f_forgot_email'])) {
      if ($this->usermodel->forgotPassword())
	$this->view->assign('email_sent', true);
      else
	$errors[] = $this->usermodel->lastError;
    }
    // Reset password
    if (isset($_POST['f_reset_email'])) {
      if ($this->usermodel->resetPassword())
	$this->login(protect($_POST['f_reset_email']));
      else
	$errors[] = $this->usermodel->lastError;
    }

    // Views
    $this->view->assign('errors', $errors);
    $this->view->display('template_header');
    $this->view->display('login_view');
    $this->view->display('template_footer_login');
  }
}

