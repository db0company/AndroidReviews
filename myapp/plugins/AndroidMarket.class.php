<?php

$libpath = TMVC_MYAPPDIR . 'plugins/android-market-api-php/';

include_once($libpath . 'proto/protocolbuffers.inc.php');
include_once($libpath . 'proto/market.proto.php');
include_once($libpath . 'Market/MarketSession.php');

class AndroidMarket {

  private $session;

  public function __construct($email, $password) {
    $this->session = new MarketSession();
    $this->session->setAndroidId('00000000000000001');
    $this->session->login($email, $password);
    if (!$this->session)
      throw new Exception('Could not connect');
  }

  private function flatten($array) {
    $finalArray = array();
    foreach ($array as $subArray) {
      foreach ($subArray as $value) {
	$finalArray[] = $value;
      }
    }
    return $finalArray;
  }

  public function searchApps($query, $extend = false, $iconPath = false,
			     $index = 0, $limit = 10) {
    $ar = new AppsRequest();
    $ar->setQuery($query);
    $ar->setStartIndex($index);
    $ar->setEntriesCount($limit);
    $ar->setWithExtendedInfo($extend);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setAppsRequest($ar);
    $response = $this->session->execute($reqGroup);
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $appsResponse = $rg->getAppsResponse();
      $apps[] = $appsResponse->getAppArray();
    }
    $apps = $this->flatten($apps);
    if ($iconPath) {
      foreach ($apps as $app) {
	$app->icon = $this->getAppIcon($app->getId(), $iconPath);
      }
    }
    return $apps;
  }

  public function getAppIcon($appId, $path, $overwrite = false) {
    $filename = 'icon_' . $appId . '.png';
    $path = $path . '/' . $filename;
    if (!$overwrite && file_exists($path))
      return $filename;
    $gir = new GetImageRequest();
    $gir->setImageUsage(GetImageRequest_AppImageUsage::ICON);
    $gir->setAppId($appId);
    $gir->setImageId(1);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setImageRequest($gir);
    try { $response = $this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    $imageResponse = $groups[0]->getImageResponse();
    $filename = 'icon_' . $appId . '.png';
    if (file_put_contents($path,
			  $imageResponse->getImageData()) === false)
      return false;
    return $filename;
  }

  public function getReviews($appId, $limit = 10) {
    $cr = new CommentsRequest();
    $cr->setAppId($appId);
    $cr->setEntriesCount($limit);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setCommentsRequest($cr);
    $response = $this->session->execute($reqGroup);
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $commentsResponse = $rg->getCommentsResponse();
      $comments[] = $commentsResponse->getCommentsArray();
    }
    return $this->flatten($comments);
  }

}
