<?php
include('ext.inc');

//Load chat messages read and unread based on username. Also load in the chatbox
$username = clean($_GET['username']);
date_default_timezone_set('US/Pacific');
$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, $username);
		$resultr = mysql_query($query, $con);
		$rowr = mysql_fetch_array($resultr);
		$sid=$rowr['id'];
		checkkey($key);
		if((isset($_GET['friendid'])))
		{$key=0;
		$friendid=clean($_GET['friendid']);
		$query = "SELECT username FROM ". $sql_database_name .".reg_info WHERE id='%s'";
		$query = sprintf($query, $friendid);
		$resultr = mysql_query($query, $con);
		$rowr = mysql_fetch_array($resultr);
		$friendname=$rowr['username'];
		$query = "select * from (SELECT * FROM ". $sql_database_name .".chat WHERE (receiver_id='%s' and sender_id='%s') or (receiver_id='%s' and sender_id='%s')  order by time desc limit 30)sub order by time asc ";
		$query = sprintf($query,$friendid,$sid,$sid,$friendid);
		$resultr = mysql_query($query, $con);
		while($v = mysql_fetch_array($resultr)){
			if($v['sender_id']==$sid)	
				{echo  "<small>".$v['time']."</small>-<b>".$username."</b>:". $v['content']."<br>"; 
				 $lasttime=$v['time'];
				 }
			else 
				{echo  "<small>".$v['time']."</small>-<b>".$friendname."</b>:". $v['content']."<br>"; 
				 $lasttime=$v['time'];
				}
			
		}echo "<script>lasttime=\"". $lasttime ."\";</script>";
		$query = "UPDATE ".$sql_database_name .".chat SET status='Read' WHERE (receiver_id='%s' and sender_id='%s') order by time desc";
		$query = sprintf($query,$sid,$friendid);
		$resultr = mysql_query($query, $con);
		$key=1;
		}	
if((isset($_GET['delete'])))	
{	//Moving to archive
		$query = "INSERT INTO ". $sql_database_name .".archive (SELECT * FROM ". $sql_database_name .".chat WHERE (receiver_id='%s' and sender_id='%s') or (receiver_id='%s' and sender_id='%s') and status='Read' order by time desc limit 30)";
		$query = sprintf($query,$friendid,$sid,$sid,$friendid);
		$resultr = mysql_query($query, $con);
		
		//Delete read in chat table
		$query = "DELETE FROM " . $sql_database_name . ".chat WHERE (receiver_id='%s' and sender_id='%s') or (receiver_id='%s' and sender_id='%s') and status='Read'";
		$query = sprintf($query,$friendid,$sid,$sid,$friendid);
		$resultr = mysql_query($query, $con);
}	

if((isset($_GET['unread'])))	
{	$query = "SELECT ". $sql_database_name .".chat.time,". $sql_database_name .".chat.content,". $sql_database_name .".chat.sender_id,". $sql_database_name .".reg_info.displayname FROM ". $sql_database_name .".chat join ". $sql_database_name .".reg_info on ". $sql_database_name .".chat.sender_id = ". $sql_database_name .".reg_info.id WHERE status='Not Read' and receiver_id='%s' order by chat.time desc";
		$query = sprintf($query,$sid) or die;
		$resultr = mysql_query($query, $con) or die($query."<br/><br/>".mysql_error());
		if(mysql_num_rows($resultr) > 0)
		{ 
			while($v=mysql_fetch_array($resultr))
					{
					echo  "<b>".$v['displayname'].":</b><small>".$v['time']."</small>-<b>". $v['content']."</b><br>"; 
					}	
		}
}		
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