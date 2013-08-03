<?php
	require('session.php');
	require('logo.php');
?>

<html>

<head>
	<title>
		.: Home :.
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
			<td colspan='12'><b>BOOKED JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;Name</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Decription<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td colspan=2><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Truck&nbsp;Size</b></td>
		</tr>
		<?php
			//Start connection with database
			require("connect.php");
			
			// if owner display all, else display only city wise
			if ($_SESSION[_userType_] == 'Owner' || $_SESSION[_userType_] == 'Admin')
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Booked' order by dateOfService1 asc";
			}
			else
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Booked' and branch = '".$_SESSION['_city_']."' order by dateOfService1 asc";
			}
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
				if ($_SESSION['_userType_'] != 'Mover')
				{
					echo"<td>
							<a href='jobsEdit.php?jobId=".$row['jobId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}

				echo "<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".date('m/d/Y', strtotime($row['dateOfService1']))."</td>
					<td colspan='2'>".$row['timeOfService1']."&nbsp;to&nbsp;".$row['timeOfService2']."</td>
					<td>".$row['truckSize']."</td>";
				
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
			<td colspan='12'><b>PENDING JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;Name</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Decription<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td colspan=2><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Truck&nbsp;Size</b></td>
		</tr>
				<?php
			//Start connection with database
			require("connect.php");
			
			if ($_SESSION[_userType_] == 'Owner' || $_SESSION[_userType_] == 'Admin')
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Pending' order by dateOfService1 asc";
			}
			else
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Pending' and branch = '".$_SESSION['_city_']."' order by dateOfService1 asc";
			}
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
				if ($_SESSION['_userType_'] != 'Mover')
				{
					echo"<td>
							<a href='jobsEdit.php?jobId=".$row['jobId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}

				echo "<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".date('m/d/Y', strtotime($row['dateOfService1']))."</td>
					<td colspan='2'>".$row['timeOfService1']."&nbsp;to&nbsp;".$row['timeOfService2']."</td>
					<td>".$row['truckSize']."</td>";
				
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
			<td colspan='12'><b>COMPLETED JOBS</b></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Job&nbsp;Id</b></td>
			<td><b>Branch<b></td>
			<td><b>Customer&nbsp;Name</b></td>
			<td><b>Type&nbsp;of&nbsp;Move<b></td>
			<td><b>Decription<b></td>
			<td><b>Number&nbsp;of&nbsp;Movers</b></td>
			<td><b>Date&nbsp;of&nbsp;Service</b></td>
			<td colspan=2><b>Time&nbsp;of&nbsp;Service</b></td>
			<td><b>Truck&nbsp;Size</b></td>
		</tr>
				<?php
			//Start connection with database
			require("connect.php");
			
			if ($_SESSION[_userType_] == 'Owner' || $_SESSION[_userType_] == 'Admin')
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Complete' order by dateOfService1 asc";
			}
			else
			{
				$query = "select jobId, branch, custName, typeOfMove, description, numOfMovrs, dateOfService1, timeOfService1, timeOfService2, truckSize from jobs 
						where statusOfMove = 'Complete' and branch = '".$_SESSION['_city_']."' order by dateOfService1 asc";
			}
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
				if ($_SESSION['_userType_'] != 'Mover')
				{
					echo"<td>
							<a href='jobsEdit.php?jobId=".$row['jobId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='jobsDelete.php?jobId=".$row['jobId']."' id='".$row['jobId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}

				echo "<td>".$row['jobId']."</td>
					<td>".$row['branch']."</td>
					<td>".$row['custName']."</td>
					<td>".$row['typeOfMove']."</td>
					<td>".$row['description']."</td>
					<td>".$row['numOfMovrs']."</td>
					<td>".date('m/d/Y', strtotime($row['dateOfService1']))."</td>
					<td colspan='2'>".$row['timeOfService1']."&nbsp;to&nbsp;".$row['timeOfService2']."</td>
					<td>".$row['truckSize']."</td>";
				
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