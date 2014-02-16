

var invite_url = 'http://invite.paysdu42.fr/';
var service_name = 'androidreviews';

function        checkInvite(invite, success, failure) {
    if (invite == 'null') {
        failure();
        return ;
    }
    var successWrapper = function(res) {
        if (!res || res == 'false')
            failure();
        else
            success();
    };
    $.ajax({
	    dataType: "json",
		type: 'GET',
		url: invite_url + '/invites/' + invite,
		data: {service_name:service_name},
		error: failure,
		success: successWrapper,
		});
}

function        sendMailInvite(email, about, success, failure) {
    console.log(email);
    console.log(about);
    if (email.length == 0 || about.length == 0) {
        failure();
        return ;
    }
    var successWrapper = function(res) {
        if (!res || res == 'false')
            failure();
        else
            success();
    };
    $.ajax({
	    dataType: "json",
		type: 'POST',
		url: 'http://api-emails.paysdu42.fr/androidreviews-ask-invite',
		data: {email:email, about:about},
		error: failure,
		success: successWrapper,
		});
}

function normalMsg() {
    $(".sentence").html('The place to be for Android developers,<br>community managers or curious users.');
}

function inviteMsg() {
    $(".sentence").html('You need to be invited to try this service.<br>Fill this form to ask for an invite!');
}

function removeErrors() {
    $(".alert").remove();
}

$(document).ready(function() {
   $("#2step").tooltip({ title: 'If you use the 2-step verification on your Google Account, you need an application-specific password to use this service.',
	       trigger: 'hover',
	       placement: 'left',
	       html: false });
 
  var anchor = window.location.hash.substr(1);
  if (anchor == 'create-account') {
      normalMsg();
       $("#f_login").hide();
       $("#f_ask_invite").hide();
       $("#f_create_account").show();
  } else if (anchor == 'login') {
      normalMsg();
       $("#f_ask_invite").hide();
       $("#f_login").show();
       $("#f_create_account").hide();
  } else if (anchor == 'ask-invite') {
      inviteMsg();
       $("#f_ask_invite").show();
       $("#f_login").hide();
       $("#f_create_account").hide();
  } else if (anchor == 'forgot') {
      $("#modalForgot").modal('show');
  } else if (anchor != '') {
      normalMsg();
      var invite = anchor;
      checkInvite(invite,
		  function() {
		      $("#f_login").hide();
		      $("#f_ask_invite").hide();
		      $("#f_create_account").show();
		      $("#f_create_account").prop('action', '#' + invite);
		      $("#f_create_account").prepend('<input type="hidden" name="f_create_account_invite" value="' + invite + '" id="f_create_account_invite" />');
		  }, function() {
		      inviteMsg();
		      $("form").first().before('<div class="alert alert-danger">Invalid invite</div>');
		  }
		  );
  } else
      inviteMsg();

   $('a[href="#forgot"]').click(function(e) {
	   e.preventDefault();
	   removeErrors();
	   normalMsg();
	   $("#f_ask_invite").hide('slow');
	   $("#f_login").hide('slow');
	   $("#f_create_account").show('slow');
       });
   $('a[href="#create-account"]').click(function(e) {
	   e.preventDefault();
	   removeErrors();
	   normalMsg();
	   $("#f_ask_invite").hide('slow');
	   $("#f_login").hide('slow');
	   $("#f_create_account").show('slow');
       });
   $('a[href="#login"]').click(function(e) {
	   e.preventDefault();
	   removeErrors();
	   normalMsg();
	   $("#f_ask_invite").hide('slow');
	   $("#f_create_account").hide('slow');
	   $("#f_login").show('slow');
       });
   $('a[href="#ask-invite"]').click(function(e) {
	   e.preventDefault();
	   removeErrors();
	   inviteMsg();
	   $("#f_ask_invite").show('slow');
	   $("#f_create_account").hide('slow');
	   $("#f_login").hide('slow');
       });

   $("#f_ask_invite").submit(function(e) {
	   e.preventDefault();
	   removeErrors();
	   sendMailInvite($("#f_ask_invite_email").val(),
			  $("#f_ask_invite_about").val(),
			  function() {
			      $("form").first().before('<div class="alert alert-android">Got it! We\'ll contact you as soon as your invite is ready.</div>');
			  }, function() {
			      $("form").first().before('<div class="alert alert-danger">Whoops, an error occured! Please check what you typed and try again.</div>');
			  });
       });

});

