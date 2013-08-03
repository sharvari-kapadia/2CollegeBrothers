<?php

	require('session.php');
	
	// either userId or jobId should be set along with a compulsory filePath
	if (!isset($_GET['filePath']) || (!isset($_GET['userId']) && !(isset($_GET['jobId']))))
	{
		header("Location:home.php");
	}
	
	//Start connection with database
	require("connect.php");
	
	if (isset($_GET['userId']))
	{
		$query = "delete from files where userId = ".$_GET['userId']." and filePath = '".$_GET['filePath']."'";
	}
	else
	{
		$query = "delete from files where jobId = ".$_GET['jobId']." and filePath = '".$_GET['filePath']."'";
	}
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//delete the file from server
	unlink($_GET['filePath']);
	
	if (isset($_GET['userId']))
	{	
		header("Location:userinfoEdit.php?userId=".$_GET['userId']);
	}
	else
	{
		header("Location:jobsEdit.php?jobId=".$_GET['jobId']);
	}
	
	mysql_close($con);

?>