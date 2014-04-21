jQuery(document).ready(function($) {
  $('.dymo-link').click(function (event){
    var url = $(this).attr("href");
    if ($.browser.webkit) {
      window.open(url, "Print", "width=500, height=500");
    }
    else {
      window.open(url, "Print", "scrollbars=1, width=500, height=500");
    }
    
    event.preventDefault();
 
    return false;
 
  });
});

