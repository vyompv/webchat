<?php 	include('ext.inc');
		$con = mysql_connect($sql_server, $sql_username, $sql_password) or die("Could not connect: " . mysql_error());	
//Get online and offline friends for the username
		if(isset($_POST['username']))
		{
			$inputname=clean($_POST['username']);
			$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
			$query = sprintf($query, $inputname);
			$resultr = mysql_query($query, $con);
			$rowr = mysql_fetch_array($resultr);
			$sid=$rowr['id'];
			$query = "select distinct(". $sql_database_name .".friendslist.receiver_id),". $sql_database_name .".reg_info.displayname from ". $sql_database_name .".friendslist  join ". $sql_database_name .".reg_info  on ". $sql_database_name .".friendslist.receiver_id=". $sql_database_name .".reg_info.id where ". $sql_database_name .".reg_info.onlinestatus=1 and ". $sql_database_name .".friendslist.sender_id='%s'";
			$query = sprintf($query, $sid);
			$result1 = mysql_query($query, $con) or die(":(".mysql_error());
			$query = "select distinct(". $sql_database_name .".friendslist.receiver_id),". $sql_database_name .".reg_info.displayname from ". $sql_database_name .".friendslist  join ". $sql_database_name .".reg_info  on ". $sql_database_name .".friendslist.receiver_id=". $sql_database_name .".reg_info.id where ". $sql_database_name .".reg_info.onlinestatus=0 and ". $sql_database_name .".friendslist.sender_id='%s'";
			$query = sprintf($query, $sid);
			$result2 = mysql_query($query, $con) or die(":(".mysql_error());
			$names1 = array();$names2 = array();
			if(mysql_num_rows($result1)>0)
			{
				
				while ($row1 = mysql_fetch_array($result1)) {
				$names1[] = $row1['displayname'];} 
			}
				if(mysql_num_rows($result1)>0)
			{	while ($row2 = mysql_fetch_array($result2)) {
				$names2[] = $row2['displayname'];}
			} 
				echo json_encode(array('result1'=>$names1,'result2'=>$names2));
			
		}
		 // else{
			// echo '<span class="error">Please relogin</span>';
		// }
?>		
<?php
function clean($str){
	return trim(mysql_real_escape_string($str));
}
?>





