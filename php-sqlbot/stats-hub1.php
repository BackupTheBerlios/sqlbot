<?
$page_title="Hub Statistics";
include("header.ini");
$stats_page="stats-hub1.php";
?>
<div align="center"><table>
<tr>
	<td nowrap>
	<form action="stats-hub1.php?select=hubstats" method="post">
	<input class="button" type="Submit" value="Hub Stats" title="Stats of your hub"></form>
	</td>
	<td nowrap>
	<form action="stats-hub1.php?select=abusers" method="post">
	<input class="button" type="Submit" value="Abusers" title="Possible abusers"></form>
	</td>
	<td nowrap>
	<form action="stats-hub1.php?select=top_users" method="post">
	<input class="button" type="Submit" value="Top Users" title="Top Users"></form>
	</td>
	<td nowrap>
	<form action="stats-hub1.php?select=userstats" method="post">
	<input class="button" type="Submit" value="User Stats" title="User Stats"></form>
	</td>
</tr>
</table>
<hr size="1" width="100%"><p>
<?
if ($select == hubstats){
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
		</tr><? ++$i; } 
echo "</table>\n\n";

///////////////////////////////////////////////////////
//// Kick Counter
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT sum(kickCountTot) FROM userDB";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Total Number Kicked<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"sum(kickCountTot)");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td>Total Number of Kicks</td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; } 
echo "</table>\n\n";

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
echo "<center>Top Ten Most Kicked <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$kickCountTot=mysql_result($result,$i,"kickCountTot");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$kickCountTot$fontend</td>\n</tr>";
		++$i; }
echo "</table>\n\n";

///////////////////////////////////////////////////////
//// Most Banned
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,tBanCountTot FROM userDB ORDER BY tBanCountTot DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Most Banned <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$tBanCountTot=mysql_result($result,$i,"tBanCountTot");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$tBanCountTot$fontend</td>\n</tr>";
		++$i; }
echo "</table>\n\n";


///////////////////////////////////////////////////////
//// Users on Most Hubs
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,hubs FROM userDB ORDER BY hubs DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Users on Most Hubs <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$hubs=mysql_result($result,$i,"hubs");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$hubs$fontend</td>\n</tr>";
		++$i; }
echo "</table>\n\n";



///////////////////////////////////////////////////////
//// Users with Most Slots
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,slots FROM userDB ORDER BY slots DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>With most slots <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$slots=mysql_result($result,$i,"slots");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$slots$fontend</td>\n</tr>";
		++$i; }
echo "</table>\n\n";
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
echo "<center>Longest Online <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$onlineTime=mysql_result($result,$i,"onlineTime");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$onlineTime$fontend</td>\n</tr>";
		++$i; }
echo "</table>\n\n";

///////////////////////////////////////////////////////
////Most Frequent / Average
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,onlineTime,loginCount FROM userDB ORDER BY loginCount DESC LIMIT 30";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Most Frequent Visitors / Average Time online<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$onlineTime=mysql_result($result,$i,"onlineTime");
$loginCount=mysql_result($result,$i,"loginCount");
$averageVisitLength=$onlineTime/$loginCount;
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$onlineTime$fontend</td>";
		echo "<td>$font$averageVisitLength$fontend Secs</td>\n</tr>";
		++$i; }
echo "</table>\n\n";

///////////////////////////////////////////////////////
////Most Powerful
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,type FROM userDB ORDER BY type DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Most Powerful <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$type=mysql_result($result,$i,"type");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$type$fontend<td>\n</tr>";
		++$i; }
echo "</table>\n\n";

///////////////////////////////////////////////////////
//// Top ten Chatters
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,lineCount FROM userDB ORDER BY lineCount DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Top Ten Speakers<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"lineCount");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>";
		echo "<td>$font$nick$fontend</td>";
		echo "<td>$font$count$fontend</td>\n</tr>";
		++$i; } 
echo "</table>\n\n";


}

if ($select == userstats){
///////////////////////////////////////////////////////
//// Total users By country
///////////////////////////////////////////////////////
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT country,COUNT(country) FROM userDB GROUP BY country ";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Total Users From Countries <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(country)");
$country=mysql_result($result,$i,"country");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$count$fontend</td>\n";
		echo "<td>$font$country$fontend</td></tr>"; ++$i; }
echo "</table>\n\n";


///////////////////////////////////////////////////////
//// users with same client
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT dcClient,COUNT(dcClient) FROM userDB GROUP BY dcClient";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Users with Same Client<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(dcClient)");
$dcClient=mysql_result($result,$i,"dcClient");
	if($count == 1){}
	else{
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$count$fontend</td>";
		echo "<td>$font$dcClient$fontend</td></tr>\n"; } ++$i; }
echo "</table>\n\n";




///////////////////////////////////////////////////////
//// users with same clientVersion
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT dcVersion,dcClient,COUNT(dcVersion) FROM userDB GROUP BY dcVersion";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Users with Same Version of Client<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(dcVersion)");
$dcVersion=mysql_result($result,$i,"dcVersion");
$dcClient=mysql_result($result,$i,"dcClient");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$count$fontend</td>\n";
		echo "<td>$font$dcClient$fontend</td>\n";
		echo "<td nowrap><a href=\"user-manage.php?field=dcVersion&search=$dcVersion\">";
		echo "$font$dcVersion$fontend</a></td></tr>\n"; ++$i; }
echo "</table>\n\n";




///////////////////////////////////////////////////////
////Total Lines Spoken
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT sum(lineCount) FROM userDB";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Lines Spoken<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"sum(lineCount)");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n<td>Lines Spoken</td>\n";
		echo "<td>$font$count$fontend</td>\n</tr>\n";} ++$i; }
echo "</table>\n\n";





///////////////////////////////////////////////////////
//// Top ten Chatters
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,lineCount FROM userDB ORDER BY lineCount DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Top Ten Speakers<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"lineCount");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		echo "<td>$font$i$fontend</td>\n";
		echo "<td>$font$nick$fontend</td>\n";
		echo "<td>$font$count$fontend</td></tr>\n"; ++$i; }
echo "</table>\n\n";


}
?>
























</center>
</body>
</html>