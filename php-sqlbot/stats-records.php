<?
$page_title="Hub Records";
include("header.ini");
?>

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if($function == reset) {
	$sql = "UPDATE records SET recordValue=0 WHERE rowID='$ud_id'";
	$result = mysql_query($sql) or die(mysql_error());}

$query="SELECT * FROM records";
$result=mysql_query($query);
$num=mysql_num_rows($result);


mysql_close();

echo "<center><table border=\"$tableBorders\" cellspacing=\"2\" cellpadding=\"2\">";

$i=0;
while ($i < $num) {
$id=mysql_result($result,$i,"rowID");
$recordName=mysql_result($result,$i,"recordName");
$recordValue=mysql_result($result,$i,"recordValue");
$date=mysql_result($result,$i,"date");
$time=mysql_result($result,$i,"time");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
?>
		<form action="<? echo "stats-records.php?function=reset&ud_id=$id" ?>" method="post">
		<td><input type="hidden" name="ud_id" value="<? echo "$id"; ?>"></t>
		<td><? echo "$font$recordName$fontend" ?></td>
		<td><? echo "$font$recordValue$fontend" ?></td>
		<td><? echo "$font$date$fontend" ?></td>
		<td><? echo "$font$time$fontend" ?></td>
		<td><input type="Submit" value="Reset" onClick="return confirmDelete()"></td>
		</form>
	</tr>

<? ++$i; }  ?>
</table>
</center>
</body>
</html>
