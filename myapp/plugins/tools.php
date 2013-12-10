<?php

$urls = array(
	      'login' => '/index.php/login/',
	      'apps' => '/index.php/apps/',
	      'reviews' => '/index.php/apps/reviews?id=',
	      );

function	getMarket() {
  return $_SESSION['AndroidMarket'];
}

function	checkLogin() {
  global $urls;
  if (!getMarket()) {
    header('location: ' . $urls['login']);
  }
}

function	redirectsApp($appId) {
  global $urls;
  header('location: ' . $urls['reviews'] . $appId);
}

function	redirectsApps() {
  global $urls;
  header('location: ' . $urls['apps']);
}

function protect($string) {
  return (htmlspecialchars(stripslashes($string)));
}
