<?
$page_title="Filter Nicknames";
include("header.ini");
?>

<?
include("dbinfo.inc.php");
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if ($function == del){
	$sql = "DELETE FROM nick_filter WHERE rowID=$id";
	$result = mysql_query($sql) or die(mysql_error());}
if ($function == add){
	if (empty($stats_log)){$stats_log = "off";}else{$stats_log = "on";}
	if (empty($hub_log)){$hub_log = "off";}else{$hub_log = "on";}
	$sql = "INSERT INTO nick_filter VALUES('','$nick','$ip_start','$ip_end','$action',
			'$stats_log','$hub_log')";
	$result = mysql_query($sql) or die(mysql_error());}
if ($function == update){
	if (empty($ud_stats_log)) {$stats_log = "off";}else{$stats_log = "on";}
	if (empty($ud_hub_log)) {$hub_log = "off";}else{$hub_log = "on";}
	$sql = "UPDATE nick_filter SET nick='$ud_nick',ip_start='$ud_ip_start',ip_end='$ud_ip_end',
		function='$ud_action',stats_log='$stats_log',hub_log='$hub_log'
		WHERE rowID='$ud_rowID'";
	$result = mysql_query($sql) or die(mysql_error());}

$query="SELECT * FROM nick_filter ORDER by rowID ASC";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();
echo "<center><table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";
?>
<tr>
	<th><? echo "$font";?>Nick Partial Nick<? echo "$fontend";?></th>
	<th><? echo "$font";?>IP Range Start<? echo "$fontend";?></th>
	<th><? echo "$font";?>IP Range End<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Function<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Stats Log<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Hub Log<? echo "$fontend";?></th>
	<th><form action="nick-add.php" method="post">
	<input type="Submit" value="Add"></form></th>
</tr>
<?
$i=0;
while ($i < $num) {
	$id=mysql_result($result,$i,"rowID");
	$nick=mysql_result($result,$i,"nick");
	$ip_start=mysql_result($result,$i,"ip_start");
	$ip_end=mysql_result($result,$i,"ip_end");
	$function=mysql_result($result,$i,"function");
	$stats_log=mysql_result($result,$i,"stats_log");
	$hub_log=mysql_result($result,$i,"hub_log");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
	
	<td nowrap><? echo "$font$nick$fontend"; ?></td>
	<td nowrap><? echo "$font$ip_start$fontend"; ?></td>
	<td nowrap><? echo "$font$ip_end$fontend"; ?></td>
	<td nowrap><? echo "$font$function$fontend"; ?></td>
	<td nowrap><? echo" $stats_log"; ?></td>
	<td nowrap><? echo" $hub_log"; ?></td>
	<td nowrap><form action="nick-edit.php?id=<? echo "$id";?>" method="post">
	<input type="Submit" value="Edit"></form></td>
	<td nowrap><form action="nick-main.php?function=del&id=<? echo "$id";?>" method="post">
	<input type="Submit" value="Delete"></form></td>
	</tr>
	
<? ++$i; }  ?></table>
</center>
</body>
</html>
