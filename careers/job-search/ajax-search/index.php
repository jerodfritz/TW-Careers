<?
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
 require_once ("../classes/TimeWarnerSearch.class.php");       
  $tw = new TimeWarnerSearch(dirname(__FILE__) . '/../options/options.xml');
  $tw->displaySearchResults();      
}
?>