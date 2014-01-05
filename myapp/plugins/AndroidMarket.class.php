<?php

$libpath = TMVC_MYAPPDIR . 'plugins/android-market-api-php/';

include_once($libpath . 'proto/protocolbuffers.inc.php');
include_once($libpath . 'proto/market.proto.php');
include_once($libpath . 'Market/MarketSession.php');

class AndroidMarket {

  private $session;
  private $email;

  public function __construct($email, $password) {
    $this->email = $email;
    $this->session = new MarketSession();
    $this->session->setAndroidId('317366BD797F0940');
    $this->session->setOperatorTmobile();
    if (!($this->session->login($email, $password)))
      throw new Exception('Could not connect');
  }

  public function getEmail() {
    return $this->email;
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
    $ar->setOrderType(AppsRequest_OrderType::POPULAR);
    $ar->setStartIndex($index);
    $ar->setEntriesCount($limit);
    $ar->setWithExtendedInfo($extend);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setAppsRequest($ar);
    try { $response = $this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
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

  public function getApp($appId, $extend = false, $iconPath = false) {
    $ar = new AppsRequest();
    $ar->setAppId($appId);
    $ar->setWithExtendedInfo($extend);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setAppsRequest($ar);
    try { $response = $this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $appsResponse = $rg->getAppsResponse();
      $apps = $appsResponse->getAppArray();
    }
    $app = $apps[0];
    if (!$app)
      return false;
    if ($iconPath) {
      $app->icon = $this->getAppIcon($app->getId(), $iconPath);
    }
    return $app;
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
    try { $response = $this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $commentsResponse = $rg->getCommentsResponse();
      if (!$commentsResponse)
	continue;
      $comments[] = $commentsResponse->getCommentsArray();
    }
    return $this->flatten($comments);
  }

}
