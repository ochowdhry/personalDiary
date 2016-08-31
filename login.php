<?php
	
	session_start();
	
	if ($_GET["logout"]==1 AND $_SESSION['id']) {
		
		session_destroy();
		
		$logoutMessage = "You have been logged out. Have a nice day!";
		
	}
	
	include ("connection.php");

	if($_POST['submit']=="Sign Up") {
	
		if (!$_POST['email']) $error.="<br />please enter your email address!";
		else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $error.="<br />Please enter a valid email address";
		
	if (!$_POST['password']) $error.="<br />please enter your password!";
	
	else {
	
	 if (strlen($_POST['password'])<8) $error.="<br />Password should be at least 8 characters long";
	 if (!preg_match('/[A-Z]/', $_POST['password'])) $error.="<br />Please enter at least 1 uppercase letter in password";
		
	}
	if ($error) $error = "There was an error in signing up:".$error;
	
		else {
						
			$query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";
			
			$result = mysqli_query($link, $query);
			
			$resultNum = mysqli_num_rows($result);
			
			if($resultNum) $error = "This email already exists. Would you like to sign in?";
			else {
				
				$query = "INSERT INTO users (email, password) VALUES ('".mysqli_real_escape_string($link,$_POST['email'])."', '".md5(md5($_POST['email']).$_POST['password'])."')";
				
				mysqli_query($link,$query);
			
				echo "You've been signed up!";
				
				$_SESSION['id'] = mysqli_insert_id($link);
				
				header("Location:mainpage.php");
				
			}
	
		}
	
	}
	
	if ($_POST['submit']=="Log In")	{
		
		$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link,$_POST['loginemail'])."' AND password = '".md5(md5($_POST['loginemail']).$_POST['loginpassword'])."'";
		$result = mysqli_query($link,$query);
			
		$row = mysqli_fetch_array($result);
		
		if ($row) {
			
			$_SESSION['id']=$row['id'];
			
			header("Location:mainpage.php");
			
		}else {
			
			$error = "That username and password could not be found.";
		}
	}

	
?>