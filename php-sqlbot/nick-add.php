<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Add a Nick Filter</title>
</head>
<body>

<?
include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Add New Nick Filter for $hubname</center></h3><br><br>";
?>
<center><form action="nick-main.php?function=add" method="post">
	<table>
	
	<tr>
		<td>Nick or Partial Nick</td>
		<td><input type="Text" name="nick"></td>
		<td></td>
	</tr><tr>
		<td>IP Range Start</td>
		<td><input type="Text" name="ip_start"></td>
		<td></td>
	</tr><tr>
		<td>IP Range End</td>
		<td><input type="Text" name="ip_end"></td>
		<td></td>
	</tr><tr>
		<td>Imdeitate ban</td>
		<td><input type="radio" name="action" value="ban" checked="true">Ban<br></td>
		<td></td>
	</tr><tr>
		<td>Allow</td>
		<td><input type="radio" name="action" value="allow">Allow<br></td>
		<td></td>
	</tr><tr>
		<td>Log into user stats</td>
		<td><input type="checkbox" name="stats_log" ></td>
		<td></td>
	</tr><tr>
		<td>Add to the Hub Log</td>
		<td><input type="checkbox" name="hub_log" ></td>
		<td></td>
	</tr></table>
	<hr>
<input type="Submit" value="Add Nick to Filter">
</form>
</center>
</body>
</html> 
