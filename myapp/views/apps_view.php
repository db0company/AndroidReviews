
<?php if (empty($trackedApps) || !count($trackedApps)) { ?>
<div class="alert-info">
  You're not tracking any app yet. Start tracking your favorite apps today by searching for them!
</div>
<?php } ?>

<?php foreach ($trackedApps as $app) { ?>

<img src="/img/appsicons/<?= $app['icon'] ?>" alt="<?= $app['title'] ?>">
<?php print_r($app) ?>
<form method="post" id="f_track">
  <input type="hidden" name="f_track_id" value="<?= $app['id'] ?>">
  <input type="submit" name="f_track_submit"
	 value="<?= $app['tracked'] ? 'Stop' : 'Start' ?> Tracking">
  <a href="/index.php/apps/reviews?id=<?= $app['id'] ?>">Browse reviews</a>
</form>
<hr>

<?php } ?>
