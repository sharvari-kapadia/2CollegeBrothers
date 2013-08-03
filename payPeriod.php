<?php
	require('session.php');
	require('logo.php');
?>

<html>

<head>
	<title>
		.: Pay Period :.
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
			require("connect.php");
		?>
	</td>
	<td width='80%' valign='top'>
		<?php
		$cities = array('Gainesville', 'Tallahassee', 'Orlando', 'Miami');
		//$cities = array('Gainesville');
		foreach ($cities as $_city)
		{
			if ($_SESSION['_userType_'] == 'Manager' && $_SESSION['_city_'] != $_city)
			{
				continue;
			}
		?>
		<table width='100%' border='1px'>
		<tr>
			<td colspan='9'><b>THIS WEEK - <?=$_city?></b></td>
		</tr>
		<tr>
			<td></td>
			<?php
				$tDay = date("N");
				for ($i = 0; $i < 7; $i++)
				{
					$diff = $tDay - 1 - $i;
					$diffString = "-".$diff." day";
					echo "<td><b>".date("l (m/d/Y)", strtotime($diffString))."</b></td>";
				}
			?>
			<td><b>Total<b></td>
		</tr>
		<?php
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
		
			$query = "select name, payPer from userinfo where userType = 'Manager' and city = '".$_city."'";
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			$row = mysql_fetch_assoc($result);
			$mPer = $row['payPer'];
			$managerName = $row['name'];
			
			// individual
			if ($_SESSION['_userType_'] != "Manager")
			{
				$indOverallTotal = 0;
				echo "
				<tr>
					<td><b>Individual</b></td>";
					$tDay = date("N");
					for ($i = 0; $i < 7; $i++)
					{
						$diff = $tDay - 1 - $i;
						$diffString = "-".$diff." day";
						$searchDate = date("Y-m-d", strtotime($diffString));
						$query = "select sum(e.individual) as iTotalProfit from expenses as e, jobs as j where e.jobId = j.jobId and j.branch = '".$_city."' and j.dateOfService1 = '".$searchDate."'";
						$result = mysql_query($query,$con);	
						if(!$result)
						{
							die("Invalid query! <br> The query is: " . $query);
						}
						$row = mysql_fetch_assoc($result);
						echo "<td>".round($row['iTotalProfit'], 2)."</td>";
						$indOverallTotal += $row['iTotalProfit'];
					}
					echo "<td>".round($indOverallTotal, 2)."</td>";
				echo "</tr>";
			}
			// manager
			$manOverallTotal = 0;
			echo "
			<tr>
				<td><b>".str_replace(" ", "&nbsp;", $managerName)."</b></td>";
				$tDay = date("N");
				for ($i = 0; $i < 7; $i++)
				{
					$diff = $tDay - 1 - $i;
					$diffString = "-".$diff." day";
					$searchDate = date("Y-m-d", strtotime($diffString));
					$query = "select sum(e.managerProfit) as mTotalProfit from expenses as e, jobs as j where e.jobId = j.jobId and j.branch = '".$_city."' and j.dateOfService1 = '".$searchDate."'";
					$result = mysql_query($query,$con);	
					if(!$result)
					{
						die("Invalid query! <br> The query is: " . $query);
					}
					$row = mysql_fetch_assoc($result);
					echo "<td>".round($row['mTotalProfit'], 2)."</td>";
					$manOverallTotal += $row['mTotalProfit'];
				}
				echo "<td>".round($manOverallTotal, 2)."</td>";
			echo "</tr>";
			
			// movers
			$query = "select * from userinfo where userType = 'Mover' and city = '".$_city."' order by name asc";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			while ($row = mysql_fetch_assoc($result))
			{
				echo "<tr>
					<td>".str_replace(" ", "&nbsp;", $row['name'])."</td>";
					$tDay = date("N");
					$totalPay = 0;
					for ($i = 0; $i < 7; $i++)
					{
						$diff = $tDay - 1 - $i;
						$diffString = "-".$diff." day";
						$searchDate = date("Y-m-d", strtotime($diffString));
						$subQuery = "select * from jobassign as ja, jobs as j where ja.movrId = ".$row['userId']." and j.jobId = ja.jobId and j.dateOfService1 = '".$searchDate."'";
						$subResult = mysql_query($subQuery,$con);	
						if(!$subResult)
						{
							die("Invalid query! <br> The query is: " . $subQuery);
						}
						
						$pay = 0;
						while ($subRow = mysql_fetch_assoc($subResult))
						{						
						$startTimeHour1 = $subRow['startTimeHour1'];
						$startTimeMinute1 = $subRow['startTimeMinute1'];
						$endTimeHour1 = $subRow['endTimeHour1'];
						$endTimeMinute1 = $subRow['endTimeMinute1'];

						$startTimeHour2 = $subRow['startTimeHour2'];
						$startTimeMinute2 = $subRow['startTimeMinute2'];
						$endTimeHour2 = $subRow['endTimeHour2'];
						$endTimeMinute2 = $subRow['endTimeMinute2'];
						
						$startTimeHour3 = $subRow['startTimeHour3'];
						$startTimeMinute3 = $subRow['startTimeMinute3'];
						$endTimeHour3 = $subRow['endTimeHour3'];
						$endTimeMinute3 = $subRow['endTimeMinute3'];
						
						$hourlyRate = $subRow['hourlyRate'];
						$minHours = $subRow['minHours'];
						$numOfMovrs = $subRow['numOfMovrs'];
						$materialsCost = $subRow['materialsCost'];
						$travelFee = $subRow['travelFee'];
						$credit = $subRow['credit'];
						$gasExpenses = $subRow['gasExpenses'];
						$truckExpenses = $subRow['truckExpenses'];

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
						$pay = $pay + $roundedHours*$row['payPer'];
						}
						echo "<td>".$pay."</td>";
						$totalPay = $totalPay + $pay;
					}
					echo "<td>".$totalPay."</td>";
				echo "</tr>";
			}
		?>
		</table>
		</br>
		
		<table width='100%' border='1px'>
		<tr>
			<td colspan='9'><b>LAST WEEK - <?=$_city?></b></td>
		</tr>
		<tr>
			<td></td>
			<?php
				$tDay = date("N");
				for ($i = 0; $i < 7; $i++)
				{
					$diff = $tDay - 1 - $i + 7;
					$diffString = "-".$diff." day";
					echo "<td><b>".date("l (m/d/Y)", strtotime($diffString))."</b></td>";
				}
			?>
			<td><b>Total<b></td>
		</tr>
		<?php
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
		
			$query = "select name, payPer from userinfo where userType = 'Manager' and city = '".$_city."'";
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			$row = mysql_fetch_assoc($result);
			$mPer = $row['payPer'];
			$managerName = $row['name'];
			
			// individual
			if ($_SESSION['_userType_'] != "Manager")
			{
				$indOverallTotal = 0;
				echo "
				<tr>
					<td><b>Individual</b></td>";
					$tDay = date("N");
					for ($i = 0; $i < 7; $i++)
					{
						$diff = $tDay - 1 - $i + 7;
						$diffString = "-".$diff." day";
						$searchDate = date("Y-m-d", strtotime($diffString));
						$query = "select sum(e.individual) as iTotalProfit from expenses as e, jobs as j where e.jobId = j.jobId and j.branch = '".$_city."' and j.dateOfService1 = '".$searchDate."'";
						$result = mysql_query($query,$con);	
						if(!$result)
						{
							die("Invalid query! <br> The query is: " . $query);
						}
						$row = mysql_fetch_assoc($result);
						echo "<td>".round($row['iTotalProfit'], 2)."</td>";
						$indOverallTotal += $row['iTotalProfit'];
					}
					echo "<td>".round($indOverallTotal, 2)."</td>";
				echo "</tr>";
			}
			
			// manager
			$manOverallTotal = 0;
			echo "
			<tr>
				<td><b>".str_replace(" ", "&nbsp;", $managerName)."</b></td>";
				$tDay = date("N");
				for ($i = 0; $i < 7; $i++)
				{
					$diff = $tDay - 1 - $i + 7;
					$diffString = "-".$diff." day";
					$searchDate = date("Y-m-d", strtotime($diffString));
					$query = "select sum(e.managerProfit) as mTotalProfit from expenses as e, jobs as j where e.jobId = j.jobId and j.branch = '".$_city."' and j.dateOfService1 = '".$searchDate."'";
					$result = mysql_query($query,$con);	
					if(!$result)
					{
						die("Invalid query! <br> The query is: " . $query);
					}
					$row = mysql_fetch_assoc($result);
					echo "<td>".round($row['mTotalProfit'], 2)."</td>";
					$manOverallTotal += $row['mTotalProfit'];
				}
				echo "<td>".round($manOverallTotal, 2)."</td>";
			echo "</tr>";
			
			// movers
			$query = "select * from userinfo where userType = 'Mover' and city = '".$_city."' order by name asc";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			while ($row = mysql_fetch_assoc($result))
			{
				echo "<tr>
					<td>".str_replace(" ", "&nbsp;", $row['name'])."</td>";
					$tDay = date("N");
					$totalPay = 0;
					for ($i = 0; $i < 7; $i++)
					{
						$diff = $tDay - 1 - $i + 7;
						$diffString = "-".$diff." day";
						$searchDate = date("Y-m-d", strtotime($diffString));
						$subQuery = "select * from jobassign as ja, jobs as j where ja.movrId = ".$row['userId']." and j.jobId = ja.jobId and j.dateOfService1 = '".$searchDate."'";
						$subResult = mysql_query($subQuery,$con);	
						if(!$subResult)
						{
							die("Invalid query! <br> The query is: " . $subQuery);
						}
						$pay = 0;
						while ($subRow = mysql_fetch_assoc($subResult))
						{					
							$startTimeHour1 = $subRow['startTimeHour1'];
							$startTimeMinute1 = $subRow['startTimeMinute1'];
							$endTimeHour1 = $subRow['endTimeHour1'];
							$endTimeMinute1 = $subRow['endTimeMinute1'];

							$startTimeHour2 = $subRow['startTimeHour2'];
							$startTimeMinute2 = $subRow['startTimeMinute2'];
							$endTimeHour2 = $subRow['endTimeHour2'];
							$endTimeMinute2 = $subRow['endTimeMinute2'];
							
							$startTimeHour3 = $subRow['startTimeHour3'];
							$startTimeMinute3 = $subRow['startTimeMinute3'];
							$endTimeHour3 = $subRow['endTimeHour3'];
							$endTimeMinute3 = $subRow['endTimeMinute3'];
							
							$hourlyRate = $subRow['hourlyRate'];
							$minHours = $subRow['minHours'];
							$numOfMovrs = $subRow['numOfMovrs'];
							$materialsCost = $subRow['materialsCost'];
							$travelFee = $subRow['travelFee'];
							$credit = $subRow['credit'];
							$gasExpenses = $subRow['gasExpenses'];
							$truckExpenses = $subRow['truckExpenses'];

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
							$pay = $pay + $roundedHours*$row['payPer'];
						}
						echo "<td>".$pay."</td>";
						$totalPay = $totalPay + $pay;
					}
					echo "<td>".$totalPay."</td>";
				echo "</tr>";
			}
		?>
		</table>
		</br>
		<?php
		}
		?>
	</td>
	</tr>
	</table>
	<?php
		mysql_close($con);
	?>
</body>
</html>