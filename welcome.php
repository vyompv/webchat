<!DOCTYPE html>
<html>
<head>
<title>Web chat</title>
	<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" />
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" href="css/jquery.ui.chatbox.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery.ui.chatbox.js"></script>
    <script type="text/javascript" src="js/chatboxManager.js"></script>
	<link type="text/css" rel="stylesheet" href="style.css" />
<style type="text/css" media="screen">
.btn {
background-color: #E3E1B8; 
padding: 2px 6px;
font: 13px sans-serif;
text-decoration: none;
border: 1px solid #000;
border-color: #aaa #444 #444 #aaa;
color: #000;
background-color:lightblue;
}

.center {
    margin-left: auto;
    margin-right: auto;
    width: 60%;
    background-color: #FFFF00;
	overflow-y: scroll; max-height:400px;
    position: relative;
    -webkit-animation: mymove 5s infinite; /* Chrome, Safari, Opera */
    -webkit-animation-delay: 2s; /* Chrome, Safari, Opera */
    animation: mymove 5s infinite;
    animation-delay: 2s;
}


@-webkit-keyframes mymove {
    from {left: 0px;}
    to {left: 200px;}
}

@keyframes mymove {
    from {left: 0px;}
    to {left: 200px;}
}
.highlight {
    background-color: yellow;
}
.rad {
    background-color: yellow;
}
.title{
	color:#90ee90;
}
    </style>
<script>
var user_name;
var friendname;
var displayusername;
var friendid;
var counter = 0;
var idList = new Array();
var lastmsgtim = new Array();
var lasttime=0;
//get selected friendid from database.
function setfriendid(){
	       $.post("userid.php",{ friendname: friendname },function(data){
			friendid=data;
});
		   
}
$(document).ready(function(){
//Storing the username	
<?php	if((isset($_GET['sessionencoder'])))
	{echo "user_name=\"".$_GET['sessionencoder']."\";"; $username=$_GET['sessionencoder'];}
if((isset($_SESSION['name'])))
	{echo "user_name=\"".$_SESSION['name']."\";";}
?>

$.ajaxSetup ({
    // Disable caching of AJAX responses 
    cache: false
});	
		});
		
 $(document).ready(function() {
//Display welcome username  
  $.post("userid.php",{ dispname: user_name },function(data){displayusername=data; $("#user").html("<h1><span class=\"title\">Welcome "+displayusername+"<h1></span>");});

//Load online and offline users at specific interval.
setInterval(function() {
 
	   var value;
	  $('#listusers').html(value); 
       $.post("onlineusers.php", { username: user_name }, function(data){
	var value="";  
	var result=JSON.parse(data);  
	  $.each(result.result1, function(index, val) {
	 value+="<input type=\"radio\" id=\"btn\" style=\"color:green\"class=\"rad\" onclick=\"friendname='"+val+"';setfriendid();\" value=\'"+val+"'\/><span class=\"highlight\">"+val+"</span><br>";
	  });
	  $.each(result.result2, function(index, val2) {
	 value+="<input type=\"radio\" id=\"btn\" style=\"color:red\"class=\"rad\" onclick=\"friendname='"+val2+"';setfriendid();\" value=\'"+val2+"'\/>"+val2+"<br>";
	  }
);  
			 $('#listusers').html(value); 
       });
}, 6000);      
 
//Post username and friendname to delete the previous chat
 setInterval(function(){$.post('load_messages.php', {'username': user_name, 'friendname' : friendname, 'delete' : 'del'});},120000);
//Display selected friend name
 setInterval(function(){$('#response').html("<b>Selected FriendName:"+friendname+"</b>");},80);
	  

    var box = null;
   var counter = 0;
      var idList = new Array();

	
//load messages from databse to the user chat
	setInterval(function(){
	  var url1="load_messages.php?unread=all&username="+user_name;
	  $("#unread").load(url1);}, 1000);
		lasttime="2014:12:1-2-3";
	   setInterval(function(){for(var i = 0; i < idList.length; i++) {
		var fid=idList[i].split('box');
		var time1=0; var time2=0;
//store the previos message sent time for popping/updating chatbox.	
		url="load_messages.php?friendid="+fid[1]+"&username="+user_name;
		if(isNaN(lasttime))
		{	time1 = replaceAll(lasttime,':','');
			time2 = replaceAll(time1,'-','');
			time1 = replaceAll(time2,' ','');
			lasttime=Number(time1);
		}
		console.log("hre"+lasttime);console.log("arraytime"+lastmsgtim[i]);
		if(lastmsgtim[i]== lasttime){
			$("#" + idList[i]).load(url);
		}
	  else{
			mesg=$("#" + idList[i]).load(url);lastmsgtim[i]= lasttime; 
			$("#"+idList[i]).chatbox("option", "boxManager").addMsg(friendname, mesg);
		} 
	  
	   }}, 900);
	   function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
		}
		function replaceAll(string, find, replace) {
		  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
		}
	  
//Send message to the database after the user input
		chatboxManager.init({messageSent : function(from, msg) {
			friendname=from;setfriendid();
			var d=new Date();timen=d.toISOString();
			for(var i = 0; i < idList.length; i ++) {
				var fid=idList[i].split('box');
				url="load_messages.php?friendid="+fid[1]+"&username="+user_name;
				chatboxManager.addBox(idList[i]);
				if("box"+friendid==idList[i])
				{
					$("#" + idList[i]).chatbox("option", "boxManager").addMsg(user_name, msg);
					$.post('send_message.php', {'username': user_name, 'sendto' :from, 'message' : msg});
				}
			}
		}});
//Open the chat box manager and  add last name of the friend		
		 $("#link_add").click(function(event, ui) {
          counter ++;
          var id = "box" + friendid;
          idList.push(id);
          chatboxManager.addBox(id, 
                                  {
                                   first_name:friendname,
                                 last_name:"Chatting"
                                  });
          event.preventDefault();
      });
	    setInterval(function(){
	  $('#addresponse').text('');}, 5000);
      });
