<?php
	require('fpdf/fpdf.php');
	require('mail/sendMail.php');

	if (!isset($_SESSION['jobId']))
	{
		header('Location:home.php');
	}

	require('connect.php');

	$todayDate = date("M j, Y");	
	$query = "select jobId, branch, custName, custPhoneNumber, custEmail, custAdd11, custAdd12, custCity1, custState1, custZip1, locationType1, floor1,
						custAdd21, custAdd22, custCity2, custState2, custZip2, locationType2, floor2, typeOfMove, numOfMovrs, hourlyRate, minHours, description, 
						truckSize, materialsCost, travelFee, credit, statusOfMove, dateOfService1, timeOfServiceHour1, timeOfServiceMinute1, timeOfService1, 
						timeOfServiceHour2, timeOfServiceMinute2, timeOfService2, dateOfService2, descriptionForSecondDate, depositDate, hearAbtUs, ccv, 
						assembly, comments, startTimeHour1, startTimeMinute1, endTimeHour1, endTimeMinute1, 
						startTimeHour2, startTimeMinute2, endTimeHour2, endTimeMinute2, 
						startTimeHour3, startTimeMinute3, endTimeHour3, endTimeMinute3, 
						gasExpenses, truckExpenses from jobs where jobId=".$_SESSION['jobId'];
	//execute the query
	$result = mysql_query($query,$con);
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}

	if (!($row = mysql_fetch_assoc($result)))
	{
		die("Cannot generate pdf. No customer details found in database for jobId ".$_SESSION['jobId']);
	}
	
	//Close connection
	mysql_close($con);
	
	class PDF extends FPDF
	{
		// Page header
		function Header()
		{
			// Logo
			$this->Image('images/logo3.png',100,5,100);
		}

		// Page footer
		function Footer()
		{
			$this->SetY(-35);
			
			$this->SetFont('Arial','B',9);
			$this->MultiCell(170,5,'2 College Brothers, Inc. Moving Company',0,'C');
			$this->MultiCell(170,5,'Licensed & Insured: IM # 1934. MR # 1084. ',0,'C');
			$this->SetFont('Arial','',9);
			$this->MultiCell(170,5,'18301 North Miami Ave. Miami, FL 33169',0,'C');
			$this->MultiCell(170,5,'office@2CollegeBrothers.com | 1-855-MOVE-BRO',0,'C');
			$this->SetFont('Arial','B',9);
			$this->MultiCell(170,5,'www.2CollegeBrothers.com',0,'C');
		}
		
		// Simple table
	}

	$pdf = new PDF();
	$pdf->AddPage();
	$pdf->SetMargins(20,20,20);

	$pdf->SetFont('Arial','B',10);
	$pdf->Ln(10);
	$pdf->MultiCell(90,5,'Client Name: '.$row['custName'],0,'L');
	$pdf->MultiCell(90,5,'Phone Number: '.$row['custPhoneNumber'],0,'L');
	$pdf->MultiCell(90,5,'E-mail: '.$row['custEmail'],0,'L');
	
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',14);
	$pdf->MultiCell(170,7,'2 College Brothers, Inc.',0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(170,7,'Written Estimate',0,'C');
	
	$pdf->Ln(12);
	$pdf->SetFont('Arial','',11);
	$pdf->MultiCell(170,6,'The total cost of service for '.$row['custName'].' will be $'.$row['hourlyRate'].' per hour with a '.$row['minHours'].' hour minimum, rounded up to the nearest quarter hour (15th minute), from when the movers begin working to when the movers finish. There is a fixed travel fee of $'.$row['travelFee'].' and material cost of $'.$row['materialsCost'].'.',0);
	
	$pdf->Ln(12);
	$pdf->SetFont('Arial','U',11);
	$pdf->Write(5,'Requested Service:',0);
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5,'  '.$row['typeOfMove'].' on '.$row['dateOfService1'].' by '.$row['numOfMovrs'].' movers',0);
	$pdf->Ln();
	$pdf->Write(5,'                                 Estimated time of arrival is between '.$row['timeOfService1'].' hours and '.$row['timeOfService2'].' hours.',0);
	
	$pdf->Ln(15);
	$pdf->SetFont('Arial','U',11);
	$pdf->Write(5, 'Terms of payment:');
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5, '  A deposit was taken to book your move. Remainder of balance is due on the day of service. Refunds are not given on deposits for cancellations within 72 hours of the date of service.');
			
	$pdf->Ln(15);
	$pdf->SetFont('Arial','B',11);
	$pdf->Write(5, 'Location #1:  ');
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5, ' '.$row['custAdd11'].', '.$row['custAdd12'].', '.$row['custCity1'].', '.$row['custState1'].' '.$row['custZip1'].'. ');
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);
	$pdf->Write(5, 'Location #2:  ');
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5, ' '.$row['custAdd21'].', '.$row['custAdd22'].', '.$row['custCity2'].', '.$row['custState2'].' '.$row['custZip2'].'. ');
	
	$pdf->Ln(15);
	$pdf->SetFont('Arial','B',11);
	$pdf->Write(5,'2 College Brothers, Inc.',0);
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5,' is registered with the State of Florida as a Mover. Registration No.1934.',0);
	
	$pdf->Ln(10);
	$pdf->MultiCell(170,5,'This written estimate was prepared on '.$todayDate.'.',0);
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','I',10);
	$pdf->MultiCell(170,5,'If there are any changes including date, time, locations, etc., please contact office@2CollegeBrothers.com.',0);
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);
	$pdf->Write(5,'Kindly sign, date, and return. ',0);
	$pdf->SetFont('Arial','',11);
	$pdf->Write(5,'Scan & email (office@2CollegeBrothers.com) or fax (1-800-795-4513).');
	
	$pdf->Ln(25);	
	$pdf->MultiCell(170,5,'Client Print Name _______________________',0);

	$pdf->Ln(10);	
	$pdf->MultiCell(170,5,'Client Signature ____________________________   Date ____________________________',0);
	
	$outputFileName = "writtenEstimates/writtenEstimate_".$_SESSION['jobId'].".pdf";
	$pdf->Output($outputFileName,'F');
	
	// array with filenames to be sent as attachment
	$files = array($outputFileName);
	 
	// email fields: to, from, subject, and so on
	$to = $row['custEmail'];
	//$to = "sharvarikapadia@gmail.com";
	$from = "office@2CollegeBrothers.com"; 
	$subject ="Confirmation of Move | 2 College Brothers, Inc."; 
	$message = $row['custName'].",
 
We really appreciate you choosing 2 College Brothers, Inc. for your moving needs. Our mission is to bring value to your moving experience.
 
Attached you will find the written estimate. Kindly sign, date, and email (office@2CollegeBrothers.com) or fax back (1-800-795-4513) as soon as possible.
 
If you have any questions feel free to contact us at your convenience. Also, please help us reach our goal of 1,000 likes on our facebook page www.facebook.com/2collegebros

 
Have a great day!

--
2 College Brothers, Inc.
1-855-MOVE-BRO (668-3276)
www.2CollegeBrothers.com
office@2CollegeBrothers.com";
	
	$result = mail_attachment($files, $to, $from, $subject, $message);
	
	if(!$result)
	{
		die('Failed to send email to '.$to);
	}
?>