
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>ODCH - Client Setup</title>
</head>
<body>
<?

include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - client settings $hubname</center></h3><br><br>";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

if ($function == delclient){
	$sql = "DELETE FROM client_rules WHERE rowID=$id";
	$result = mysql_query($sql) or die(mysql_error());}
if ($function == addclient){
	$sql = "INSERT INTO client_rules VALUES ('','$client','$min_version','$allowed',
		'$min_slots','$max_slots','$slot_ratio','$max_hubs','$min_share','$min_connection','$client_name')";
	$result = mysql_query($sql) or die(mysql_error());}
if ($function == updateclient){
	$sql = "UPDATE client_rules SET client='$ud_client', min_version='$ud_min_version',
		allowed='$ud_allowed', min_slots='$ud_min_slots', max_slots='$ud_max_slots',
		slot_ratio='$ud_slot_ratio',max_hubs='$ud_max_hubs', min_share='$ud_min_share',
		min_connection='$ud_min_connection',client_name='$ud_client_name'
		WHERE rowID='$ud_rowID'";
	$result = mysql_query($sql) or die(mysql_error());}

// Show the current client rules
$query="SELECT * FROM client_rules";
$result=mysql_query($query);
$num=mysql_numrows($result);

echo "$font<h3><center>ODCH Admin Control Center $hubname</h3></center>$fontend";
echo "$font<p><b>Current Per Client Rules</b><br>$fontend";
?>

<table border="1" cellspacing="2" cellpadding="2"><tr>
		<th><? echo "$font"; ?>Client<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Full Client Name<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Min Version<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Allowed<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Min Slots<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Max Slots<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Slot Ratio<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Max Hubs<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Min Share<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Min Connection<? echo "$fontend"; ?></th>
		<th><form action="client-add.php" method="post">
		<input type="Submit" value="Add"></form></th></tr><?
$i=0;
while ($i < $num) {
	$id=mysql_result($result,$i,"rowID");
	$client=mysql_result($result,$i,"client");
	$client_name=mysql_result($result,$i,"client_name");
	$min_version=mysql_result($result,$i,"min_version");
	$allowed=mysql_result($result,$i,"allowed");
	$min_slots=mysql_result($result,$i,"min_slots");
	$max_slots=mysql_result($result,$i,"max_slots");
	$slot_ratio=mysql_result($result,$i,"slot_ratio");
	$max_hubs=mysql_result($result,$i,"max_hubs");
	$min_share=mysql_result($result,$i,"min_share");
	$min_connection=mysql_result($result,$i,"min_connection");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	// Convert the min_connection to text
	$connectarray = array(9);
	$connectarray[0] = "Not Specifed";
	$connectarray[1] = "28.8k";
	$connectarray[2] = "33.6k";
	$connectarray[3] = "56k";
	$connectarray[4] = "Sat";
	$connectarray[5] = "ISDN";
	$connectarray[6] = "DSL";
	$connectarray[7] = "Cable";
	$connectarray[8] = "T1";
	$connectarray[9] = "T3";?>

	<td nowrap><? echo "$font$client$fontend"; ?></td>
	<td nowrap><? echo "$font$client_name$fontend"; ?></td>
	<td nowrap><? echo "$font$min_version$fontend"; ?></td>
	<td nowrap><? echo "$font$allowed$fontend"; ?></td>
	<td nowrap><? echo "$font$min_slots$fontend"; ?></td>
	<td nowrap><? echo "$font$max_slots$fontend"; ?></td>
	<td nowrap><? echo "$font$slot_ratio$fontend"; ?></td>
	<td nowrap><? echo "$font$max_hubs$fontend"; ?></td>
	<td nowrap><? echo "$font$min_share$fontend"; ?> Gb</td>
	<td nowrap><? echo "$font$connectarray[$min_connection]$fontend"; ?></font></td>
	<form action="client-edit.php?id=<? echo "$id";?>" method="post">
	<td nowrap><input type="Submit" value="Edit"></td></form>
	<form action="client-main.php?function=delclient&id=<? echo "$id";?>" method="post">
	<td nowrap><input type="Submit" value="Delete" onClick="return confirmDelete()"></td></form></tr>
	<?++$i;} ?></table>
<? echo "$fontend";?>
</body>
</html>
