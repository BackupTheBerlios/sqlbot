<? $page_title="Users Ban/Allow/Kick Settings";include("header.ini");?>

<div align="center"><?
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

<table border="<? echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr>
<td align="center"><strong><font color="#0100FF"><? echo "$nick"; ?></font></strong> (<? echo "$font$status$fontend"; ?>)</td></tr>
<tr>
<table border="1">
<tr>
<td align="center" class="top_table"><strong>User Details</strong></td>
<td align="center" class="top_table"><strong>Client Details</strong></td>
</tr>
<tr>
<td valign="top">
<table border="<? echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr><td><? echo "$font";?>Allow Status:<? echo "$fontend";?></td><td nowrap><? echo "$font$allowStatus$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Total Logins:<? echo "$fontend";?></td><td nowrap><? echo "$font$loginCount$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>User Type:<? echo "$fontend";?></td><td nowrap><? echo "$font$uType1$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>IP:<? echo "$fontend";?></td><td nowrap><? echo "$font$IP$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Hostname:<? echo "$fontend";?></td><td nowrap><? echo "$font$hostname$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Country:<? echo "$fontend";?></td><td nowrap><? echo "$font$country$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Current Kick Count:<? echo "$fontend";?></td><td nowrap><? echo "$font$kickCount$fontend"; ?></td></tr> 
<tr><td><? echo "$font";?>Total Kicks:<? echo "$fontend";?></td><td nowrap><? echo "$font$kickCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Total pBans:<? echo "$fontend";?></td><td nowrap><? echo "$font$pBanCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Current tBan Count:<? echo "$fontend";?></td><td nowrap><? echo "$font$tBanCount$fontend"; ?></td></tr> 
<tr><td><? echo "$font";?>Total tBans:<? echo "$fontend";?></td><td nowrap><? echo "$font$tBanCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Last Action:<? echo "$fontend";?></td><td nowrap><? echo "$font$lastAction$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Last Reason:<? echo "$fontend";?></td><td nowrap><? echo "$font$lastReason$fontend"; ?></td></tr>
</table>
</td>
<td valign="top">
<table border="<? echo "$tableBorders";?>" cellspacing="2" cellpadding="2">
<tr><td><? echo "$font";?>Client Name:<? echo "$fontend";?></td><td nowrap><? echo "$font$dcClient$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Client Version:<? echo "$fontend";?></td><td nowrap><? echo "$font$dcVersion$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Full Description:<? echo "$fontend";?></td><td nowrap><? echo "$font$fullDescription$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Slots:<? echo "$fontend";?></td><td nowrap><? echo "$font$slots$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Hubs:<? echo "$fontend";?></td><td nowrap><? echo "$font$hubs$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Limiter:<? echo "$fontend";?></td><td nowrap><? echo "$font$limiter KB/s$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Connection:<? echo "$fontend";?></td><td nowrap><? echo "$font$connection$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Connection Mode:<? echo "$fontend";?></td><td nowrap><? echo "$font$connectionMode$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Current Share:<? echo "$fontend";?></td><td nowrap><? echo "$font$byteShare bytes ($Share)$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Average Share:<? echo "$fontend";?></td><td nowrap><? echo "$font$averageShare bytes ($avShare)$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Total Lines Spoken:<? echo "$fontend";?></td><td nowrap><? echo "$font$totalMessages lines$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Last Login:<? echo "$fontend";?></td><td nowrap><? echo "$font$inTime$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>First Login:<? echo "$fontend";?></td><td nowrap><? echo "$font$firstLogin$fontend"; ?></td></tr>
<!--<tr><td><? echo "$font";?>Total time on hub:<? echo "$fontend";?></td><td nowrap><? echo "$font$totalOnline$fontend"; ?></td></tr>-->
</table>
</td>
</tr>
<? if ($uType == "Op-Admin") {echo "</table>User is OP-Admin, Op level and Ban settings Cannot be Modified.<br> Adminstration of OP-Admin should be performed with OpendcHub commands";}else{?>

<tr>
<td align="center" class="top_table"><strong>Modify Type</strong></td>
<td align="center" class="top_table"><strong>Modify User Status</strong></td>
</tr>
<td valign="top">
<table>

<form action="<? echo "user-manage.php?f=uType&nick=$nick&ip=$IP";?>" method="post">
<tr><td><input type="radio" name="uType" value=31 <? if ($uType == "Operator") echo"checked=\"true\"";?> ><? echo "$font";?>Operator (Requires Password)</font><br></td></tr>
<tr><td><input type="radio" name="uType" value=32 <? if ($uType == "Registered") echo"checked=\"true\"";?> ><? echo "$font";?>Register (Requires Password)</font></td></tr>
<tr><td><input type="radio" name="uType" value=33 <? if ($uType == "User") echo"checked=\"true\"";?> ><? echo "$font";?>Un-Register</font></td></tr>
<tr><td><input type="text" name="passwd" value=""><? echo "$font";?>Password</font></td></tr>
<tr><td><input type="Submit" value="Submit changes"></tr></td></form>
</table>

</td>
<td valign="top">
<table>
<form action="<? echo "user-manage.php?f=aStatus&nick=$nick&ip=$IP";?>" method="post">
<tr><td><input type="radio" name="aStatus" value=21 <? if ($allowStatus == "Banned" && $lastAction == "P-Banned" && $lastReason != "Fake(Share)" && $lastReason !="Fake(Tag)")  echo"checked=\"true\"";?>><? echo "$font";?>Permanent Ban - Place a permanent Ban</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=22 <? if ($allowStatus == "Banned" && $lastAction == "T-Banned" && $lastReason != "Fake(Share)" && $lastReason !="Fake(Tag)")  echo"checked=\"true\"";?>><? echo "$font";?>Temporary Ban - Place under temporary Ban</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=25 <? if ($allowStatus == "Banned" && $lastReason == "Fake(Share)") echo"checked=\"true\"";?>><? echo "$font";?>Fake Share</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=24 <? if ($allowStatus == "Banned" && $lastReason == "Fake(Tag)") echo"checked=\"true\"";?>><? echo "$font";?>Fake Tag</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=20 <? if ($allowStatus == "allow") echo"checked=\"true\"";?>><a title="Useful for Hub List Bots" style="cursor:help"><? echo "$font";?>Allow - No logging or client checks</font></a></td></tr>
<tr><td><input type="radio" name="aStatus" value=23 <? if ($allowStatus == "Normal") echo"checked=\"true\"";?>><? echo "$font";?>Normal (UnBan) & Reset Kick Counter</font></td></tr>
<tr><td><input type="radio" name="aStatus" value=10 ><? echo "$font";?>Kick User</font></td></tr>
<tr><td><input type="Submit" value="Submit changes"></tr></td></form>
</table>
</table>
</td></tr>
</table>
<?}
}
else{
?>
<br><br>
<table>
<tr>
This User record has been updated by another user  with the same IP.
<?

}
?>
</tr>
</table>

</div>

<? echo "$fontend";?>
</body>
</html>
