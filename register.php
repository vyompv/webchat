<?php
	include('ext.inc');
//Signup and store the database input for data creation
	if(isset($_POST['username']))
	{
		//if database is not yet created
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "CREATE DATABASE IF NOT EXISTS ". $sql_database_name;
		mysql_query($query, $con);
		
		//Create tables if they dont exist
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".reg_info (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(12) NOT NULL, displayname VARCHAR(12) NOT NULL, password VARCHAR(120) NOT NULL,onlinestatus BOOL NOT NULL DEFAULT FALSE,INDEX (id), UNIQUE(username)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".chat (chat_id INT NOT NULL AUTO_INCREMENT, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL DEFAULT 'Not Read', mime VARCHAR(20) NOT NULL DEFAULT 'chat', INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".archive (chat_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL, mime VARCHAR(20) NOT NULL, INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".online (id INT NOT NULL AUTO_INCREMENT, time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (id))";
		mysql_query($query, $con);
		
		
		//Username and others should be checked for duplicate here
		$query = "SELECT * FROM " . $sql_database_name . ".reg_info WHERE username='%s'";
		$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])));
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			//header("Location: signup.php?unamee");
			//ob_end_flush();
			echo "User Alredy Registered";
		}
		
		$query = "INSERT INTO " . $sql_database_name . ".reg_info (username, displayname, password) VALUES ('%s', '%s', '%s')";
		$password = crypt($_POST['password'],'pole'); // let the salt be automatically generated
		$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])), mysql_real_escape_string(stripslashes($_POST['displayname'])), $password);
		if(mysql_query($query, $con))
		{
			$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
			$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])));
			$result = mysql_query($query, $con);
			$row = mysql_fetch_assoc($result);
			//Register the user into the online table
			$query = "INSERT INTO " . $sql_database_name . ".online (id, time) VALUES ('%s',NOW())";
			$query = sprintf($query, $row['id']);
			mysql_query($query, $con);
			//print_r($row);
			//start the session
			session_start();
			$_SESSION['uid'] = $row['id'];
			$_SESSION['username'] = $_POST['username'];
			echo "Successfully Registered!<br>";
			
		}
		else
		{
			echo "An error occured! <br>" . mysql_error();
		}
	}
	
	//none
	//$location = "Location: " . $_SERVER['HTTP_REFERER'] . "?unknown";
	//header($location);
?>