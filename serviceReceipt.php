<?php

	if (false == $writtenEstimateSent)
	{
		require('fpdf/fpdf.php');
		require('mail/sendMail.php');
	}

	if (!isset($_SESSION['jobId']))
	{
		header('Location:home.php');
	}

	require('connect.php');
			
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
	$query = "select custName, custPhoneNumber, custEmail, numOfMovrs from jobs where jobId=".$_SESSION['jobId'];
	
	if (!($row = mysql_fetch_assoc($result)))
	{
		die("Cannot generate pdf. No customer details found in database for jobId ".$_SESSION['jobId']);
	}
	
	$query = "select email from userinfo where userType='Manager' and city='".$row['branch']."'";
	//execute the query
	$result = mysql_query($query,$con);
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}

	if (!($row1 = mysql_fetch_assoc($result)))
	{
		die("Cannot generate pdf. Manager email details not found in database for jobId ".$_SESSION['jobId']);
	}
	
	//Close connection
	mysql_close($con);

	class PDF2 extends FPDF
	{
		function BasicTable1($header, $data)
		{
			$w = array(35, 51, 35, 51);
			
			// Header
			for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],1,$header[$i],0);
			$this->Ln();
		
			//Data
			foreach($data as $row)
			{
				$this->SetFont('Arial','B',11);
				$this->Cell($w[0],6,$row[0],0);
				$this->SetFont('Arial','U',11);
				$this->Cell($w[1],6,$row[1],0);
				$this->SetFont('Arial','B',11);
				$this->Cell($w[2],6,$row[2],0);
				$this->SetFont('Arial','U',11);
				$this->Cell($w[3],6,$row[3],0);
				$this->Ln();
			}
			
			$this->SetFont('Arial','',11);
			
			// Closing line
			$this->Cell(array_sum($w),0);
		}
		
		function BasicTable2($header, $data)
		{
			$w = array(25, 60, 35, 55);
			
			// Header
			for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],1,$header[$i],0);
			$this->Ln();
		
			//Data
			foreach($data as $row)
			{
				$this->SetFont('Arial','B',11);
				$this->Cell($w[0],6,$row[0],0);
				$this->SetFont('Arial','U',11);
				$this->Cell($w[1],6,$row[1],0);
				$this->SetFont('Arial','B',11);
				$this->Cell($w[2],6,$row[2],0);
				$this->SetFont('Arial','U',11);
				$this->Cell($w[3],6,$row[3],0);
				$this->Ln();
			}
			
			$this->SetFont('Arial','',11);
			
			// Closing line
			$this->Cell(array_sum($w),0);
		}
		
	}

	$pdf = new PDF2();
	$pdf->AddPage();
	$pdf->SetMargins(20,20,20);
		
	$pdf->Ln(15);
	$pdf->SetFont('Arial','B',16);						
	$pdf->MultiCell(170,10,'2 College Brothers, Inc.',0,'C');									
	
	$pdf->SetFont('Arial','I',14);
	$pdf->MultiCell(170,10,'Service Receipt',0,'C');									
	
	$header = array(' ', ' ', ' ', ' ');
	$data = array(
				array('Client Name:', $row['custName'], 'Date of Service:', $row['dateOfService1']),
				array('Phone', $row['custPhoneNumber'], 'Scheduled Time', $row['timeOfService1'].' to '.$row['timeOfService2'].' hours'),
				array('Type of Move: ', $row['typeOfMove'], 'Truck Size: ', $row['truckSize']),
				array('Description: ', $row['description'], '', ''),
				array('Assembly: ', $row['assembly'], '', ''),
				array('Location #1: ', $row['custAdd11'].',', 'Location #2: ', $row['custAdd21']),
				array(' ', $row['custAdd12'].',', '', $row['custAdd22']),
				array(' ', $row['custCity1'].', '.$row['custState1'].' '.$row['custZip1'], ' ', $row['custCity2'].', '.$row['custState2'].' '.$row['custZip2']),
				array(' ', $row['locationType1'], ' ', $row['locationType2']),
				array('', '', '', ''),
				array('Start Time: ', '                      ', 'Client Signature: ', '                      '),
				array('End Time:	', '                      ', 'Client Signature: ', '                      ')
			);
			
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',11);
	$pdf->BasicTable1($header,$data);
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(170,6,'There is no better thank-you a client can give us than a positive, descriptive testimonial.');		
	$pdf->MultiCell(170,6,'Please feel free to describe your experience with the movers, office staff, or any other related comments.');					
	
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',11);								
	$pdf->MultiCell(170,6,'Testimonial:');
	
	$pdf->Rect(20,154,170,72);
	
	$pdf->Ln(76);
	$pdf->SetFont('Arial','',10);								
	$pdf->MultiCell(170,6,'By writing in the box above you authorize us to publish your testimonial on our website.');
	
	$pdf->Ln(7);
	$pdf->SetFont('Arial','U',10);								
	$pdf->Write(6,'NOTE:');
	$pdf->SetFont('Arial','',10);								
	$pdf->Write(6,' Complete this section only if 2 or more trips are required.');
	
	$header = array(' ', ' ');
	$data = array(
				array('Start Time:', '                 ', 'Client Signature:   ', '                 '),
				array('End Time:', '                 ', 'Client Signature:   ', '                 '),
				array('Start Time:', '                 ', 'Client Signature:   ', '                 '),
				array('End Time:', '                 ', 'Client Signature:   ', '                 '),
			);
			
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',11);
	$pdf->BasicTable2($header,$data);
	
	
	$outputFileName = "serviceReceipts/serviceReceipt_".$_SESSION['jobId'].".pdf";
	$pdf->Output($outputFileName,'F');
		
	// array with filenames to be sent as attachment
	$files = array($outputFileName);
	 
	// email fields: to, from, subject, and so on
	$to = $row1['email'];
	//$to = "sharvarikapadia@gmail.com";
	$from = "office@2collegebrothers.com"; 
	$subject ="Service Receipt"; 
	$message = "Service Receipt is attached in the e-mail.";
	
	$result = mail_attachment($files, $to, $from, $subject, $message);
	
	if(!$result)
	{
		die('Failed to send email to '.$to);
	}
?>