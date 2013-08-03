<?php
	require('session.php');
	
	if (isset($_GET['jobId']))
	{
		require('connect.php');
		
		// first delete all files from server related to the user
		$query = "select filePath from files where whichExists=1 and jobId=".$_GET['jobId'];
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		while($row = mysql_fetch_assoc($result))
		{
			unlink($row['filePath']);
		}
		
		$query = "delete from jobs where jobId=".$_GET['jobId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		$query = "delete from files where jobId=".$_GET['jobId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		$query = "delete from expenses where jobId=".$_GET['jobId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		mysql_close($con);
		header("Location:home.php");
	}
	else
	{
		header("Location:home.php");
	}
?>