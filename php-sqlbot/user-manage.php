<?php  $page_title="User Management";include("header.ini");?>

<div align="center"><?php 
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

if ($f == parselog)
	{$sql = "DELETE FROM userDB WHERE loginCount='1' && pBanCountTot='0' && status='Offline' && uType='User'"; $result = mysql_query($sql) or die(mysql_error());}
if ($f == delete)
	{$sql = "DELETE FROM userDB $where";$result = mysql_query($sql) or die(mysql_error());}
if ($f == uType){
	$cleannick =  	mysql_escape_string($nick);
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid',$uType,'$cleannick','$ip','$passwd')";
	$result = mysql_query($sql) or die(mysql_error());}
else if ($f == aStatus){
	$cleannick =  	mysql_escape_string($nick);
	$sql = "INSERT INTO botWorker VALUES ('mysql_insertid','$aStatus','$cleannick','$ip','$information')";
	$result = mysql_query($sql) or die(mysql_error());}
	
if (empty($offset)) {$offset=0;}
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$numresult=mysql_query("SELECT * FROM userDB $where");
$numrows=mysql_num_rows($numresult);
$result=mysql_query("SELECT * FROM userDB $where ORDER by $orderby LIMIT $offset,$defaultLogEntries");
mysql_close();
?>
<table border="<?php  echo $tableBorders;?>" cellspacing="2" cellpadding="2">
<tr>
	<th> Preset Filters</th>
	<th><form action="<?php  echo "user-manage.php?field=status&search=Online" ?>" method="post">
	<input type="Submit" value="Online"></form></th>
	<th><form action="<?php  echo "user-manage.php?field=lastAction&search=Kicked" ?>" method="post">
	<input type="Submit" value="Show Kicked"></form></th>
	<th><form action="<?php  echo "user-manage.php?field=lastReason&search=Fake" ?>" method="post">
	<input type="Submit" value="Show Fakers"></form></th>
	<th><form action="<?php  echo "user-manage.php?field=allowStatus&search=Banned" ?>" method="post">
	<input type="Submit" value="Show Banned"></form></th>
	</tr><tr>
	<th><?php  echo " Filters Applied $font$field $search$fontend";?></th>
	<th><form action="<?php  echo "user-manage.php" ?>" method="post">
	<input type="Submit" value="Reset Filters"></form></th>
	<th><form action="<?php  echo "user-manage.php?f=delete&field=$field&search=$search" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()" title="Delete all in selection"></form></th>
	
	<th><form action="<?php  echo "user-manage.php?f=parselog" ?>" method="post">
	<input type="Submit" value="Delete Single Entries" onClick="return confirmLogParse()"></form>
	</th>
	</tr><tr>
	<th><form action="<?php  echo "user-manage.php?field=nick&search=$search" ?>" method="post">
	<input type="text" name="search" value=""><?php  echo $font;?></th><th>
	<input type="Submit" value="Nick Search"></form></th>
</tr>
</table><?php 
echo "Totals :Users $numrows<br>";
?>
<table border="<?php  echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr>
<th><a href="<?php  echo "user-manage.php?order=nick" ?>"> <?php  echo $font;?>Nick<?php  echo $fontend;?></a></th>
<th><?php  echo $font;?>Status<?php  echo "$fontend";?></th>
<th><a href="<?php  echo "user-manage.php?order=uType" ?>"> <?php  echo $font;?>Type<?php  echo "$fontend";?></a></th>
<th><?php  echo $font;?>Client<?php  echo "$fontend";?></th>
<th><?php  echo $font;?>Allowed?<?php  echo "$fontend";?></th>
 <th><a href="<?php  echo "user-manage.php?order=connection" ?>"> <?php  echo $font;?>Connection<?php  echo "$fontend";?></a></th>
 <th><a href="<?php  echo "user-manage.php?order=country" ?>"> <?php  echo $font;?>Country<?php  echo "$fontend";?></a></th>
<th><a href="<?php  echo "user-manage.php?order=IP" ?>"> <?php  echo $font;?>IP<?php  echo "$fontend";?></a></th>
<th><a href="<?php  echo "user-manage.php?order=inTime" ?>"> <?php  echo $font;?>Checkin Time<?php  echo "$fontend";?></a></th>
 <th><a href="<?php  echo "user-manage.php?order=shareByte" ?>"> <?php  echo $font;?>Shared_Bytes<?php  echo "$fontend";?></a></th>
 <th><a href="<?php  echo "user-manage.php?order=lastAction" ?>"> <?php  echo $font;?>Last Action<?php  echo "$fontend";?></a></th>
 <th><a href="<?php  echo "user-manage.php?order=lastReason" ?>"> <?php  echo $font;?>Last Reason<?php  echo "$fontend";?></a></th>

