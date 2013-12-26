<?php

$urls = array(
	      'login' => '/index.php/login/',
	      'apps' => '/index.php/apps/',
	      'reviews' => '/index.php/apps/reviews?id=',
	      'logout' => '/index.php/logout/',
	      );


function getUrl($name) {
  global $urls;
  return $urls[$name];
}

function getMarket() {
  return $_SESSION['AndroidMarket'];
}

function checkLogin() {
  global $urls;
  if (!getMarket()) {
    header('location: ' . $urls['login']);
  }
}

function redirectsApp($appId) {
  global $urls;
  header('location: ' . $urls['reviews'] . $appId);
}

function getIconPath() {
  return getcwd() . '/img/appsicons/';
}

function redirectsApps() {
  global $urls;
  header('location: ' . $urls['apps']);
}

function protect($string) {
  return (htmlspecialchars(stripslashes($string)));
}

function idToValid($str) {
  return str_replace('.', '_', str_replace(':', '_', $str));
}

//////////////////////////////////////////////////////////////////
// Summary
//////////////////////////////////////////////////////////////////

function    summary_($str, $len, $st)
{
  if (strlen($str) < $len)
    return ($str);
  elseif (preg_match("/(.{1,$len})\s./ms", $str, $match))
    {
      if ($st)
	return ($match[1]."...");
      else
	return ($match[1]);
    }
  else
    {
      if ($st)
	return (substr($str, 0, $len)."...");
      else
	return (substr($str, 0, $len));
    }
}

function summary($str, $len)
{
  return (summary_($str, $len, true));
}

//////////////////////////////////////////////////////////////////
// Views
//////////////////////////////////////////////////////////////////

function viewAlert($type, $msg) {
  if ($type == 'warning')
    $icon = 'warning';
  elseif ($type == 'danger')
    $icon = 'exclamation-circle';
  elseif ($type == 'success')
    $icon = 'check-circle';
  else
    $icon = 'info-circle';
  echo '<div class="alert alert-',
    $type,
    '">
  <i class="fa fa-',
    $icon,
    ' fa-lg"></i>
  ',$msg, '
</div>';
}

function viewGrid($per_line, $elements, $displayer, $param, $size = 'md') {
  $row_nb = 0;
  $idx = 0;
?>
  <div class="row row<?= $row_nb ?>">
    <?php $i = 1;
	  foreach ($elements as $e) { ?>
    <div class="col-<?= $size ?>-<?= 12 / $per_line ?>">
	      <?php $displayer($e, $param, $idx) ?>
    </div> <!-- col -->
<?php if ($i == $per_line) { $row_nb++; ?>
  </div> <!-- row -->
  <div class="row row<?= $row_nb ?>">
    <?php $i = 0;
	  }
	  $i++;
	  $idx++;
	  } ?>
  </div> <!-- row -->
<?php
}

function viewSearchApp($app) { ?>
<div class="well">
  <div class="row">
    <div class="col-xs-4">
      <img src="/img/appsicons/<?= $app['icon'] ?>" alt="<?= $app['title'] ?>">
    </div> <!-- col -->
    <div class="col-xs-8">
      <h5><?= $app['title'] ?></h5>
      <form method="post" id="f_track">
	<input type="hidden" name="f_track_id" value="<?= $app['id'] ?>">
	<button type="submit" class="btn btn-android btn-xs" name="f_track_submit">
	  <i class="fa fa-<?= $isTracked ? 'stop' : 'play' ?>"></i>
	  <?= $app['tracked'] ? 'Stop' : 'Start' ?> Tracking
	</button>
	<a href="/index.php/apps/reviews?id=<?= $app['id'] ?>" class="btn btn-xs btn-android">
	  Browse reviews</a>
      </form>
    </div> <!-- col -->
  </div> <!-- row -->
</div> <!-- well -->
<?php }

function viewSearchApps($searchApps, $errorSearch, $currindex = 0) {
  if (isset($searchApps)) {
    if (!empty($errorSearch)) {
      foreach ($errorSearch as $error) {
	viewAlert('danger', $error);
      }
    } elseif (empty($searchApps) || !count($searchApps)) {
      viewAlert('info', 'No result match your search.');
    } else {
      viewGrid(3, $searchApps, viewSearchApp); ?>
	<div class="text-center">
	  <hr class="hover">
	  <a href="#" id="loadmore" class="btn btn-default btn-s">
	    <span class="loadindex hidden"><?= $currindex + 9 ?></span>Load more</a>
	</div>
<?php
    }
  }
}

function viewRatingStars($rating) {
  echo '<div class="text-',
    ($rating <= 2 ? 'danger' : 'android'), '">';
  $full = floor($rating);
  for ($i = 0; $i < $full; $i++)
    echo '<i class="fa fa-2x fa-star"></i>';
  $rest = $rating - $full;
  if ($full == 5);
  elseif ($rest < 0.25)
    echo '<i class="fa fa-2x fa-star-o"></i>';
  elseif ($rest >= 0.25 && $rest <= 0.75)
    echo '<i class="fa fa-2x fa-star-half-o"></i>';
  else
    echo'<i class="fa fa-2x fa-star"></i>';
  $empty = 5 - $full - 1;
  for ($i = 0; $i < $empty; $i++)
    echo '<i class="fa fa-2x fa-star-o"></i>';  
  echo '</div>';
}
