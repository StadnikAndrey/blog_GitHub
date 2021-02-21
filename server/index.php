<?php 
$path = "prerendered/{$_GET['querysystemurl']}/index.html";
$path = preg_replace('/\/+/', '/', $path);
if (!file_exists($path)) {
	header('HTTP/1.1 404 Not Found');
	$path = 'index.html';
}
include_once($path);
?>