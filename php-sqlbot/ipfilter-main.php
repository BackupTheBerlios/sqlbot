<?$page_title="Ip Filtering ";
include("header.ini");?>

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

if ($f == del){
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',41,'$ipMask','$ipStart','$information')";
	$result = mysql_query($sql) or die(mysql_error());

	$sql = "DELETE FROM ipFiltering WHERE rowID=$id";
	$result = mysql_query($sql) or die(mysql_error());}

if ($f == add){
	if (empty($log)){$log = "off";}else{$log = "on";}
	$sql = "INSERT INTO ipFiltering VALUES  ('','$ipStart','','$ipMask','$function','$information','$log')";
$result = mysql_query($sql) or die(mysql_error());

	if ($function == ban){
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',40,'$ipMask','$ipStart','$information')";
	$result = mysql_query($sql) or die(mysql_error());}
	else {
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',42,'$ipMask','$ipStart','$information')";
	$result = mysql_query($sql) or die(mysql_error());}

}
$query="SELECT * FROM ipFiltering ORDER by rowID ASC";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";?>
<tr>
	<th><? echo "$font";?>IP Range Start<? echo "$fontend";?></th>
	<th><? echo "$font";?>IP Mask<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Function<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Log<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Information<? echo "$fontend";?></th>
	<th><form action="ipfilter-add.php" method="post">
	<input type="Submit" value="Add"></form></th>
</tr>
<?$i=0;
while ($i < $num) {
	$id=mysql_result($result,$i,"rowID");
	$ipStart=mysql_result($result,$i,"ipStart");
	$ipMask=mysql_result($result,$i,"ipMask");
	$function=mysql_result($result,$i,"function");
	$log=mysql_result($result,$i,"log");
	$information=mysql_result($result,$i,"information");

	if($function == "allow") {echo "<TR bgcolor="; echo "$AllowRowColour"; echo ">\n";}
	else if(($function == "ban")) { echo "<TR bgcolor=";echo "$BanRowColour"; echo ">\n";}
	else if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	}
	
	else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
	<form action="ipfilter-main.php?f=del&id=<? echo "$id";?>&ipStart=<? echo "$ipStart";?>&ipMask=<? echo "$ipMask";?>&information=<? echo "$information";?>" method="post">
	<td nowrap><? echo "$font$ipStart$fontend"; ?></td>
	<td nowrap><? echo "$font$ipMask$fontend"; ?></td>
	<td nowrap><? echo "$font$function$fontend"; ?></td>
	<td nowrap><? echo "$font$log$fontend"; ?></td>
	<td nowrap><? echo" $font$information$fontend"; ?></td>
	<td nowrap><input type="Submit" value="Delete"></td></form>
	</tr>
	
<? ++$i; }  ?></table>
</center>
</body>
</html>
