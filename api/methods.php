<?php
//
// Made by        db0
// Contact        db0company@gmail.com
// Website        http://db0.fr/
// Repo           https://github.com/db0company/generic-api
//

include_once('AndroidMarket.class.php');

function getMarket() {
  global $conf;
  if (!isset($_SESSION['market']) || !$_SESSION['market']) {
    try {
      $_SESSION['market'] = new AndroidMarket($conf['market']['email'],
					      $conf['market']['pass'],
					      $conf['market']['androidid']);
    } catch (Exception $s) {
      throw new Exception(500);
    }
  }
  return $_SESSION['market'];
}

$methods =
    array(

	  array('type'            => 'GET',
		'resource'        => 'apps',
		'one'             => false,
		'required_params' => array('query' => 'string'),
		'optional_params' => array('extended' => false,
					   'index' => 0,
					   'limit' => 10,
					   'tracked_apps' => ''),
		'function'        => function($resource, $_, $params) {
		  $market = getMarket();
		  global $conf;
		  if (!($apps = $market->searchApps($params['query'], $params['extended'],
						    $conf['icons_path'],
						    explode(',', $params['tracked_apps']),
						    $params['index'], $params['limit'])))
		    throw new Exception (500);
		  return $apps;
		},
		'response'        => 'the apps',
		'doc' => 'Search through apps',
              ),

	  array('type'            => 'GET',
		'resource'        => 'app',
		'one'             => false,
		'required_params' => array('app_id' => 'string'),
		'optional_params' => array('extended' => false,
					   'tracked' => false),
		'function'        => function($resource, $_, $params) {
		  $market = getMarket();
		  global $conf;
		  if (!($app = $market->getApp($params['app_id'], $params['extended'],
					       $conf['icons_path'], $params['tracked'])))
		    throw new Exception (500);
		  return $app;
		},
		'response'        => 'the app',
		'doc' => 'Get the details of an app',
              ),

	  array('type'            => 'GET',
		'resource'        => 'reviews',
		'one'             => false,
		'required_params' => array('app_id' => 'string'),
		'optional_params' => array('limit' => 10),
		'function'        => function($resource, $_, $params) {
		  $market = getMarket();
		  if (!($reviews = $market->getReviews($params['app_id'], $params['limit'])))
		    throw new Exception (500);
		  return $reviews;
		},
		'response'        => 'the reviews',
		'doc' => 'Get the reviews of an app',
              ),

          );

include_once("include.php");
$methods = setDefault($methods);
