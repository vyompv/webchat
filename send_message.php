<?php
include('ext.inc');
//Store the message send by the user.
$username = clean($_POST['username']);
$friendname=clean($_POST['sendto']);
$message = clean($_POST['message']);
checkkey($key);
$key=0;
date_default_timezone_set('US/Pacific');
$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, $username);
		$resultr = mysql_query($query, $con);
		$rowr = mysql_fetch_array($resultr);
		$sid=$rowr['id'];
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE displayname='%s'";
		$query = sprintf($query, $friendname);
		$resultr = mysql_query($query, $con);
		$rowr = mysql_fetch_array($resultr);
		$rid=$rowr['id'];
		$query = "INSERT INTO " . $sql_database_name . ".chat (sender_id, receiver_id, content, time) VALUES ('%s', '%s', '%s', '%s')";
		$query = sprintf($query,$sid, $rid, mysql_real_escape_string($_POST['message']), date('Y-m-d H:i:s', strtotime('now')));
		mysql_query($query, $con);
		$key=1;
?>


<?php
function clean($str){
	return trim(mysql_real_escape_string($str));
}
function checkkey($key){
	while($key!=1)
	{}
}	
?>


