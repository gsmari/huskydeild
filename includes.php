<?php
/*
* Guðmundur Smári Guðmundsson,Sunna Berglind Sigurðardóttir
* gummismari@gummismari.net
* S: 690-6756
* 2014
*/
/* Includes á allar síður */

// Debug begin
$debug = true;	// Breyta fyrir release


if($debug) {
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	// If debug: 
	error_reporting(E_ALL);
}
// Else if release
//	error_reporting(0);
// Debug end
ini_set("session.cookie_httponly", 1);	// Kökur eru bara aðgengilegar í gegnum HTTP 
ini_set("session.use_cookies", 1);		// Kökur eru notaðar til að geyma session id
ini_set("session.use_only_cookies", 1);	// Session id eru aðeins leyfileg í kökum
ini_set("session.use_trans_id", 0);		// Leyfum EKKI session id í url
ini_set("session.hash_function", "whirlpool");	// Algorithm sem er notað til að búa til session id. md5 er default
ini_set('session.entropy_file', '/dev/urandom');
ini_set('default_charset', 'utf-8');
date_default_timezone_set('UTC');
$root = '/kanban/';
$path = getRemotePath($root);	// Remote root directory
require_once($path.'functions.php');


function getRemotePath() {
	$cwd = rtrim(str_replace('\\', '/', realpath(dirname(__FILE__))), '/');
	if($cwd[strlen($cwd)-1]!="/")
		$cwd .= "/";	// Höfum skástrik í endan
	return $cwd;
}
?>
