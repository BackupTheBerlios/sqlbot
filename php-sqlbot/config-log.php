<?php 
$page_title="Configure the log";
include("header.ini");
?>

<?php  
mysql_connect($databasehost,$username,$password); 
@mysql_select_db($database) or die( "Unable to select database"); 
$query="SELECT * FROM log_config ORDER by rowID ASC"; 
$result=mysql_query($query); 
$num=mysql_num_rows($result);  
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\"><form action=\"config-main.php?function=logupdate\" method=\"post\" >";
while ($i < $num) {
	$rowID=mysql_result($result,$i,"rowID"); 
	$rule=mysql_result($result,$i,"rule");
	$value=mysql_result($result,$i,"value");
	$description=mysql_result($result,$i,"description");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	?> 
		<td><?php  echo "$font$description$fontend :";?></td>
		<td><?php  echo "$font( $value )$fontend";?></td>
		<td><input type="checkbox" name="<?php  echo "ud_$rule";?>" <?php  if ($value == "on") echo" checked";?> </td>
	</tr>
	
<?php  ++$i; }  ?></table>
<input type="submit" value="Submit">
	</form>
</center>
</body>
</html>
