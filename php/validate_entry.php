<?php
        include("config.php");
	$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
	/* $ID = $_POST['user']; $Password = $_POST['pass']; */
	function SignIn()
	{
		session_start(); //starting the session for user profile page
		//echo "username : ".$_POST['username'];
		//echo "password : ".$_POST['password'];
		if(empty($_POST['username'])){ //checking the 'user' name which is from Sign-In.html, is it empty or have some text
                        echo "Username cannot be left blank. Login Failed.";
                } elseif (empty($_POST['password'])){
                        echo "Password cannot be left blank. Login Failed.";
                }else {
			$query = mysqli_query($GLOBALS['con'],"SELECT * FROM user_data where lower(USERNAME) = lower('$_POST[username]') AND PASSWORD = '$_POST[password]'");
			$row = mysqli_fetch_array($query,MYSQLI_BOTH);
			//echo "user: ".$row['username'];
			//echo "pswd: ".$row['password'];
            if(!empty($row['username']) AND !empty($row['password'])) 
			{ 
				//$_SESSION['USERNAME'] = $row['PASSWORD'];
                                $_SESSION['loggedin'] = true;
                                $_SESSION['userid'] = $row['username'];
                                $_SESSION['username'] = $_POST['username'];
                                //$_SESSION['profilepic'] = $row['ProfilePic'];
                                header("Location: ../homepage.php");
				exit();
			} else {
				echo "SORRY... YOU ENTERED WRONG ID AND/OR PASSWORD... PLEASE RETRY..."; 
			} 
		}
	} 
	if(isset($_POST['signIn']))
	{
		SignIn();
	}
?>