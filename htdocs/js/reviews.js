
////////////////////////////////////////////
// Switch Views
////////////////////////////////////////////

function getURLParameter(name) {
    return decodeURI((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
}

function switchOneView() {
    $("#list_view").removeClass('active');
    $("#one_view").addClass('active');
    $(".reviews").removeClass('list_view');
    $(".reviews").addClass('one_view');
    setEqualHeight();
};

function switchListView() {
    $("#one_view").removeClass('active');
    $("#list_view").addClass('active');
    $(".reviews").removeClass('one_view');
    $(".reviews").addClass('list_view');
    $(".review").show();
    setEqualHeight();
}

$("#one_view").click(function(e) {
	e.preventDefault();
	switchOneView();
    });

$("#list_view").click(function(e) {
	e.preventDefault();
	switchListView();
    });

function switcher(e) {
    e.preventDefault();

    $(this).parent().find(".btn").removeClass('active');
    $(this).addClass('active');

    var appId = getURLParameter('id');

    $.get('/index.php/ajax/reviews?appId=' + appId
    	  + '&filter=' + $(this).prop("id")
    	  + '&viewStyle=' + ($(".reviews").hasClass('list_view') ? 'list_view' : 'one_view')
    	  + '&packageName=' + $("#packageName").text(),
    	  function(result) {
    	      $(".reviews").replaceWith(result);
	      setClicks();
	      setEqualHeight();
    	  });
}

$("#view_all").click(switcher);
$("#view_unread").click(switcher);
$("#view_read").click(switcher);

$("#list_view").tooltip({ title: 'Overview',
	    trigger: 'hover',
	    placement: 'top',
	    html: false });
$("#one_view").tooltip({ title: 'Interactive<br>Reviews reader',
	    trigger: 'hover',
	    placement: 'top',
	    html: true });
$("#view_all").tooltip({ title: 'View all<br>reviews',
	    trigger: 'hover',
	    placement: 'top',
	    html: true });
$("#view_unread").tooltip({ title: 'View only<br>unread reviews',
	    trigger: 'hover',
	    placement: 'top',
	    html: true });
$("#view_read").tooltip({ title: 'View only<br>read reviews',
	    trigger: 'hover',
	    placement: 'top',
	    html: true });

////////////////////////////////////////////
// Mark Read/Unread
////////////////////////////////////////////

function readToUnreadButton(button) {
    button.removeClass('read');
    button.addClass('unread');
    button.find('.fa').removeClass('fa-envelope');
    button.find('.fa').addClass('fa-envelope-o');
    button.find('span').text('Mark as read');
    setClicks();
}

function unreadToReadButton(button) {
    button.removeClass('unread');
    button.addClass('read');
    button.find('.fa').removeClass('fa-envelope-o');
    button.find('.fa').addClass('fa-envelope');
    button.find('span').text('Mark as unread');
    setClicks();
}

function tooltipDelay(elt, content) {
    elt.tooltip({ title: content,
		trigger: 'manual',
		html: true });
    elt.tooltip('show');
    setTimeout(function() {
	    elt.tooltip('hide');
	}, 3000);
}

function changeReadCount(add) {
    var value = Number($(".counter .hexagon").text());
    var url = $(".counter a").prop("href");
    var newValue = add ? value + 1 : value - 1;
    if (newValue < 0)
	newValue = 0;
    if (!value) {
	$(".counter").replaceWith('<div class="counter unread pull-right"><a href="' + url + '"><div class="hexagon">' + newValue + '</div></a><span class="fa-stack bubble"><i class="fa fa-comment fa-stack-2x"></i><i class="fa fa-envelope fa-stack-1x"></i></span></div>');
    } else if (!newValue) {
	$(".counter").replaceWith('<div class="counter read pull-right"><a href="' + url + '"><div class="hexagon"><i class="fa fa-envelope-o"></i></div></a></div>');
    } else {
	$(".counter .hexagon").text(newValue);
    }
}

function markAsRead(review) {
    var reviewId = review.prop('id');
    var button = review.find('.unread');
    var fail = function(res) {
	button.show();
	tooltipDelay(button, 'Something went wrong');
	console.log(res);
    };
    $.get('/index.php/ajax/markread?r='
	  + reviewId,
	  function(result) {
	      if (result == 'true') {
		  changeReadCount(false);
		  unreadToReadButton(button);
		  button.show();
	      } else {
		  fail(result);
	      }
	  }).fail(fail);
}

function markAsUnread(review) {
    var reviewId = review.prop('id');
    var button = review.find('.read');
    var fail = function(res) {
	button.show();
	tooltipDelay(button, 'Something went wrong');
	console.log(res);
    };
    $.get('/index.php/ajax/markunread?r='
	  + reviewId,
	  function(result) {
	      if (result == 'true') {
		  changeReadCount(true);
		  readToUnreadButton(button);
		  button.show();
	      } else {
		  fail(result);
	      }
	  }).fail(fail);
}

////////////////////////////////////////////
// Browse Reviews in interactive mode
////////////////////////////////////////////

function showReview(review) {
    review.show('slow');
    if (review.find('.unread').length > 0)
	markAsRead(review);
}

$(".js_start").click(function(e) {
	e.preventDefault();
	$('.start_reading').hide('slow');
	showReview($('.review').first());
    });

function setClicks() {
    $("button.unread").off('click');
    $("button.unread").click(function(e) {
	    e.preventDefault();
	    var button = $(this);
	    var review = button.closest('.review');
	    markAsRead(review);
	});
    $("button.read").off('click');
    $("button.read").click(function(e) {
	    e.preventDefault();
	    var button = $(this);
	    var review = button.closest('.review');
	    markAsUnread(review);
	});
    $(".next").off('click');
    $(".next").click(function(e) {
	    e.preventDefault();
	    var button = $(this);
	    var review = button.closest('.review');
	    review.hide('slow');
	    var idNext = button.find('.id_next').text();
	    showReview($("#" + idNext));
	});
    $(".prev").off('click');
    $(".prev").click(function(e) {
	    e.preventDefault();
	    var button = $(this);
	    var review = button.closest('.review');
	    review.hide('slow');
	    var idPrev = button.find('.id_prev').text();
	    showReview($("#" + idPrev));
	});
}

setClicks();

////////////////////////////////////////////
// Show/Hide summary
////////////////////////////////////////////

$("summary").click(function() {
	$(this).text('Show/Hide description');
    });

////////////////////////////////////////////
// Disable interactive mode on small screens
////////////////////////////////////////////

if ($(window).width() < 992) {
    switchListView();
}
