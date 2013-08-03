<?php
	require('session.php');
	require('logo.php');
?>

<html>

<head>
	<title>
		.: View Jobs :.
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
		<!-- Display Booked Jobs -->
		<table width='100%' border='1px'>
		<tr>
			<td colspan='33'><b>BOOKED JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;name</b></td>
			<td><b>Phone&nbsp;Number</b></td>
			<td><b>E-&nbsp;mail</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Description<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td><b>Date&nbsp;of&nbsp;Service2</b></td>
			<td><b>Description&nbsp;for&nbsp;second&nbsp;date</b></td>
			<td colspan='2'><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Deposit&nbsp;Date<b></td>
			<td><b>Assembly</b></td>
			<td><b>Truck&nbsp;Size</b></td>
			<td><b>Written&nbsp;Estimate&nbsp;Complete</b></td>
			<td><b>Start&nbsp;time</b></td>
			<td><b>End&nbsp;time</b></td>
			<td><b>Gas&nbsp;Expenses</b></td>
			<td><b>Truck&nbsp;Expenses</b></td>
			<td><b>Comments</b></td>
		</tr>
		<?php
			//Start connection with database
			require("connect.php");
			
			$query = "select jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, custAdd21, custAdd22,
						custCity2, custState2, custZip2, typeOfMove, description, numOfMovrs, dateOfService1, dateOfService2, descriptionForSecondDate, 
						timeOfService1, timeOfService2, depositDate, assembly, truckSize, writtenEstimateComplete, startTime, endTime, gasExpenses, 
						truckExpenses, comments from jobs where statusOfMove = 'Booked'";
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			// display the rows here
			while ($row = mysql_fetch_assoc($result))
			{
				echo "<tr>";
				echo 
					"<td><a href='jobsEdit.php?jobId=".$row['jobId']."'>Edit&nbsp;Job&nbsp;details</a>
					<br /><a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'>Delete&nbsp;Job</a></td>
					<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['custPhoneNumber']."</td>
					<td>".$row['custEmail']."</td>
					<td>".$row['custAdd11']."</td>
					<td>".$row['custAdd12']."</td>
					<td>".$row['custCity1']."</td>
					<td>".$row['custState1']."</td>
					<td>".$row['custZip1']."</td>
					<td>".$row['custAdd21']."</td>
					<td>".$row['custAdd22']."</td>
					<td>".$row['custCity2']."</td>
					<td>".$row['custState2']."</td>
					<td>".$row['custZip2']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".$row['dateOfService1']."</td>
					<td>".$row['dateOfService2']."</td>
					<td>".$row['descriptionForSecondDate']."</td>
					<td>".$row['timeOfService1']."</td>
					<td>".$row['timeOfService2']."</td>
					<td>".$row['depositDate']."</td>
					<td>".$row['assembly']."</td>
					<td>".$row['truckSize']."</td>
					<td>".$row['writtenEstimateComplete']."</td>
					<td>".$row['startTime']."</td>
					<td>".$row['endTime']."</td>
					<td>".$row['gasExpenses']."</td>
					<td>".$row['truckExpenses']."</td>
					<td>".$row['comments']."</td>";
				echo "</tr>";
			}
			
			//Close connection
			mysql_close($con);
		?>
		</table>
		<br />
		
		<!-- Display Pending Jobs -->
		<table width='100%' border='1px'>
		<tr>
			<td colspan='33'><b>PENDING JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;name</b></td>
			<td><b>Phone&nbsp;Number</b></td>
			<td><b>E-mail</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Description<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td><b>Date&nbsp;of&nbsp;Service2</b></td>
			<td><b>Description&nbsp;for&nbsp;second&nbsp;date</b></td>
			<td colspan='2'><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Deposit&nbsp;Date<b></td>
			<td><b>Assembly</b></td>
			<td><b>Truck&nbsp;Size</b></td>
			<td><b>Written&nbsp;Estimate&nbsp;Complete</b></td>
			<td><b>Start&nbsp;time</b></td>
			<td><b>End&nbsp;time</b></td>
			<td><b>Gas&nbsp;Expenses</b></td>
			<td><b>Truck&nbsp;Expenses</b></td>
			<td><b>Comments</b></td>
		</tr>
				<?php
			//Start connection with database
			require("connect.php");
			
			$query = "select jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, custAdd21, custAdd22,
						custCity2, custState2, custZip2, typeOfMove, description, numOfMovrs, dateOfService1, dateOfService2, descriptionForSecondDate, 
						timeOfService1, timeOfService2, depositDate, assembly, truckSize, writtenEstimateComplete, startTime, endTime, gasExpenses, 
						truckExpenses, comments from jobs where statusOfMove = 'Pending'";
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			// display the rows here
			while ($row = mysql_fetch_assoc($result))
			{
				echo "<tr>";
				echo 
					"<td><a href='jobsEdit.php?jobId=".$row['jobId']."'>Edit&nbsp;Job&nbsp;details</a>
					<br /><a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'>Delete&nbsp;Job</a></td>
					<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['custPhoneNumber']."</td>
					<td>".$row['custEmail']."</td>
					<td>".$row['custAdd11']."</td>
					<td>".$row['custAdd12']."</td>
					<td>".$row['custCity1']."</td>
					<td>".$row['custState1']."</td>
					<td>".$row['custZip1']."</td>
					<td>".$row['custAdd21']."</td>
					<td>".$row['custAdd22']."</td>
					<td>".$row['custCity2']."</td>
					<td>".$row['custState2']."</td>
					<td>".$row['custZip2']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".$row['dateOfService1']."</td>
					<td>".$row['dateOfService2']."</td>
					<td>".$row['descriptionForSecondDate']."</td>
					<td>".$row['timeOfService1']."</td>
					<td>".$row['timeOfService2']."</td>
					<td>".$row['depositDate']."</td>
					<td>".$row['assembly']."</td>
					<td>".$row['truckSize']."</td>
					<td>".$row['writtenEstimateComplete']."</td>
					<td>".$row['startTime']."</td>
					<td>".$row['endTime']."</td>
					<td>".$row['gasExpenses']."</td>
					<td>".$row['truckExpenses']."</td>
					<td>".$row['comments']."</td>";
				echo "</tr>";
			}
			
			//Close connection
			mysql_close($con);
		?>
		</table>
		<br />
		
		<!-- Display Completed Jobs -->
		<table width='100%' border='1px'>
		<tr>
			<td colspan='33'><b>COMPLETED JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;name</b></td>
			<td><b>Phone&nbsp;Number</b></td>
			<td><b>E-mail</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Address<b></td>
			<td><b>Address</b></td>
			<td><b>City</b></td>
			<td><b>State<b></td>
			<td><b>Zip&nbsp;Code</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Description<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td><b>Date&nbsp;of&nbsp;Service2</b></td>
			<td><b>Description&nbsp;for&nbsp;second&nbsp;date</b></td>
			<td colspan='2'><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Deposit&nbsp;Date<b></td>
			<td><b>Assembly</b></td>
			<td><b>Truck&nbsp;Size</b></td>
			<td><b>Written&nbsp;Estimate&nbsp;Complete</b></td>
			<td><b>Start&nbsp;time</b></td>
			<td><b>End&nbsp;time</b></td>
			<td><b>Gas&nbsp;Expenses</b></td>
			<td><b>Truck&nbsp;Expenses</b></td>
			<td><b>Comments</b></td>
		</tr>
				<?php
			//Start connection with database
			require("connect.php");
			
			$query = "select jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, custAdd21, custAdd22,
						custCity2, custState2, custZip2, typeOfMove, description, numOfMovrs, dateOfService1, dateOfService2, descriptionForSecondDate, 
						timeOfService1, timeOfService2, depositDate, assembly, truckSize, writtenEstimateComplete, startTime, endTime, gasExpenses, 
						truckExpenses, comments from jobs where statusOfMove = 'Complete'";
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			// display the rows here
			while ($row = mysql_fetch_assoc($result))
			{
				echo "<tr>";
				echo 
					"<td><a href='jobsEdit.php?jobId=".$row['jobId']."'>Edit&nbsp;Job&nbsp;details</a>
					<br /><a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'>Delete&nbsp;Job</a></td>
					<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['custPhoneNumber']."</td>
					<td>".$row['custEmail']."</td>
					<td>".$row['custAdd11']."</td>
					<td>".$row['custAdd12']."</td>
					<td>".$row['custCity1']."</td>
					<td>".$row['custState1']."</td>
					<td>".$row['custZip1']."</td>
					<td>".$row['custAdd21']."</td>
					<td>".$row['custAdd22']."</td>
					<td>".$row['custCity2']."</td>
					<td>".$row['custState2']."</td>
					<td>".$row['custZip2']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".$row['dateOfService1']."</td>
					<td>".$row['dateOfService2']."</td>
					<td>".$row['descriptionForSecondDate']."</td>
					<td>".$row['timeOfService1']."</td>
					<td>".$row['timeOfService2']."</td>
					<td>".$row['depositDate']."</td>
					<td>".$row['assembly']."</td>
					<td>".$row['truckSize']."</td>
					<td>".$row['writtenEstimateComplete']."</td>
					<td>".$row['startTime']."</td>
					<td>".$row['endTime']."</td>
					<td>".$row['gasExpenses']."</td>
					<td>".$row['truckExpenses']."</td>
					<td>".$row['comments']."</td>";
				echo "</tr>";
			}
			
			//Close connection
			mysql_close($con);
		?>
		</table>
		<br />
		
	</td>
	</tr>
	</table>
</body>
</html>

