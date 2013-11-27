<?php

class Default_Controller extends TinyMVC_Controller
{
  function index()
  {
    if (isset($_SESSION['AndroidMarket'])) {
      header('location: /index.php/apps/');
    } else {
      header('location: /index.php/login/');
    }
  }
}
