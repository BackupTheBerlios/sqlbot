<? $page_title="User Management";include("header.ini");?>

<div align="center"><?
$entry=0;$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$where = "";
if ($field == shareByte)
	{$where="WHERE $field = '$search'";}
if ($field == dcVersion)
	{$where="WHERE $field = '$search'";}	
else if (!empty($field))  
	{$where="WHERE $field LIKE '%$search%'";}
if (!empty($order))  
	{$orderby ="'$order'";}
else {$orderby="uType,nick";}

if ($f == delete)
	{$sql = "DELETE FROM userDB $where";$result = mysql_query($sql) or die(mysql_error());}
if ($f == uType){
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',$uType,'$nick','$ip','$passwd')";
	echo "$uType $nicksearch($ip) status changed.<br>";
	$result = mysql_query($sql) or die(mysql_error());}
else if ($f == aStatus){
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',$aStatus,'$nick','$ip','$information')";
	echo "$aStatus $search($ip) status changed.<br>";
	$result = mysql_query($sql) or die(mysql_error());}
if (empty($offset)) {$offset=0;}
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$numresult=mysql_query("SELECT * FROM userDB $where");
$numrows=mysql_num_rows($numresult);
$result=mysql_query("SELECT * FROM userDB $where ORDER by $orderby LIMIT $offset,$defaultLogEntries");
mysql_close();
?>
<table border="<? echo $tableBorders;?>" cellspacing="2" cellpadding="2">
<tr>
	<th> Preset Filters</th>
	<th><form action="<? echo "user-manage.php?field=status&search=Online" ?>" method="post">
	<input type="Submit" value="Online"></form></th>
	<th><form action="<? echo "user-manage.php?field=lastAction&search=Kicked" ?>" method="post">
	<input type="Submit" value="Kicked"></form></th>
	<th><form action="<? echo "user-manage.php?field=lastReason&search=Fake" ?>" method="post">
	<input type="Submit" value="Faker"></form></th>
	<th><form action="<? echo "user-manage.php?field=allowStatus&search=Banned" ?>" method="post">
	<input type="Submit" value="Banned"></form></th>
	</tr><tr>
	<th><? echo " Filters Applied $font$field $search$fontend";?></th>
	<th><form action="<? echo "user-manage.php" ?>" method="post">
	<input type="Submit" value="Reset Filters"></form></th>
	<th><form action="<? echo "user-manage.php?f=delete&field=$field&search=$search" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form></th>
	</tr><tr>
	<th><form action="<? echo "user-manage.php?field=nick&search=$search" ?>" method="post">
	<input type="text" name="search" value=""><? echo $font;?></th><th>
	<input type="Submit" value="Nick Search"></form></th>
</tr>
</table><?
echo "Totals :Users $numrows<br>";
?>
<table border="<? echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr>
<th><a href="<? echo "user-manage.php?order=nick" ?>"> <? echo $font;?>Nick<? echo $fontend;?></a></th>
<th><? echo $font;?>Status<? echo "$fontend";?></th>
<th><a href="<? echo "user-manage.php?order=uType" ?>"> <? echo $font;?>User Type<? echo "$fontend";?></a></th>
<th><? echo $font;?>Allow Status<? echo "$fontend";?></th>
 <th><a href="<? echo "user-manage.php?order=connection" ?>"> <? echo $font;?>Connection<? echo "$fontend";?></a></th>
 <th><a href="<? echo "user-manage.php?order=country" ?>"> <? echo $font;?>Country<? echo "$fontend";?></a></th>
<th><a href="<? echo "user-manage.php?order=IP" ?>"> <? echo $font;?>IP<? echo "$fontend";?></a></th>
<th><a href="<? echo "user-manage.php?order=inTime" ?>"> <? echo $font;?>Checkin Time<? echo "$fontend";?></a></th>
 <th><a href="<? echo "user-manage.php?order=shareByte" ?>"> <? echo $font;?>Shared_Bytes<? echo "$fontend";?></a></th>
 <th><a href="<? echo "user-manage.php?order=lastAction" ?>"> <? echo $font;?>Last Action<? echo "$fontend";?></a></th>
 <th><a href="<? echo "user-manage.php?order=lastReason" ?>"> <? echo $font;?>Last Reason<? echo "$fontend";?></a></th>

</tr>
<?

