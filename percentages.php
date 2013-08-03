<?php
	require('session.php');
	
	if ($_SESSION['_userType_'] != 'Owner' && $_SESSION['_userType_'] != 'Admin')
	{
		header("Location:home.php");
	}
	
	if (isset($_POST['transactionFeePer']))
	{
		$_POST['transactionFeePer'] = ('' == $_POST['transactionFeePer'] ? 0 : $_POST['transactionFeePer']);
		$_POST['staffingFeePer'] = ('' == $_POST['staffingFeePer'] ? 0 : $_POST['staffingFeePer']);
		$_POST['bankPer'] = ('' == $_POST['bankPer'] ? 0 : $_POST['bankPer']);
		
		//Start connection with database
		require("connect.php");
		$query = "update percentages set transactionFeePer=".$_POST['transactionFeePer'].", staffingFeePer=".$_POST['staffingFeePer'].", 
					bankPer=".$_POST['bankPer'];
				
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
				
		//Close connection
		mysql_close($con);
		
		header("Location:home.php");
	}
		
	require('logo.php');
	
?>

<html>

<head>
	<title>
		.: Percentages :.
	</title>
	<link rel="stylesheet" href="homestyle.css">
	<script type="text/javascript" src="js/confirmation.js"></script>
</head>
 
<body>
	<table class='outertable' width='80%' align='center'>
	<tr>
	<td class='homestyle' width='0%' valign='top'>
		<?php
			require('leftbarlinks.php');
		?>
	</td>
	<td width='80%' valign='top'>
			
		<table width='100%'>
		<?php
			//Start connection with database
			require("connect.php");
					
			$query = "select * from percentages";
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
	
			if ($row = mysql_fetch_assoc($result))
			{
				echo "<form name='percentages' method='post' action='percentages.php'>
					<tr>
						<td colspan='2' align='center'><h3>PERCENTAGES</h3></td>
					</tr>
					<tr>
						<td>Transaction Fee Percentage</td>
						<td><input type='text' name='transactionFeePer' value='".$row['transactionFeePer']."' /></td>
					</tr>
					<tr>
						<td>Staffing Fee Percentage</td>
						<td><input type='text' name='staffingFeePer' value='".$row['staffingFeePer']."' /></td>
					</tr>
					<tr>
						<td>Bank Percentage</td>
						<td><input type='text' name='bankPer' value='".$row['bankPer']."' /></td>
					</tr>
				<tr><td colspan='2'><input type='submit' value='Save' /></td></tr>
				</tr>
				<form>";
			}
					
			//Close connection
			mysql_close($con);
		?>
		
		</table>
	</td>
	</tr>
	</table>
</body>
</html>

