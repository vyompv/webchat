<?php
include('ext.inc');
//Add new friend in the databse for the given username
$username = clean($_POST['username']);
$friendname = clean($_POST['friendid']);
date_default_timezone_set('US/Pacific');
$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, $username);
		$resultr = mysql_query($query, $con);
		$rowr = mysql_fetch_array($resultr);
		$sid=$rowr['id'];
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, $friendname);
		$resultr = mysql_query($query, $con) or die("No such friendid found!");
		$rowr = mysql_fetch_array($resultr);
		$rid=$rowr['id'];
		if($rid==$sid){echo "<span class=\"highlight\">You can't add yourself!</span>"; exit;}
		$query = "INSERT INTO " . $sql_database_name . ".friendslist (sender_id, receiver_id, time) VALUES ('%s', '%s','%s')";
		$query = sprintf($query,$sid,$rid,date('Y-m-d H:i:s', strtotime('now')));
		mysql_query($query, $con) or die("Trouble in adding!" . mysql_error());;
		echo "<span class=\"highlight\">Friend added Sucessfully</span>";
?>


<?php
function clean($str){
	return trim(mysql_real_escape_string($str));
}


