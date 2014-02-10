
////////////////////////////////////////////
// Switch SeeAll / See Unread
////////////////////////////////////////////

function switcher(e) {
    e.preventDefault();

    $(this).parent().find(".btn").removeClass('active');
    $(this).addClass('active');

    $.get('/ajax/trackedApps?filter='
	  + $(this).prop("id"),
	  function(result) {
	      $("#apps").replaceWith(result);
	      setEqualHeight();
	  });
}

$("#view_all").click(switcher);
$("#view_unread").click(switcher);
$("#view_read").click(switcher);

$("#view_all").tooltip({ title: 'View All Apps',
	    trigger: 'hover',
	    placement: 'top',
	    html: false });
$("#view_unread").tooltip({ title: 'View only Apps with unread reviews',
	    trigger: 'hover',
	    placement: 'top',
	    html: false });
$("#view_read").tooltip({ title: 'View only Apps without unread reviews',
	    trigger: 'hover',
	    placement: 'top',
	    html: false });

