<?php

$libpath = 'android-market-api-php/';

include_once($libpath . 'proto/protocolbuffers.inc.php');
include_once($libpath . 'proto/market.proto.php');
include_once($libpath . 'Market/MarketSession.php');

class AndroidMarket {

  private $session;
  private $email;

  public function __construct($email, $password, $androidid) {
    $this->email = $email;
    $this->session = new MarketSession();
    $this->session->setAndroidId($androidid);
    $this->session->setOperatorTmobile();
    if (!($this->session->login($email, $password)))
      throw new Exception('Could not connect to the Android Market');
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

  private function appToArray_isTracked($tracked, $app) {
    foreach ($tracked as $trackedApp) {
      if ($trackedApp == $app->getId())
	return true;
    }
    return false;
  }

  private function appToArray($app, $tracked = false) {
    $ext = $app->getExtendedInfo();
    return array('id' => $app->getId(),
		 'title' => $app->getTitle(),
		 'icon' => $app->icon,
		 'creator' => $app->getCreator(),
		 'rating' => $app->getRating(),
		 'ratingsCount' => $app->getRatingsCount(),
		 'description' => ($ext ? $ext->getDescription() : null),
		 'contactEmail' => ($ext ? $ext->getContactEmail() : null),
		 'packageName' => $app->getPackageName(),
		 'tracked' => $tracked
		 );
  }

  private function appsToArrays($appsObjects, $trackedApps) {
    if (!$appsObjects)
      throw new Exception(500);
    $apps = array();
    foreach ($appsObjects as $appObject)
      $apps[] = $this->appToArray($appObject,
				   $this->appToArray_isTracked($trackedApps, $appObject));
    return $apps;
  }

  public function searchApps($query, $extend = false, $iconPath = false,
			     $tracked_apps = array(),
			     $index = 0, $limit = 10) {
    $ar = new AppsRequest();
    $ar->setQuery($query);
    $ar->setOrderType(AppsRequest_OrderType::POPULAR);
    $ar->setStartIndex($index);
    $ar->setEntriesCount($limit);
    $ar->setWithExtendedInfo($extend);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setAppsRequest($ar);
    try { $response = @$this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $appsResponse = $rg->getAppsResponse();
      $apps[] = $appsResponse->getAppArray();
    }
    $apps = $this->flatten($apps);
    $apps = $this->appsToArrays($apps, $tracked_apps);
    if ($iconPath) {
      foreach ($apps as $i => $app) {
    	$apps[$i]['icon'] = $this->getAppIcon($app['id'], $iconPath);
      }
    }
    return $apps;
  }

  public function getApp($appId, $extend = false, $iconPath = false, $tracked = false) {
    $ar = new AppsRequest();
    $ar->setAppId($appId);
    $ar->setWithExtendedInfo($extend);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setAppsRequest($ar);
    try { $response = @$this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $appsResponse = $rg->getAppsResponse();
      $apps = $appsResponse->getAppArray();
    }
    $app = $apps[0];
    if (!$app)
      return false;
    $app = $this->appToArray($app, $tracked);
    if ($iconPath) {
      $app['icon'] = $this->getAppIcon($app['id'], $iconPath);
    }
    return $app;
  }

  public function getAppIcon($appId, $path, $overwrite = false) {
    $base_url = 'http://files.'.$_SERVER['SERVER_NAME'].'/';
    $filename = 'icon_' . $appId . '.png';
    $path = $path . '/' . $filename;
    if (!$overwrite && file_exists($path))
      return $base_url.$filename;
    $gir = new GetImageRequest();
    $gir->setImageUsage(GetImageRequest_AppImageUsage::ICON);
    $gir->setAppId($appId);
    $gir->setImageId(1);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setImageRequest($gir);
    try { $response = @$this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    $imageResponse = $groups[0]->getImageResponse();
    $filename = 'icon_' . $appId . '.png';
    if (file_put_contents($path,
			  $imageResponse->getImageData()) === false)
      return false;
    return $base_url.$filename;
  }

  private function reviewToArray($appId, $review) {
    return array('app_id' => $appId,
		 'creationTime' => $review->getCreationTime(),
		 'author' => $review->getAuthorName(),
		 'authorId' => $review->getAuthorId(),
		 'text' => $review->getText(),
		 'rating' => $review->getRating());
  }

  private function reviewsToArrays($appId, $reviewsObjects) {
    if (!$reviewsObjects)
      throw new Exception(500);
    $reviews = array();
    foreach ($reviewsObjects as $reviewObject)
      $reviews[] = $this->reviewToArray($appId, $reviewObject);
    return $reviews;
  }

  public function getReviews($appId, $limit = 10) {
    $cr = new CommentsRequest();
    $cr->setAppId($appId);
    $cr->setEntriesCount($limit);
    $reqGroup = new Request_RequestGroup();
    $reqGroup->setCommentsRequest($cr);
    try { $response = @$this->session->execute($reqGroup);
    } catch (Exception $e) { return false; }
    $groups = $response->getResponsegroupArray();
    foreach ($groups as $rg) {
      $commentsResponse = $rg->getCommentsResponse();
      if (!$commentsResponse)
	continue;
      $comments[] = $commentsResponse->getCommentsArray();
    }
    $comments = $this->flatten($comments);
    $comments = $this->reviewsToArrays($appId, $comments);
    return $comments;
  }

}
