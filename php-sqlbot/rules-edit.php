<?php 
$page_title="Edit Rules";
include("header.ini");
?>

<?php 
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM hub_rules WHERE rowID=$id";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
$i=0;
while ($i < $num) {
$id=mysql_result($result,$i,"rowID");
$rule=mysql_result($result,$i,"rule");?>

<center>
	<table>
	<tr><form action="rules-main.php?function=update&ud_id=<?php  echo"$id";?>" method="post">
		<td>Edit Rule</td>
		<td><input type="Text" size="70" name="ud_rule" value="<?php  echo"$rule";?> "></td>
		<td></td>
	<td><input type="Submit" value="Submit"></td>
	</form>
	</tr>
	</table>
</center>
<?php  ++$i; }  ?>
</body>
</html> 
