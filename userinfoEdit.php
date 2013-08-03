<?php
	require('session.php');
	
	if (isset($_GET['userId']))
	{
		$_SESSION['userId'] = $_GET['userId'];
	}
	
	else if (isset($_POST['name']))
	{			
		//Start connection with database
		require("connect.php");
		
		//**************************************************
		// check if the file was uploaded, if yes store
		// the file on the server, else just skip the part
		for ($ii = 1; $ii <=6; $ii++)
		{
			$htmlFileId = "fileName".$ii;
			if ($_FILES[$htmlFileId]['error'] <= 0)
			{
				// check if the filename already exists
				// the new file name is like this: <nameGivenByUser>_<userIdforWhichFileWasUploaded>
				$upFileName = "upload/".$_SESSION['userId']."_".$_FILES[$htmlFileId]['name'];
				if (file_exists($upFileName))
				{
					die("File: ".$upFileName." already exists!");
				}
				else
				{
					move_uploaded_file($_FILES[$htmlFileId]['tmp_name'], $upFileName);
				}

				// as the file was uploaded successfully, store the path in the database
				$query = "insert into files values(-1, ".$_SESSION['userId'].", 0, '".$upFileName."', '".$_POST['fileComments']."')";
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
		
		$_POST['payPer'] = ('' == $_POST['payPer'] ? 0 : $_POST['payPer']);
		$query = "update userinfo set name='".$_POST['name']."', username='".$_POST['username']."', password='".$_POST['password']."', city='".$_POST['city']."', phoneNumber='".$_POST['phoneNumber']."', 
											email='".$_POST['email']."', userType='".$_POST['userType']."', payPer=".$_POST['payPer']." 
											where userId=".$_SESSION['userId'];
				
		$result = mysql_query($query,$con);	
		if(!$result)
		{
			die("Invalid query! <br> The query is: " . $query);
		}
		$msg="Saved!";
		
		//Close connection
		mysql_close($con);
		unset($_SESSION['userId']);
		
		header("Location:userinfoView.php");
	}
	
	else
	{
		header("Location:userinfoView.php");
	}
	require('logo.php');
	
?>

<html>

<head>
	<title>
		.: Edit User profile :.
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
		<table width='100%'>
		
		<?php
			//Start connection with database
			require("connect.php");
					
			$query = "select userId, username, password, name, city, phoneNumber, email, userType, payPer from userinfo where userId=".$_SESSION['userId'];
			//execute the query
			$result = mysql_query($query,$con);
			if(!$result)
			{
				die("Invalid query! <br> The query is: " . $query);
			}
	
			if ($row = mysql_fetch_assoc($result))
			{
				echo "<form name='".$_SESSION['userId']."' method='post' action='userinfoEdit.php' enctype='multipart/form-data'>";
				echo "<tr>
						<td>Name</td>
						<td><input type='text' name='name' value='".$row['name']."' /></td>
					</tr>";
				if ($_SESSION['_userId_'] == $_SESSION['userId'] || $_SESSION['_userType_'] == 'Owner' || $_SESSION['_userType_'] == 'Admin')
				{
					echo "
					<tr>
						<td>Username</td>
						<td><input type='text' name='username' value='".$row['username']."' /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type='password' name='password' value='".$row['password']."' /></td>
					</tr>";
				}
				
				echo"
					<tr>
						<td>Branch</td>
						<td>
							<select name='city'>
								<option value='Tallahassee'"; echo ($row['city']=='Tallahassee' ? 'selected' : ''); echo ">Tallahassee</option>
								<option value='Gainesville'"; echo ($row['city']=='Gainesville' ? 'selected' : ''); echo ">Gainesville</option>
								<option value='Orlando'"; echo ($row['city']=='Orlando' ? 'selected' : ''); echo ">Orlando</option>
								<option value='Miami'"; echo ($row['city']=='Miami' ? 'selected' : ''); echo ">Miami</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Phone Number</td>
						<td><input type='text' name='phoneNumber' value='".$row['phoneNumber']."' /></td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td><input type='text' name='email' value='".$row['email']."' /></td>
					</tr>
					<tr>
						<td>Profile Type</td>
						<td>
							<select name='userType'>
								<option value='Owner'"; echo ($row['userType']=='Owner' ? 'selected' : ''); echo ">Owner</option>
								<option value='Manager'"; echo ($row['userType']=='Manager' ? 'selected' : ''); echo ">Manager</option>
								<option value='Mover'"; echo ($row['userType']=='Mover' ? 'selected' : ''); echo ">Mover</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Pay Per Hour/Percentage Share</td>
						<td><input type='text' name='payPer' value='".$row['payPer']."' /></td>
					</tr>";
					
				//display file
				$subQuery = "select * from files where whichExists = -1 and userId=".$_SESSION['userId'];
				$subResult = mysql_query($subQuery,$con);
				if(!$subResult)
				{
					die("Invalid query! <br> The query is: " . $subQuery);
				}

				while ($subRow = mysql_fetch_assoc($subResult))
				{
					echo "<tr>
						<td>Uploaded File</td>";
						$orgfileName = strtok($subRow['filePath'], "_");
						$orgfileName = strtok("_");
						echo "<td><a href='".$subRow['filePath']."'>".$orgfileName."</a>&nbsp;&nbsp;
						<a href='deleteSingleFile.php?filePath=".$subRow['filePath']."&userId=".$_SESSION['userId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a></td>
					</tr>";
				}
				for ($ii = 1; $ii <=6; $ii++)
				{
					echo "<tr>
						<td>
						File to upload
						</td>
						<td><input name='fileName".$ii."' id='fileName".$ii."' type='file'/></td>
					</tr>";
				}
				echo "<tr><td colspan='2'><input type='submit' value='Save' /></td></tr>";
				echo "</tr>";
				echo "<form>";
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

