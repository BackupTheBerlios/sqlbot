 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Add a Nick Filter</title>
</head>
<body>

<?
include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Add New Rule for $hubname</center></h3><br><br>";
?>
<center>
	<table>
	<tr><form action="rules-main.php?function=add" method="post">
		<td>New Rule</td>
		<td><input type="Text" name="rule"></td>
		<td></td>
	<td><input type="Submit" value="Add New Rule"></td>
	</form>
	</tr>
	</table>
</center>
</body>
</html> 
