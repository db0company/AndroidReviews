<?php

define('TMVC_MYAPPDIR', '/var/www/androidreviews/myapp/');
include_once('myapp/plugins/tools.php');
include_once('myapp/plugins/consumer.php');
include_once('myapp/plugins/AndroidMarket.class.php');
include_once('myapp/plugins/updateTracking.php');
include_once('myapp/configs/config_database.php');
include_once('Mail.php');

function sendMailNewReview($email, $appid, $review) {
  global $config;
  $content = '
<center>
<h1><span style="color:#9acd32">A</span>ndroid <span style="color:#9acd32">R</span>eviews <span style="color:#9acd32">M</span>anager</h1>
<i style="color:#9acd32">The Android Developer\'s best friend</i>
</center>
<br><br>

Hello,<br>
<br>
You\'ve got a new review!<br>
<a href="'.$config['website']['url'].'apps/reviews?id='.$appid.'"
 style="display: inline-block; color: #ffffff; text-decoration: none; font-weight: bold; padding: 20px; background-color: #9acd32; border-radius: 10px; margin: 10px;">
Read it</a>

<br><br>
<small style="color: #cccccc;">You received this email because you follow an app on <a href="'.$config['website']['url'].'">AndroidReviewsManager</a>. Unfollow this app to stop receiving this kind of emails.</small>
</center>
';

  $headers['From']    = 'notifications@androidreviewsmanager.com';
  $headers['To']      = $email;
  $headers['Subject'] = 'New Review';
  $content = utf8_encode($content);
  $headers['Content-Type'] = "text/html; charset=\"UTF-8\"";
  $headers['Content-Transfer-Encoding'] = "8bit";
  
  $params['sendmail_path'] = '/usr/lib/sendmail';
  
  $mail_object =& Mail::factory('sendmail', $params);
  $mail_object->send($headers['To'], $headers, $content);

  echo 'New review, email sent! '.$email.' '.$appid.' '.$review."\n";
}


$market = new AndroidMarket();

$db = new PDO('mysql:host='.$config['default']['host'].';dbname='.$config['default']['name'],
              $config['default']['user'], $config['default']['pass']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$r = $db->prepare('SELECT * FROM apps_tracker');
$r->execute();
$r = $r->fetchAll();

foreach ($r as $app) {
  echo '# Update reviews for: '.$app['user'], $app['app_id']."\n";
  $newReviews = updateTracking($db, $market, $app['user'], $app['app_id']);
  foreach ($newReviews as $newReview)
    sendMailNewReview($app['user'], $app['app_id'], $newReview);
}

