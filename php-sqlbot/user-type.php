<?php  $page_title="Users Ban/Allow/Kick Settings";include("header.ini");?>

<div align="center"><?php 
$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$where = "";
if (!empty($nick))  
	{$where="WHERE nick='$nick' AND IP='$IP'";}
else {$where = ""; }




$numresults=mysql_query("SELECT * FROM userDB $where");
$numrows=mysql_num_rows($numresults);
if (!empty($numrows)){
$result=mysql_query("SELECT * FROM userDB $where ORDER by inTime DESC LIMIT $defaultLogEntries");

mysql_close();
if (!empty($numrows)){
	$nick=htmlentities(mysql_result($result,$i,"nick"));
	$allowStatus=mysql_result($result,$i,"allowStatus");
	$status=mysql_result($result,$i,"status");
	$inTime=mysql_result($result,$i,"inTime");
	$loginCount=mysql_result($result,$i,"loginCount");
	$uType=mysql_result($result,$i,"uType");
	$IP=mysql_result($result,$i,"IP");
	$hostname=mysql_result($result,$i,"hostname");
	$country=mysql_result($result,$i,"country");
	$fullDescription=htmlentities(mysql_result($result,$i,"fullDescription"));
	$dcClient=mysql_result($result,$i,"dcClient");
	$dcVersion=mysql_result($result,$i,"dcVersion");
	$slots=mysql_result($result,$i,"slots");
	$hubs=mysql_result($result,$i,"hubs");
	$limiter=mysql_result($result,$i,"limiter");
	$connection=mysql_result($result,$i,"connection");
	$connectionMode=mysql_result($result,$i,"connectionMode");
	$byteShare=mysql_result($result,$i,"shareByte");
	$averageShare=mysql_result($result,$i,"avShareBytes");
	$kickCountTot=mysql_result($result,$i,"kickCountTot");
	$kickCount=mysql_result($result,$i,"kickCount");
	$tBanCountTot=mysql_result($result,$i,"tBanCountTot");
	$tBanCount=mysql_result($result,$i,"tBanCount");
	$pBanCountTot=mysql_result($result,$i,"pBanCountTot");
	$pBanCount=mysql_result($result,$i,"pBanCount");
	$lastReason=mysql_result($result,$i,"lastReason");
	$lastAction=mysql_result($result,$i,"lastAction");
	$totalMessages=mysql_result($result,$i,"lineCount");
	$firstLogin=mysql_result($result,$i,"firstTime");
	$totalOnline=mysql_result($result,$i,"onlineTime");
}
	if ($status=="Online") {$status1="<font color=\"#1C8510\">Online</font>";};
	if ($status=="Offline") {$status1="<font color=\"#FF1124\">Offline</font>";};
	if ($uType=="Operator") {$uType1="<strong><font color=\"#0100FF\">Operator</font></strong>";};
	if ($uType=="Op-Admin") {$uType1="<strong><font color=\"#0100FF\"><u>Op-Admin</u></font></strong>";};
	if ($uType=="Registered") {$uType1="<strong>Registered</strong>";};
	if ($uType=="User") {$uType1="<font color=\"#7F7F7F\">User</font>";};
	
	// Code to parse bytes to other
	if (($byteShare / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024 / 1024), 2); $Share="$Shared TB";}
	else if (($byteShare / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024), 2); $Share="$Shared GB";}
	else if (($byteShare / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024), 2); $Share="$Shared MB";}
	else if (($byteShare / 1024) > 1) { $Shared=round(($byteShare / 1024), 2); $Share="$Shared KB";};
	
	if (($averageShare / 1024 / 1024 / 1024 / 1024) > 1) { $avShared=round(($averageShare / 1024 / 1024 / 1024 / 1024), 2); $avShare="$avShared TB";}
	else if (($averageShare / 1024 / 1024 / 1024) > 1) { $avShared=round(($averageShare / 1024 / 1024 / 1024), 2); $avShare="$avShared GB";}
	else if (($averageShare / 1024 / 1024) > 1) { $avShared=round(($averageShare / 1024 / 1024), 2); $avShare="$avShared MB";}
	else if (($averageShare / 1024) > 1) { $avShared=round(($averageShare / 1024), 2); $avShare="$avShared KB";};


?>

