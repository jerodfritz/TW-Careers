<?
header ("Content-Type:text/xml");
include_once('./classes/simple_html_dom.php');

$cookie_jar = tempnam('/tmp','cookie');

$c = curl_init('https://careers.timewarner.com/1033/ASP/TG/cim_home.asp?partnerid=391&siteid=36');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_jar);
$page = curl_exec($c);
curl_close($c);

$c = curl_init('https://careers.timewarner.com/1033/ASP/TG/cim_advsearch.asp');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_jar);
$page = curl_exec($c);
curl_close($c);

$form = str_get_html($page);

function addOption($form, &$doc, &$root, $options, $option_name, $questionID, $questionNumber, $type) {
  $options_node = $doc->createElement($options);
  $options_node->setAttribute('questionID', $questionID);
  $options_node->setAttribute('questionNumber', $questionNumber);
  $options_node->setAttribute('type', $type);
  $first = true;
  if($type=='select'){
	  foreach($form->find('#Question' . $questionID . ' option') as $option) {
	    if(!$first){
	      $child = $doc->createElement('Options');
	      $child->setAttribute('Description', $option->innertext);
	      $child = $options_node->appendChild($child);
	      $value = $doc->createTextNode($option->value);
	      $value = $child->appendChild($value);
	    } else {
	      $first = false;
	    }
	  }
  }
  $root->appendChild($options_node);
}

$doc = new DomDocument('1.0');

$root = $doc->createElement('options');
$root = $doc->appendChild($root);

addOption($form,$doc,$root,'Departments','Department','1136__Department',1136, 'select');
addOption($form,$doc,$root,'JobDescriptions','Job Description','',1142, 'textarea');
addOption($form,$doc,$root,'Keywords','Keyword',' ',1158, 'Text');
addOption($form,$doc,$root,'Locations','Location','1140__Location',1140, 'select');
addOption($form,$doc,$root,'Titles','Title',' ',1135, 'text');
addOption($form,$doc,$root,'Industries','Industry','23702__FORMTEXT7',23702, 'select');
addOption($form,$doc,$root,'Interests','Interest','11524__FormText3',11524, 'select');
addOption($form,$doc,$root,'Types','Type','11512__FormText2',11512, 'select');

$xml = $doc->saveXml();
$f = "options.xml";
$fh = fopen($f, 'w');
fwrite($fh, $xml);
// remove the cookie jar
unlink($cookie_jar) or die("Can't unlink $cookie_jar");
print $xml;
