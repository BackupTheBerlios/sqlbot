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

if ($hubID == "" || $uiNick == "" || $uiIp == "") {
echo "$hubID ; $uiNick ; $uiIp ; Error... information parsed!<p>Please return to the <a href=\"index.php\"><font color=\"blue\">index.php</font></a>";}
else {
	
// GET BOT NAME
$botresult=mysql_query("SELECT * FROM botConfig");
	$bcName=htmlentities(mysql_result($botresult,$i,"bcName"));
	$bcMaster=htmlentities(mysql_result($botresult,$i,"bcMaster"));



// $hubID = "1";
// MySQL Connection
$result=mysql_query("SELECT * FROM hubConfig WHERE hubID='$hubID'");
	$hubID=htmlentities(mysql_result($result,$i,"hubID"));
	$hcStatus=htmlentities(mysql_result($result,$i,"hcStatus"));
	$hcName=htmlentities(mysql_result($result,$i,"hcName"));
	$hcHost=mysql_result($result,$i,"hcHost");


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


<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Detailed information about <?php echo "[ $uiNick ]"; ?> &nbsp; </font></LEGEND>	
<table class="userdb">
<?php
$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS lastdate,
									DATE_FORMAT(uiFirstSeenTime, '%d/%m/%Y %H:%i') AS firstdate FROM userInfo
									WHERE hubID='$hubID' && uiNick='$uiNick' && uiIp='$uiIp'");

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
//	$uiFirstSeenTime=mysql_result($userresult,$i,"uiFirstSeenTime");
//	$uiLastSeenTime=mysql_result($userresult,$i,"uiLastSeenTime");
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

// DATE CONVERSIONS
$uiFirstSeenTime=mysql_result($userresult,$i,"firstdate");
$uiLastSeenTime=mysql_result($userresult,$i,"lastdate");
//TIME CONVERSIONS
$days=(int)($uiTimeOnline / 86400);
if ($days > "0") { $total_days = "$days days";}
$Grandtotal = date("H:i:s", mktime(0,0,$uiTimeOnline));

//USER STATUS CONVERSIONS
if ($uiIsAway == "1") { $away = "(Away)"; }
if ($uiStatus == "1") { $online_status = "<font color=\"#23FF07\">Online</font> $away";
							$uiLastSeenTime = "Currently online";}
if ($uiStatus == "0") { $online_status = "<font color=\"#EBEBEB\">Offline</font>"; }

?>
	<tr>
		<th>User Details</th>
		<th>Client Details</th>
	</tr>
	<tr>
		<td valign="top">
			<!-- User Details -->
			<table class="userinfo">
				<tr>
					<td nowrap>Status</td>
					<td nowrap> : &nbsp; <?php echo "$online_status"; ?></td>
				</tr>
				<tr>
					<td nowrap>IP</td>
					<td nowrap> : &nbsp; <?php echo "$uiIp"; ?></td>
				</tr>
				<tr>
					<td nowrap>Country</td>
					<td nowrap> : &nbsp; <?php echo "$uiCountry"; ?></td>
				</tr>
				<tr>
					<td nowrap>User Type</td>
					<td nowrap> : &nbsp; <?php echo "$uiIsAdmin"; ?></td>
				</tr>
				<tr>
					<td nowrap>Total Logins</td>
					<td nowrap> : &nbsp; <?php echo "$uiLoginCount"; ?></td>
				</tr>
				<tr>
					<td nowrap>Total Searches</td>
					<td nowrap> : &nbsp; <?php echo "$uiTotalSearches"; ?></td>
				</tr>
				<tr>
					<td nowrap>First Seen</td>
					<td nowrap> : &nbsp; <?php echo "$uiFirstSeenTime"; ?></td>
				</tr>
				<tr>
					<td nowrap>Last Seen</td>
					<td nowrap> : &nbsp; <?php echo "$uiLastSeenTime"; ?></td>
				</tr>
				<tr>
					<td nowrap>Lines Spoken</td>
					<td nowrap> : &nbsp; <?php echo "$uiSayTotal"; ?></td>
				</tr>
				<tr>
					<td nowrap>Kicks</td>
					<td nowrap> : &nbsp; <?php echo "$uiKickTotal"; ?></td>
				</tr>
				<tr>
					<td nowrap>Bans</td>
					<td nowrap> : &nbsp; <?php echo "$uiBanTotal"; ?></td>
				</tr>
			</table>			
		</td>
		<td valign="top">
			<!-- Client Details -->
			<table class="userinfo">
				<tr>
					<td nowrap>Client</td>
					<td nowrap> : &nbsp; <?php echo "$uiClient"; ?></td>
				</tr>
				<tr>
					<td nowrap>Client Version</td>
					<td nowrap> : &nbsp; <?php echo "$uiVersion"; ?></td>
				</tr>
				<tr>
					<td nowrap>Tag</td>
					<td nowrap> : &nbsp; <?php echo "$uiTag"; ?></td>
				</tr>
				<tr>
					<td nowrap>Message</td>
					<td nowrap> : &nbsp; <?php echo "$uiDescription"; ?></td>
				</tr>
				<tr>
					<td nowrap>Connection</td>
					<td nowrap> : &nbsp; <?php echo "$uiSpeed"; ?></td>
				</tr>
				<tr>
					<td nowrap>Hubs (Norm/Reg/Op)</td>
					<td nowrap> : &nbsp; <?php echo "$uiHubs/$uiHubsReg/$uiHubsOp"; ?></td>
				</tr>
				<tr>
					<td nowrap>Limiter</td>
					<td nowrap> : &nbsp; <?php echo "$uiLimiter KB/s"; ?></td>
				</tr>
				<tr>
					<td nowrap>Connection Mode</td>
					<td nowrap> : &nbsp; <?php echo "$uiMode"; ?></td>
				</tr>
				<tr>
					<td nowrap>Current Share</td>
					<td nowrap> : &nbsp; <?php echo "$uiShare bytes"; ?></td>
				</tr>
				<tr>
					<td nowrap>Total Time on hub</td>
					<td nowrap> : &nbsp; <?php echo "$total_days $Grandtotal"; ?></td>
				</tr>
			</table>
		</td>
	</tr>
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