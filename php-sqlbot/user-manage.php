<? $page_title="User Management";include("header.ini");?>

<div align="center"><?
$entry=0;$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$where = "";
if (!empty($nicksearch))  
	{$where="WHERE nick LIKE '%$nicksearch%'";$ipsearch = "";}
else if (!empty($ipsearch)) 
	{$where="WHERE IP LIKE '%$ipsearch%'";$nicksearch = "";}
else {$where = ""; }
if ($function == delete)
	{$sql = "DELETE FROM userDB $where";$result = mysql_query($sql) or die(mysql_error());}
if ($f == uType){
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',$uType,'$nicksearch','$ip','$passwd')";
	echo "$nicksearch($ip) status changed.<br>";
	$result = mysql_query($sql) or die(mysql_error());}
else if ($f == aStatus){
	
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',$aStatus,'$nicksearch','$ip','Admin')";
	echo "$nicksearch($ip) status changed.<br>";
	$result = mysql_query($sql) or die(mysql_error());}
if (empty($offset)) {$offset=0;}
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$numresult=mysql_query("SELECT * FROM userDB");
$numrows=mysql_num_rows($numresult);
$result=mysql_query("SELECT * FROM userDB $where ORDER by uType,nick ASC LIMIT $offset,$defaultLogEntries");
mysql_close();
?>
<b>Filter Users Stats</b>
<table> 
	<tr><td nowrap><form method="get" class='inline' action="user-manage.php">
		Nick Search<input  TYPE="text" VALUE="<? echo "$nicksearch";?>" NAME="nicksearch" SIZE="30" MAXLENGTH="50" >
		IP Search<input  TYPE="text" VALUE="<? echo "$ipsearch";?>" NAME="ipsearch" SIZE="20" MAXLENGTH="20" >
		<input type="submit" value="Apply"></form>
		</td>
	<td nowrap>
		
<form action="<? echo "user-manage.php?function=delete&ipsearch=$ipsearch&nicksearch=$nicksearch" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form>
</td></tr>
</form>
</td>
</table>		
<?
echo "Totals :Users $numrows<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2">
<tr>
<th><? echo "$font";?>nick<? echo "$fontend";?></th>
<!-- <th><? echo "$font";?>passwd<? echo "$fontend";?></th> -->
<th><? echo "$font";?>status<? echo "$fontend";?></th>
 <th><? echo "$font";?>utype<? echo "$fontend";?></th> 
