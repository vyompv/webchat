<?php
include('ext.inc');
//display the chat history for the username and friendid
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
		$query = "select * from (SELECT * FROM ". $sql_database_name .".chat WHERE (receiver_id='%s' and sender_id='%s') or (receiver_id='%s' and sender_id='%s')  order by time desc)sub order by time asc ";
		//$query = "SELECT  chat.content,chat.sender_id,chat.receiver_id,chat.chat_id,chat.time,reg_info.displayname FROM ". $sql_database_name .".chat,". //$sql_database_name .".reg_info WHERE receiver_id='%s' or sender_id='%s' order by chat_id";
		$query = sprintf($query,$friendid,$sid,$sid,$friendid);
		$resultr = mysql_query($query, $con);
		//$rowr = mysql_fetch_array($resultr);
		while($v = mysql_fetch_array($resultr)){
			if($v['sender_id']==$sid)	
				{echo  "<b>".$username.":</b><small>".$v['time']."</small>-". $v['content']."<br>";
				 $lasttime=$v['time'];
				 }
			else 
				{echo  "<b>".$friendname.":</b><small>".$v['time']."</small>-". $v['content']."<br>"; 
				 $lasttime=$v['time'];
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