while ($data=mysql_fetch_array($result)) 
{
	$nick=mysql_result($result,$i,"nick");
//	$passwd=mysql_result($result,$i,"passwd");
	$status=mysql_result($result,$i,"status");
	$uType=mysql_result($result,$i,"uType");
//	$type=mysql_result($result,$i,"type");
	$allowStatus=mysql_result($result,$i,"allowStatus");	
//	$awayStatus=mysql_result($result,$i,"awayStatus");
//	$awayMSg=mysql_result($result,$i,"awayMSg");
//	$fullDescription=mysql_result($result,$i,"fullDescription");
//	$dcClient=mysql_result($result,$i,"dcClient");
//	$dcVersion=mysql_result($result,$i,"dcVersion");
//	$slots=mysql_result($result,$i,"slots");
//	$hubs=mysql_result($result,$i,"hubs");
//	$limiter=mysql_result($result,$i,"limiter");
	$connection=mysql_result($result,$i,"connection");
//	$connectionMode=mysql_result($result,$i,"connectionMode");
	$country=mysql_result($result,$i,"country");
	$IP=mysql_result($result,$i,"IP");
//	$hostname=mysql_result($result,$i,"hostname");
//	$firstTime=mysql_result($result,$i,"firstTime");
//	$outTime=mysql_result($result,$i,"outTime");
	$inTime=mysql_result($result,$i,"inTime");
//	$onlineTime=mysql_result($result,$i,"onlineTime");
//	$loginCount=mysql_result($result,$i,"loginCount");
	$kickCount=mysql_result($result,$i,"kickCount");
//	$kickCountTot=mysql_result($result,$i,"kickCountTot");
//	$tBanCount=mysql_result($result,$i,"tBanCount");
//	$tBanCountTot=mysql_result($result,$i,"tBanCountTot");
//	$pBanCount=mysql_result($result,$i,"pBanCount");
//	$pBanCountTot=mysql_result($result,$i,"pBanCountTot");
//	$lineCount=mysql_result($result,$i,"lineCount");
//	$avShareBytes=mysql_result($result,$i,"avShareBytes");
	$byteShare=mysql_result($result,$i,"shareByte");
	$lastAction=mysql_result($result,$i,"lastAction");
	$lastReason=mysql_result($result,$i,"lastReason");

	if (($byteShare / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024 / 1024), 2); $Share="$Shared TB";}
	else if (($byteShare / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024), 2); $Share="$Shared GB";}
	else if (($byteShare / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024), 2); $Share="$Shared MB";}
	else if (($byteShare / 1024) > 1) { $Shared=round(($byteShare / 1024), 2); $Share="$Shared KB";};

	// Colour Rows
	if(($uType == "Operator") || ($uType == "Op-Admin")) {echo "<TR bgcolor="; echo "$OpRowColour"; echo ">\n";}
	else if($allowStatus == "Allow") {echo "<TR bgcolor="; echo "$AllowRowColour"; echo ">\n";}
	else if(($allowStatus == "Banned")) { echo "<TR bgcolor=";echo "$BanRowColour"; echo ">\n";}
	else if($kickCount != 0) {echo "<TR bgcolor="; echo "$KickRowColour"; echo ">\n";}
	else if($i % 2)	{echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";}
	else{echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n";}

	?>
	
	
<td nowrap><a href="<? echo "user-type.php?nick=$nick&IP=$IP" ?>"> <? echo "$font$nick$fontend"?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=status&search=$status" ?>>" title="Search for users <?echo "$status"?>"> <? echo "$font$status$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=uType&search=$uType" ?>"><? echo "$font$uType$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=allowStatus&search=$allowStatus" ?>"><? echo "$font$allowStatus$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=connection&search=$connection" ?>"><? echo "$font$connection$fontend"; ?></a></td>
<td nowrap><a href="user-manage.php?field=country&search=<? echo "$country"?>" title="Search for all users from: <? echo "$country"?>"><? echo "$font$country$fontend"; ?></a></td>
<td nowrap><a href="user-manage.php?field=IP&search=<? echo "$IP"?>" title="Search for all users with: <? echo "$IP"?>"><? echo "$font$IP$fontend"; ?></a></td>
<td nowrap><? echo "$font$inTime$fontend"; ?></td>
<td nowrap><a href="user-manage.php?field=shareByte&search=<? echo "$byteShare"?>" title="<? echo "$Share" ?>" style="cursor:help"><? echo "$font$byteShare$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=lastAction&search=$lastAction" ?>"><? echo "$font$lastAction$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-manage.php?field=lastReason&search=$lastReason" ?>"><? echo "$font$lastReason$fontend"; ?></a></td>
	
	</tr>
	<?
	$i++;
} 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"user-manage.php?order=$order&offset=$prevoffset&field=$field&search=$search\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"user-manage.php?order=$order&offset=$newoffset&field=$field&search=$search\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"user-manage.php?order=$order&offset=$newoffset&field=$field&search=$search\">NEXT</a><p>\n";
}

?></div>
<? echo "$fontend";?>
</body>
</html>

