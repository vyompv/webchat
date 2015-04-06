<?php include('ext.inc'); 
//get friendname userid	
		if((isset($_POST['friendname'])))
		{
		$username = clean($_POST['friendname']);	
		//$username="test 3";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE displayname='%s'";
		$query = sprintf($query, $username);
		$result = mysql_query($query, $con);
		$rowr = mysql_fetch_array($result);
		echo "". $rowr['id'] ."";
		}
		if((isset($_POST['dispname'])))
		{
		$username = clean($_POST['dispname']);	
		//$username="test3";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT displayname FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, $username);
		$result = mysql_query($query, $con);
		$rowr = mysql_fetch_array($result);
		echo "". $rowr['displayname'] ."";
		}
//Database update on userlogout
		if((isset($_GET['logout'])))
		{
		$username = clean($_GET['logout']);	
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "UPDATE ". $sql_database_name .".reg_info SET onlinestatus=0 WHERE username='%s'";
		$query = sprintf($query, $username);
		mysql_query($query, $con);
		echo "<script>window.location='index.php';</script>";
		}
?>
<?php
function clean($str){
	return trim(mysql_real_escape_string($str));
}
?>
