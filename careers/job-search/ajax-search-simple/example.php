<!DOCTYPE html>
<html>
<head>
  <style>
  body{ font-size: 12px; font-family: Arial; }
  </style>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
  
<b>Search Response:</b>
<div id="search-response">
<div id="ajax-loader"><img src="../images/ajax-loader.gif"/></div>
<div id="error"></div>
</div>
  
<script>
$("#search-response").load("index.php?location=TG_SEARCH_ALL&hotjobs=true", function(response, status, xhr) {
  if (status == "error") {
    var msg = "Sorry but there was an error: ";
    $("#error").html(msg + xhr.status + " " + xhr.statusText);
  }
});
</script>

</body>
</html>
