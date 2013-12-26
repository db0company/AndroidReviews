
<div class="modal fade" id="modalAbout" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">About</h4>
      </div>
      <div class="modal-body">
	<div class="row about">
	  <div class="col-sm-4">
	    <i class="fa fa-th"></i>
	    <p>Search and track your favorite Apps</p>
	  </div> <!-- col -->
	  <div class="col-sm-4">
	    <i class="fa fa-exclamation-circle"></i>
	    <p>Get notified when new reviews are posted</p>
	  </div> <!-- col -->
	  <div class="col-sm-4">
	    <i class="fa fa-smile-o"></i>
	    <p>Read, reply, improve your ratings!</p>
	  </div> <!-- col -->
	</div> <!-- row -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-android" data-dismiss="modal">Got it!</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalTerms" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Terms Of Service</h4>
      </div>
      <div class="modal-body">
	to be written
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-android" data-dismiss="modal">Got it!</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalPrivacy" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Privacy Policy</h4>
      </div>
      <div class="modal-body">
	to be written
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-android" data-dismiss="modal">Got it!</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
	  <div class="col-sm-9 text-android text-right">
	    <div class="sentence">
	      Use your Google Account to start tracking your favorite Apps' reviews!
	    </div> <!-- sentence-->
	  </div> <!-- col -->
	  <div class="col-sm-3 text-android android">
	    <i class="fa fa-android"></i>
	  </div> <!-- col -->
	</div> <!-- row tip -->
	<form class="form-horizontal" role="login" id="f_login">
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
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10 text-right">
	      <small class="create">
		<a href="#" href="_target" id="2step">
		  2-step?
		</a>
	      </small>
	      <small class="create">
		<a href="https://accounts.google.com/SignUp" href="_target">
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
  <footer class="text-right">
    <a href="#about" data-toggle="modal" data-target="#modalAbout">About</a> - 
    <a href="#terms" data-toggle="modal" data-target="#modalTerms">Terms Of Service</a> - 
    <a href="#privacy" data-toggle="modal" data-target="#modalPrivacy">Privacy Policy</a>
  </footer> <!-- text-right -->
</div> <!-- container -->
