<?
$page_title="Hub Configuration";
include("header.ini");
?>

<? 
include("dbinfo.inc.php");
mysql_connect($databasehost,$username,$password); 
@mysql_select_db($database) or die( "Unable to select database"); 
$query="SELECT * FROM hub_config ORDER by rowID ASC"; 
$result=mysql_query($query); 
$num=mysql_num_rows($result);  
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
<form action=\"config-main.php?function=configupdate\" method=\"post\" >";
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
		<td><?echo "$font$description$fontend :";?></td>
		<td><?echo "$font( $value )$fontend";?></td>
		<td><input type="checkbox" name="<?echo "ud_$rule";?>" <? if ($value == "on") echo" checked";?> </td>
	</tr>
	
<? ++$i; }  ?></table>
<input type="submit" value="Submit">
	</form>
</center>
</body>
</html>
