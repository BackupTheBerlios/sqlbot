<? $page_title="Users Ban/Allow/Kick Settings";include("header.ini");?>

<div align="center"><?
$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$where = "";
if (!empty($nicksearch))  
	{$where="WHERE nick='$nicksearch'";}
else {$where = ""; }




$numresults=mysql_query("SELECT * FROM userDB $where");
$numrows=mysql_num_rows($numresults);
if (empty($offset)) {$offset=0;}
if (!empty($numrows)){
$result=mysql_query("SELECT * FROM userDB $where ORDER by loginCount DESC LIMIT $offset,$defaultLogEntries");
}
mysql_close();
if (!empty($numrows)){
	$nick=mysql_result($result,$i,"nick");
	$allowStatus=mysql_result($result,$i,"allowStatus");
	$inTime=mysql_result($result,$i,"inTime");
	$loginCount=mysql_result($result,$i,"loginCount");
	$uType=mysql_result($result,$i,"uType");
	$IP=mysql_result($result,$i,"IP");
	$hostname=mysql_result($result,$i,"hostname");
	$country=mysql_result($result,$i,"country");
	$fullDescription=mysql_result($result,$i,"fullDescription");
	$slots=mysql_result($result,$i,"slots");
	$hubs=mysql_result($result,$i,"hubs");
	$byteShare=mysql_result($result,$i,"shareByte");
	$kickCountTot=mysql_result($result,$i,"kickCountTot");
	$kickCount=mysql_result($result,$i,"kickCount");
	$tBanCountTot=mysql_result($result,$i,"tBanCountTot");
	$tBanCount=mysql_result($result,$i,"tBanCount");
	$pBanCountTot=mysql_result($result,$i,"pBanCountTot");
	$pBanCount=mysql_result($result,$i,"pBanCount");
	$lastReason=mysql_result($result,$i,"lastReason");
	$lastAction=mysql_result($result,$i,"lastAction");
}	?>
<table border="<? echo "$tableBorders";?>" cellspacing="2" cellpadding="2">	
<tr><td><? echo "$font";?>Nick<? echo "$fontend";?></td><td nowrap><? echo "$font$nick$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Allow Status<? echo "$fontend";?></td><td nowrap><? echo "$font$allowStatus$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>In Time<? echo "$fontend";?></td><td nowrap><? echo "$font$inTime$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Total Logins<? echo "$fontend";?></td><td nowrap><? echo "$font$loginCount$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Full Description<? echo "$fontend";?></td><td nowrap><? echo "$font$fullDescription$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>User Type<? echo "$fontend";?></td><td nowrap><? echo "$font$uType$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>IP<? echo "$fontend";?></td><td nowrap><? echo "$font$IP$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Hostname<? echo "$fontend";?></td><td nowrap><? echo "$font$hostname$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Country<? echo "$fontend";?></td><td nowrap><? echo "$font$country$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Slots<? echo "$fontend";?></td><td nowrap><? echo "$font$slots$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Hubs<? echo "$fontend";?></td><td nowrap><? echo "$font$hubs$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Byte Share<? echo "$fontend";?></td><td nowrap><? echo "$font$byteShare$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Current Kick Count<? echo "$fontend";?></td><td nowrap><? echo "$font$kickCount$fontend"; ?></td></tr> 
<tr><td><? echo "$font";?>Total Kicks<? echo "$fontend";?></td><td nowrap><? echo "$font$kickCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Total pBans<? echo "$fontend";?></td><td nowrap><? echo "$font$pBanCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Current tBan Count<? echo "$fontend";?></td><td nowrap><? echo "$font$tBanCount$fontend"; ?></td></tr> 
<tr><td><? echo "$font";?>Total tBans<? echo "$fontend";?></td><td nowrap><? echo "$font$tBanCountTot$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Last Action<? echo "$fontend";?></td><td nowrap><? echo "$font$lastAction$fontend"; ?></td></tr>
<tr><td><? echo "$font";?>Last Reason<? echo "$fontend";?></td><td nowrap><? echo "$font$lastReason$fontend"; ?></td></tr>
</table>	
<table>
<? if ($uType == "Op-Admin") {echo "User is OP-Admin, Op level and Ban settings Cannot be Modified.<br> Adminstration of OP-Admin should be performed with OpendcHub commands";}else{?>
<form action="<? echo "user-manage.php?f=aStatus&nicksearch=$nick&ip=$IP";?>" method="post">
<tr><td><input type="radio" name="aStatus" value=24 <? if ($allowStatus == "Normal") echo"checked=\"true\"";?>>Add to Fakers</td></tr>

<tr><td><input type="radio" name="aStatus" value=20 <? if ($allowStatus == "allow") echo"checked=\"true\"";?>>Allow - No logging or client checks (Useful for Hub list Bots)</td></tr>
<tr><td><input type="radio" name="aStatus" value=21 <? if ($allowStatus == "P-Banned") echo"checked=\"true\"";?>>Permanent Ban - Place a permanent Ban</td></tr>
<tr><td><input type="radio" name="aStatus" value=22 <? if ($allowStatus == "T-Banned") echo"checked=\"true\"";?>>Temporary Ban - Place under temporary Ban</td></tr>
<tr><td><input type="radio" name="aStatus" value=23 <? if ($allowStatus == "Normal") echo"checked=\"true\"";?>>Normal (UnBan) & Reset Kick Counter</td></tr>
<tr><td><input type="radio" name="aStatus" value=10 >Kick User</td></tr>
<tr><td><input type="Submit" value="Submit changes"></tr></td></form>

<form action="<? echo "user-manage.php?f=uType&nicksearch=$nick&ip=$IP&in=$information";?>" method="post">
<tr><td> 
<tr><td><input type="radio" name="uType" value=31 <? if ($uType == "Operator") echo"checked=\"true\"";?> >Operator<br></td></tr>
<tr><td><input type="radio" name="uType" value=32 <? if ($uType == "Registered") echo"checked=\"true\"";?> >Register</td></tr>
<tr><td><input type="radio" name="uType" value=33 <? if ($uType == "User") echo"checked=\"true\"";?> >Remove Registration</td></tr>
<tr><td><input type="text" name="passwd" value="">Password</td></tr>
<tr><td><input type="Submit" value="Submit changes"></tr></td></form>
<?}?>
</table>
</div>

<? echo "$fontend";?>
</body>
</html>
