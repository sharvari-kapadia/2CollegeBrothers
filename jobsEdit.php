<?php
	require('session.php');

	if (isset($_GET['jobId']))
	{
		$_SESSION['jobId'] = $_GET['jobId'];
	}
	else if (isset($_POST['branch']))
	{			
		//Start connection with database
		require("connect.php");
		
		//**************************************************
		// check if the file was uploaded, if yes store
		// the file on the server, else just skip the part
		for ($ii = 1; $ii <= 6; $ii++)
		{
			$htmlFileId = "fileName".$ii;
			if ($_FILES[$htmlFileId]['error'] <= 0)
			{
				// check if the filename already exists
				// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
				$upFileName = "upload/".$_SESSION['jobId']."_".$_FILES[$htmlFileId]['name'];
				if (file_exists($upFileName))
				{
					die("File: ".$upFileName." already exists!");
				}
				else
				{
					move_uploaded_file($_FILES[$htmlFileId]['tmp_name'], $upFileName);
				}

				// as the file was uploaded successfully, store the path in the database
				$query = "insert into files values(1, 0, ".$_SESSION['jobId'].", '".$upFileName."', '".$_POST['fileComments']."')";
				$result = mysql_query($query,$con);	
				if(!$result)
				{
					die("Invalid query! <br> The query is: " . $query);
				}
			}
			else
			{
				// no file uploaded just update the comments
				$query = "update files set comments = '".$_POST['fileComments']."'";
				$result = mysql_query($query,$con);	
				if(!$result)
				{
					die("Invalid query! <br> The query is: " . $query);
				}
			}
		}
		//**************************************************
		
		$_POST['gasExpenses'] = ('' == $_POST['gasExpenses'] ? 0 : $_POST['gasExpenses']);
		$_POST['truckExpenses'] = ('' == $_POST['truckExpenses'] ? 0 : $_POST['truckExpenses']);
		
		$timeOfService1 = $_POST['timeOfServiceHour1'].":".$_POST['timeOfServiceMinute1'].":00";
		$timeOfService2 = $_POST['timeOfServiceHour2'].":".$_POST['timeOfServiceMinute2'].":00";
		$startTime1 = $_POST['startTimeHour1'].":".$_POST['startTimeMinute1'].":00";
		$endTime1 = $_POST['endTimeHour1'].":".$_POST['endTimeMinute1'].":00";
		$startTime2 = $_POST['startTimeHour2'].":".$_POST['startTimeMinute2'].":00";
		$endTime2 = $_POST['endTimeHour2'].":".$_POST['endTimeMinute2'].":00";
		$startTime3 = $_POST['startTimeHour3'].":".$_POST['startTimeMinute3'].":00";
		$endTime3 = $_POST['endTimeHour3'].":".$_POST['endTimeMinute3'].":00";
			
		//Fill jobs table
		$query = "update jobs set branch='".$_POST['branch']."',custName='".$_POST['custName']."',custPhoneNumber='".$_POST['custPhoneNumber']."',
					custEmail='".$_POST['custEmail']."',custAdd11='".$_POST['custAdd11']."',custAdd12='".$_POST['custAdd12']."', 
					custCity1='".$_POST['custCity1']."',custState1='".$_POST['custState1']."',custZip1=".$_POST['custZip1'].",
					locationType1='".$_POST['locationType1']."',floor1='".$_POST['floor1']."',custAdd21='".$_POST['custAdd21']."', 
					custAdd22='".$_POST['custAdd22']."',custCity2='".$_POST['custCity2']."',custState2='".$_POST['custState2']."', 
					custZip2=".$_POST['custZip2'].",locationType2='".$_POST['locationType2']."',floor2='".$_POST['floor2']."', 
					typeOfMove='".$_POST['typeOfMove']."',numOfMovrs=".$_POST['numOfMovrs'].",hourlyRate=".$_POST['hourlyRate'].",
					minHours=".$_POST['minHours'].",description='".$_POST['description']."',truckSize='".$_POST['truckSize']."',materialsCost=".$_POST['materialsCost'].",
					travelFee=".$_POST['travelFee'].",credit=".$_POST['credit'].",statusOfMove='".$_POST['statusOfMove']."',dateOfService1='".$_POST['dateOfService1']."', 
					timeOfServiceHour1=".$_POST['timeOfServiceHour1'].",timeOfServiceMinute1=".$_POST['timeOfServiceMinute1'].",timeOfService1 ='".$timeOfService1."',
					timeOfServiceHour2=".$_POST['timeOfServiceHour2'].",timeOfServiceMinute2=".$_POST['timeOfServiceMinute2'].",timeOfService2 ='".$timeOfService2."',
					dateOfService2='".$_POST['dateOfService2']."',descriptionForSecondDate='".$_POST['descriptionForSecondDate']."',depositDate='".$_POST['depositDate']."',
					hearAbtUs='".$_POST['hearAbtUs']."',ccv='".$_POST['ccv']."',assembly='".$_POST['assembly']."',comments='".$_POST['comments']."',
					startTimeHour1=".$_POST['startTimeHour1'].",startTimeMinute1=".$_POST['startTimeMinute1'].",startTime1 ='".$startTime1."',
					endTimeHour1=".$_POST['endTimeHour1'].",endTimeMinute1=".$_POST['endTimeMinute1'].",endTime1 ='".$endTime1."',
					startTimeHour2=".$_POST['startTimeHour2'].",startTimeMinute2=".$_POST['startTimeMinute2'].",startTime2='".$startTime2."',
					endTimeHour2=".$_POST['endTimeHour2'].",endTimeMinute2=".$_POST['endTimeMinute2'].",endTime2='".$endTime2."',
					startTimeHour3=".$_POST['startTimeHour3'].",startTimeMinute3=".$_POST['startTimeMinute3'].",startTime3='".$startTime3."',
					endTimeHour3=".$_POST['endTimeHour3'].",endTimeMinute3=".$_POST['endTimeMinute3'].",endTime3='".$endTime3."',
					gasExpenses=".$_POST['gasExpenses'].",truckExpenses=".$_POST['truckExpenses']." where jobId=".$_SESSION['jobId'];
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////
		
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
	
		$query = "select payPer, city from userinfo where userType = 'Manager' and city = '".$_POST['branch']."'";
		$result = mysql_query($query,$con);
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		if ($row = mysql_fetch_assoc($result))
		{
			$mPer = $row['payPer'];
		}
		else
		{
			die("Unable to extract manager percentage for city ".$_POST['branch']);
		}
		
		$hourlyRate = $_POST['hourlyRate'];
		$minHours = $_POST['minHours'];
		$numOfMovrs = $_POST['numOfMovrs'];
		$materialsCost = $_POST['materialsCost'];
		$travelFee = $_POST['travelFee'];
		$credit = $_POST['credit'];
		$gasExpenses = $_POST['gasExpenses'];
		$truckExpenses = $_POST['truckExpenses'];
		
		$timeIn1 = $_POST['startTimeHour1'] + ($_POST['startTimeMinute1']/60);
		$timeOut1 = $_POST['endTimeHour1'] + ($_POST['endTimeMinute1']/60); 
		$timeIn2 = $_POST['startTimeHour2'] + ($_POST['startTimeMinute2']/60);
		$timeOut2 = $_POST['endTimeHour2'] + ($_POST['endTimeMinute2']/60); 
		$timeIn3 = $_POST['startTimeHour3'] + ($_POST['startTimeMinute3']/60);
		$timeOut3 = $_POST['endTimeHour3'] + ($_POST['endTimeMinute3']/60); 
		
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
	
		$subQuery = "select u.name, u.payPer from userinfo as u, jobassign as j where u.userId = j.movrId and j.jobId = ".$_SESSION['jobId']." order by u.name";
		$subResult = mysql_query($subQuery,$con);
		if(!$subResult)
		{
			die("Invalid query! <br> The query is: " . $subQuery);
		}
		$totalPrice = $roundedHours * $_POST['hourlyRate'] + $_POST['materialsCost'] + $_POST['travelFee'] - $_POST['credit'];
		$totalPayOfMovers = 0;
		
		while ($subRow = mysql_fetch_assoc($subResult))
		{
			$totalPayOfMovers = $totalPayOfMovers + $roundedHours*$subRow['payPer'];
		}

		$transactionFee = round(($totalPrice * ($tFeePer/100)),2);				
		$staffingFee = round(($totalPayOfMovers * ($sFeePer/100)),2);				
		$managerProfit = round((($totalPrice - $totalPayOfMovers - $gasExpenses - $truckExpenses - $transactionFee - $staffingFee) * ($mPer/100)),2);
		$netProfit = round(($totalPrice - $totalPayOfMovers - $gasExpenses - $truckExpenses - $transactionFee - $staffingFee - $managerProfit),2);
		$bank = round(($netProfit * ($bPer/100)),2);
		$individual = round((($netProfit - $bank)/2),2);
		
		$query = "update expenses set totalPrice =".$totalPrice.", labor=".$totalPayOfMovers.", transactionFee=".$transactionFee.", 
			staffingFee=".$staffingFee.", managerProfit=".$managerProfit.", netProfit=".$netProfit.", bank=".$bank.", 
			individual=".$individual." where jobId=".$_SESSION['jobId'];
		
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		//////////////////////////////////////////////////////////////////////////////////////////////
		
		//Fill job assign table
		// delete all entries in the current table and then add all
		$query = "delete from jobassign where jobId = ".$_SESSION['jobId'];
		$result = mysql_query($query,$con);
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		// find the movers selected by the user
		$selMovers = count($_POST['assignedMovrs']);
		for ($i = 0; $i < $selMovers; $i++)
		{
			$query = "insert into jobassign values(".$_SESSION['jobId'].",".$_POST['assignedMovrs'][$i].")";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		//Close connection
		mysql_close($con);
		
		$writtenEstimateSent = false;
		
		if ($_POST['sendCustEmail'])
		{
			require('writtenEstimate.php');
			$writtenEstimateSent = true;
		}
		
		if ($_POST['sendMngrEmail'])
		{
			require('serviceReceipt.php');
		}
		
		unset($_SESSION['jobId']);
		
		header("Location:home.php");
	}
	else
	{
		header("Location:home.php");
	}
	require('logo.php');
	
?>

<html>

<head>
	<title>
		.: Edit Job profile :.
	</title>
	<link rel="stylesheet" href="homestyle.css">
	<script language="javascript" src="calendar/cal2.js">
	/*
	Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
	Script featured on/available at http://www.dynamicdrive.com/
	This notice must stay intact for use
	*/
	</script>
	<script language="javascript" src="calendar/cal_conf2.js">
	</script>
	<script type="text/javascript" src="js/confirmation.js">
	</script>
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
			
			$query = "select jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, locationType1, floor1,
						custAdd21, custAdd22, custCity2, custState2, custZip2, locationType2, floor2, typeOfMove, numOfMovrs, hourlyRate, minHours, description, 
						truckSize, materialsCost, travelFee, credit, statusOfMove, dateOfService1, timeOfServiceHour1, timeOfServiceMinute1, timeOfServiceHour2, 
						timeOfServiceMinute2, dateOfService2, descriptionForSecondDate, depositDate, hearAbtUs, ccv, assembly, comments, 
						startTimeHour1, startTimeMinute1, endTimeHour1, endTimeMinute1, 
						startTimeHour2, startTimeMinute2, endTimeHour2, endTimeMinute2, 
						startTimeHour3, startTimeMinute3, endTimeHour3, endTimeMinute3, 
						gasExpenses, truckExpenses from jobs where jobId=".$_SESSION['jobId'];
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			if ($row = mysql_fetch_assoc($result))
			{
				echo "<form name='editJobProfile' method='post' action='jobsEdit.php' enctype='multipart/form-data'>";
				echo "<tr>
						<td colspan='2'>Job Id: ".$_SESSION['jobId']."</td>
					</tr>
					<tr>
						<td colspan=2><input type='hidden' name='jobId' value='".$_SESSION['jobId']."' /></td>
					</tr>
					<tr>
						<td>Branch</td>
						<td>
							<select name='branch'>
								<option value='Gainesville'"; echo ($row['branch']=='Gainesville' ? 'selected' : ''); echo ">Gainesville</option>
								<option value='Tallahassee'"; echo ($row['branch']=='Tallahassee' ? 'selected' : ''); echo ">Tallahassee</option>
								<option value='Orlando'"; echo ($row['branch']=='Orlando' ? 'selected' : ''); echo ">Orlando</option>
								<option value='Miami'"; echo ($row['branch']=='Miami' ? 'selected' : ''); echo ">Miami</option>
							</select>
					</tr>
					<tr>
						<td>Customer Name</td>
						<td><input type='text' name='custName' value='".$row['custName']."' /></td>
					</tr>
					<tr>
						<td>Phone Number</td>
						<td><input type='text' name='custPhoneNumber' value='".$row['custPhoneNumber']."' /></td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td><input type='text' name='custEmail' value='".$row['custEmail']."' /></td>
					</tr>
					<tr>
						<td>Address</td>
						<td><input type='text' name='custAdd11' value='".$row['custAdd11']."' /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type='text' name='custAdd12' value='".$row['custAdd12']."' /></td>
					</tr>
					<tr>
						<td>City</td>
						<td><input type='text' name='custCity1' value='".$row['custCity1']."' />
							&nbsp;&nbsp;&nbsp;&nbsp;State
							<select name='custState1'>
								<option> -- </option>
								<option value='AL'"; echo ($row['custState1']=='AL' ? 'selected' : ''); echo ">AL</option>
								<option value='AK'"; echo ($row['custState1']=='AK' ? 'selected' : ''); echo ">AK</option>
								<option value='AZ'"; echo ($row['custState1']=='AZ' ? 'selected' : ''); echo ">AZ</option>
								<option value='AR'"; echo ($row['custState1']=='AR' ? 'selected' : ''); echo ">AR</option>
								<option value='CA'"; echo ($row['custState1']=='CA' ? 'selected' : ''); echo ">CA</option>
								<option value='CO'"; echo ($row['custState1']=='CO' ? 'selected' : ''); echo ">CO</option>
								<option value='CT'"; echo ($row['custState1']=='CT' ? 'selected' : ''); echo ">CT</option>
								<option value='DE'"; echo ($row['custState1']=='DE' ? 'selected' : ''); echo ">DE</option>
								<option value='FL'"; echo ($row['custState1']=='FL' ? 'selected' : ''); echo ">FL</option>
								<option value='GA'"; echo ($row['custState1']=='GA' ? 'selected' : ''); echo ">GA</option>
								<option value='HI'"; echo ($row['custState1']=='HI' ? 'selected' : ''); echo ">HI</option>
								<option value='ID'"; echo ($row['custState1']=='ID' ? 'selected' : ''); echo ">ID</option>
								<option value='IL'"; echo ($row['custState1']=='IL' ? 'selected' : ''); echo ">IL</option>
								<option value='IN'"; echo ($row['custState1']=='IN' ? 'selected' : ''); echo ">IN</option>
								<option value='IA'"; echo ($row['custState1']=='IA' ? 'selected' : ''); echo ">IA</option>
								<option value='KS'"; echo ($row['custState1']=='KS' ? 'selected' : ''); echo ">KS</option>
								<option value='KY'"; echo ($row['custState1']=='KY' ? 'selected' : ''); echo ">KY</option>
								<option value='LA'"; echo ($row['custState1']=='LA' ? 'selected' : ''); echo ">LA</option>
								<option value='ME'"; echo ($row['custState1']=='ME' ? 'selected' : ''); echo ">ME</option>
								<option value='MD'"; echo ($row['custState1']=='MD' ? 'selected' : ''); echo ">MD</option>
								<option value='MA'"; echo ($row['custState1']=='MA' ? 'selected' : ''); echo ">MA</option>
								<option value='MI'"; echo ($row['custState1']=='MI' ? 'selected' : ''); echo ">MI</option>
								<option value='MN'"; echo ($row['custState1']=='MN' ? 'selected' : ''); echo ">MN</option>
								<option value='MS'"; echo ($row['custState1']=='MS' ? 'selected' : ''); echo ">MS</option>
								<option value='MO'"; echo ($row['custState1']=='MO' ? 'selected' : ''); echo ">MO</option>
								<option value='MT'"; echo ($row['custState1']=='MT' ? 'selected' : ''); echo ">MT</option>
								<option value='NE'"; echo ($row['custState1']=='NE' ? 'selected' : ''); echo ">NE</option>
								<option value='NV'"; echo ($row['custState1']=='NV' ? 'selected' : ''); echo ">NV</option>
								<option value='NM'"; echo ($row['custState1']=='NM' ? 'selected' : ''); echo ">NM</option>
								<option value='NY'"; echo ($row['custState1']=='NY' ? 'selected' : ''); echo ">NY</option>
								<option value='NC'"; echo ($row['custState1']=='NC' ? 'selected' : ''); echo ">NC</option>
								<option value='ND'"; echo ($row['custState1']=='ND' ? 'selected' : ''); echo ">ND</option>
								<option value='OH'"; echo ($row['custState1']=='OH' ? 'selected' : ''); echo ">OH</option>
								<option value='OK'"; echo ($row['custState1']=='OK' ? 'selected' : ''); echo ">OK</option>
								<option value='OR'"; echo ($row['custState1']=='OR' ? 'selected' : ''); echo ">OR</option>
								<option value='PA'"; echo ($row['custState1']=='PA' ? 'selected' : ''); echo ">PA</option>
								<option value='RI'"; echo ($row['custState1']=='RI' ? 'selected' : ''); echo ">RI</option>
								<option value='SC'"; echo ($row['custState1']=='SC' ? 'selected' : ''); echo ">SC</option>
								<option value='SD'"; echo ($row['custState1']=='SD' ? 'selected' : ''); echo ">SD</option>
								<option value='TN'"; echo ($row['custState1']=='TN' ? 'selected' : ''); echo ">TN</option>
								<option value='TX'"; echo ($row['custState1']=='TX' ? 'selected' : ''); echo ">TX</option>
								<option value='UT'"; echo ($row['custState1']=='UT' ? 'selected' : ''); echo ">UT</option>
								<option value='VT'"; echo ($row['custState1']=='VT' ? 'selected' : ''); echo ">VT</option>
								<option value='VA'"; echo ($row['custState1']=='VA' ? 'selected' : ''); echo ">VA</option>
								<option value='WA'"; echo ($row['custState1']=='WA' ? 'selected' : ''); echo ">WA</option>
								<option value='WV'"; echo ($row['custState1']=='WV' ? 'selected' : ''); echo ">WV</option>
								<option value='WI'"; echo ($row['custState1']=='WI' ? 'selected' : ''); echo ">WI</option>
								<option value='WY'"; echo ($row['custState1']=='WY' ? 'selected' : ''); echo ">WY</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;Zip Code<input type='text' name='custZip1' value='".$row['custZip1']."' />
						</td>
					</tr>
					<tr>
						<td>Location Type</td>
						<td>
							<select name='locationType1'>
								<option> -- </option>
								<option value='House'"; echo ($row['locationType1']=='House' ? 'selected' : ''); echo ">House</option>
								<option value='Storage'"; echo ($row['locationType1']=='Storage' ? 'selected' : ''); echo ">Storage</option>
								<option value='Apartment'"; echo ($row['locationType1']=='Apartment' ? 'selected' : ''); echo ">Apartment</option>
								<option value='Townhouse'"; echo ($row['locationType1']=='Townhouse' ? 'selected' : ''); echo ">Townhouse</option>
								<option value='Office'"; echo ($row['locationType1']=='Office' ? 'selected' : ''); echo ">Office</option>
								<option value='Condo'"; echo ($row['locationType1']=='Condo' ? 'selected' : ''); echo ">Condo</option>
								<option value='House'"; echo ($row['locationType1']=='House' ? 'selected' : ''); echo ">House</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Floor
							<input type='text' name='floor1' value='".$row['floor1']."' />
						</td>
					</tr>		
					<tr>
						<td>Address</td>
						<td><input type='text' name='custAdd21' value='".$row['custAdd21']."' /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type='text' name='custAdd22' value='".$row['custAdd22']."' /></td>
					</tr>
					<tr>
						<td>City</td>
						<td><input type='text' name='custCity2' value='".$row['custCity2']."' />
							&nbsp;&nbsp;&nbsp;&nbsp;State
							<select name='custState2'>
								<option> -- </option>
								<option value='AL'"; echo ($row['custState2']=='AL' ? 'selected' : ''); echo ">AL</option>
								<option value='AK'"; echo ($row['custState2']=='AK' ? 'selected' : ''); echo ">AK</option>
								<option value='AZ'"; echo ($row['custState2']=='AZ' ? 'selected' : ''); echo ">AZ</option>
								<option value='AR'"; echo ($row['custState2']=='AR' ? 'selected' : ''); echo ">AR</option>
								<option value='CA'"; echo ($row['custState2']=='CA' ? 'selected' : ''); echo ">CA</option>
								<option value='CO'"; echo ($row['custState2']=='CO' ? 'selected' : ''); echo ">CO</option>
								<option value='CT'"; echo ($row['custState2']=='CT' ? 'selected' : ''); echo ">CT</option>
								<option value='DE'"; echo ($row['custState2']=='DE' ? 'selected' : ''); echo ">DE</option>
								<option value='FL'"; echo ($row['custState2']=='FL' ? 'selected' : ''); echo ">FL</option>
								<option value='GA'"; echo ($row['custState2']=='GA' ? 'selected' : ''); echo ">GA</option>
								<option value='HI'"; echo ($row['custState2']=='HI' ? 'selected' : ''); echo ">HI</option>
								<option value='ID'"; echo ($row['custState2']=='ID' ? 'selected' : ''); echo ">ID</option>
								<option value='IL'"; echo ($row['custState2']=='IL' ? 'selected' : ''); echo ">IL</option>
								<option value='IN'"; echo ($row['custState2']=='IN' ? 'selected' : ''); echo ">IN</option>
								<option value='IA'"; echo ($row['custState2']=='IA' ? 'selected' : ''); echo ">IA</option>
								<option value='KS'"; echo ($row['custState2']=='KS' ? 'selected' : ''); echo ">KS</option>
								<option value='KY'"; echo ($row['custState2']=='KY' ? 'selected' : ''); echo ">KY</option>
								<option value='LA'"; echo ($row['custState2']=='LA' ? 'selected' : ''); echo ">LA</option>
								<option value='ME'"; echo ($row['custState2']=='ME' ? 'selected' : ''); echo ">ME</option>
								<option value='MD'"; echo ($row['custState2']=='MD' ? 'selected' : ''); echo ">MD</option>
								<option value='MA'"; echo ($row['custState2']=='MA' ? 'selected' : ''); echo ">MA</option>
								<option value='MI'"; echo ($row['custState2']=='MI' ? 'selected' : ''); echo ">MI</option>
								<option value='MN'"; echo ($row['custState2']=='MN' ? 'selected' : ''); echo ">MN</option>
								<option value='MS'"; echo ($row['custState2']=='MS' ? 'selected' : ''); echo ">MS</option>
								<option value='MO'"; echo ($row['custState2']=='MO' ? 'selected' : ''); echo ">MO</option>
								<option value='MT'"; echo ($row['custState2']=='MT' ? 'selected' : ''); echo ">MT</option>
								<option value='NE'"; echo ($row['custState2']=='NE' ? 'selected' : ''); echo ">NE</option>
								<option value='NV'"; echo ($row['custState2']=='NV' ? 'selected' : ''); echo ">NV</option>
								<option value='NM'"; echo ($row['custState2']=='NM' ? 'selected' : ''); echo ">NM</option>
								<option value='NY'"; echo ($row['custState2']=='NY' ? 'selected' : ''); echo ">NY</option>
								<option value='NC'"; echo ($row['custState2']=='NC' ? 'selected' : ''); echo ">NC</option>
								<option value='ND'"; echo ($row['custState2']=='ND' ? 'selected' : ''); echo ">ND</option>
								<option value='OH'"; echo ($row['custState2']=='OH' ? 'selected' : ''); echo ">OH</option>
								<option value='OK'"; echo ($row['custState2']=='OK' ? 'selected' : ''); echo ">OK</option>
								<option value='OR'"; echo ($row['custState2']=='OR' ? 'selected' : ''); echo ">OR</option>
								<option value='PA'"; echo ($row['custState2']=='PA' ? 'selected' : ''); echo ">PA</option>
								<option value='RI'"; echo ($row['custState2']=='RI' ? 'selected' : ''); echo ">RI</option>
								<option value='SC'"; echo ($row['custState2']=='SC' ? 'selected' : ''); echo ">SC</option>
								<option value='SD'"; echo ($row['custState2']=='SD' ? 'selected' : ''); echo ">SD</option>
								<option value='TN'"; echo ($row['custState2']=='TN' ? 'selected' : ''); echo ">TN</option>
								<option value='TX'"; echo ($row['custState2']=='TX' ? 'selected' : ''); echo ">TX</option>
								<option value='UT'"; echo ($row['custState2']=='UT' ? 'selected' : ''); echo ">UT</option>
								<option value='VT'"; echo ($row['custState2']=='VT' ? 'selected' : ''); echo ">VT</option>
								<option value='VA'"; echo ($row['custState2']=='VA' ? 'selected' : ''); echo ">VA</option>
								<option value='WA'"; echo ($row['custState2']=='WA' ? 'selected' : ''); echo ">WA</option>
								<option value='WV'"; echo ($row['custState2']=='WV' ? 'selected' : ''); echo ">WV</option>
								<option value='WI'"; echo ($row['custState2']=='WI' ? 'selected' : ''); echo ">WI</option>
								<option value='WY'"; echo ($row['custState2']=='WY' ? 'selected' : ''); echo ">WY</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;Zip Code<input type='text' name='custZip2' value='".$row['custZip2']."' />
						</td>
					</tr>
					<tr>
						<td>Location Type</td>
						<td>
							<select name='locationType2'>
								<option> -- </option>
								<option value='House'"; echo ($row['locationType2']=='House' ? 'selected' : ''); echo ">House</option>
								<option value='Storage'"; echo ($row['locationType2']=='Storage' ? 'selected' : ''); echo ">Storage</option>
								<option value='Apartment'"; echo ($row['locationType2']=='Apartment' ? 'selected' : ''); echo ">Apartment</option>
								<option value='Townhouse'"; echo ($row['locationType2']=='Townhouse' ? 'selected' : ''); echo ">Townhouse</option>
								<option value='Office'"; echo ($row['locationType2']=='Office' ? 'selected' : ''); echo ">Office</option>
								<option value='Condo'"; echo ($row['locationType2']=='Condo' ? 'selected' : ''); echo ">Condo</option>
								<option value='House'"; echo ($row['locationType2']=='House' ? 'selected' : ''); echo ">House</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Floor
							<input type='text' name='floor2' value='".$row['floor2']."' />
						</td>
					</tr>		
					<tr>
						<td>Type of Move</td>
						<td>
							<select name='typeOfMove'>
								<option> -- </option>
								<option value='Loading'"; echo ($row['typeOfMove']=='Loading' ? 'selected' : ''); echo ">Loading</option>
								<option value='Unloading'"; echo ($row['typeOfMove']=='Unloading' ? 'selected' : ''); echo ">Unloading</option>
								<option value='Junk Removal'"; echo ($row['typeOfMove']=='Junk Removal' ? 'selected' : ''); echo ">Junk Removal</option>
								<option value='Relocation'"; echo ($row['typeOfMove']=='Relocation' ? 'selected' : ''); echo ">Relocation</option>
								<option value='Labor Help'"; echo ($row['typeOfMove']=='Labor Help' ? 'selected' : ''); echo ">Labor Help</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Number of Movers</td>
						<td><input type='text' name='numOfMovrs' value='".$row['numOfMovrs']."' /></td>
					</tr>
					<tr>
						<td>Hourly Rate</td>
						<td><input type='text' name='hourlyRate' value='".$row['hourlyRate']."' /></td>
					</tr>
					<tr>
						<td>Minimum Hours</td>
						<td><input type='text' name='minHours' value='".$row['minHours']."' /></td>
					</tr>
					<tr>
						<td>Description</td>
						<td>
							<select name='description'>
								<option> -- </option>
								<option value='1 Bedroom'"; echo ($row['description']=='1 Bedroom' ? 'selected' : ''); echo ">1 Bedroom</option>
								<option value='2 Bedrooms'"; echo ($row['description']=='2 Bedrooms' ? 'selected' : ''); echo ">2 Bedrooms</option>
								<option value='3 Bedrooms'"; echo ($row['description']=='3 Bedrooms' ? 'selected' : ''); echo ">3 Bedrooms</option>
								<option value='4 Bedrooms'"; echo ($row['description']=='4 Bedrooms' ? 'selected' : ''); echo ">4 Bedrooms</option>
								<option value='5 Bedrooms'"; echo ($row['description']=='5 Bedrooms' ? 'selected' : ''); echo ">5 Bedrooms</option>
								<option value='6+ Bedrooms'"; echo ($row['description']=='6+ Bedrooms' ? 'selected' : ''); echo ">6+ Bedrooms</option>
								<option value='Studio'"; echo ($row['description']=='Studio' ? 'selected' : ''); echo ">Studio</option>
								<option value='Other'"; echo ($row['description']=='Other' ? 'selected' : ''); echo ">Other</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Truck Size</td>
						<td>
							<select name='truckSize'>
								<option> -- </option>
								<option value='10 ft. Uhaul'"; echo ($row['truckSize']=='10 ft. Uhaul' ? 'selected' : ''); echo ">10' Uhaul</option>
								<option value='12 ft. Penske'"; echo ($row['truckSize']=='12 ft. Penske' ? 'selected' : ''); echo ">12' Penske</option>
								<option value='14 ft. Uhaul'"; echo ($row['truckSize']=='14 ft. Uhaul' ? 'selected' : ''); echo ">14' Uhaul</option>
								<option value='16 ft. Penske'"; echo ($row['truckSize']=='16 ft. Penske' ? 'selected' : ''); echo ">16' Penske</option>
								<option value='17 ft. Uhaul'"; echo ($row['truckSize']=='17 ft. Uhaul' ? 'selected' : ''); echo ">17' Uhaul</option>
								<option value='17 ft. 2CB'"; echo ($row['truckSize']=='17 ft. 2CB' ? 'selected' : ''); echo ">17' 2CB</option>
								<option value='20 ft. Uhaul'"; echo ($row['truckSize']=='20 ft. Uhaul' ? 'selected' : ''); echo ">20' Uhaul</option>
								<option value='22 ft. Penske'"; echo ($row['truckSize']=='22 ft. Penske' ? 'selected' : ''); echo ">22' Penske</option>
								<option value='24 ft. Uhaul'"; echo ($row['truckSize']=='24 ft. Uhaul' ? 'selected' : ''); echo ">24' Uhaul</option>
								<option value='26 ft. Penske'"; echo ($row['truckSize']=='26 ft. Penske' ? 'selected' : ''); echo ">26' Penske</option>
								<option value='26 ft. Uhaul'"; echo ($row['truckSize']=='26 ft. Uhaul' ? 'selected' : ''); echo ">26' Uhaul</option>
								<option value='Not needed'"; echo ($row['truckSize']=='Not needed' ? 'selected' : ''); echo ">Not needed</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Materials Cost</td>
						<td><input type='text' name='materialsCost' value='".$row['materialsCost']."' /></td>
					</tr>
					<tr>
						<td>Travel Fee</td>
						<td><input type='text' name='travelFee' value='".$row['travelFee']."' /></td>
					</tr>
					<tr>
						<td>Credit</td>
						<td><input type='text' name='credit' value='".$row['credit']."' /></td>
					</tr>
					<tr>
						<td>Status of Move</td>
						<td>
							<select name='statusOfMove'>
								<option value='Pending'"; echo ($row['statusOfMove']=='Pending' ? 'selected' : ''); echo ">Pending</option>
								<option value='Booked'"; echo ($row['statusOfMove']=='Booked' ? 'selected' : ''); echo ">Booked</option>
								<option value='Complete'"; echo ($row['statusOfMove']=='Complete' ? 'selected' : ''); echo ">Complete</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Date of Service</td>
						<td>";
							if ($row['dateOfService1'] == '0000-00-00')
							{
							$row['dateOfService1'] = '';
							}
							echo"<input type='text' name='dateOfService1' value='".$row['dateOfService1']."' />
							<a href=javascript:showCal('dateOfService1_editJobProfile')>&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
						</td>
					</tr>
					<tr>
						<td>Time of Service</td>
						<td>
							<select name='timeOfServiceHour1'>
								<option value='00'"; echo ($row['timeOfServiceHour1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['timeOfServiceHour1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['timeOfServiceHour1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['timeOfServiceHour1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['timeOfServiceHour1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['timeOfServiceHour1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['timeOfServiceHour1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['timeOfServiceHour1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['timeOfServiceHour1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['timeOfServiceHour1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['timeOfServiceHour1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['timeOfServiceHour1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['timeOfServiceHour1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['timeOfServiceHour1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['timeOfServiceHour1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['timeOfServiceHour1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['timeOfServiceHour1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['timeOfServiceHour1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['timeOfServiceHour1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['timeOfServiceHour1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['timeOfServiceHour1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['timeOfServiceHour1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['timeOfServiceHour1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['timeOfServiceHour1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['timeOfServiceHour1']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='timeOfServiceMinute1'>
								<option value='00'"; echo ($row['timeOfServiceMinute1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['timeOfServiceMinute1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['timeOfServiceMinute1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['timeOfServiceMinute1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['timeOfServiceMinute1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['timeOfServiceMinute1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['timeOfServiceMinute1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['timeOfServiceMinute1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['timeOfServiceMinute1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['timeOfServiceMinute1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['timeOfServiceMinute1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['timeOfServiceMinute1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['timeOfServiceMinute1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['timeOfServiceMinute1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['timeOfServiceMinute1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['timeOfServiceMinute1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['timeOfServiceMinute1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['timeOfServiceMinute1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['timeOfServiceMinute1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['timeOfServiceMinute1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['timeOfServiceMinute1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['timeOfServiceMinute1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['timeOfServiceMinute1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['timeOfServiceMinute1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['timeOfServiceMinute1']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['timeOfServiceMinute1']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['timeOfServiceMinute1']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['timeOfServiceMinute1']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['timeOfServiceMinute1']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['timeOfServiceMinute1']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['timeOfServiceMinute1']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['timeOfServiceMinute1']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['timeOfServiceMinute1']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['timeOfServiceMinute1']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['timeOfServiceMinute1']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['timeOfServiceMinute1']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['timeOfServiceMinute1']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['timeOfServiceMinute1']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['timeOfServiceMinute1']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['timeOfServiceMinute1']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['timeOfServiceMinute1']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['timeOfServiceMinute1']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['timeOfServiceMinute1']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['timeOfServiceMinute1']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['timeOfServiceMinute1']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['timeOfServiceMinute1']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['timeOfServiceMinute1']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['timeOfServiceMinute1']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['timeOfServiceMinute1']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['timeOfServiceMinute1']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['timeOfServiceMinute1']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['timeOfServiceMinute1']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['timeOfServiceMinute1']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['timeOfServiceMinute1']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['timeOfServiceMinute1']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['timeOfServiceMinute1']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['timeOfServiceMinute1']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['timeOfServiceMinute1']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['timeOfServiceMinute1']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['timeOfServiceMinute1']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
							&nbsp;&nbsp;to&nbsp;&nbsp;
							<select name='timeOfServiceHour2'>
								<option value='00'"; echo ($row['timeOfServiceHour2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['timeOfServiceHour2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['timeOfServiceHour2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['timeOfServiceHour2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['timeOfServiceHour2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['timeOfServiceHour2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['timeOfServiceHour2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['timeOfServiceHour2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['timeOfServiceHour2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['timeOfServiceHour2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['timeOfServiceHour2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['timeOfServiceHour2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['timeOfServiceHour2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['timeOfServiceHour2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['timeOfServiceHour2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['timeOfServiceHour2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['timeOfServiceHour2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['timeOfServiceHour2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['timeOfServiceHour2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['timeOfServiceHour2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['timeOfServiceHour2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['timeOfServiceHour2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['timeOfServiceHour2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['timeOfServiceHour2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['timeOfServiceHour2']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='timeOfServiceMinute2'>
								<option value='00'"; echo ($row['timeOfServiceMinute2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['timeOfServiceMinute2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['timeOfServiceMinute2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['timeOfServiceMinute2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['timeOfServiceMinute2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['timeOfServiceMinute2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['timeOfServiceMinute2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['timeOfServiceMinute2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['timeOfServiceMinute2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['timeOfServiceMinute2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['timeOfServiceMinute2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['timeOfServiceMinute2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['timeOfServiceMinute2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['timeOfServiceMinute2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['timeOfServiceMinute2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['timeOfServiceMinute2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['timeOfServiceMinute2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['timeOfServiceMinute2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['timeOfServiceMinute2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['timeOfServiceMinute2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['timeOfServiceMinute2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['timeOfServiceMinute2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['timeOfServiceMinute2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['timeOfServiceMinute2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['timeOfServiceMinute2']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['timeOfServiceMinute2']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['timeOfServiceMinute2']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['timeOfServiceMinute2']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['timeOfServiceMinute2']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['timeOfServiceMinute2']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['timeOfServiceMinute2']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['timeOfServiceMinute2']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['timeOfServiceMinute2']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['timeOfServiceMinute2']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['timeOfServiceMinute2']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['timeOfServiceMinute2']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['timeOfServiceMinute2']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['timeOfServiceMinute2']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['timeOfServiceMinute2']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['timeOfServiceMinute2']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['timeOfServiceMinute2']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['timeOfServiceMinute2']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['timeOfServiceMinute2']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['timeOfServiceMinute2']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['timeOfServiceMinute2']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['timeOfServiceMinute2']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['timeOfServiceMinute2']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['timeOfServiceMinute2']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['timeOfServiceMinute2']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['timeOfServiceMinute2']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['timeOfServiceMinute2']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['timeOfServiceMinute2']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['timeOfServiceMinute2']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['timeOfServiceMinute2']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['timeOfServiceMinute2']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['timeOfServiceMinute2']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['timeOfServiceMinute2']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['timeOfServiceMinute2']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['timeOfServiceMinute2']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['timeOfServiceMinute2']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Second Date</td>
						<td>";
							if ($row['dateOfService2'] == '0000-00-00')
							{
							$row['dateOfService2'] = '';
							}
							echo"
							<input type='text' name='dateOfService2' value='".$row['dateOfService2']."' />
							<a href=javascript:showCal('dateOfService2_editJobProfile')>&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
						</td>
					</tr>
					<tr>
						<td>Desription for second date</td>
						<td><input type='text' name='descriptionForSecondDate' value='".$row['descriptionForSecondDate']."' /></td>
					</tr>
					<tr>
						<td>Deposit Date</td>
						<td>";
							if ($row['depositDate'] == '0000-00-00')
							{
							$row['depositDate'] = '';
							}
							echo"
							<input type='text' name='depositDate' value='".$row['depositDate']."' />
							<a href=javascript:showCal('depositDate_editJobProfile')>&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
						</td>
					</tr>
					<tr>
						<td>How did you hear about us?</td>
						<td>
							<select name='hearAbtUs'>
								<option> -- </option>
								<option value='Craigslist'"; echo ($row['hearAbtUs']=='Craigslist' ? 'selected' : ''); echo ">Craigslist</option>
								<option value='Search engine'"; echo ($row['hearAbtUs']=='Search engine' ? 'selected' : ''); echo ">Search engine</option>
								<option value='Storage facility'"; echo ($row['hearAbtUs']=='Storage facility' ? 'selected' : ''); echo ">Storage facility</option>
								<option value='Apartment complex'"; echo ($row['hearAbtUs']=='Apartment complex' ? 'selected' : ''); echo ">Apartment complex</option>
								<option value='Friend'"; echo ($row['hearAbtUs']=='Friend' ? 'selected' : ''); echo ">Friend</option>
								<option value='Facebook'"; echo ($row['hearAbtUs']=='Facebook' ? 'selected' : ''); echo ">Facebook</option>
								<option value='Direct Mail'"; echo ($row['hearAbtUs']=='Direct Mail' ? 'selected' : ''); echo ">Direct Mail</option>
								<option value='Chamber of Commerce'"; echo ($row['hearAbtUs']=='Chamber of Commerce' ? 'selected' : ''); echo ">Chamber of Commerce</option>
								<option value='Other'"; echo ($row['hearAbtUs']=='Other' ? 'selected' : ''); echo ">Other</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>CCV #</td>
						<td><input type='text' name='ccv' value='".$row['ccv']."' /></td>
					</tr>
					<tr>
						<td>Assembly</td>
						<td><textarea name='assembly' rows='5' cols='50'>".$row['assembly']."</textarea></td>
					</tr>
					<tr>
						<td>Comments</td>
						<td><textarea name='comments' rows='5' cols='50'>".$row['comments']."</textarea></td>
					</tr>
					<tr>
						<td colspan='2'><input type='checkbox' name='sendCustEmail'>&nbsp;Send email to customer with Written Estimate attached.</td>
					</tr>
					<tr>
						<td colspan='2'><input type='checkbox' name='sendMngrEmail'>&nbsp;Send email to manager with Service Receipt attached.</td>
					</tr>
					
					<tr>
						<td>Start Time</td>
						<td>
							<select name='startTimeHour1'>
								<option value='00'"; echo ($row['startTimeHour1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeHour1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeHour1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeHour1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeHour1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeHour1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeHour1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeHour1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeHour1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeHour1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeHour1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeHour1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeHour1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeHour1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeHour1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeHour1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeHour1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeHour1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeHour1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeHour1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeHour1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeHour1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeHour1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeHour1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeHour1']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='startTimeMinute1'>
								<option value='00'"; echo ($row['startTimeMinute1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeMinute1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeMinute1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeMinute1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeMinute1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeMinute1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeMinute1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeMinute1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeMinute1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeMinute1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeMinute1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeMinute1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeMinute1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeMinute1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeMinute1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeMinute1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeMinute1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeMinute1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeMinute1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeMinute1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeMinute1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeMinute1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeMinute1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeMinute1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeMinute1']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['startTimeMinute1']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['startTimeMinute1']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['startTimeMinute1']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['startTimeMinute1']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['startTimeMinute1']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['startTimeMinute1']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['startTimeMinute1']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['startTimeMinute1']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['startTimeMinute1']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['startTimeMinute1']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['startTimeMinute1']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['startTimeMinute1']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['startTimeMinute1']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['startTimeMinute1']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['startTimeMinute1']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['startTimeMinute1']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['startTimeMinute1']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['startTimeMinute1']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['startTimeMinute1']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['startTimeMinute1']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['startTimeMinute1']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['startTimeMinute1']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['startTimeMinute1']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['startTimeMinute1']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['startTimeMinute1']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['startTimeMinute1']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['startTimeMinute1']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['startTimeMinute1']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['startTimeMinute1']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['startTimeMinute1']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['startTimeMinute1']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['startTimeMinute1']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['startTimeMinute1']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['startTimeMinute1']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['startTimeMinute1']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							End time
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<select name='endTimeHour1'>
								<option value='00'"; echo ($row['endTimeHour1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeHour1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeHour1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeHour1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeHour1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeHour1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeHour1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeHour1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeHour1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeHour1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeHour1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeHour1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeHour1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeHour1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeHour1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeHour1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeHour1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeHour1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeHour1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeHour1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeHour1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeHour1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeHour1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeHour1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeHour1']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='endTimeMinute1'>
								<option value='00'"; echo ($row['endTimeMinute1']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeMinute1']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeMinute1']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeMinute1']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeMinute1']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeMinute1']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeMinute1']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeMinute1']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeMinute1']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeMinute1']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeMinute1']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeMinute1']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeMinute1']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeMinute1']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeMinute1']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeMinute1']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeMinute1']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeMinute1']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeMinute1']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeMinute1']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeMinute1']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeMinute1']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeMinute1']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeMinute1']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeMinute1']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['endTimeMinute1']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['endTimeMinute1']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['endTimeMinute1']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['endTimeMinute1']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['endTimeMinute1']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['endTimeMinute1']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['endTimeMinute1']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['endTimeMinute1']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['endTimeMinute1']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['endTimeMinute1']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['endTimeMinute1']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['endTimeMinute1']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['endTimeMinute1']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['endTimeMinute1']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['endTimeMinute1']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['endTimeMinute1']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['endTimeMinute1']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['endTimeMinute1']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['endTimeMinute1']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['endTimeMinute1']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['endTimeMinute1']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['endTimeMinute1']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['endTimeMinute1']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['endTimeMinute1']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['endTimeMinute1']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['endTimeMinute1']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['endTimeMinute1']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['endTimeMinute1']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['endTimeMinute1']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['endTimeMinute1']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['endTimeMinute1']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['endTimeMinute1']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['endTimeMinute1']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['endTimeMinute1']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['endTimeMinute1']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td>Start Time</td>
						<td>
							<select name='startTimeHour2'>
								<option value='00'"; echo ($row['startTimeHour2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeHour2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeHour2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeHour2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeHour2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeHour2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeHour2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeHour2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeHour2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeHour2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeHour2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeHour2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeHour2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeHour2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeHour2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeHour2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeHour2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeHour2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeHour2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeHour2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeHour2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeHour2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeHour2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeHour2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeHour2']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='startTimeMinute2'>
								<option value='00'"; echo ($row['startTimeMinute2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeMinute2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeMinute2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeMinute2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeMinute2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeMinute2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeMinute2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeMinute2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeMinute2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeMinute2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeMinute2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeMinute2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeMinute2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeMinute2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeMinute2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeMinute2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeMinute2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeMinute2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeMinute2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeMinute2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeMinute2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeMinute2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeMinute2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeMinute2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeMinute2']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['startTimeMinute2']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['startTimeMinute2']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['startTimeMinute2']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['startTimeMinute2']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['startTimeMinute2']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['startTimeMinute2']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['startTimeMinute2']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['startTimeMinute2']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['startTimeMinute2']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['startTimeMinute2']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['startTimeMinute2']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['startTimeMinute2']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['startTimeMinute2']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['startTimeMinute2']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['startTimeMinute2']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['startTimeMinute2']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['startTimeMinute2']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['startTimeMinute2']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['startTimeMinute2']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['startTimeMinute2']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['startTimeMinute2']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['startTimeMinute2']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['startTimeMinute2']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['startTimeMinute2']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['startTimeMinute2']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['startTimeMinute2']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['startTimeMinute2']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['startTimeMinute2']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['startTimeMinute2']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['startTimeMinute2']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['startTimeMinute2']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['startTimeMinute2']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['startTimeMinute2']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['startTimeMinute2']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['startTimeMinute2']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							End time
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<select name='endTimeHour2'>
								<option value='00'"; echo ($row['endTimeHour2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeHour2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeHour2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeHour2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeHour2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeHour2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeHour2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeHour2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeHour2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeHour2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeHour2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeHour2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeHour2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeHour2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeHour2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeHour2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeHour2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeHour2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeHour2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeHour2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeHour2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeHour2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeHour2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeHour2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeHour2']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='endTimeMinute2'>
								<option value='00'"; echo ($row['endTimeMinute2']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeMinute2']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeMinute2']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeMinute2']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeMinute2']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeMinute2']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeMinute2']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeMinute2']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeMinute2']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeMinute2']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeMinute2']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeMinute2']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeMinute2']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeMinute2']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeMinute2']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeMinute2']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeMinute2']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeMinute2']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeMinute2']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeMinute2']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeMinute2']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeMinute2']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeMinute2']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeMinute2']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeMinute2']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['endTimeMinute2']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['endTimeMinute2']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['endTimeMinute2']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['endTimeMinute2']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['endTimeMinute2']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['endTimeMinute2']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['endTimeMinute2']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['endTimeMinute2']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['endTimeMinute2']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['endTimeMinute2']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['endTimeMinute2']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['endTimeMinute2']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['endTimeMinute2']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['endTimeMinute2']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['endTimeMinute2']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['endTimeMinute2']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['endTimeMinute2']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['endTimeMinute2']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['endTimeMinute2']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['endTimeMinute2']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['endTimeMinute2']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['endTimeMinute2']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['endTimeMinute2']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['endTimeMinute2']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['endTimeMinute2']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['endTimeMinute2']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['endTimeMinute2']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['endTimeMinute2']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['endTimeMinute2']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['endTimeMinute2']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['endTimeMinute2']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['endTimeMinute2']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['endTimeMinute2']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['endTimeMinute2']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['endTimeMinute2']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td>Start Time</td>
						<td>
							<select name='startTimeHour3'>
								<option value='00'"; echo ($row['startTimeHour3']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeHour3']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeHour3']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeHour3']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeHour3']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeHour3']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeHour3']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeHour3']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeHour3']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeHour3']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeHour3']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeHour3']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeHour3']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeHour3']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeHour3']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeHour3']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeHour3']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeHour3']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeHour3']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeHour3']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeHour3']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeHour3']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeHour3']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeHour3']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeHour3']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='startTimeMinute3'>
								<option value='00'"; echo ($row['startTimeMinute3']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['startTimeMinute3']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['startTimeMinute3']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['startTimeMinute3']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['startTimeMinute3']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['startTimeMinute3']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['startTimeMinute3']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['startTimeMinute3']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['startTimeMinute3']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['startTimeMinute3']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['startTimeMinute3']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['startTimeMinute3']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['startTimeMinute3']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['startTimeMinute3']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['startTimeMinute3']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['startTimeMinute3']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['startTimeMinute3']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['startTimeMinute3']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['startTimeMinute3']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['startTimeMinute3']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['startTimeMinute3']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['startTimeMinute3']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['startTimeMinute3']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['startTimeMinute3']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['startTimeMinute3']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['startTimeMinute3']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['startTimeMinute3']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['startTimeMinute3']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['startTimeMinute3']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['startTimeMinute3']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['startTimeMinute3']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['startTimeMinute3']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['startTimeMinute3']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['startTimeMinute3']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['startTimeMinute3']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['startTimeMinute3']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['startTimeMinute3']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['startTimeMinute3']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['startTimeMinute3']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['startTimeMinute3']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['startTimeMinute3']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['startTimeMinute3']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['startTimeMinute3']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['startTimeMinute3']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['startTimeMinute3']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['startTimeMinute3']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['startTimeMinute3']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['startTimeMinute3']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['startTimeMinute3']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['startTimeMinute3']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['startTimeMinute3']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['startTimeMinute3']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['startTimeMinute3']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['startTimeMinute3']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['startTimeMinute3']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['startTimeMinute3']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['startTimeMinute3']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['startTimeMinute3']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['startTimeMinute3']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['startTimeMinute3']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							End time
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<select name='endTimeHour3'>
								<option value='00'"; echo ($row['endTimeHour3']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeHour3']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeHour3']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeHour3']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeHour3']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeHour3']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeHour3']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeHour3']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeHour3']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeHour3']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeHour3']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeHour3']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeHour3']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeHour3']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeHour3']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeHour3']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeHour3']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeHour3']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeHour3']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeHour3']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeHour3']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeHour3']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeHour3']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeHour3']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeHour3']=='24' ? 'selected' : ''); echo ">24</option>
							</select>
							<select name='endTimeMinute3'>
								<option value='00'"; echo ($row['endTimeMinute3']=='00' ? 'selected' : ''); echo ">00</option>
								<option value='01'"; echo ($row['endTimeMinute3']=='01' ? 'selected' : ''); echo ">01</option>
								<option value='02'"; echo ($row['endTimeMinute3']=='02' ? 'selected' : ''); echo ">02</option>
								<option value='03'"; echo ($row['endTimeMinute3']=='03' ? 'selected' : ''); echo ">03</option>
								<option value='04'"; echo ($row['endTimeMinute3']=='04' ? 'selected' : ''); echo ">04</option>
								<option value='05'"; echo ($row['endTimeMinute3']=='05' ? 'selected' : ''); echo ">05</option>
								<option value='06'"; echo ($row['endTimeMinute3']=='06' ? 'selected' : ''); echo ">06</option>
								<option value='07'"; echo ($row['endTimeMinute3']=='07' ? 'selected' : ''); echo ">07</option>
								<option value='08'"; echo ($row['endTimeMinute3']=='08' ? 'selected' : ''); echo ">08</option>
								<option value='09'"; echo ($row['endTimeMinute3']=='09' ? 'selected' : ''); echo ">09</option>
								<option value='10'"; echo ($row['endTimeMinute3']=='10' ? 'selected' : ''); echo ">10</option>
								<option value='11'"; echo ($row['endTimeMinute3']=='11' ? 'selected' : ''); echo ">11</option>
								<option value='12'"; echo ($row['endTimeMinute3']=='12' ? 'selected' : ''); echo ">12</option>
								<option value='13'"; echo ($row['endTimeMinute3']=='13' ? 'selected' : ''); echo ">13</option>
								<option value='14'"; echo ($row['endTimeMinute3']=='14' ? 'selected' : ''); echo ">14</option>
								<option value='15'"; echo ($row['endTimeMinute3']=='15' ? 'selected' : ''); echo ">15</option>
								<option value='16'"; echo ($row['endTimeMinute3']=='16' ? 'selected' : ''); echo ">16</option>
								<option value='17'"; echo ($row['endTimeMinute3']=='17' ? 'selected' : ''); echo ">17</option>
								<option value='18'"; echo ($row['endTimeMinute3']=='18' ? 'selected' : ''); echo ">18</option>
								<option value='19'"; echo ($row['endTimeMinute3']=='19' ? 'selected' : ''); echo ">19</option>
								<option value='20'"; echo ($row['endTimeMinute3']=='20' ? 'selected' : ''); echo ">20</option>
								<option value='21'"; echo ($row['endTimeMinute3']=='21' ? 'selected' : ''); echo ">21</option>
								<option value='22'"; echo ($row['endTimeMinute3']=='22' ? 'selected' : ''); echo ">22</option>
								<option value='23'"; echo ($row['endTimeMinute3']=='23' ? 'selected' : ''); echo ">23</option>
								<option value='24'"; echo ($row['endTimeMinute3']=='24' ? 'selected' : ''); echo ">24</option>
								<option value='25'"; echo ($row['endTimeMinute3']=='25' ? 'selected' : ''); echo ">25</option>
								<option value='26'"; echo ($row['endTimeMinute3']=='26' ? 'selected' : ''); echo ">26</option>
								<option value='27'"; echo ($row['endTimeMinute3']=='27' ? 'selected' : ''); echo ">27</option>
								<option value='28'"; echo ($row['endTimeMinute3']=='28' ? 'selected' : ''); echo ">28</option>
								<option value='29'"; echo ($row['endTimeMinute3']=='29' ? 'selected' : ''); echo ">29</option>
								<option value='30'"; echo ($row['endTimeMinute3']=='30' ? 'selected' : ''); echo ">30</option>
								<option value='31'"; echo ($row['endTimeMinute3']=='31' ? 'selected' : ''); echo ">31</option>
								<option value='32'"; echo ($row['endTimeMinute3']=='32' ? 'selected' : ''); echo ">32</option>
								<option value='33'"; echo ($row['endTimeMinute3']=='33' ? 'selected' : ''); echo ">33</option>
								<option value='34'"; echo ($row['endTimeMinute3']=='34' ? 'selected' : ''); echo ">34</option>
								<option value='35'"; echo ($row['endTimeMinute3']=='35' ? 'selected' : ''); echo ">35</option>
								<option value='36'"; echo ($row['endTimeMinute3']=='36' ? 'selected' : ''); echo ">36</option>
								<option value='37'"; echo ($row['endTimeMinute3']=='37' ? 'selected' : ''); echo ">37</option>
								<option value='38'"; echo ($row['endTimeMinute3']=='38' ? 'selected' : ''); echo ">38</option>
								<option value='39'"; echo ($row['endTimeMinute3']=='39' ? 'selected' : ''); echo ">39</option>
								<option value='40'"; echo ($row['endTimeMinute3']=='40' ? 'selected' : ''); echo ">40</option>
								<option value='41'"; echo ($row['endTimeMinute3']=='41' ? 'selected' : ''); echo ">41</option>
								<option value='42'"; echo ($row['endTimeMinute3']=='42' ? 'selected' : ''); echo ">42</option>
								<option value='43'"; echo ($row['endTimeMinute3']=='43' ? 'selected' : ''); echo ">43</option>
								<option value='44'"; echo ($row['endTimeMinute3']=='44' ? 'selected' : ''); echo ">44</option>
								<option value='45'"; echo ($row['endTimeMinute3']=='45' ? 'selected' : ''); echo ">45</option>
								<option value='46'"; echo ($row['endTimeMinute3']=='46' ? 'selected' : ''); echo ">46</option>
								<option value='47'"; echo ($row['endTimeMinute3']=='47' ? 'selected' : ''); echo ">47</option>
								<option value='48'"; echo ($row['endTimeMinute3']=='48' ? 'selected' : ''); echo ">48</option>4
								<option value='49'"; echo ($row['endTimeMinute3']=='49' ? 'selected' : ''); echo ">49</option>
								<option value='50'"; echo ($row['endTimeMinute3']=='50' ? 'selected' : ''); echo ">50</option>
								<option value='51'"; echo ($row['endTimeMinute3']=='51' ? 'selected' : ''); echo ">51</option>
								<option value='52'"; echo ($row['endTimeMinute3']=='52' ? 'selected' : ''); echo ">52</option>
								<option value='53'"; echo ($row['endTimeMinute3']=='53' ? 'selected' : ''); echo ">53</option>
								<option value='54'"; echo ($row['endTimeMinute3']=='54' ? 'selected' : ''); echo ">54</option>
								<option value='55'"; echo ($row['endTimeMinute3']=='55' ? 'selected' : ''); echo ">55</option>
								<option value='56'"; echo ($row['endTimeMinute3']=='56' ? 'selected' : ''); echo ">56</option>
								<option value='57'"; echo ($row['endTimeMinute3']=='57' ? 'selected' : ''); echo ">57</option>
								<option value='58'"; echo ($row['endTimeMinute3']=='58' ? 'selected' : ''); echo ">58</option>
								<option value='59'"; echo ($row['endTimeMinute3']=='59' ? 'selected' : ''); echo ">59</option>
							</select>
						</td>
					</tr>
					
					<tr>
						<td>Gas Expenses</td>
						<td><input type='text' name='gasExpenses' value='".$row['gasExpenses']."' /></td>
					</tr>
					<tr>
						<td>Truck Expenses</td>
						<td><input type='text' name='truckExpenses' value='".$row['truckExpenses']."' /></td>
					</tr>
					<tr>
						<td>Assign Movers";
					
					$query2 = "select numOfMovrs from jobs where jobId=".$_SESSION['jobId'];
					$result2 = mysql_query($query2,$con);	
					if(!$result2)
					{
						die("Invalid query! <br> The query is: " . $query2);
					}
					
					$row2 = mysql_fetch_assoc($result2);
					if ($row2['numOfMovrs'])
					{
						echo "<br/>(You need to assign ".$row2['numOfMovrs']." movers)";
					}
					
					echo "</td><td>";
					
					$query2 = "select userId, name from userinfo where userType='Mover' and city='".$row['branch']."' order by name asc";
					$result2 = mysql_query($query2,$con);	
					if(!$result2)
					{
						die("Invalid query! <br> The query is: " . $query2);
					}
					// Display all movers on the page
					$rowCount = 0;
					while (1)
					{
						$row2 = mysql_fetch_assoc($result2);
						if (!$row2['userId'])
						{
							break;
						}
						$rowCount++;
						/* find out if the mover is already assigned to the job, if yes
							show a tick with attribute checked='yes' */
						$subQuery = "select movrId from jobassign where jobId = ".$_SESSION['jobId']." and movrId = ".$row2['userId'];
						$subResult = mysql_query($subQuery,$con);	
						if(!$subResult)
						{
							die("Invalid query! <br> The query is: " . $subQuery);
						}
						$subRow = mysql_fetch_assoc($subResult);
						if ($subRow['movrId'])
						{
							echo "<input type='checkbox' name='assignedMovrs[]' value='".$row2['userId']."' checked='yes' /> ".ucfirst($row2['name'])."<br />";
						}
						else 
						{
							echo "<input type='checkbox' name='assignedMovrs[]' value='".$row2['userId']."' /> ".ucfirst($row2['name'])."<br />";
						}

					}
					if ($rowCount == 0)
					{
						echo "No movers found in ".$row['branch']."! <a href='signup.php'>Click here</a> to create one";
					}
					
					echo "</td>
					</tr>";
					//display file
					$subQuery = "select * from files where whichExists = 1 and jobId=".$_SESSION['jobId'];
					$subResult = mysql_query($subQuery,$con);
					if(!$subResult)
					{
						die("Invalid query! <br> The query is: " . $subQuery);
					}

					while ($subRow = mysql_fetch_assoc($subResult))
					{
						echo "<tr>
							<td>Uploaded File(s)</td>";
							$orgfileName = strtok($subRow['filePath'], "_");
							$orgfileName = strtok("_");
							echo "<td><a href='".$subRow['filePath']."'>".$orgfileName."</a>&nbsp;&nbsp;
							<a href='deleteSingleFile.php?filePath=".$subRow['filePath']."&jobId=".$_SESSION['jobId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a></td>
						</tr>";
					}
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName1' id='fileName1' type='file'/></td>
					</tr>";
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName2' id='fileName2' type='file'/></td>
					</tr>";
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName3' id='fileName3' type='file'/></td>
					</tr>";
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName4' id='fileName4' type='file'/></td>
					</tr>";
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName5' id='fileName5' type='file'/></td>
					</tr>";
					echo "<tr>
						<td>File to upload</td>
						<td><input name='fileName6' id='fileName6' type='file'/></td>
					</tr>";
					echo "<tr>
						<td colspan='2'><input type='submit' value='Save' /></td>
					</tr>";
				echo "</form>";
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