
<body id="login-body">

  <?php viewModals(); ?>

<div class="container">
  <main id="login">
    <div class="row">
      <div class="col-md-6 title">
	<h1><span>A</span>ndroid <span>R</span>eviews <span>M</span>anager</h1>
	<h2>The growth Android Developer's best friend</h2>
	<h3>Never miss a review again!</h3>
      </div> <!-- col -->
      <div class="col-md-6">
	<?php if ($is_error) { ?>
	<?php viewAlert('danger', 'Please check your e-mail and your password again.'); ?>
	<?php } ?>
	<div class="row tip">
	  <div class="col-xs-9 text-android text-right">
	    <div class="sentence">
	      Use your Google Account to start tracking your favorite Apps' reviews!
	    </div> <!-- sentence-->
	  </div> <!-- col -->
	  <div class="col-xs-3 text-android android">
	    <i class="fa fa-android"></i>
	  </div> <!-- col -->
	</div> <!-- row tip -->
	<form class="form-horizontal" role="login" id="f_login" method="post">
	  <div class="form-group">
	    <label for="f_login_email" class="col-sm-2 control-label">Email</label>
	    <div class="col-sm-10">
	  <input type="email" class="form-control" id="f_login_email"
		 value="" name="f_login_email" placeholder="Your Google E-mail address">
	    </div>
	  </div> 
	  <div class="form-group">
	    <label for="f_login_password" class="col-sm-2 control-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="f_login_password"
		     name="f_login_password" placeholder="Your Google Password">
	      <div class="forgot-password">
		<small>
		  <a href="https://www.google.com/accounts/recovery/" target="_blank">
		    Forgot password?
		  </a>
		</small>
	      </div> <!-- text-right -->
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10 text-right">
	      <small class="create">
		<a href="https://accounts.google.com/IssuedAuthSubTokens#accesscodes" target="_blank" id="2step">
		  2-step?
		</a>
	      </small>
	      <small class="create">
		<a href="https://accounts.google.com/SignUp" target="_blank">
		  Create an account
		</a>
	      </small>
	      <button type="submit" class="btn btn-android" name="f_login_submit">Sign in</button>
	    </div>
	  </div>
	</form>
      </div> <!-- col -->
    </div> <!-- row -->
  </main>
