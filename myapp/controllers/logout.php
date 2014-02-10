<?php

class Logout_Controller extends TinyMVC_Controller {

  function index() {
    session_destroy();
    header('location: /login/');
  }
}
