<?php 
$page_title="Edit your Hub variables";
include("header.ini");
?>

<?php 
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if($function == update) {
	$sql = "UPDATE hub_variables SET value='$ud_value' WHERE rowID='$ud_id'";
	$result = mysql_query($sql) or die(mysql_error());}

$query="SELECT * FROM hub_variables where rowID=$id";
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
		<form action="<?php  echo "config-vars.php?function=update" ?>" method="post">
		<td><input type="hidden" name="ud_id" value="<?php  echo "$id"; ?>"></t>
		<td><?php  echo "$font$description$fontend" ?></td>
		<td><?php  echo"$font";?><input size="50" type="text" name="<?php  echo "ud_value" ?>" value="<?php  echo "$value" ?>"><?php  echo"$fontend";?></td>
		<td><input type="Submit" value="Update"></td>
		</form>
	</tr>

<?php  ++$i; }  ?>
</table>
</center>

</body>
</html>
