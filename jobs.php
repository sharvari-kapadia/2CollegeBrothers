<?php
	require('session.php');
	
	if (isset($_POST['branch']))
	{			
		//Start connection with database
		require("connect.php");
				
			//Computing jobId by combination of date and time: yyyymmddhhmmss
			//$newJobId = date("YmdHis");
			$query = "select max(jobId) as oldJobId from jobs";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			$row = mysql_fetch_assoc($result);
			if ($row['oldJobId'])
			{
				$newJobId = $row['oldJobId']+1;
			}
			else
			{
				// min 6 digit jobId
				$newJobId = 100000;
			}
			
			$_POST['custZip1'] = ('' == $_POST['custZip1'] ? 0 : $_POST['custZip1']);
			$_POST['custZip2'] = ('' == $_POST['custZip2'] ? 0 : $_POST['custZip2']);
			$_POST['numOfMovrs'] = ('' == $_POST['numOfMovrs'] ? 0 : $_POST['numOfMovrs']);
			$_POST['hourlyRate'] = ('' == $_POST['hourlyRate'] ? 0 : $_POST['hourlyRate']);	
			$_POST['minHours'] = ('' == $_POST['minHours'] ? 0 : $_POST['minHours']);	
			$_POST['materialsCost'] = ('' == $_POST['materialsCost'] ? 0 : $_POST['materialsCost']);	
			$_POST['travelFee'] = ('' == $_POST['travelFee'] ? 0 : $_POST['travelFee']);	
			$_POST['credit'] = ('' == $_POST['credit'] ? 0 : $_POST['credit']);	
			
			$timeOfService1 = $_POST['timeOfServiceHour1'].":".$_POST['timeOfServiceMinute1'].":00";
			$timeOfService2 = $_POST['timeOfServiceHour2'].":".$_POST['timeOfServiceMinute2'].":00";
			
			//Fill jobs table
			$query = "insert into jobs (jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, 
										locationType1, floor1, custAdd21, custAdd22, custCity2, custState2, custZip2, locationType2, floor2, typeOfMove, 
										numOfMovrs, hourlyRate, minHours, description, truckSize, materialsCost, travelFee, credit, statusOfMove, dateOfService1, 
										timeOfServiceHour1, timeOfServiceMinute1, timeOfService1, timeOfServiceHour2, timeOfServiceMinute2, timeOfService2, 
										dateOfService2, descriptionForSecondDate, depositDate, hearAbtUs, ccv, assembly, comments) values 
										(".$newJobId.",'".$_POST['branch']."','".$_POST['custName']."','".$_POST['custPhoneNumber']."','".$_POST['custEmail']."',
										'".$_POST['custAdd11']."','".$_POST['custAdd12']."','".$_POST['custCity1']."','".$_POST['custState1']."',
										".$_POST['custZip1'].",'".$_POST['locationType1']."','".$_POST['floor1']."',
										'".$_POST['custAdd21']."','".$_POST['custAdd22']."','".$_POST['custCity2']."','".$_POST['custState2']."',
										".$_POST['custZip2'].",'".$_POST['locationType2']."','".$_POST['floor2']."',
										'".$_POST['typeOfMove']."',".$_POST['numOfMovrs'].",".$_POST['hourlyRate'].",".$_POST['minHours'].",
										'".$_POST['description']."','".$_POST['truckSize']."',".$_POST['materialsCost'].",".$_POST['credit'].",".$_POST['credit'].",
										'".$_POST['statusOfMove']."','".$_POST['dateOfService1']."',
										".$_POST['timeOfServiceHour1'].",".$_POST['timeOfServiceMinute1'].",'".$timeOfService1."',
										".$_POST['timeOfServiceHour2'].",".$_POST['timeOfServiceMinute2'].",'".$timeOfService2."',
										'".$_POST['dateOfService2']."','".$_POST['descriptionForSecondDate']."','".$_POST['depositDate']."',
										'".$_POST['hearAbtUS']."','".$_POST['ccv']."','".$_POST['assembly']."','".$_POST['comments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			$query = "insert into expenses (jobId, totalPrice, labor, transactionFee, staffingFee, managerProfit, netProfit, bank, individual) values 
										(".$newJobId.", 0, 0, 0, 0, 0, 0, 0, 0)";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
			
			//Close connection
			mysql_close($con);
			
			// bug fix start
			// written estimate and manager email require jobid to be stored in a session
			// variable $_SESSION['jobId'] which is not set in jobs.php. set it here
			// and then unset it after the emails have been sent!!!
			$_SESSION['jobId'] = $newJobId;
			// bug fix end
			
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
		
		// bug fix start
		unset($_SESSION['jobId']);
		// bug fix end
		
		header('Location:home.php');
	}

	require('logo.php');