</tr>
<?php 

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
	$dcVersion=mysql_result($result,$i,"dcVersion");
	$slots=mysql_result($result,$i,"slots");
	$hubs=mysql_result($result,$i,"hubs");
	$connectionMode=mysql_result($result,$i,"connectionMode");
	$limiter=mysql_result($result,$i,"limiter");
		if ($limiter == "0") {$limiter = "none";}
	$dcClient=mysql_result($result,$i,"dcClient");
	
	$default_popup="$dcClient $dcVersion  Hubs=$hubs  Slots=$slots  Limiter=$limiter  Mode=$connectionMode";
	
	if ( $dcClient == "No Tag" ) { $icoClient = NoTag ; $popup = "No Tag";}
	if ( $dcClient == "++" ) { $icoClient = DCpp ; $popup=$default_popup;}
	if ( $dcClient == "DC" ) { $icoClient = DC ; $popup=$default_popup;}			
	if ( $dcClient == "DCGUI" ) { $icoClient = DCGUI ; $popup=$default_popup;}



	$connection=mysql_result($result,$i,"connection");
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
	else if($uType == "Registered") {echo "<TR bgcolor="; echo "$RegRowColour"; echo ">\n";}
	else if($allowStatus == "Allow") {echo "<TR bgcolor="; echo "$AllowRowColour"; echo ">\n";}
	else if(($allowStatus == "Banned")) { echo "<TR bgcolor=";echo "$BanRowColour"; echo ">\n";}
	else if($kickCount != 0) {echo "<TR bgcolor="; echo "$KickRowColour"; echo ">\n";}
	else if($i % 2)	{echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";}
	else{echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n";}

	?>
	
	
<td nowrap><a href="<?php  echo "user-type.php?nick=$nick&IP=$IP" ?>"> <?php  echo "$font$nick$fontend"?></a></td>
<td nowrap><div align="center"><a href="<?php  echo "user-manage.php?field=status&search=$status" ?>" title="Search for users <?php  echo "$status"?>"><img src="img/user/<?php  echo "$status" ?>.gif" border="0"></div></a> 
</td>
<td nowrap><div align="center"><a href="<?php  echo "user-manage.php?field=uType&search=$uType" ?>" title="Search for all <?php  echo "$uType" ?>s"><img src="img/user/<?php  echo "$uType" ?>.gif" border="0"></a></div></td>

<td nowrap>
	<div align="center"><a title="<?php  echo "$popup" ?>" style="cursor:help"><img src="img/clients/<?php  echo "$icoClient" ?>.gif" border="0"></a>
	</div>
</td>

<td nowrap><a href="<?php  echo "user-manage.php?field=allowStatus&search=$allowStatus" ?>"><?php  echo "$font$allowStatus$fontend"; ?></a></td>
<td nowrap><a href="<?php  echo "user-manage.php?field=connection&search=$connection" ?>"><?php  echo "$font$connection$fontend"; ?></a></td>
<td nowrap><center><a href="user-manage.php?field=country&search=<?php  echo "$country"?>"  title="Search for all users from: <?php  echo "$country"?>">
<img src="img/flags/<?php  echo "$country" ?>.GIF" alt="<?php  echo "$country" ?>" border="0" title="<?php  echo "$country" ?>"></a></center></td>

<td nowrap><a href="user-manage.php?field=IP&search=<?php  echo "$IP"?>" title="Search for all users with: <?php  echo "$IP"?>"><?php  echo "$font$IP$fontend"; ?></a></td>
<td nowrap><?php  echo "$font$inTime$fontend"; ?></td>
<td nowrap><a href="user-manage.php?field=shareByte&search=<?php  echo "$byteShare"?>" title="<?php  echo "$Share" ?>" style="cursor:help"><?php  echo "$font$byteShare$fontend"; ?></a></td>
<td nowrap><a href="<?php  echo "user-manage.php?field=lastAction&search=$lastAction" ?>"><?php  echo "$font$lastAction$fontend"; ?></a></td>
<td nowrap><a href="<?php  echo "user-manage.php?field=lastReason&search=$lastReason" ?>"><?php  echo "$font$lastReason$fontend"; ?></a></td>
<?php  if ($allowStatus == Banned)
{?>
<td>
<form action="<?php  echo "user-manage.php?f=aStatus&field=$field&search=$search&nick=$nick&ip=$IP&aStatus=23&information=Un-ban";?>" method="post">
<input type="Submit" value="UnBan"></form>
</td>
<?php  }?>	
	</tr>
	<?php 
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
<?php  echo "$fontend";?>
</body>
</html>

