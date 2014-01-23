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
	    <div class="sentence"></div> <!-- sentence-->
	  </div> <!-- col -->
	  <div class="col-xs-3 text-android android">
	    <i class="fa fa-android"></i>
	  </div> <!-- col -->
	</div> <!-- row tip -->

	<?php if (!empty($errors)) { ?>
	<?php   foreach ($errors as $error) { ?>
	<?php     viewAlert('danger', $error); ?>
	<?php   } ?>
	<?php } ?>

	<form class="form-horizontal" role="ask for an invite" id="f_ask_invite" method="post"
	      action="#ask-invite">
	  <div class="form-group">
	    <label for="f_ask_invite_email" class="col-sm-2 control-label">Email</label>
	    <div class="col-sm-10">
	      <input type="email" class="form-control" id="f_ask_invite_email"
		     value="" name="f_login_email" placeholder="Your E-mail address">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="f_ask_invite_about" class="col-sm-2 control-label">About you</label>
	    <div class="col-sm-10">
	      <textarea class="form-control" id="f_ask_invite_about"
			name="f_ask_invite_about"
			placeholder="Tell us a little bit more about you! Developer? Community manager? Curious user? What are the apps you would like to track?"
			rows="4"
			></textarea>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10 text-right">
	      <small class="create">
		<a href="#login">
		  Already have an account?
		</a>
	      </small>
	      <button type="submit" class="btn btn-android" name="f_login_submit">Ask for an invite</button>
	    </div>
	  </div>
	</form>


	<form class="form-horizontal" role="login" id="f_login" method="post" action="#login">
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
		  <a href="#forgot">
		    Forgot password?
		  </a>
		</small>
	      </div> <!-- text-right -->
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10 text-right">
	      <small class="create">
		<!-- <a href="#create-account"> -->
		<!--   Create an account -->
		<!-- </a> -->
		<a href="#ask-invite">
		  Ask for an invite
		</a>
	      </small>
	      <button type="submit" class="btn btn-android" name="f_login_submit">Sign in</button>
	    </div>
	  </div>
	</form>


	<form class="form-horizontal" role="create account" id="f_create_account" method="post" action="#create-account">
	  <div class="form-group">
	    <label for="f_create_account_email" class="col-sm-2 control-label">Email</label>
	    <div class="col-sm-10">
	  <input type="email" class="form-control" id="f_create_account_email"
		 value="" name="f_create_account_email" placeholder="Your E-mail address">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="f_create_account_password" class="col-sm-2 control-label">Password</label>
	    <div class="col-sm-10">
	      <input type="password" class="form-control" id="f_create_account_password"
		     name="f_create_account_password" placeholder="Your Password">
	      <br>
	      <input type="password" class="form-control" id="f_create_account_password_check"
		     name="f_create_account_password_check" placeholder="Retype your password">
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10 text-right">
	      <small class="create">
		<a href="#login">
		  Already have an account?
		</a>
	      </small>
	      <button type="submit" class="btn btn-android" name="f_create_account_submit">Create an account</button>
	    </div>
	  </div>
	</form>


      </div> <!-- col -->
    </div> <!-- row -->
  </main>