?>

<html>

<head>
	<title>
		.: New Job profile :.
	</title>
	<link rel="stylesheet" href="homestyle.css">
	<script language="javascript" src="calendar/cal2.js">
	/*
	Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
	Script featured on/available at http://www.dynamicdrive.com/
	This notice must stay intact for use
	*/
	</script>
	<script language="javascript" src="calendar/cal_conf2.js"></script>
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
		<form name='createJobProfile' method='post' action='jobs.php'>
		<tr>
			<td colspan='2' align='center'><h3>Create Job profile</h3></td>
		</tr>
		<tr>
			<td>Branch</td>
			<td>
				<select name='branch'>
					<option value='Gainesville'>Gainesville</option>
					<option value='Tallahassee'>Tallahassee</option>
					<option value='Orlando'>Orlando</option>
					<option value='Miami'>Miami</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Customer Name</td>
			<td><input name='custName' type='text'/></td>
		</tr>
		<tr>
			<td>Phone Number</td>
			<td><input name='custPhoneNumber' type='text'/></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input name='custEmail' type='text'/></td>
		</tr>
		<tr>
			<td>Address</td>
			<td><input name='custAdd11' type='text'/> <br /> <input name='custAdd12' type='text'/></td>
		</tr>
		<tr>
			<td>City</td>
			<td>
				<input name='custCity1' type='text'/>
				&nbsp;&nbsp;&nbsp;&nbsp;State
				<select name='custState1'>
					<option> -- </option>
					<option value='AL'>AL</option>
					<option value='AK'>AK</option>
					<option value='AZ'>AZ</option>
					<option value='AR'>AR</option>
					<option value='CA'>CA</option>
					<option value='CO'>CO</option>
					<option value='CT'>CT</option>
					<option value='DE'>DE</option>
					<option value='FL'>FL</option>
					<option value='GA'>GA</option>
					<option value='HI'>HI</option>
					<option value='ID'>ID</option>
					<option value='IL'>IL</option>
					<option value='IN'>IN</option>
					<option value='IA'>IA</option>
					<option value='KS'>KS</option>
					<option value='KY'>KY</option>
					<option value='LA'>LA</option>
					<option value='ME'>ME</option>
					<option value='MD'>MD</option>
					<option value='MA'>MA</option>
					<option value='MI'>MI</option>
					<option value='MN'>MN</option>
					<option value='MS'>MS</option>
					<option value='MO'>MO</option>
					<option value='MT'>MT</option>
					<option value='NE'>NE</option>
					<option value='NV'>NV</option>
					<option value='NM'>NM</option>
					<option value='NY'>NY</option>
					<option value='NC'>NC</option>
					<option value='ND'>ND</option>
					<option value='OH'>OH</option>
					<option value='OK'>OK</option>
					<option value='OR'>OR</option>
					<option value='PA'>PA</option>
					<option value='RI'>RI</option>
					<option value='SC'>SC</option>
					<option value='SD'>SD</option>
					<option value='TN'>TN</option>
					<option value='TX'>TX</option>
					<option value='UT'>UT</option>
					<option value='VT'>VT</option>
					<option value='VA'>VA</option>
					<option value='WA'>WA</option>
					<option value='WV'>WV</option>
					<option value='WI'>WI</option>
					<option value='WY'>WY</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;Zipcode
				<input name='custZip1' type='text'/>
			</td>
		</tr>
		<tr>
			<td>Location Type</td>
			<td>
				<select name='locationType1'>
					<option> -- </option>
					<option value='House'>House</option>
					<option value='Storage'>Storage</option>
					<option value='Apartment'>Apartment</option>
					<option value='Townhouse'>Townhouse</option>
					<option value='Office'>Office</option>
					<option value='Condo'>Condo</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Floor
				<input name='floor1' type='text'/>
			</td>
		</tr>
		
		<tr>
			<td>Address</td>
			<td><input name='custAdd21' type='text'/> <br /> <input name='custAdd22' type='text'/></td>
		</tr>
		<tr>
			<td>City</td>
			<td>
				<input name='custCity2' type='text'/>
				&nbsp;&nbsp;&nbsp;&nbsp;State
				<select name='custState2'>
					<option> -- </option>
					<option value='AL'>AL</option>
					<option value='AK'>AK</option>
					<option value='AZ'>AZ</option>
					<option value='AR'>AR</option>
					<option value='CA'>CA</option>
					<option value='CO'>CO</option>
					<option value='CT'>CT</option>
					<option value='DE'>DE</option>
					<option value='FL'>FL</option>
					<option value='GA'>GA</option>
					<option value='HI'>HI</option>
					<option value='ID'>ID</option>
					<option value='IL'>IL</option>
					<option value='IN'>IN</option>
					<option value='IA'>IA</option>
					<option value='KS'>KS</option>
					<option value='KY'>KY</option>
					<option value='LA'>LA</option>
					<option value='ME'>ME</option>
					<option value='MD'>MD</option>
					<option value='MA'>MA</option>
					<option value='MI'>MI</option>
					<option value='MN'>MN</option>
					<option value='MS'>MS</option>
					<option value='MO'>MO</option>
					<option value='MT'>MT</option>
					<option value='NE'>NE</option>
					<option value='NV'>NV</option>
					<option value='NM'>NM</option>
					<option value='NY'>NY</option>
					<option value='NC'>NC</option>
					<option value='ND'>ND</option>
					<option value='OH'>OH</option>
					<option value='OK'>OK</option>
					<option value='OR'>OR</option>
					<option value='PA'>PA</option>
					<option value='RI'>RI</option>
					<option value='SC'>SC</option>
					<option value='SD'>SD</option>
					<option value='TN'>TN</option>
					<option value='TX'>TX</option>
					<option value='UT'>UT</option>
					<option value='VT'>VT</option>
					<option value='VA'>VA</option>
					<option value='WA'>WA</option>
					<option value='WV'>WV</option>
					<option value='WI'>WI</option>
					<option value='WY'>WY</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;Zipcode
				<input name='custZip2' type='text'/>
			</td>
		</tr>
		<tr>
			<td>Location Type</td>
			<td>
				<select name='locationType2'>
					<option> -- </option>
					<option value='House'>House</option>
					<option value='Storage'>Storage</option>
					<option value='Apartment'>Apartment</option>
					<option value='Townhouse'>Townhouse</option>
					<option value='Office'>Office</option>
					<option value='Condo'>Condo</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Floor
				<input name='floor2' type='text'/>
			</td>
		</tr>
		<tr>
			<td>Type of Move</td>
			<td>
				<select name='typeOfMove'>
					<option> -- </option>
					<option value='Loading'>Loading</option>
					<option value='Unloading'>Unloading</option>
					<option value='Junk Removal'>Junk Removal</option>
					<option value='Relocation'>Relocation</option>
					<option value='Labor Help'>Labor Help</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Number of Movers</td>
			<td><input name='numOfMovrs' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Hourly Rate</td>
			<td><input name='hourlyRate' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Minimum Hours</td>
			<td><input name='minHours' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Description</td>
			<td>
				<select name='description'>
					<option> -- </option>
					<option value='1 Bedroom'>1 Bedroom</option>
					<option value='2 Bedrooms'>2 Bedrooms</option>
					<option value='3 Bedrooms'>3 Bedrooms</option>
					<option value='4 Bedrooms'>4 Bedrooms</option>
					<option value='5 Bedrooms'>5 Bedrooms</option>
					<option value='6+ Bedrooms'>6+ Bedrooms</option>
					<option value='Studio'>Studio</option>
					<option value='Other'>Other</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Truck Size</td>
			<td>
				<select name='truckSize'>
					<option> -- </option>
					<option value="10 ft.Uhaul">10' Uhaul</option>
					<option value="12 ft. Penske">12' Penske</option>
					<option value="14 ft. Uhaul">14' Uhaul</option>
					<option value="16 ft. Penske">16' Penske</option>
					<option value="17 ft. Uhaul">17' Uhaul</option>
					<option value="17 ft. 2CB">17' 2CB</option>
					<option value="20 ft. Uhaul">20' Uhaul</option>
					<option value="22 ft. Penske">22' Penske</option>
					<option value="24 ft. Uhaul">24' Uhaul</option>
					<option value="26 ft. Penske">26' Penske</option>
					<option value="26 ft. Uhaul">26' Uhaul</option>
					<option value='Not needed'>Not needed</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Materials Cost</td>
			<td><input name='materialsCost' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Travel Fee</td>
			<td><input name='travelFee' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Credit</td>
			<td><input name='credit' type='text' size='3' /></td>
		</tr>
		<tr>
			<td>Status of Move</td>
			<td>
				<select name='statusOfMove'>
					<option value='Pending'>Pending</option>
					<option value='Booked'>Booked</option>
					<option value='Complete'>Complete</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Date of Service</td>
			<td>
				<input name='dateOfService1' type='text' value='YYYY-MM-DD'/>
				<a href="javascript:showCal('dateOfService1_createJobProfile')">&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
			</td>
		</tr>
		<tr>
			<td>Time of Service</td>
			<td>
				<select name='timeOfServiceHour1'>
					<option value='00'>00</option>
					<option value='01'>01</option>
					<option value='02'>02</option>
					<option value='03'>03</option>
					<option value='04'>04</option>
					<option value='05'>05</option>
					<option value='06'>06</option>
					<option value='07'>07</option>
					<option value='08'>08</option>
					<option value='09'>09</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					<option value='13'>13</option>
					<option value='14'>14</option>
					<option value='15'>15</option>
					<option value='16'>16</option>
					<option value='17'>17</option>
					<option value='18'>18</option>
					<option value='19'>19</option>
					<option value='20'>20</option>
					<option value='21'>21</option>
					<option value='22'>22</option>
					<option value='23'>23</option>
				</select>
				<select name='timeOfServiceMinute1'>
					<option value='00'>00</option>
					<option value='01'>01</option>
					<option value='02'>02</option>
					<option value='03'>03</option>
					<option value='04'>04</option>
					<option value='05'>05</option>
					<option value='06'>06</option>
					<option value='07'>07</option>
					<option value='08'>08</option>
					<option value='09'>09</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					<option value='13'>13</option>
					<option value='14'>14</option>
					<option value='15'>15</option>
					<option value='16'>16</option>
					<option value='17'>17</option>
					<option value='18'>18</option>
					<option value='19'>19</option>
					<option value='20'>20</option>
					<option value='21'>21</option>
					<option value='22'>22</option>
					<option value='23'>23</option>
					<option value='24'>24</option>
					<option value='25'>25</option>
					<option value='26'>26</option>
					<option value='27'>27</option>
					<option value='28'>28</option>
					<option value='29'>29</option>
					<option value='30'>30</option>
					<option value='31'>31</option>
					<option value='32'>32</option>
					<option value='33'>33</option>
					<option value='34'>34</option>
					<option value='35'>35</option>
					<option value='36'>36</option>
					<option value='37'>37</option>
					<option value='38'>38</option>
					<option value='39'>39</option>
					<option value='40'>40</option>
					<option value='41'>41</option>
					<option value='42'>42</option>
					<option value='43'>43</option>
					<option value='44'>44</option>
					<option value='45'>45</option>
					<option value='46'>46</option>
					<option value='47'>47</option>
					<option value='48'>48</option>
					<option value='49'>49</option>
					<option value='50'>50</option>
					<option value='51'>51</option>
					<option value='52'>52</option>
					<option value='53'>53</option>
					<option value='54'>54</option>
					<option value='55'>55</option>
					<option value='56'>56</option>
					<option value='57'>57</option>
					<option value='58'>58</option>
					<option value='59'>59</option>
				</select>
				&nbsp;&nbsp;to&nbsp;&nbsp;
				<select name='timeOfServiceHour2'>
					<option value='00'>00</option>
					<option value='01'>01</option>
					<option value='02'>02</option>
					<option value='03'>03</option>
					<option value='04'>04</option>
					<option value='05'>05</option>
					<option value='06'>06</option>
					<option value='07'>07</option>
					<option value='08'>08</option>
					<option value='09'>09</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					<option value='13'>13</option>
					<option value='14'>14</option>
					<option value='15'>15</option>
					<option value='16'>16</option>
					<option value='17'>17</option>
					<option value='18'>18</option>
					<option value='19'>19</option>
					<option value='20'>20</option>
					<option value='21'>21</option>
					<option value='22'>22</option>
					<option value='23'>23</option>
				</select>
				<select name='timeOfServiceMinute2'>
					<option value='00'>00</option>
					<option value='01'>01</option>
					<option value='02'>02</option>
					<option value='03'>03</option>
					<option value='04'>04</option>
					<option value='05'>05</option>
					<option value='06'>06</option>
					<option value='07'>07</option>
					<option value='08'>08</option>
					<option value='09'>09</option>
					<option value='10'>10</option>
					<option value='11'>11</option>
					<option value='12'>12</option>
					<option value='13'>13</option>
					<option value='14'>14</option>
					<option value='15'>15</option>
					<option value='16'>16</option>
					<option value='17'>17</option>
					<option value='18'>18</option>
					<option value='19'>19</option>
					<option value='20'>20</option>
					<option value='21'>21</option>
					<option value='22'>22</option>
					<option value='23'>23</option>
					<option value='24'>24</option>
					<option value='25'>25</option>
					<option value='26'>26</option>
					<option value='27'>27</option>
					<option value='28'>28</option>
					<option value='29'>29</option>
					<option value='30'>30</option>
					<option value='31'>31</option>
					<option value='32'>32</option>
					<option value='33'>33</option>
					<option value='34'>34</option>
					<option value='35'>35</option>
					<option value='36'>36</option>
					<option value='37'>37</option>
					<option value='38'>38</option>
					<option value='39'>39</option>
					<option value='40'>40</option>
					<option value='41'>41</option>
					<option value='42'>42</option>
					<option value='43'>43</option>
					<option value='44'>44</option>
					<option value='45'>45</option>
					<option value='46'>46</option>
					<option value='47'>47</option>
					<option value='48'>48</option>
					<option value='49'>49</option>
					<option value='50'>50</option>
					<option value='51'>51</option>
					<option value='52'>52</option>
					<option value='53'>53</option>
					<option value='54'>54</option>
					<option value='55'>55</option>
					<option value='56'>56</option>
					<option value='57'>57</option>
					<option value='58'>58</option>
					<option value='59'>59</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Second Date</td>
			<td>
				<input name='dateOfService2' type='text' value='YYYY-MM-DD'/>
				<a href="javascript:showCal('dateOfService2_createJobProfile')">&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
			</td>
		</tr>
		<tr>
			<td>Description for second date</td>
			<td>
				<input name='descriptionForSecondDate' type='text'/>
			</td>
		</tr>
		<tr>
			<td>Deposit Date</td>
			<td><input name='depositDate' type='text' value='YYYY-MM-DD'/>
				<a href="javascript:showCal('depositDate_createJobProfile')">&nbsp;&nbsp;&nbsp;&nbsp;Select Date</a>
			</td>
		</tr>
		<tr>
			<td>How did you hear about us?</td>
			<td>
				<select name='hearAbtUs'>
					<option> -- </option>
					<option value='Craiglist'>Craiglist</option>
					<option value='Search Engine'>Search Engine</option>
					<option value='Apartment Complex'>Apartment Complex</option>
					<option value='Friend'>Friend</option>
					<option value='Facebook'>Facebook</option>
					<option value='Direct Mail'>Direct Mail</option>
					<option value='Chamber of Commerce'>Chamber of Commerce</option>
					<option value='Other'>Other</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>CCV #</td>
			<td>
				<input name='ccv' type='text'/>
			</td>
		</tr>
		<tr>
			<td>Assembly</td>
			<td><textarea name='assembly' rows='5' cols='50'></textarea></td>
		</tr>
		<tr>
			<td>Comments</td>
			<td><textarea name='comments' rows='5' cols='50'></textarea></td>
		</tr>
		<tr>
			<td colspan='2'><input type='checkbox' name='sendCustEmail'>&nbsp;Send email to customer with Written Estimate attached.</td>
		</tr>
		<tr>
			<td colspan='2'><input type='checkbox' name='sendMngrEmail'>&nbsp;Send email to manager with Service Receipt attached.</td>
		</tr>
		<tr>
			<td colspan='2' align='center'><input type='submit' value=' Create profile ' /></td>
		</tr>
		</form>
		</table>
	</td>
	</tr>
	</table>
</body>
</html>