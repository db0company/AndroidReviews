
////////////////////////////////////////////
// Track App
////////////////////////////////////////////

function trackStarted(app) {
    var button = $("input[value='" + app.id + "']").parent().find("[name='f_track_submit']");
    button.removeClass('start');
    button.addClass('stop');
    button.html('<i class="fa fa-stop"></i> Stop Tracking');
    $("nav").append('<a href="/apps/reviews?id='
		    + app.id
		    + '"><img src="'
		    + app.icon
		    + '" alt="'
		    + app.name
		    + '"></a>');
}

function trackStopped(app) {
    var button = $("input[value='" + app.id + "']").parent().find("[name='f_track_submit']");
    button.removeClass('stop');
    button.addClass('start');
    button.html('<i class="fa fa-play"></i> Start Tracking');
    $("nav").find("[href='/apps/reviews?id=" + app.id + "']").remove();
}

function switchTrack(appId, onfailure) {
    $.get('/ajax/switchtrack'
	  + '?appId=' + appId,
    	  function(result) {
	      result = jQuery.parseJSON(result);
	      if (result.status == 'start')
		  trackStarted(result.app);
	      else if (result.status == 'stop')
		  trackStopped(result.app);
	      else onfailure();
    	  }).fail(onfailure);
}

function switchTrackClick(button) {
    var icon = button.find(".fa");
    var oldclasses = icon.attr('class');
    icon.removeClass('fa-play');
    icon.removeClass('fa-stop');
    icon.addClass('fa-refresh fa-spin');
    switchTrack(button.parent().find("[name='f_track_id']").val(),
		function() {
		    icon.removeClass();
		    icon.addClass(oldclasses);
		    button.tooltip({ title: 'Failed',
				trigger: 'manual',
				placement: 'top',
				html: false });
		    button.tooltip('show');
		    setTimeout(function() { button.tooltip('hide'); }, 2000);
		});
}

$("[name='f_track_submit']").click(function(e) {
	e.preventDefault();
	switchTrackClick($(this));
    });

////////////////////////////////////////////
// Track All Apps
////////////////////////////////////////////

function updateTotal() {
    $(".total-search").text($(".well").length);
}

updateTotal();

$(".track-all").click(function(e) {
	e.preventDefault();
	$(".f_track .start").each(function() {
		switchTrackClick($(this));
	    });
    });

$(".untrack-all").click(function(e) {
	e.preventDefault();
	$(".f_track .stop").each(function() {
		switchTrackClick($(this));
	    });
    });

////////////////////////////////////////////
// Ajax load results
////////////////////////////////////////////

function buttonToNextPage(e) {
    e.preventDefault();
    var button = $(this);
    var index = button.find(".loadindex").text();
    button.html('<i class="fa fa-refresh fa-spin"></i>');
    $.get('/ajax/search?q='
	  + $("#searchQuery").text()
	  + '&index=' + index,
	  function(result) {
	      button.parent().replaceWith(result);
	      $("#loadmore").on('click', buttonToNextPage);
	      setEqualHeight();
	      updateTotal();
	  });
};

$("#loadmore").on('click', buttonToNextPage);

