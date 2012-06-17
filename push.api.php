<?php

/* Description: AJAX Push API
 * Author: Matheus Pratta
 * Details: http://push.mpratta.com.br/
 *********************************************/
 
require_once("mysql.php");
 
/* Read all messages from '$channel' that were posted after '$last' and match '$columns' */
function push_read($channel, $last, $desc = false, $columns = array())
{
	global $db;
	global $db_pref;
	global $db_tabl;
	
	// Sanitize extra column names and values for security and make them SQL-ready
	$extra = "";
	foreach($columns as $col => $val)
	{
		$extra .= " AND `".sanitize($col)."`='".sanitize($val)."'";
	}
	
	// Descending or Ascending?
	$order = $desc?"DESC":"ASC";
	
	// Start database
	$db = db_connect();
	
	$sql = "SELECT * FROM ".sanitize($db_pref.$db_tabl)." WHERE `channel`='".sanitize($channel)."' AND `time` > '".sanitize($last)."'".$extra." ORDER BY time ".$order;
	$data = $db->prepare($sql);
	$data->execute();
	$data = $data->fetchAll(PDO::FETCH_ASSOC);
	
	// Close database - let's not spend all available connections in case we're a loop! ;)
	$db = null;
	
	return $data;
}

/* Writes a new message and post it into '$channel', with '$message' as the body and '$columns' as the optional extra columns */
function push_send($channel, $message, $columns = array())
{
	global $db;
	global $db_pref;
	global $db_tabl;
	
	// Sanitize extra column names and values for security and make them SQL-ready
	$extra = "";
	$extraVal = "";
	foreach($columns as $col => $val)
	{
		$extra .= ", `".sanitize($col)."`";
		$extraVal .= ", '".sanitize($val)."'";
	}
	
	// Start database
	$db = db_connect();
	
	$sql = "INSERT INTO ".sanitize($db_pref.$db_tabl)." (`channel`, `body`, `time`".$extra.") VALUES ('".sanitize($channel)."', '".sanitize($message)."', '".time()."'".$extraVal.")";
	$db->prepare($sql)->execute();
	
	$db = null;
}

/* Loops until one or more new messages received on '$channel' after '$last' (optional) or '$limit' attempts are made, with a interval of '$interval' seconds */
function push_loop($channel, $interval, $limit, $last = NULL, $desc = false, $columns = array())
{
	$amount = 0;
	$loop = true;
	
	if($last === NULL || empty($last)) $last = time();
	
	while($loop)
	{
		$new = push_read($channel, $last, $desc, $columns);
		
		if(count($new) > 0) return $new;
		
		$interval++;
		if($amount >= $limit) { $loop = false; break; }
		usleep($interval * 1000000);
	}
	
	return false;
}
 
 ?>