<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Hub variables</title>
</head>
<body>
<?
include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Current Variables for $hubname</center></h3><br><br>";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if($function == update) {
	$sql = "UPDATE hub_variables SET value='$ud_value' WHERE rowID='$ud_id'";
	$result = mysql_query($sql) or die(mysql_error());}

$query="SELECT * FROM hub_variables";
$result=mysql_query($query);
$num=mysql_num_rows($result);


mysql_close();

echo "<center><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";

$i=0;
while ($i < $num) {
$id=mysql_result($result,$i,"rowID");
$rule=mysql_result($result,$i,"rule");
$value=mysql_result($result,$i,"value");
$description=mysql_result($result,$i,"description");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
?>
		<td><input type="hidden" name="ud_id" value="<? echo "$id"; ?>"></t>
		<td><? echo "$font$description$fontend" ?></td>
		<td><? echo"$font";?><? echo "$value" ?><? echo"$fontend";?></td>
	<form action="config-vars-edit.php?id=<? echo "$id";?>" method="post">
	<td nowrap><input type="Submit" value="Edit"></td></form>
		</form>
	</tr>

<? ++$i; }  ?>
</table>
</center>

</body>
</html>
