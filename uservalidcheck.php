<?php 	include('ext.inc');
		$con = mysql_connect($sql_server, $sql_username, $sql_password) or die("Could not connect: " . mysql_error());;
		$query = "CREATE DATABASE IF NOT EXISTS ". $sql_database_name;
		mysql_query($query, $con);
		
		//Create tables if they dont exist
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".reg_info (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(12) NOT NULL, displayname VARCHAR(12) NOT NULL, password VARCHAR(120) NOT NULL,onlinestatus BOOL NOT NULL DEFAULT FALSE,INDEX (id), UNIQUE(username)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".chat (chat_id INT NOT NULL AUTO_INCREMENT, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL DEFAULT 'Not Read', mime VARCHAR(20) NOT NULL DEFAULT 'chat', INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".archive (chat_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL, mime VARCHAR(20) NOT NULL, INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".friendslist (id INT NOT NULL AUTO_INCREMENT, sender_id INT NOT NULL, receiver_id INT NOT NULL,time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (id))";
		mysql_query($query, $con);
		
				
		//Username and password check.
		
		if(isset($_POST['username'],$_POST['password']))
		{
			$inputname=$_POST['username'];
			$inputpassword=crypt($_POST['password'],'pole');
			$query = "SELECT * FROM " . $sql_database_name . ".reg_info WHERE username='%s'";
			$query = sprintf($query, mysql_real_escape_string(stripslashes($inputname)));
			$result = mysql_query($query, $con) or die(":(");
			$row = mysql_fetch_array($result) or die("Please <a href=\"signup.php\">Sign up here</a>");
			if(mysql_num_rows($result)>0)
			{
				$username=$row['username'];
				$status=$row['onlinestatus'];
				$dispname=$row['displayname'];
				$passwd=$row['password'];
				if($inputpassword==$passwd)
				{
					if(!$status)
					{
						$now = date('Y-m-d h:i:s', strtotime('now'));
						$query = "UPDATE " . $sql_database_name . ".online SET time = '%s' WHERE id = '%s'";
						$query = sprintf($query, $now, $row['id']);
						mysql_query($query, $con) or die("Trouble in database checkin time");
						$query = "UPDATE " . $sql_database_name . ".reg_info SET onlinestatus = TRUE WHERE username = '%s'";
						$query = sprintf($query,$username);
						mysql_query($query, $con) or die("Trouble in database checkin.");
						session_start();
						$_SESSION['name'] = stripslashes(htmlspecialchars($username));
						$_SESSION['dispname'] = stripslashes(htmlspecialchars($dispname));
						$enc=randomPassword();
						$enc1=randomPassword();						
						echo "<script>window.location='welcome.php?sessionuser=".$enc."&sessionencoder=".$username."&".$enc1."';</script>";
					}
					else//log out option if userlogged in
					{echo '<span class="error">User already logged in!<br></span>';
						echo '<a id="logout" href="./userid.php?logout='.$username.'">Sign Out</a>';
					}
				}
				else 
					echo '<span class="error">Please type in correct name or password</span>';
			}
		}
		 else{
			echo '<span class="error">Please type in a name or password</span>';
		}
		//encoding random password for url
	function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 28; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); 
}
?>		