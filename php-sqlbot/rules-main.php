<?
$page_title="Add / Edit Clients";
include("header.ini");
?>
<div align="center">

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

if($function == update) {
	$sql = "UPDATE hub_rules SET rule='$ud_rule' WHERE rowID='$ud_id'";
	$result = mysql_query($sql) or die(mysql_error());}
if($function == add) {
	$sql = "INSERT INTO hub_rules VALUES ('','$rule')";
	$result = mysql_query($sql) or die(mysql_error());}
if ($function == delete){
	$sql = "DELETE FROM hub_rules WHERE rowID=$ud_id";
	$result = mysql_query($sql) or die(mysql_error());}

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
	<form action="rules-main.php?function=delclient&id=<? echo "$id";?>" method="post">
	<td nowrap><input type="Submit" value="Delete" onClick="return confirmDelete()"></td></form></tr>
	<?++$i;} ?></table><br><br>
	NOTE: if min AND/OR max slots is set to 0 then slots based on connection is ACTIVE
	<form action="client-cslots-edit.php" method="post">
	<input class="button" type="Submit" value="Edit Connection Slots Rules" title="Edit connection Slot Rules"></form>
</div>
<? echo "$fontend";?>

<?
echo"$font";
echo "<center><p>The rules here are additonal rules to those generated dynamically based a particular users client.<br> The rules you configure here should be static rules.<br> For Example, Grant slot to op on request, No Porn, No Installed files etc.</p></center>";
echo"$fontend";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM hub_rules";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;
while ($i < $num) {
$id=mysql_result($result,$i,"rowID");
$rule=mysql_result($result,$i,"rule");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
?>
		<td><input type="hidden"  value="<? echo "$id"; ?>"></td>
		<td><? echo"$font";?><? echo "$rule" ?><? echo"$fontend";?></td>
		<td><form action="<? echo "rules-edit.php?id=$id" ?>" method="post">
		<input type="Submit" value="Edit"></td></form>
		<td><form action="<? echo "rules-main.php?function=delete&ud_id=$id" ?>" method="post">
		<input type="Submit" value="Delete" onClick="return confirmDelete()"></td>
		</form>
	</tr>
<? ++$i; }  
$rule ="";
?>
</table>
<br>
<br>
<table>
<td><form action="<? echo "rules-add.php" ?>" method="post"></td>
<input type="Submit" value="Add New Rule"></td>
</form>
</table>
</center>
</body>
</html>
