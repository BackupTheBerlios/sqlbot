<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
	include("conf/dbinfo.inc.php");
	include("conf/forms.php");
// CONNECT TO MYSQL SERVER
	mysql_connect($databasehost,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");

if ($hubID == "") {
echo "Error... no hubID parsed!<p>Please return to the <a href=\"index.php\"><font color=\"blue\">index.php</font></a>";}
else {
	





// $hubID = "1";
// MySQL Connection
$result=mysql_query("SELECT * FROM hubConfig WHERE hubID='$hubID'");
	$hubID=htmlentities(mysql_result($result,$i,"hubID"));
	$hcPwd=htmlentities(mysql_result($result,$i,"hcPwd"));
	$hcStatus=htmlentities(mysql_result($result,$i,"hcStatus"));
	$hcAutoConnect=mysql_result($result,$i,"hcAutoConnect");
	$hcName=htmlentities(mysql_result($result,$i,"hcName"));
	$hcDescription=htmlentities(mysql_result($result,$i,"hcDescription"));
	$hcHost=mysql_result($result,$i,"hcHost");
	$hcMaxUsers=mysql_result($result,$i,"hcMaxUsers");
	$hcMinShare=mysql_result($result,$i,"hcMinShare");
	$hcMinShareMultiplier=mysql_result($result,$i,"hcMinShareMultiplier");
	$hcRedirectHost=mysql_result($result,$i,"hcRedirectHost");
	$hcTempBan=mysql_result($result,$i,"hcTempBan");
	$hcTempBanMultiplier=mysql_result($result,$i,"hcTempBanMultiplier");
	$hcShareCheckTimeout=mysql_result($result,$i,"hcShareCheckTimeout");
	$hcShareCheckTimeoutMultiplier=mysql_result($result,$i,"hcShareCheckTimeoutMultiplier");
	$hcOwner=htmlentities(mysql_result($result,$i,"hcOwner"));
	$hcSoftware=htmlentities(mysql_result($result,$i,"hcSoftware"));
	$hcVersion=htmlentities(mysql_result($result,$i,"hcVersion"));
	$hcMotd=htmlentities(mysql_result($result,$i,"hcMotd"));
	$hcMinConnection=mysql_result($result,$i,"hcMinConnection");
	$hcMinSlots=mysql_result($result,$i,"hcMinSlots");
	$hcMaxSlots=mysql_result($result,$i,"hcMaxSlots");
	$hcMaxHubs=mysql_result($result,$i,"hcMaxHubs");
	$hcSlotRatio=mysql_result($result,$i,"hcSlotRatio");
	$hcEnableTagCheck=mysql_result($result,$i,"hcEnableTagCheck");
	$hcKickNoTag=mysql_result($result,$i,"hcKickNoTag");
	$hcLogChat=mysql_result($result,$i,"hcLogChat");
	$hcLogSearches=mysql_result($result,$i,"hcLogSearches");
	$hcLogSystem=mysql_result($result,$i,"hcLogSystem");
	$hcFileListDl=mysql_result($result,$i,"hcFileListDl");

// ADD ONLINE/OFFLINE COLOUR
if ($hcStatus == "Online"){
$hcStatus = "<font color=\"#000000\"><strong>Online</strong></font>";
}
if ($hcStatus == "Offline"){
$hcStatus = "<font color=\"#FF1D28\"><strong>Offline</strong></font>";
}

?>

<!-- TOP BANNER -->
<table class="top">
	<tr>
		<td align="center" >C++ SqlBOT</td>
	</tr>
</table>
<!-- END TOP BANNER -->

<!-- MAIN TABLE -->
<table cellpadding="0" cellspacing="0" class="main">
	<tr>
		<td class="menu"><!-- MAIN MENU -->
				<form action="<?php echo "index.php"; ?>" method="post">
				<input type="submit" value="Back to hubs..." class="button">
				</form>
				<p>
				<?php include("conf/mainmenu.php"); ?><p>
				<?php include("conf/udbmenu.php"); ?>
		</td>
		<td class="main"><!-- MAIN BODY -->
		
<table cellpadding="0" cellspacing="0" class="userdbarea">
<tr>
<td>
	<!-- START USER DATABACE SPACE -->
<?php
if ($parse == "All") { $parseoption = ""; }
if ($parse == "Online") { $parseoption = "&& uiStatus='1'"; }
if ($parse == "Fakers") { $parseoption = ""; }

// GET TOTAL USERS IN DB 
$total_result=mysql_query("SELECT * FROM userInfo WHERE hubID='$hubID'");
$total_users=mysql_num_rows($total_result);

// LIMIT $defaultLogEntries ORDER BY uiNick
$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption ORDER BY uiIsAdmin  DESC,uiNick");
$total_selection=mysql_num_rows($userresult);

?>
<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Selected users: [ <?php echo "$parse"; ?> ] &nbsp; Selected:
					[ <?php echo "$total_selection / $total_users"; ?> ] &nbsp; </font></LEGEND>	
	
	<table class="userdb">
	<tr nowrap>
		<th>User Nick</th>
		<th>Rank</th>
		<th>Client</th>
		<th>Connection</th>
		<th>IP</th>
		<th>CheckIn</th>
		<th>Shared Bytes</th>
	</tr>
<?php
while ($data=mysql_fetch_array($userresult)) 
{

	$rowID=mysql_result($userresult,$i,"rowID");
	$uiNick=htmlentities(mysql_result($userresult,$i,"uiNick"));
	$uiIp=mysql_result($userresult,$i,"uiIp");
	$uiHost=mysql_result($userresult,$i,"uiHost");
	$uiIsAway=mysql_result($userresult,$i,"uiIsAway");
	$uiStatus=mysql_result($userresult, $i, "uiStatus");
	$HubID=mysql_result($userresult,$i,"HubID");
	$uiCountry=mysql_result($userresult,$i,"uiCountry");
	$uiIsAdmin=mysql_result($userresult,$i,"uiIsAdmin");
	$uiPassword=htmlentities(mysql_result($userresult,$i,"uiPassword"));
	$uiShare=mysql_result($userresult,$i,"uiShare");
	$uiTag=htmlentities(mysql_result($userresult,$i,"uiTag"));
	$uiClient=mysql_result($userresult,$i,"uiClient");
	$uiTClient=mysql_result($userresult,$i,"uiTClient");
	$uiDescription=htmlentities(mysql_result($userresult,$i,"uiDescription"));
	$uiVersion=mysql_result($userresult,$i,"uiVersion");
	$uiMode=mysql_result($userresult,$i,"uiMode");
	$uiHubs=mysql_result($userresult,$i,"uiHubs");
	$uiHubsOp=mysql_result($userresult,$i,"uiHubsOp");
	$uiHubsReg=mysql_result($userresult,$i,"uiHubsReg");
	$uiHubsNorm=mysql_result($userresult,$i,"uiHubsNorm");
	$uiSlots=mysql_result($userresult,$i,"uiSlots");
	$uiLimiter=mysql_result($userresult,$i,"uiLimiter");
	$uiSpeed=mysql_result($userresult,$i,"uiSpeed");
	$uiFirstSeenTime=mysql_result($userresult,$i,"uiFirstSeenTime");
	$uiLastSeenTime=mysql_result($userresult,$i,"uiLastSeenTime");
	$uiTimeOnline=mysql_result($userresult,$i,"uiTimeOnline");
	$uiTotalSearches=mysql_result($userresult,$i,"uiTotalSearches");
	$uiKickTotal=mysql_result($userresult,$i,"uiKickTotal");
	$uiBanTotal=mysql_result($userresult,$i,"uiBanTotal");
	$uiSayTotal=mysql_result($userresult,$i,"uiSayTotal");
	$uiShareChckd=mysql_result($userresult,$i,"uiShareChckd");
	$uiShareChckdStart=mysql_result($userresult,$i,"uiShareChckdStart");
	$uiShareChckdExpire=mysql_result($userresult,$i,"uiShareChckdExpire");
	$uiBanFlag=mysql_result($userresult,$i,"uiBanFlag");
	$uiBanTime=mysql_result($userresult,$i,"uiBanTime");
	$uiBanExpire=mysql_result($userresult,$i,"uiBanExpire");
	$uiLoginCount=mysql_result($userresult,$i,"uiLoginCount");

	$conv_time=mysql_result($userresult,$i,"date");
	if ($uiClient == "Unknown") { $uiClient = "<img src=\"img/clients/NoTag.gif\" alt=\"\">"; }
	if ($uiClient == "DCGUI") { $uiClient = "<img src=\"img/clients/DCGUI.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if ($uiClient == "++") { $uiClient = "<img src=\"img/clients/DCpp.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if ($uiClient == "DC") { $uiClient = "<img src=\"img/clients/DC.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if  ($uiStatus == "1") { $uiStatus ="<img src=\"img/Online.gif\" alt=\"Online\">";}
	if  ($uiStatus == "0") { $uiStatus ="<img src=\"img/Offline.gif\" alt=\"Offline\">";}

	echo "<tr>
		<td nowrap>$uiStatus $uiNick</td>
		<td nowrap align=\"center\">$uiIsAdmin</td>
		<td nowrap align=\"center\">$uiClient</td>
		<td nowrap align=\"center\">$uiSpeed</td>
		<td nowrap align=\"center\">$uiIp</td>
		<td nowrap align=\"center\">$conv_time</td>
		<td nowrap align=\"center\">$uiShare</td>
	</tr>";
	$i++;
}
	
	
	
	
?>
	</table>
	
	
	<!-- END USER DATABACE SPACE -->
	</FIELDSET>
</td>
</tr>
</table>


		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close(); }?>
</body>
</html>