//For chat history	  
	function chathistory(){
		url="./chathistory.php?username="+user_name+"&friendid="+friendid;
		window.open(url);	
	}
//Add friends and update friend list in database
	function addfriend(){
	var friendid = document.forms["friendform"]["friendid"].value;
	formData = "username="+user_name+"&friendid="+friendid;
		$('#friendform').submit(function(){		 
					$('#addresponse').html("<span class=\"highlight\">Adding friend</span>");
							$.post("addfriends.php",
        {
			  username: user_name,
			  friendid: friendid
        },
        function(data,status){
            $('#addresponse').html(data);
        }); return false;
				});
}
//Logout and clear the value in chat online status
function logout(val){
	console.log(document.getElementById('logout').attributes[1].value);
	val.attributes[0].value="./userid.php?logout="+user_name;
  window.location='./userid.php?logout='+user_name;
} 	  

</script>
</head>
<body>
<header>
<div id="user"></div>
</header>
<!--For logout-->
<div id="logout"  style="position: absolute; top:0; right: 0; align:right">
<a id="logout" href="" onclick="logout(this)">Sign Out</a>
</div>
<div>
<!--For display offline/online users-->
<div id="listusers"  style="float:left;clear: both;overflow-y: scroll; width:90px;max-height:400px; font-weight: bold;align:left">
<span>Loading friendslist</span>
</div>
<!--For chatbox display-->
<div id="link_add"  style="float:left;clear: both;width:90px;font-weight: bold;align:left">
 <a id="link_add" href="#">Start chat</a><br><br>
</div>
<!--For chat history-->
<div   style="float:left;clear: both;width:90px;font-weight: bold;align:left">
<input class="btn" id="btn" onclick="chathistory();" type="button" value="Chat History"/><br><br>
</div>
<!--For Add friend-->
<div style="float:left;clear: both;width:70px;font-weight: bold;align:left">
	<form name="friendform" id="friendform" >
	<input type="text" name="friendid" id="friendid" placeholder="New Friendid"/>
	<input type=submit class="btn" id="btn" onclick="addfriend();" type="button" value="Add friend"/>	
	</form>
</div>
<!--For unread messages-->
<div id="unread"  class="center" >
Unread Messages
</div>
</div>
<!--For response after selecting friend-->
<div id="response">
</div>
<!--For response after adding friend-->
<div id="addresponse" style="font-weight: bold" >
</div>

</body>
</html>