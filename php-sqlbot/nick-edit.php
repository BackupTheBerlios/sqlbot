<?
$page_title="Edit a filtered Nickname";
include("header.ini");
?>

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT * FROM nick_filter where rowID=$id";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";
while ($i < $num) {
$rowID=mysql_result($result,$i,"rowID");
$nick=mysql_result($result,$i,"nick");
$ip_start=mysql_result($result,$i,"ip_start");
$ip_end=mysql_result($result,$i,"ip_end");
$action=mysql_result($result,$i,"function");
$stats_log=mysql_result($result,$i,"stats_log");
$hub_log=mysql_result($result,$i,"hub_log");
?>
<center><form action="nick-main.php?function=update" method="post">
	<table>
	
	<tr>
		<td></td>	
		<td><input type="hidden" name="ud_rowID" value="<? echo "$rowID"; ?>"></t>
	</tr><tr>
		<td>Nick or Partial Nick</td>
		<td><input type="Text" name="ud_nick" value="<? echo"$nick";?>" ></td>
		<td></td>
	</tr><tr>
		<td>IP Range Start</td>
		<td><input type="Text" name="ud_ip_start" value="<? echo"$ip_start";?>"></td>
		<td></td>
	</tr><tr>
		<td>IP Range End</td>
		<td><input type="Text" name="ud_ip_end" value="<? echo"$ip_end";?>"></td>
		<td></td>
	</tr><tr>
		<td></td>
		<td><input type="radio" name="ud_action" value="ban" <? if ($action == "ban") echo"checked=\"true\"";?> >Ban<br></td>
		<td></td>
	</tr><tr>
		<td></td>
		<td><input type="radio" name="ud_action" value="allow" <? if ($action == "allow") echo"checked=\"true\"";?> >Allow<br></td>
		<td></td>
	</tr><tr>
		<td>Add to User Stats</td>
		<td><input type="checkbox" name="ud_stats_log" value="<? echo"$stats_log";?>" <? if ($stats_log == "on") echo" checked";?> ></td>
		<td></td>
	</tr><tr>
		<td>Add to Hub Log</td>
		<td><input type="checkbox" name="ud_hub_log" value="<? echo"$hub_log";?>" <? if ($hub_log == "on") echo" checked";?> ></td>
		<td></td>
	</tr></table>
	<hr>
<input type="Submit" value="Submit changes">
</form>
<? ++$i; }  ?>
</center>
</body>
</html> 
