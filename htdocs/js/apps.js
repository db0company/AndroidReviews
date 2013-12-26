
function buttonToNextPage(e) {
    e.preventDefault();
    var button = $(this);
    var index = button.find(".loadindex").text();
    button.html('<i class="fa fa-refresh fa-spin"></i>');
    $.get('/index.php/ajax/search?q='
	  + $("#searchQuery").text()
	  + '&index=' + index,
	  function(result) {
	      button.parent().replaceWith(result);
	      $("#loadmore").on('click', buttonToNextPage);
	  });
};

$("#loadmore").on('click', buttonToNextPage);

