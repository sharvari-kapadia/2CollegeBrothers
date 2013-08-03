<?php
	require('session.php');
	
	if (isset($_POST['name']))
	{
		//Start connection
		require("connect.php");
		
		$query = "select max(userId) as oldUserId from userinfo";
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		//Condition for generating unique id
		$row = mysql_fetch_assoc($result);
		if ($row['oldUserId'])
		{
			//For values other than first value
			$newUserId = $row['oldUserId'] + 1;
		}
		else
		{
			//For first value
			$newUserId = 1;
		}
		
		// check if the username is already taken
		$query = "select * from userinfo where username = '".$_POST['username']."'";
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		if(mysql_num_rows($result) == 1)
		{
			die("Username ".$_POST['username']." already taken. Please use another!");
		}
		
		//**************************************************
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName1']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName1']['error']);
		}
		else
		{
			// check if the filename1 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName1']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName1']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName2']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName2']['error']);
		}
		else
		{
			// check if the fileName2 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName2']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName2']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName3']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName3']['error']);
		}
		else
		{
			// check if the fileName3 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName3']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName3']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName4']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName4']['error']);
		}
		else
		{
			// check if the fileName4 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName4']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName4']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName5']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName5']['error']);
		}
		else
		{
			// check if the fileName5 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName5']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName5']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		
		// check if the file was uploaded, if yes store
		// the file on the server
		if ($_FILES['fileName6']['error'] > 0)
		{
			//die("Failed to upload file! error: ".$_FILES['fileName6']['error']);
		}
		else
		{
			// check if the filename6 already exists
			// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
			$upFileName = "upload/".$newUserId."_".$_FILES['fileName6']['name'];
			if (file_exists($upFileName))
			{
				die("File: ".$upFileName." already exists!");
			}
			else
			{
				move_uploaded_file($_FILES['fileName6']['tmp_name'], $upFileName);
			}

			// as the file was uploaded successfully, store the path in the database
			$query = "insert into files values(-1, ".$newUserId.", 0, '".$upFileName."', '".$_POST['fileComments']."')";
			$result = mysql_query($query,$con);	
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
		}
		//**************************************************
		
		$dateToday = date("Y-m-d");
		//input id, name, city, state, phoneNumer,email into movr table
		$_POST['payPer'] = ('' == $_POST['payPer'] ? 0 : $_POST['payPer']);
		$query = "insert into userinfo values(".$newUserId.",'".$_POST['username']."','".$_POST['pass']."','".$dateToday."','".$_POST['name']."',
											'".$_POST['city']."','".$_POST['phoneNumber']."','".$_POST['email']."','".$_POST['userType']."',".$_POST['payPer']." )";
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		
		//Close connection
		mysql_close($con);
		
		header('Location:userinfoView.php');
	}
	require('logo.php');
?>

<html>

<head>
	<title>
		.: New User profile :.
	</title>
	<link rel="stylesheet" href="homestyle.css">
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
		<form name='createUserProfile' method='post' action='signup.php' enctype='multipart/form-data'>
		<tr>
			<td colspan='2' align='center'><h3>Create New User profile</h3></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><input name='username' type='text'/></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input name='pass' type='password'/></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><input name='name' type='text'/></td>
		</tr>
		<tr>
			<td>Branch</td>
			<td>
				<select name='city'>
					<option value='Gainesville'>Gainesville</option>
					<option value='Tallahassee'>Tallahassee</option>
					<option value='Orlando'>Orlando</option>
					<option value='Miami'>Miami</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Phone Number</td>
			<td><input name='phoneNumber' type='text'/></td>
		</tr>
		<tr>
			<td>E-mail Address</td>
			<td><input name='email' type='text'/></td>
		</tr>
		<tr>
			<td>Profile type</td>
			<td>
				<select name='userType'>
					<option value='Mover'>Mover</option>
					<?php
					if ($_SESSION['_userType_'] != 'Manager')
					{
					echo "
						<option value='Manager'>Manager</option>
						<option value='Owner'>Owner</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Pay Per Hour/Percentage Share</td>
			<td><input name='payPer' type='text'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName1' id='fileName1' type='file'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName2' id='fileName2' type='file'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName3' id='fileName3' type='file'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName4' id='fileName4' type='file'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName5' id='fileName5' type='file'/></td>
		</tr>
		<tr>
			<td>File to upload</td>
			<td><input name='fileName6' id='fileName6' type='file'/></td>
		</tr>
		<tr>
			<td>Comments for file</td>
			<td><input name='fileComments' type='text'/></td>
		</tr>
		<tr>
			<td colspan='2'><center><input type='submit' value='Create Profile'/></center></td>
		</tr>
		</form>
		</table>
	</td>
	</tr>
	</table>
</body>
</html>