<!--<th><? echo "$font";?>type<? echo "$fontend";?></th> -->
<th><? echo "$font";?>allowStatus<? echo "$fontend";?></th>
<!-- <th><? echo "$font";?>awayStatus<? echo "$fontend";?></th>  -->
<!-- <th><? echo "$font";?>awayMSg<? echo "$fontend";?></th> -->
<!-- <th><? echo "$font";?>fullDescription<? echo "$fontend";?></th> -->
<!-- <th><? echo "$font";?>dcClient<? echo "$fontend";?></th>
<th><? echo "$font";?>dcVersion<? echo "$fontend";?></th>
<th><? echo "$font";?>slots<? echo "$fontend";?></th>
<th><? echo "$font";?>hubs<? echo "$fontend";?></th>
<!--<th><? echo "$font";?>limiter<? echo "$fontend";?></th>-->
<th><? echo "$font";?>connection<? echo "$fontend";?></th>
<!--<th><? echo "$font";?>connectionMode<? echo "$fontend";?></th>-->
<th><? echo "$font";?>country<? echo "$fontend";?></th>
<th><? echo "$font";?>IP<? echo "$fontend";?></th>
<!-- <th><? echo "$font";?>hostname<? echo "$fontend";?></th> 
<th><? echo "$font";?>firstTime<? echo "$fontend";?></th> 
<th><? echo "$font";?>outTime<? echo "$fontend";?></th> -->
<th><? echo "$font";?>inTime<? echo "$fontend";?></th>
<!-- <th><? echo "$font";?>onlineTime<? echo "$fontend";?></th>
<th><? echo "$font";?>loginCount<? echo "$fontend";?></th>
<th><? echo "$font";?>kickCount<? echo "$fontend";?></th>
<th><? echo "$font";?>kickCountTot<? echo "$fontend";?></th>
<th><? echo "$font";?>tBanCount<? echo "$fontend";?></th>
<th><? echo "$font";?>tBanCountTot<? echo "$fontend";?></th>
<th><? echo "$font";?>pBanCount<? echo "$fontend";?></th>
<th><? echo "$font";?>pBanCountTot<? echo "$fontend";?></th>
<th><? echo "$font";?>lineCount<? echo "$fontend";?></th> 
<th><? echo "$font";?>avShareBytes<? echo "$fontend";?></th> -->
<th><? echo "$font";?>shared_bytes<br>[hover]<? echo "$fontend";?></th>
<th><? echo "$font";?>lastAction<? echo "$fontend";?></th>
<th><? echo "$font";?>lastReason<? echo "$fontend";?></th>

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
	else if($allowStatus == "allow") {echo "<TR bgcolor="; echo "$AllowRowColour"; echo ">\n";}
	else if(($allowStatus == "T-Banned") || ($allowStatus == "P-Banned")) { echo "<TR bgcolor=";echo "$BanRowColour"; echo ">\n";}
	else if($kickCount != 0) {echo "<TR bgcolor="; echo "$KickRowColour"; echo ">\n";}
	else if($i % 2)	{echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";}
	else{echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n";}

	?>
	
	
<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$nick$fontend"; ?></a></td>
<!-- <td nowrap><? echo "$font$passwd$fontend"; ?></td> -->
<td nowrap><? echo "$font$status$fontend"; ?></td>
<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$uType$fontend"; ?></a></td>
<!--<td nowrap><? echo "$font$type$fontend"; ?></td> -->
<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$allowStatus$fontend"; ?></a></td>
<!--<td nowrap><? echo "$font$awayStatus$fontend"; ?></td>-->
<!-- <td nowrap><? echo "$font$awayMSg$fontend"; ?></td> -->
<!-- <td nowrap><? echo "$font$fullDescription$fontend"; ?></td>-->
<!--<td nowrap><? echo "$font$dcClient$fontend"; ?></td>
<td nowrap><? echo "$font$dcVersion$fontend"; ?></td>
<td nowrap><? echo "$font$slots$fontend"; ?></td>
<td nowrap><? echo "$font$hubs$fontend"; ?></td>
<td nowrap><? echo "$font$limiter$fontend"; ?></td> -->
<td nowrap><? echo "$font$connection$fontend"; ?></td>
<!-- <td nowrap><? echo "$font$connectionMode$fontend"; ?></td> -->
<td nowrap><? echo "$font$country$fontend"; ?></td>
<td nowrap><? echo "$font$IP$fontend"; ?></td>
<!-- <td nowrap><? echo "$font$hostname$fontend"; ?></td>
<td nowrap><? echo "$font$firstTime$fontend"; ?></td>
<td nowrap><? echo "$font$outTime$fontend"; ?></td> -->
<td nowrap><? echo "$font$inTime$fontend"; ?></td>
<!--<td nowrap><? echo "$font$onlineTime$fontend"; ?></td>
<td nowrap><? echo "$font$loginCount$fontend"; ?></td>
<td nowrap><? echo "$font$kickCount$fontend"; ?></td>
<td nowrap><? echo "$font$kickCountTot$fontend"; ?></td>
<td nowrap><? echo "$font$tBanCount$fontend"; ?></td>
<td nowrap><? echo "$font$tBanCountTot$fontend"; ?></td>
<td nowrap><? echo "$font$pBanCount$fontend"; ?></td>
<td nowrap><? echo "$font$pBanCountTot$fontend"; ?></td>
<td nowrap><? echo "$font$lineCount$fontend"; ?></td>
<td nowrap><? echo "$font$avShareBytes$fontend"; ?></td> -->
<td nowrap><a title="<? echo "$Share" ?>" style="cursor:help"><? echo "$font$byteShare$fontend"; ?></a></td>
<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$lastAction$fontend"; ?></a></td> 
<td nowrap><? echo "$font$lastReason$fontend"; ?></td>
	
	</tr>
	<?
	$i++;
} 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"user-manage.php?offset=$prevoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"user-manage.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"user-manage.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">NEXT</a><p>\n";
}

?></div>
<? echo "$fontend";?>
</body>
</html>
