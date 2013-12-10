
<?php if (empty($searchApps) || !count($searchApps)) { ?>
<div class="alert-info">
  No result match your search.
</div>
<?php } ?>

<h3>Search Results for <?= $searchQuery ?></h3>

<?php foreach ($searchApps as $app) { ?>

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
