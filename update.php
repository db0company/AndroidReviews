<?php

define('TMVC_MYAPPDIR', '/var/www/androidreviews/myapp/');
include_once('myapp/plugins/tools.php');
include_once('myapp/plugins/consumer.php');
include_once('myapp/plugins/AndroidMarket.class.php');
include_once('myapp/plugins/updateTracking.php');
include_once('myapp/configs/config_database.php');
include_once('Mail.php');

function sendMailNewReviews($email, $newReviews) {
  global $config;

  $date = date('l, F m');

  $content = '
<center>
<h1><span style="color:#9acd32">A</span>ndroid <span style="color:#9acd32">R</span>eviews <span style="color:#9acd32">M</span>anager</h1>
<i style="color:#9acd32">The Android Developer\'s best friend</i>
</center>
<br><br>

<h3>Daily Report <small style="color:#9acd32">'.$date.'</small></h3>

Hello,<br>
<br>';

  if (empty($newReviews)) {
    $content .= '<p>No new reviews were published on the Google Play Store on the Apps you track.</p><br /><br />';
  } else {

    $content .= '<table>';

    foreach ($newReviews as $app_id => $app) {
      $reviews = $app['reviews'];
      $app = $app['app'];

      $content .= '<a href="'.$config['website']['url'].'apps/reviews?id='.$app_id.'">';
      $content .= '<tr>
<td style="width:30%"><img src="'.$app['icon'].'">
'.$app['title'].'</td>
<td><div style="display: inline-block; color: #ffffff; text-decoration: none; font-weight: bold; padding: 20px; background-color: #9acd32; border-radius: 10px; margin: 10px;">
Reply</div></td>
</tr>';

      foreach ($reviews as $review) {

	$content .= '<tr><td>'.viewRatingPics($review['rating']).'</td>';
	$content .= '<td>';
	if (preg_match('/^([^\t]+)\t(.+)$/', $review['text'], $reviewContent)) {
	  $content .= '<p><strong>'.$reviewContent[1].'</strong></p>';
	  $content .= '<p>'.$reviewContent[2].'</p>';
	} else {
	  $content .= '<p>'.$review['text'].'</p>';
	}
	$content .= '</td></tr>';
      }
      $content .= '</a>';
    }
    $content .= '</table>';
  }

  $content .= '<small style="color: #cccccc;">You received this email because you follow at least one App on <a href="'.$config['website']['url'].'">AndroidReviewsManager</a>.
Unfollow all Apps to stop receiving this daily report.</small>
</center>
';

  $headers['From']    = 'notifications@androidreviewsmanager.com';
  $headers['To']      = $email;
  $headers['Subject'] = 'ARM Daily Report: '.(empty($newReviews) ? 'no' : count($newReviews)).' new user reviews on '.$date;
  $content = utf8_encode($content);
  $headers['Content-Type'] = "text/html; charset=\"UTF-8\"";
  $headers['Content-Transfer-Encoding'] = "8bit";
  
  $params['sendmail_path'] = '/usr/lib/sendmail';
  
  $mail_object =& Mail::factory('sendmail', $params);
  //$mail_object->send($headers['To'], $headers, $content);

  echo $content;
  //echo 'Email sent! '.$email;
}


$market = new AndroidMarket();

$db = new PDO('mysql:host='.$config['default']['host'].';dbname='.$config['default']['name'],
              $config['default']['user'], $config['default']['pass']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$r = $db->prepare('SELECT * FROM users');
$r->execute();
$users = $r->fetchAll();

foreach ($users as $user) {
  $flag = false;
  $r = $db->prepare('SELECT t.*, a.title AS app_name, a.icon AS app_icon FROM apps_tracker AS t JOIN apps AS a WHERE t.app_id=a.id AND t.user=? ORDER BY a.title');
  $r->execute(array($user['email']));
  $apps = $r->fetchAll();

  $newReviews = array();
  foreach ($apps as $app) {
    $flag = true;
    $reviews = updateTracking($db, $market, $app['user'], $app['app_id']);
    if (!empty($reviews)) {
      $newReviews[$app['app_id']]['reviews'] = $reviews;
      $newReviews[$app['app_id']]['app'] = array('id' => 'app_id',
						 'title' => $app['app_name'],
						 'icon' => $app['app_icon']);
    }
  }
  if ($flag)
    sendMailNewReviews($user, $newReviews);
}

