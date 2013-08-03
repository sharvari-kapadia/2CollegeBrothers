<?php

	session_start();

	if (isset($_POST['loginUsername']))
	{
		//Start connection
		require("connect.php");
	
		$query = "select * from userinfo where username='".$_POST['loginUsername']."' and password='".$_POST['loginPass']."'";
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		if(mysql_num_rows($result) == 1)
		{
			$_SESSION['loggedIn'] = 1;
			$_SESSION['loginUsername'] = $_POST['loginUsername'];
			$row = mysql_fetch_assoc($result);
			if ($row['userType'])
			{
				$_SESSION['_userType_'] = $row['userType'];
				$_SESSION['_city_'] = $row['city'];
				$_SESSION['_userId_'] = $row['userId'];
			}
			
			header("Location:home.php");
		}
		else
		{
			require_once('logo.php');
			echo "<center><font class='error'><br /><br />Invalid username and/or password!</font></center>";
		}
		
		//Close connection
		mysql_close($con);
	}
	
	require_once('logo.php');
?>

<html>

<head>
	<title>
		.: Welcome :.
	</title>
	<link rel="stylesheet" href="loginstyle.css">
</head>

<body> 
	<br /> <br /> <br />
	<center><h3>Use movebro as username and password to login.</h3></center>
	<form name='loginForm' method='post' action='login.php'>
	<table align='center'>
		<tr>
			<td>Username</td>
			<td><input name='loginUsername' type='text'/></td>
	
			<td>Password</td>
			<td><input name='loginPass' type='password'/></td>
		
			<td><input type='submit' value=' Login ' /></td>
		</tr>
	</table>
	</form>
</body>

</html>