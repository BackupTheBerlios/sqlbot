<?
$page_title="Hub Statistics";
include("header.ini");
?>

<?
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$count$fontend" ?></td>
		<td><? echo "$font$country$fontend" ?></td></tr><? ++$i; }  ?>
</table>

<?
///////////////////////////////////////////////////////
////Total Share figures
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT shareByte,COUNT(shareByte) FROM userDB GROUP BY shareByte";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Users with Same Share<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$count=mysql_result($result,$i,"COUNT(shareByte)");
$shareByte=mysql_result($result,$i,"shareByte");
	if($count == 1){}
	else{
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$count$fontend" ?></td>
		<td nowrap><a href="user-manage.php?field=shareByte&search=<? echo "$shareByte"?>" title="<? echo "$Share" ?>" style="cursor:help"><? echo "$font$shareByte$fontend"; ?></a></td>
		</tr><?} ++$i; }  ?>
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
echo "<center>Users with Same Client<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
		<td><? echo "$font$dcClient$fontend" ?></td>
		</tr><?} ++$i; }  ?>
</table>

<?
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$count$fontend" ?></td>
		<td><? echo "$font$dcClient$fontend" ?></td>
		<td nowrap><a href="user-manage.php?field=dcVersion&search=<? echo "$dcVersion"?>""><? echo "$font$dcVersion$fontend"; ?></a></td>
		</tr><? ++$i; }  ?>
</table>

<?
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
echo "<center>Total Number Kicked<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
echo "<center>Lines Spoken<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$count$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
///////////////////////////////////////////////////////
////Top Ten Shares
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,shareByte FROM userDB ORDER BY shareByte DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Top Ten Shares<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
$i=0;while ($i < $num) {
$shareByte=mysql_result($result,$i,"shareByte");
$nick=mysql_result($result,$i,"nick");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$shareByte$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>
<?
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
echo "<center>Most Banned <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
//// Most Visitors
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT nick,loginCount FROM userDB ORDER BY loginCount DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "<center>Most Logins <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
echo "<center>With most slots <table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$type$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

<?
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$onlineTime$fontend" ?></td>
		<td><? echo "$font$averageVisitLength$fontend" ?> Secs</td>
		</tr><? ++$i; }  ?>
</table>

<?
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
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }?>
		<td><? echo "$font$i$fontend" ?></td>
		<td><? echo "$font$nick$fontend" ?></td>
		<td><? echo "$font$onlineTime$fontend" ?></td>
		</tr><? ++$i; }  ?>
</table>

</center>
</body>
</html>
