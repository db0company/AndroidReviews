
<?php if (!count($apps)) { ?>
<div class="alert-info">
  No result match your search.
</div>
<?php } ?>

<?php foreach ($apps as $app) { ?>

<img src="/img/appsicons/<?= $app->icon ?>" alt="<?= $app->getTitle() ?>">
<pre><?php print_r($app) ?></pre>
<hr>

<?php } ?>
