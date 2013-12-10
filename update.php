<?php

define('TMVC_MYAPPDIR', '/var/www/androidreviews/myapp/');
include_once('myapp/plugins/AndroidMarket.class.php');
include_once('myapp/plugins/updateTracking.php');
include_once('myapp/configs/config_database.php');
include_once('Mail.php');


function sendMailNewReview($email, $appid, $review) {
  $content = 'Hello,<br>
<br>
You\'ve got a new review!<br>
<a href="http://androidreviews.paysdu42.fr/index.php/apps/reviews?id='.$appid.'">Click here to read it and reply to it!</a>';

  $headers['From']    = 'noreply@paysdu42.fr';
  $headers['To']      = $email;
  $headers['Subject'] = 'New Review';
  $content = utf8_encode($content);
  $headers['Content-Type'] = "text/html; charset=\"UTF-8\"";
  $headers['Content-Transfer-Encoding'] = "8bit";
  
  $params['sendmail_path'] = '/usr/lib/sendmail';
  
  $mail_object =& Mail::factory('sendmail', $params);
  $mail_object->send($headers['To'], $headers, $content);

  echo 'New review, email sent! '.$email.' '.$appid.' '.$review;
}


$market = new AndroidMarket($config['market']['email'],
			    $config['market']['pass']);

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

