<?php
	require('session.php');
	require('logo.php');
?>

<html>

<head>
	<title>
		.: Expenses :.
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
		<table width='100%' border='1px'>
		<tr>
			<?php
			if ($_SESSION['_userType_'] == 'Manager')
			{
				echo "<td colspan='12'><b>EXPENSES</b></td>";
			}
			else
			{
				echo "<td colspan='16'><b>EXPENSES</b></td>";
			}
			?>
		</tr>
		<tr>
			<?php
			if ($_SESSION['_userType_'] == 'Manager')
			{
				echo "
				<td><b>Job&nbsp;Id</b></td>
				<td><b>Date&nbsp;of&nbsp;Service<b></td>
				<td><b>Client&nbsp;Name</b></td>
				<td><b>Total&nbsp;Price<b></td>
				<td><b>Labor<b></td>
				<td><b>Gas</b></td>
				<td><b>Truck</b></td>
				<td><b>Transaction&nbsp;Fee</b></td>
				<td><b>Staffing&nbsp;Fee</b></td>
				<td><b>Manager</b></td>
				<td><b>Rounded&nbsp;Hours</b></td>
				<td><b>Movers</b></td>";
			}
			else
			{
				echo "
				<td><b>Job&nbsp;Id</b></td>
				<td><b>Branch<b></td>
				<td><b>Date&nbsp;of&nbsp;Service<b></td>
				<td><b>Client&nbsp;Name</b></td>
				<td><b>Total&nbsp;Price<b></td>
				<td><b>Labor<b></td>
				<td><b>Gas</b></td>
				<td><b>Truck</b></td>
				<td><b>Transaction&nbsp;Fee</b></td>
				<td><b>Staffing&nbsp;Fee</b></td>
				<td><b>Manager</b></td>
				<td><b>Net&nbsp;Profit</b></td>
				<td><b>Bank</b></td>
				<td><b>Individual</b></td>
				<td><b>Rounded&nbsp;Hours</b></td>
				<td><b>Movers</b></td>";
			}
			?>
		</tr>
		<?php
			//Start connection with database
			require("connect.php");
			
			$query = "select * from percentages";
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			$row = mysql_fetch_assoc($result);
			$tFeePer = $row['transactionFeePer'];
			$sFeePer = $row['staffingFeePer'];
			$bPer = $row['bankPer'];
		
			$query = "select payPer, city from userinfo where userType = 'Manager'";
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			while ($row = mysql_fetch_assoc($result))
			{
				if ($row['city'] == 'Gainesville')
				{
					$gMngrPer = $row['payPer'];
				}
				else if ($row['city'] == 'Miami')
				{
					$mMngrPer = $row['payPer'];
				}
				else if ($row['city'] == 'Orlando')
				{
					$oMngrPer = $row['payPer'];
				}
				else if ($row['city'] == 'Tallahassee')
				{
					$tMngrPer = $row['payPer'];
				}
			}
			
			if ($_SESSION['_userType_'] == 'Manager')
			{
				$query = "select * from jobs where branch = '".$_SESSION['_city_']."' and statusOfMove = 'Complete' order by dateOfService1 asc";
			}
			else
			{
				$query = "select * from jobs where statusOfMove = 'Complete' order by dateOfService1 asc";
			}
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			// display the rows here
			while ($row = mysql_fetch_assoc($result))
			{
				$startTimeHour1 = $row['startTimeHour1'];
				$startTimeMinute1 = $row['startTimeMinute1'];
				$endTimeHour1 = $row['endTimeHour1'];
				$endTimeMinute1 = $row['endTimeMinute1'];

				$startTimeHour2 = $row['startTimeHour2'];
				$startTimeMinute2 = $row['startTimeMinute2'];
				$endTimeHour2 = $row['endTimeHour2'];
				$endTimeMinute2 = $row['endTimeMinute2'];
				
				$startTimeHour3 = $row['startTimeHour3'];
				$startTimeMinute3 = $row['startTimeMinute3'];
				$endTimeHour3 = $row['endTimeHour3'];
				$endTimeMinute3 = $row['endTimeMinute3'];
				
				$hourlyRate = $row['hourlyRate'];
				$minHours = $row['minHours'];
				$numOfMovrs = $row['numOfMovrs'];
				$materialsCost = $row['materialsCost'];
				$travelFee = $row['travelFee'];
				$credit = $row['credit'];
				$gasExpenses = $row['gasExpenses'];
				$truckExpenses = $row['truckExpenses'];

				$timeIn1 = $startTimeHour1 + ($startTimeMinute1/60);
				$timeOut1 = $endTimeHour1 + ($endTimeMinute1/60); 
				$timeIn2 = $startTimeHour2 + ($startTimeMinute2/60);
				$timeOut2 = $endTimeHour2 + ($endTimeMinute2/60); 
				$timeIn3 = $startTimeHour3 + ($startTimeMinute3/60);
				$timeOut3 = $endTimeHour3 + ($endTimeMinute3/60); 

				$roundedHoursTemp = ($timeOut1 - $timeIn1) + ($timeOut2 - $timeIn2)+ ($timeOut3 - $timeIn3);
				$min = ($roundedHoursTemp - floor($roundedHoursTemp))*60;
				
				if($min > 0 and $min <= 15)
				{
					$min = 15/60;
				}
				else if($min > 15 and $min <= 30)
				{
					$min = 30/60;
				}
				else if($min > 30 and $min <= 45)
				{
					$min = 45/60;
				}
				else if($min > 45 and $min <= 60)
				{
					$min = 60/60;
				}
				
				$roundedHours = floor($roundedHoursTemp) + $min;
				
				if($roundedHours > 0 and $roundedHours < $minHours)
				{
					$roundedHours = $minHours;
				}
				
				if ($row['branch'] == 'Gainesville')
				{
					$mPer = $gMngrPer;
				}
				else if ($row['branch'] == 'Tallahassee')
				{
					$mPer = $tMngrPer;
				}
				else if ($row['branch'] == 'Orlando')
				{
					$mPer = $oMngrPer;
				}
				else if ($row['branch'] == 'Miami')
				{
					$mPer = $mMngrPer;
				}
				
				$subQuery = "select u.name, u.payPer from userinfo as u, jobassign as j where u.userId = j.movrId and j.jobId = ".$row['jobId']." order by u.name";
				$subResult = mysql_query($subQuery,$con);
				if(!$subResult)
				{
					die("Invalid query! <br> The query is: " . $subQuery);
				}

				echo "<tr>";
				echo "<td>".$row['jobId']."</td>";
				if ($_SESSION['_userType_'] != 'Manager')
				{
					echo "<td>".$row['branch']."</td>";
				}

				echo "<td>".date('m/d/Y', strtotime($row['dateOfService1']))."</td>";
				echo "<td>".str_replace(" ", "&nbsp;", $row['custName'])."</td>";
				
				$totalPrice = $roundedHours * $hourlyRate + $materialsCost + $travelFee - $credit;
				echo "<td>".$totalPrice."</td>";
				
				echo "<td>";
					$totalPayOfMovers = 0;
					$moverNames;
					while ($subRow = mysql_fetch_assoc($subResult))
					{
						$moverNames[] = $subRow['name'];
						$totalPayOfMovers = $totalPayOfMovers + $roundedHours*$subRow['payPer'];
					}
				echo $totalPayOfMovers;
				echo "</td>";
				echo "<td>".$gasExpenses."</td>";
				echo "<td>".$truckExpenses."</td>";
			
				$transactionFee = round(($totalPrice * ($tFeePer/100)),2);				
				echo "<td>".$transactionFee."</td>";
				
				$staffingFee = round(($totalPayOfMovers * ($sFeePer/100)),2);				
				echo "<td>".$staffingFee."</td>";
				
				$manager = round((($totalPrice - $totalPayOfMovers - $gasExpenses - $truckExpenses - $transactionFee - $staffingFee) * ($mPer/100)),2);
				echo "<td>".$manager."</td>";
			
				if ($_SESSION['_userType_'] != 'Manager')
				{
					$netProfit = round(($totalPrice - $totalPayOfMovers - $gasExpenses - $truckExpenses - $transactionFee - $staffingFee - $manager),2);
					echo "<td>".$netProfit."</td>";
				
					$bank = round(($netProfit * ($bPer/100)),2);
					echo "<td>".$bank."</td>";
					
					$individual = round((($netProfit - $bank)/2),2);
					echo "<td>".$individual."</td>";
				}
				
				echo "<td>".$roundedHours."</td>";
				
				echo "<td>";
				for ($ii = 0; $ii < count($moverNames); $ii++)
				{
					echo str_replace(" ", "&nbsp;", $moverNames[$ii]);
					if ($ii+1 < count($moverNames))
					{
						echo ",&nbsp;";
					}
				}
				
				unset($moverNames);
				echo "</td>";
				
				echo "</tr>";
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