<table border="<?php  echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr>
<td align="center"><strong><font color="#0100FF"><?php  echo "$nick"; ?></font></strong> (<?php  echo "$font$status$fontend"; ?>)</td></tr>
<tr>
<table border="1">
<tr>
<td align="center" class="top_table"><strong>User Details</strong></td>
<td align="center" class="top_table"><strong>Client Details</strong></td>
</tr>
<tr>
<td valign="top">
<table border="<?php  echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr><td><?php  echo "$font";?>Allow Status:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$allowStatus$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Total Logins:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$loginCount$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>User Type:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$uType1$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>IP:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$IP$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Hostname:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$hostname$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Country:<?php  echo "$fontend";?></td><td nowrap><img src="img/flags/<?php  echo "$country" ?>.GIF" alt="<?php  echo "$country" ?>" border="0" title="<?php  echo "$country" ?>"> <?php  echo "$font$country$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Current Kick Count:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$kickCount$fontend"; ?></td></tr> 
<tr><td><?php  echo "$font";?>Total Kicks:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$kickCountTot$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Total pBans:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$pBanCountTot$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Current tBan Count:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$tBanCount$fontend"; ?></td></tr> 
<tr><td><?php  echo "$font";?>Total tBans:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$tBanCountTot$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Last Action:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$lastAction$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Last Reason:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$lastReason$fontend"; ?></td></tr>
</table>
</td>
<td valign="top">
<table border="<?php  echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr><td><?php  echo "$font";?>Client Name:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$dcClient$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Client Version:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$dcVersion$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Full Description:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$fullDescription$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Slots:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$slots$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Hubs:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$hubs$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Limiter:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$limiter KB/s$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Connection:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$connection$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Connection Mode:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$connectionMode$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Current Share:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$byteShare bytes ($Share)$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Average Share:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$averageShare bytes ($avShare)$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Total Lines Spoken:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$totalMessages lines$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Last Login:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$inTime$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>First Login:<?php  echo "$fontend";?></td><td nowrap><?php  echo "$font$firstLogin$fontend"; ?></td></tr>
<tr><td><?php  echo "$font";?>Total time on hub:<?php  echo "$fontend";?></td><td nowrap><?php 
function mod($a, $b) {
 return ((($a % $b) + $b) % $b);
}
	$days= $totalOnline/86400;
        $time=date("H:i:s", mktime(0,0,$totalOnline));echo "$font";echo (int)($days); echo "days  $time h:m:s ($totalOnline Seconds)$fontend"; 
?></td></tr>
</table>
</td>
</tr>
<?php  if ($uType == "Op-Admin") {echo "</table>User is OP-Admin, Op level and Ban settings Cannot be Modified.<br> Adminstration of OP-Admin should be performed with OpendcHub commands";}else{?>

<tr>
<td align="center" class="top_table"><strong>Modify Type</strong></td>
<td align="center" class="top_table"><strong>Modify User Status</strong></td>
</tr>
<td valign="top">
<table>

<form action="<?php  echo "user-manage.php?f=uType&nick=$nick&ip=$IP";?>" method="post">
<tr><td><input type="radio" name="uType" value=31 <?php  if ($uType == "Operator") echo"checked=\"true\"";?> ><?php  echo "$font";?>Operator (Requires Password)</font><br></td></tr>
<tr><td><input type="radio" name="uType" value=32 <?php  if ($uType == "Registered") echo"checked=\"true\"";?> ><?php  echo "$font";?>Register (Requires Password)</font></td></tr>
<tr><td><input type="radio" name="uType" value=33 <?php  if ($uType == "User") echo"checked=\"true\"";?> ><?php  echo "$font";?>Un-Register</font></td></tr>
<tr><td><input type="text" name="passwd" value=""><?php  echo "$font";?>Password</font></td></tr>
<tr><td><input type="Submit" value="Submit changes"></tr></td></form>
</table>

</td>
<td valign="top">
<table>
<form action="<?php  echo "user-manage.php?f=aStatus&nick=$nick&ip=$IP";?>" method="post">
<tr><td><input type="radio" name="aStatus" value=21 <?php  if ($allowStatus == "Banned" && $lastAction == "P-Banned" && $lastReason != "Fake(Share)" && $lastReason !="Fake(Tag)")  echo"checked=\"true\"";?>><?php  echo "$font";?>Permanent Ban - Place a permanent Ban</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=22 <?php  if ($allowStatus == "Banned" && $lastAction == "T-Banned" && $lastReason != "Fake(Share)" && $lastReason !="Fake(Tag)")  echo"checked=\"true\"";?>><?php  echo "$font";?>Temporary Ban - Place under temporary Ban</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=27 <?php  if ($allowStatus == "Normal") echo"checked=\"true\"";?>><?php  echo "$font";?>Reset Kick & Ban Counters</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=23 <?php  if ($allowStatus == "Normal") echo"checked=\"true\"";?>><?php  echo "$font";?>Normal (UnBan) & Reset Kick Counter</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=10 ><?php  echo "$font";?>Kick User</font></td></tr>
<tr><td><input type="Text" size="50" name="information" value=""></tr></td>
<tr><td></tr></td>
<tr><td><input type="radio" name="aStatus" value=25 <?php  if ($allowStatus == "Banned" && $lastReason == "Fake(Share)") echo"checked=\"true\"";?>><?php  echo "$font";?>Fake Share</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=24 <?php  if ($allowStatus == "Banned" && $lastReason == "Fake(Tag)") echo"checked=\"true\"";?>><?php  echo "$font";?>Fake Tag</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=50 <?php  if ($allowStatus == "Allow") echo"checked=\"true\"";?>><a title="Useful for Hub List Bots" style="cursor:help"><?php  echo "$font";?>Allow - No logging or client checks</font></a></td></tr>

<tr><td><input type="Submit" value="Submit changes"></tr></td></form>
</table>
</table>
</td></tr>
</table>
<?php  }
}
else{
?>
<br><br>
<table>
<tr>
This User record has been updated by another user  with the same IP.
<?php 

}
?>
</tr>
</table>

</div>

<?php  echo "$fontend";?>
</body>
</html>
