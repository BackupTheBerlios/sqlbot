<?
$page_title="Records";
include("header.ini");
?>
<div align="center"><table>
<tr>
	<td nowrap>
	<form action="stats-hub.php?select=countrydist" method="post">
	<input class="button" type="Submit" value="Country Distribution" title="Country Distribution"></form>
	</td>
	<td nowrap>
	<form action="stats-hub.php?select=hubcounters" method="post">
	<input class="button" type="Submit" value="Counts from hub" title="Counts from hub"></form>
	</td>
	<td nowrap>
	<form action="stats-hub.php?select=abusers" method="post">
	<input class="button" type="Submit" value="Abusers" title="Abusers"></form>
	</td>
	<td nowrap>
	<form action="stats-hub.php?select=top_users" method="post">
	<input class="button" type="Submit" value="Top 10" title="Top 10"></form>
	</td>
	<td nowrap>
	<form action="stats-hub.php?select=userstats" method="post">
	<input class="button" type="Submit" value="User Information" title="User Information"></form>
	</td>
	<td nowrap>
	<form action="hub-records.php" method="post">
	<input class="button" type="Submit" value="Hub Records" title="Hub Records"></form>
	</td>
</tr>
</table>

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

if ($function == reset){
	$sql = "UPDATE records SET recordValue='0' where rowID='$id'";
	$result = mysql_query($sql) or die(mysql_error());}


// Show the current client rules
$query="SELECT * FROM records";
$result=mysql_query($query);
$num=mysql_numrows($result);
mysql_close();
echo "$font<p><b>Hub Records</b><br>$fontend";
?>

<table border="1" cellspacing="2" cellpadding="2"><tr>
		<th><? echo "$font"; ?>Record Name<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Value<? echo "$fontend"; ?></th>
		<th><? echo "$font"; ?>Reset<? echo "$fontend"; ?></th></tr>

<?
$i=0;
while ($i < $num) {
	$id=mysql_result($result,$i,"rowID");
	$name=mysql_result($result,$i,"recordName");
	$value=mysql_result($result,$i,"recordValue");


	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; } ?>

	<td nowrap><? echo "$font$name$fontend"; ?></td>
	<td nowrap><? echo "$font$value$fontend"; ?></td>
	<form action="hub-records.php?function=reset&id=<? echo "$id";?>" method="post">
	<td nowrap><input type="Submit" value="Reset" title="Reset value to 0" onClick="return confirmDelete()"></td></form></tr>
	<?++$i;} ?></table>
</center>
</body>
</html>
