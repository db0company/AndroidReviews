
<?php if ($reviews !== false && !count($reviews)) { ?>
<div class="alert-info">
  No result match your search.
</div>
<?php } ?>

<?php print_r($app); ?>

<form method="post" id="f_track">
  <input type="submit" name="f_track_submit"
	 value="<?= $tracked ? 'Stop' : 'Start' ?> Tracking">
</form>

<?php foreach ($reviews as $review) { ?>

<div class="review">
  <?= $review['id'] ?><h5><?= $review['author'] ?></h5><h4><?= $review['rating'] ?></h4><p><?= $review['text'] ?></p>
  <?php if ($tracked && isset($review['read'])) { ?>
  <form method="post" class="f_read">
    <input type="hidden" name="f_read_id" value="<?= $review['id'] ?>">
    <input type="submit" name="f_read_<?= $review['read'] ? 'unread' : 'read' ?>"
	   value="Mark as <?= $review['read'] ? 'unread' : 'read' ?>">
    <?php if ($canReply) { ?>
    <a href="https://play.google.com/apps/publish#ReviewsPlace:p=<?= $app['packageName'] ?>"
       target="_blank">
      Reply
    </a>
    <?php } ?>
  </form>
  <?php } ?>
</div>
<hr>

<?php } ?>
