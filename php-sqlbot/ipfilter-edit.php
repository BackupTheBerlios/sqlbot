<?php 
$page_title="Edit a filtered Nickname";
include("header.ini");
?>

<?php 
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT * FROM ipFiltering where rowID=$id";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";
while ($i < $num) {
$rowID=mysql_result($result,$i,"rowID");
$ipStart=mysql_result($result,$i,"ipStart");
$ipMask=mysql_result($result,$i,"ipMask");
$function=mysql_result($result,$i,"function");
$information=mysql_result($result,$i,"information");
$log=mysql_result($result,$i,"log");

?>
<center><form action="ipfilter-main.php?f=update" method="post">
	<table>
	
	<tr>
		<td></td>	
		<td><input type="hidden" name="rowID" value="<?php  echo "$rowID"; ?>"></t>
	</tr><tr>
		<td>IP Range Start</td>
		<td><input type="Text" name="ipStart" value="<?php  echo"$ipStart";?>"></td>
		<td></td>
	</tr><tr>
		<td>IP Mask</td>
		<td><input type="Text" name="ipMask" value="<?php  echo"$ipMask";?>"></td>
		<td></td>
	</tr><tr>
		<td></td>
		<td><input type="radio" name="function" value="ban" <?php  if ($function == "ban") echo"checked=\"true\"";?> >Ban<br></td>
		<td></td>
	</tr><tr>
		<td></td>
		<td><input type="radio" name="function" value="allow" <?php  if ($function == "allow") echo"checked=\"true\"";?> >Allow<br></td>
		<td></td>
	</tr><tr>
		<td>Log</td>
		<td><input type="checkbox" name="log" value="<?php  echo"$log";?>" <?php  if ($log == "on") echo" checked";?> ></td>
		<td></td>
	</tr><tr>
		<td>Information</td>
		<td><input type="Text" name="information" value="<?php  echo"$information";?>" ></td>
		<td></td>		
	</tr></table>
	<hr>
<input type="Submit" value="Submit changes">
</form>
<?php  ++$i; }  ?>
</center>
</body>
</html> 
