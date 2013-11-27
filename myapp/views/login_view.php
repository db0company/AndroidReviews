
<?php if ($is_error) { ?>
<div class="alert-error">
  Please check your e-mail and your password again.
</div>
<?php } ?>

<form method="post" id="f_login">
   <input type="email" value="" name="f_login_email" placeholder="Your Google E-mail address">
   <input type="password" value="" name="f_login_password" placeholder="Your Google Password">
   <input type="submit" name="f_login_submit" value="Login">
</form>
