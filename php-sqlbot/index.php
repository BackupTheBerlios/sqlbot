<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>ODCH Admin Control Center</title>
</head>
<body>
<?

include("dbinfo.inc.php");?>

<table><b>Configurations</b>
<tr>
	<td nowrap>
	<form action="client-main.php" method="post">
	<input type="Submit" value="Clients"></form>
	</td>
	<td nowrap>
	<form action="config-main.php" method="post">
	<input type="Submit" value="Config Main"></form>
	</td>
	<td nowrap>
	<form action="config-vars.php" method="post">
	<input type="Submit" value="Config Vars"></form>
	</td>
	<td nowrap>
	<form action="nick-main.php" method="post">
	<input type="Submit" value="Nick Filtering"></form>
	<td nowrap>
	<form action="rules-main.php" method="post">
	<input type="Submit" value="Hub Rules"></form>
	</td>
	<td nowrap>
	<form action="stats-records.php" method="post">
	<input type="Submit" value="Hub Records"></form>
	</td>
	<td nowrap>
	<form action="stats-user.php" method="post">
	<input type="Submit" value="User Stats"></form>
	</td>
	<td nowrap>
	<form action="log-hub.php" method="post">
	<input type="Submit" value="Hub Log"></form>
	</td>
	<td nowrap>
	<form action="log-fakers.php" method="post">
	<input type="Submit" value="Fakers Log"></form>
	</td>
	<td nowrap>
	<form action="log-online.php" method="post">
	<input type="Submit" value="Online"></form>
	</td>
</tr></table>
</p>
<p><a href="/index.php">Exit Admin (Return to site Root)</a></p>
<? echo "$fontend";?>
</body>
</html>

