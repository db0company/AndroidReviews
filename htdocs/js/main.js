
function setEqualHeight() {
    $("nav").css('min-height', 0);
    $("main").css('min-height', 0);
    var height = $("main").height(),
	navH = $("nav").height(),
	windowH = $(window).height() - $("header").height() - 41;
    if (height < windowH)
	height = windowH;
    if (height < (navH - 40))
	    height = navH - 40;
    if ($(window).width() > 992)
	$("nav").css('min-height', height + 40);
    $("main").css('min-height', height);
};

$(document).ready(function() {
	setEqualHeight();
	$(window).on('resize', setEqualHeight);
	$('nav a[href="#more"]').click(function(e) {
		e.preventDefault();
		$("nav a.app").toggle('slow');
	    });
    });
