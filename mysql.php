<?php

/* Description: Configuration file for AJAX Push API
 * Author: Matheus Pratta
 * Details: http://push.mpratta.com.br/
 *********************************************/

error_reporting(E_ERROR);

global $db_type;
global $db_serv;
global $db_name;
global $db_user;
global $db_pass;
global $db_pref;
global $db_tabl;
 
/* START EDITING HERE */

$db_type = "mysql"; // Database type
$db_serv = "localhost"; // Database server
$db_name = ""; // Database name
$db_user = ""; // Database user
$db_pass = ""; // Database password
$db_pref = ""; // Table prefix
$db_tabl = "messages"; // Table name

/* STOP EDITING HERE */

function sanitize($text) 
{ 
	$text = str_replace("<", "&lt;", $text); 
	$text = str_replace(">", "&gt;", $text); 
	$text = str_replace("\"", "&quot;", $text); 
	$text = str_replace("'", "&#039;", $text); 
	$text = addslashes($text); 
	
	return $text; 
}
function db_connect()
{
	global $db_type;
	global $db_serv;
	global $db_name;
	global $db_user;
	global $db_pass;
	
	$db = new PDO($db_type.":host=".$db_serv.";dbname=".$db_name, $db_user, $db_pass) or die("Error: Cannot connect to database.");
	return $db;
}

?>