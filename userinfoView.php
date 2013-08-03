<?php
	require('session.php');
	require('logo.php');
?>

<html>

<head>
	<title>
		.: View User Info :.
	</title>
	<link rel="stylesheet" href="homestyle.css">
	<script type="text/javascript" src="js/confirmation.js"></script>
</head>

<body>
	<?php
	
	echo "<table class='outertable' width='80%' align='center'>";
	echo "<tr>";
		echo "<td class='homestyle' width='0%' valign='top'>";
				require('leftbarlinks.php');
		echo "</td>";
		
		echo "<td width='80%' valign='top'>";
		
		//Start connection with database
		require("connect.php");
		
		//Display Owners	
		if ($_SESSION['_userType_'] == 'Owner' || $_SESSION['_userType_'] == 'Admin')
		{
			echo "<table width='100%' border='1px'>
					<tr>
						<td colspan='5'><b>ADMINISTRATORS</b></td>
					</tr>
					<tr>
						<td></td>
						<td><b>Name</b></td>
						<td><b>Branch</b></td>
						<td><b>Phone Number</b></td>
						<td><b>E-mail Address</b></td>
					</tr>";
			
			$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Owner' order by name";
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
				if ($_SESSION['_userId_'] == $row['userId'] || $_SESSION['_userType_'] == 'Admin')
				{
					echo"<td>
							<a href='userinfoEdit.php?userId=".$row['userId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='userinfoDelete.php?userId=".$row['userId']."' id='".$row['userId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}
				
				echo "<td>".$row['name']."</td>
					<td>".$row['city']."</td>
					<td>".$row['phoneNumber']."</td>
					<td>".$row['email']."</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<br />";
		}
			
		//Display Managers
		echo "<table width='100%' border='1px'>
			<tr>
				<td colspan='5'><b>MANAGERS</b></td>
			</tr>
			<tr>
				<td></td>
				<td><b>Name</b></td>
				<td><b>Branch</b></td>
				<td><b>Phone Number</b></td>
				<td><b>E-mail Address</b></td>
			</tr>";
		
			//if the user logged in is a mover then display managers from his region only
			if ($_SESSION['_userType_'] == 'Mover')
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Manager' and city='".$_SESSION['_city_']."'";
			}
			else if ($_SESSION['_userType_'] == 'Manager')
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Manager'";
			}
			else
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Manager' order by name";
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
				if ($_SESSION['_userType_'] == 'Owner' || $_SESSION['_userId_'] == $row['userId']  || $_SESSION['_userType_'] == 'Admin')
				{
					echo"<td>
							<a href='userinfoEdit.php?userId=".$row['userId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='userinfoDelete.php?userId=".$row['userId']."' id='".$row['userId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}
				
				echo "<td>".$row['name']."</td>
					<td>".$row['city']."</td>
					<td>".$row['phoneNumber']."</td>
					<td>".$row['email']."</td>";
				echo "</tr>";
			}
		echo "</table>";
		echo "<br />";
		
		//Display Movers
		echo "<table width='100%' border='1px'>
			<tr>
				<td colspan='5'><b>MOVERS</b></td>
			</tr>
			<tr>
				<td></td>
				<td><b>Name</b></td>
				<td><b>Branch</b></td>
				<td><b>Phone Number</b></td>
				<td><b>E-mail Address</b></td>
			</tr>";
		
			//if the user logged in is a manager then display only movers from his region
			if ($_SESSION['_userType_'] == 'Manager')
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Mover' and city='".$_SESSION['_city_']."'";
			}
			else if ($_SESSION['_userType_'] == 'Mover')
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Mover' and city='".$_SESSION['_city_']."'";
			}
			else
			{
				$query = "select userId, name, city, phoneNumber, email from userinfo where userType='Mover' order by name";
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
				if ($_SESSION['_userType_'] == 'Owner' || $_SESSION['_userType_'] == 'Manager' || $_SESSION['_userId_'] == $row['userId']  || $_SESSION['_userType_'] == 'Admin')
				{
					echo"<td>
							<a href='userinfoEdit.php?userId=".$row['userId']."'><img src='images/edit.png'></a>&nbsp;&nbsp;<a href='userinfoDelete.php?userId=".$row['userId']."' id='".$row['userId']."' onclick='return doConfirmDelete(this.id);'><img src='images/trash.jpg'></a>
						</td>";
				}
				else
				{
					echo "<td></td>";
				}
				
				echo "<td>".$row['name']."</td>
					<td>".$row['city']."</td>
					<td>".$row['phoneNumber']."</td>
					<td>".$row['email']."</td>";
				echo "</tr>";
				
			}
		echo "</table>";
		echo "<br />";
		
		echo "</td>";
	echo "</tr>";
	echo "</table>";
	
	?>
</body>
</html>

