<?php
	//Start connection
	require("connect.php");
	
	//USER INFO 
	$query = "create table if not exists userinfo (userId int primary key, username varchar(32), password varchar(32), userinfoDate date, name varchar(32), 
													city varchar(16), phoneNumber varchar(16), email varchar(32), userType varchar(8), payPer float)";
	
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	$query = "insert into userinfo values (1, 'movebro', 'movebro', '2012-01-01', 'MoveBro', 'Gainesville', '000-000-0000', 'email@email.com', 'Owner', 0)";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//JOBS
	$query = "create table if not exists jobs(jobId bigint primary key, branch varchar(16), custName varchar(32), custPhoneNumber varchar(16), 
												custEmail varchar(32), custAdd11 varchar(64), custAdd12 varchar(64), custCity1 varchar(16), 
												custState1 varchar(2), custZip1 int, locationType1 varchar(16), floor1 varchar(8), custAdd21 varchar(64), 
												custAdd22 varchar(64), custCity2 varchar(16), custState2 varchar(2), custZip2 int, locationType2 varchar(16), 
												floor2 varchar(8), typeOfMove varchar(16), numOfMovrs int, hourlyRate int, minHours float, description varchar(16), 
												truckSize varchar(16), materialsCost float, travelFee float, statusOfMove varchar(8), dateOfService1 date, 
												timeOfServiceHour1 int, timeOfServiceMinute1 int, timeOfService1 time, 
												timeOfServiceHour2 int, timeOfServiceMinute2 int, timeOfService2 time, 
											 	dateOfService2 date, descriptionForSecondDate varchar(32), depositDate date, hearAbtUs varchar(32), 
												ccv varchar(64), assembly varchar(128), comments varchar(128), 
												startTimeHour1 int, startTimeMinute1 int, startTime1 time, 
												endTimeHour1 int, endTimeMinute1 int, endTime1 time, 
												startTimeHour2 int, startTimeMinute2 int, startTime2 time, 
												endTimeHour2 int, endTimeMinute2 int, endTime2 time, 
												startTimeHour3 int, startTimeMinute3 int, startTime3 time, 
												endTimeHour3 int, endTimeMinute3 int, endTime3 time, gasExpenses float, truckExpenses float, credit float)"; 
	//jobId: yyyymmddhhmmss
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//JOB ASSIGN 
	$query = "create table if not exists jobassign (jobId bigint, movrId int, primary key (jobId,movrId), foreign key (jobId) references jobs(jobId), 
													foreign key (movrId) references userinfo (userId))";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//EXPENSES
	$query = "create table if not exists expenses (jobId bigint, totalPrice float, labor float,	transactionFee float, staffingFee float, managerProfit float, 
													netProfit float, bank float, individual float, primary key (jobId), foreign key (jobId) references jobs(jobId))";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//FILES UPLOAD
	//$query = "create table if not exists files(fileId varchar(32) primary key, filePath varchar(128), userId int, jobId bigint, 
												//foreign key (userId) references userinfo (userId), foreign key (jobId) references jobs(jobId))";
	$query = "create table if not exists files(whichExists int, userId int, jobId bigint, filePath varchar(128), comments varchar(256),
												foreign key (userId) references userinfo (userId), foreign key (jobId) references jobs(jobId))";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	//PERCENTAGES
	$query = "create table if not exists percentages (transactionFeePer float, staffingFeePer float, gainesvilleManagerPer float, tallahasseeManagerPer float, 
													orlandoManagerPer float, miamiManagerPer float, bankPer float)";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	$query = "insert into percentages values (10, 5, 10, 10, 10, 10, 3)";
	$result = mysql_query($query,$con);	
	if(!$result)
	{
		die("Invalid query! <br> The query is: " . $query);
	}
	
	// Close connection to database
	mysql_close($con);

	echo "Database created successfully!";	
?>