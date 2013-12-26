
$("#one_view").click(function(e) {
	e.preventDefault();
	$("#list_view").removeClass('active');
	$(this).addClass('active');
	$(".reviews").removeClass('list_view');
	$(".reviews").addClass('one_view');
	
    });

$("#list_view").click(function(e) {
	e.preventDefault();
	$("#one_view").removeClass('active');
	$(this).addClass('active');
	$(".reviews").removeClass('one_view');
	$(".reviews").addClass('list_view');
	$(".review").show();
    });

function readToUnreadButton(button) {
    button.removeClass('read');
    button.addClass('unread');
    button.find('.fa').removeClass('fa-square-o');
    button.find('.fa').addClass('fa-check-square-o');
    button.find('span').text('Mark as read');
    setClicks();
}

function unreadToReadButton(button) {
    button.removeClass('unread');
    button.addClass('read');
    button.find('.fa').removeClass('fa-check-square-o');
    button.find('.fa').addClass('fa-square-o');
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

function showReview(review) {
    review.show('slow');
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
}

setClicks();

$(".next").click(function(e) {
	e.preventDefault();
	var button = $(this);
	var review = button.closest('.review');
	review.hide('slow');
	var idNext = button.find('.id_next').text();
	showReview($("#" + idNext));
    });
$(".prev").click(function(e) {
	e.preventDefault();
	var button = $(this);
	var review = button.closest('.review');
	review.hide('slow');
	var idPrev = button.find('.id_prev').text();
	showReview($("#" + idPrev));
    });

$("summary").click(function() {
	$(this).text('Show/Hide description');
    });
