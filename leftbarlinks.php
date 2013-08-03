<?php
	
	echo
		"<table>
			<tr><td>".ucFirst($_SESSION['loginUsername'])."<br />".$_SESSION['_userType_'].",<br />".$_SESSION['_city_']."</td></tr>
			<tr><td><a href='home.php'>Home</a></td></tr>
			<tr><td><a href='userinfoView.php'>View&nbsp;User&nbsp;Info</a></td></tr>";
			if ($_SESSION['_userType_'] != 'Mover')
			{
				echo "<tr><td><a href='signup.php'>Add&nbsp;New&nbsp;User</a></td></tr>";
			}
			if ($_SESSION['_userType_'] != 'Mover')
			{
				echo "<tr><td><a href='jobs.php'>Add&nbsp;New&nbsp;Job</a></td></tr>";
			}
			if ($_SESSION['_userType_'] == 'Admin' || $_SESSION['_userType_'] == 'Owner' || $_SESSION['_userType_'] == 'Manager')
			{
				echo "<tr><td><a href ='expenses.php'>Expenses</a></td></tr>";
				echo "<tr><td><a href ='payPeriod.php'>Pay Period</a></td></tr>";
			}
			if ($_SESSION['_userType_'] == 'Admin' || $_SESSION['_userType_'] == 'Owner')
			{
				echo "<tr><td><a href ='percentages.php'>Edit&nbsp;Percentages</a></td></tr>";
			}
			echo "<tr><td><a href ='logout.php'>Logout</a></td></tr>
		</table>";
?>