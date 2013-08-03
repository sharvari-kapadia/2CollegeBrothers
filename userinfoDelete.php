<?php
	require('session.php');
	
	if (isset($_GET['userId']))
	{
		require('connect.php');
		
		// first delete all files from server related to the user
		$query = "select filePath from files where whichExists=-1 and userId=".$_GET['userId'];
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		while($row = mysql_fetch_assoc($result))
		{
			unlink($row['filePath']);
		}
		
		$query = "delete from files where whichExists=-1 and userId=".$_GET['userId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		$query = "delete from userinfo where userId=".$_GET['userId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		mysql_close($con);
		header("Location:userinfoView.php");
	}
	else
	{
		header("Location:userinfoView.php");
	}
?>