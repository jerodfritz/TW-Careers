$(function() {
  $("button").hover(
    function () {
      $(this).addClass("hover");
    },
    function () {
      $(this).removeClass("hover");
    }
  ).click(function(){
    if($(this).attr('href')){
      window.location = $(this).attr('href');
    }
  });
  $(".search-form label").inFieldLabels();
  
});