<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title></title>

    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
    </script>
    <![endif]-->
  </head>
  <style>
    body {
      white-space: pre;
      font-family: monospace;
    }
  </style>
  <body>

<?php if (!empty($errors)) { ?>
<?php   foreach ($errors as $error) { ?>
<div class="alert-error">
  <?= $error ?>
</div>
<?php   } ?>
<?php } ?>
