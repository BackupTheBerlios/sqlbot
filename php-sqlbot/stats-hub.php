<?
$page_title="Hub Statistics";
include("header.ini");
$stats_page="stats-hub1.php";
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
</tr>
</table>
<hr size="1" width="100%"><p>
<?
if (empty($select)){
$select = countrydist;
}
if ($select == countrydist){
///////////////////////////////////////////////////////
//// Total users By country
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT country,COUNT(country) FROM userDB GROUP BY country ";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Total Users From Countries</h2> <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\"><tr>";

$i=0;while ($i < $num) {
	$country=mysql_result($result,$i,"country");
	if($i % 2) { //this means if there is a remainder
        echo "<td bgcolor="; echo "$rowColour"; echo ">";
    	} else { 
        echo "<td bgcolor="; echo "$rowColourAlt"; echo ">"; }?>
	<? echo "$font$country$fontend" ?></td>
<? ++$i; }  ?>
</tr><tr>
<?
$i=0;while ($i < $num) {
	$count=mysql_result($result,$i,"COUNT(country)");
	if($i % 2) { //this means if there is a remainder
        echo "<td bgcolor="; echo "$rowColour"; echo ">";
    	} else { 
        echo "<td bgcolor="; echo "$rowColourAlt"; echo ">"; }?>
	<? echo "$font$count$fontend" ?></td>
<? ++$i; }  ?>
</tr>
<tr>
<?
$i=0;while ($i < $num) {
	$count=mysql_result($result,$i,"COUNT(country)");
	if($i % 2) { 
        echo "<td bgcolor="; echo "$rowColour"; echo ">";
    	} else { 
        echo "<td bgcolor="; echo "$rowColourAlt"; echo ">"; }?>
	<TABLE bgColor=red height=<? echo "$count" ?> width=10 
	cellSpacing=0 cellPadding=0 border= 0><td></td> </TABLE></td>	

<? ++$i; }  ?>
</tr>
</table>
<?
}
if ($select == hubcounters){
///////////////////////////////////////////////////////
//// Banned Counter
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT COUNT(allowStatus) FROM userDB WHERE allowStatus='Banned'";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Number of Banned Users<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(allowStatus)");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td>Total Number of Bans in place</td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
///////////////////////////////////////////////////////
//// Kick Counter
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT sum(kickCountTot) FROM userDB";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Total Number Kicked</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"sum(kickCountTot)");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td>Total Number of Kicks</td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

<?
///////////////////////////////////////////////////////
////Total Lines Spoken
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT sum(lineCount) FROM userDB";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Lines Spoken</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"sum(lineCount)");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td>Lines Spoken</td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

<?

}
if ($select == abusers){
///////////////////////////////////////////////////////
//// Most Kicked
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,kickCountTot FROM userDB ORDER BY kickCountTot DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Top Ten Most Kicked</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$kickCountTot=mysql_result($result,$i,"kickCountTot");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$kickCountTot$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
///////////////////////////////////////////////////////
//// Most Banned
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,tBanCountTot FROM userDB ORDER BY tBanCountTot DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Most Banned</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$tBanCountTot=mysql_result($result,$i,"tBanCountTot");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$tBanCountTot$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
///////////////////////////////////////////////////////
//// Users on Most Hubs
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,hubs FROM userDB ORDER BY hubs DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Users on Most Hubs</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$hubs=mysql_result($result,$i,"hubs");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$hubs$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

<?

///////////////////////////////////////////////////////
//// Users with Most Slots
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,slots FROM userDB ORDER BY slots DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>With most slots</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$slots=mysql_result($result,$i,"slots");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$slots$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?

}

if ($select == top_users){

///////////////////////////////////////////////////////
////Longest Online
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,onlineTime FROM userDB ORDER BY onlineTime DESC LIMIT 20";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Longest Online</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$onlineTime=mysql_result($result,$i,"onlineTime");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$onlineTime$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
///////////////////////////////////////////////////////
////Most Frequent / Average
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,type FROM userDB ORDER BY type DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Most Powerful</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$type=mysql_result($result,$i,"type");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$type$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

<?

///////////////////////////////////////////////////////
//// Top ten Chatters
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,lineCount FROM userDB ORDER BY lineCount DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Top Ten Speakers</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"lineCount");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?

///////////////////////////////////////////////////////
//// Most Visitors
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,loginCount FROM userDB ORDER BY loginCount DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Most Logins</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$loginCount=mysql_result($result,$i,"loginCount");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
	<td><? echo "$font$i$fontend" ?></td>
	<td><? echo "$font$nick$fontend" ?></td>
	<td><? echo "$font$loginCount$fontend" ?></td>
	</tr><? ++$i; }  ?>
</table>

<?

}

if ($select == userstats){

///////////////////////////////////////////////////////
//// users with same clientVersion
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT dcVersion,dcClient,COUNT(dcVersion) FROM userDB GROUP BY dcVersion";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Users with Same Version of Client</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(dcVersion)");
$dcVersion=mysql_result($result,$i,"dcVersion");
$dcClient=mysql_result($result,$i,"dcClient");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$count$fontend" ?></td>
		<td><? echo "$font$dcClient$fontend" ?></td>
		<td nowrap><a href="user-manage.php?field=dcVersion&search=<? echo "$dcVersion"?>""><? echo "$font$dcVersion$fontend"; ?></a></td>
		<td><TABLE bgColor=red height=10 width=<? echo "$count" ?> 
		cellSpacing=0 cellPadding=0 border= 0> <TR><TD></TD></TR></TABLE></td>

		</tr><? ++$i; }  ?>
</table>

<?

///////////////////////////////////////////////////////
//// users with same client
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT dcClient,COUNT(dcClient) FROM userDB GROUP BY dcClient";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center><h2>Users with Same Client</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(dcClient)");
$dcClient=mysql_result($result,$i,"dcClient");
	if($count == 1){}
	else{
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$count$fontend" ?></td>
		<td><TABLE bgColor=red height=10 width=<? echo "$count" ?> 
		cellSpacing=0 cellPadding=0 border= 0> <TR><TD></TD></TR></TABLE></td>

		<td><? echo "$font$dcClient$fontend" ?></td>
		</tr><?} ++$i; }  ?>
</table>

<?

///////////////////////////////////////////////////////
////Total Share figures
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$gigabyte = 1024 * 1024 * 1024;
$range = 5 * $gigabyte;
$limit = 150 * $gigabyte;
$lowRange = 0;
$highRange = $range;
	echo "<center><h2>Hub Share</h2><table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
while ($limit > $highRange)
{
	$query="SELECT COUNT(nick) FROM userDB WHERE shareByte BETWEEN $lowRange AND $highRange";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	$count=mysql_result($result,0,"COUNT(nick)");
	$lowRangeGb=round(($lowRange / 1024 / 1024 / 1024), 2);
	$highRangGb=round(($highRange / 1024 / 1024 / 1024), 2);
	$tablewidth=$count *2;
	?>
	<td><? echo "$font$lowRangeGb - $highRangGb GB $fontend" ?></td>
	<td><? echo "$font$count$fontend" ?></td>
	<td><TABLE bgColor=red height=10 width=<? echo "$tablewidth" ?> 
	cellSpacing=0 cellPadding=0 border= 0> <TR><TD></TD></TR></TABLE></td>
	</tr><?
	++$i;
	 $lowRange  =  $lowRange + $range;
	 $highRange  =  $highRange + $range;}
mysql_close();?>

</table>
<?


}
?>

</center>
</body>
</html>
