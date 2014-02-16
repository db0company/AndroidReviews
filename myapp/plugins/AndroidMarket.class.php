<?php

include_once(__DIR__.'/../configs/config_database.php');

class AndroidMarket {

  var $format = 'json';
  public $country;

  private function url() {
    global $config;
    if (!array_key_exists($this->country, $config['api']['countries']))
      $this->country = $config['api']['default_country'];
    return 'http://'.$this->country.'.'.$config['api']['base_url'];
  }

  public function searchApps($query, $extended = false,
  			     $index = 0, $limit = 10,
  			     $tracked = array()) {
    $r = consume($this->url(),
		 'apps', $this->format,
		 'GET', null,
		 array('query' => $query,
		       'extended' => $extended,
		       'index' => $index,
		       'limit' => $limit,
		       'tracked_apps' => implode(',', $tracked)));
    return $r;
  }

  public function getApp($appId, $extended = false, $tracked = false) {
    $r = consume($this->url(),
		 'app', $this->format,
		 'GET', null,
		 array('app_id' => $appId,
		       'extended' => $extended,
		       'tracked' => $tracked));
    return $r;
  }

  public function getReviews($appId, $limit = 10) {
    $r = consume($this->url(),
		 'reviews', $this->format,
		 'GET', null,
		 array('app_id' => $appId,
		       'limit' => $limit));
    return $r;
  }

}
