
function twostep(e) {
    e.preventDefault();
    $("#2step").tooltip({ title: 'If you use the 2-step verification on your Google Account, you need <a href="https://accounts.google.com/IssuedAuthSubTokens#accesscodes" href="_blank">an application-specific password</a> to use this service.',
		trigger: 'manual',
		html: true });
    $("#2step").tooltip('show');
    setTimeout(function() {
	    $("2step").tooltip('hide');
	}, 3000);
}

$("#2step").hover(twostep);

