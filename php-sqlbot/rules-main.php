<?
$page_title="Hub Rules";
include("header.ini");
?>

<?
echo"$font";
echo "<center><p>The rules here are additonal rules to those generated dynamically based a particular users client.<br> The rules you configure here should be static rules.<br> For Example, Grant slot to op on request, No Porn, No Installed files etc.</p></center>";
echo"$fontend";
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
