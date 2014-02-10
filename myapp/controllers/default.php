<?php

class Default_Controller extends TinyMVC_Controller
{
  function index()
  {
    if (isset($_SESSION['AndroidMarket'])) {
      header('location: /apps/');
    } else {
      header('location: /login/');
    }
  }
}
