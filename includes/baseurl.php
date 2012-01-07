<?php
/**
 * BaseUrl and BasePath helper
 *
 * If this file lives in the root of your website, comment out line 26.
 *
 * $BaseUrl:
 *   Base url of your web application.
 *   Always has a leading and trailing slash.
 *
 *
 * $BasePath:
 *   This will always point to the base of your website directory.
 *   Always has a leading and trailing slash.
 *   Ex: To include a file: {website root}/partials/footer.php
 *       include $basePath . 'partials/footer.php';
 */

// document root
$docRoot = $_SERVER['DOCUMENT_ROOT'];
// make sure the document_root has a trailing slash
$docRoot .= (substr($docRoot, -1) == '/') ? '' : '/';

// path to this file
$basePath = dirname(__FILE__);
// if this file lives one directory deeper than the website root (comment the next line out if it doesn't)
$basePath = dirname($basePath);
// incase your on a windows machine
$basePath = str_replace('\\', '/', $basePath);
// make sure the base path has a trailing slash
$basePath .= (substr($basePath, -1) == '/') ? '' : '/';

// base url
$baseUrl = str_replace($docRoot, '', $basePath);
// make sure the base url has a leading slash
$baseUrl = (strlen($baseUrl) > 0 && $baseUrl{0} = '/') ? $baseUrl : '/' . $baseUrl;