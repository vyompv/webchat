<html>
	<head>
		<title>Webchat - Sign up</title>
		<link href="style.css" media=all type="text/css" rel="stylesheet" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	</head>
	<script>
		$(document).ready(function() {
			$("#submit").attr("disabled", true);	
			$("#cpwd").keyup(validate);});
		
		$(document).ready(function(){
			
			$('#signupform').submit(function(){
			 
				// show that something is loading
				$('#response').html("<b>Loading response...</b>");
				 
				/*
				 * 'post_receiver.php' - where you will pass the form data
				 * $(this).serialize() - to easily read form data
				 * function(data){... - data contains the response from post_receiver.php
				 */
				$.ajax({
					type: 'POST',
					url: 'register.php', 
					data: $(this).serialize()
				})
				.done(function(data){
					 
					// show the response
					$('#response').html(data);
					 
					})
				.fail(function() {
				 
					// just in case posting your form failed
					alert( "Posting failed." );
					 
					});
		 
				// to prevent refreshing the whole page page
				return false;
		 
			});
		});
		function validate() {
		  var password1 = $("#password").val();
		  var password2 = $("#cpwd").val();
			if(password1 == password2) {
			   $("#response").text("Password confirmed"); 
			  $("#submit").attr("disabled", false);			   
			}
			else {
				$("#response").text("Confirm password not matching");  
				$("#submit").attr("disabled", true);
			}
		}
	</script>	
	<body>
		<div id="signup">
			<form name="signupform" id="signupform" >
			<h1>Sign Up</h1>
			<p>
				<label for="user_login">Username<br />
				<input placeholder="Username" type="text" name="username" id="user_signup" value="" /></label>
			</p>
			<p>
				<label for="user_login">Display name<br />
				<input placeholder="Display name" type="text" name="displayname" id="user_signup" value="" /></label>
			</p>
			<p>
				<label for="user_pass">Password<br />
				<input placeholder="Password" type="password" name="password" id="password" value="" /></label>
			</p>
			<p>
				<label for="user_pass">Confirm Password<br />
				<input placeholder="Confirm Password" type="password" name="cpwd" id="cpwd" value="" /></label></p>
			<br>
			<p id="response"></p>
			<br>
			<p class="submit">
				<input type="submit" id="submit" class="btn" value="Sign Up" />
			</p>
			</form>
			<a href='index.php'>Login</a>
		</div>
		</body>
</html>