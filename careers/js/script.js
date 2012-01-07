$(function() {
  $("button").hover(
    function () {
      $(this).addClass("hover");
    },
    function () {
      $(this).removeClass("hover");
    }
  );
  $(".search-form label").inFieldLabels();
  console.log('test');
});