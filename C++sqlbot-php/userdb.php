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
<?php
if ($parse == "All") { $parseoption = "";}
if ($parse == "Online") { $parseoption = "&& uiStatus='1'"; }
if ($parse == "Fakers") { $parseoption = ""; }

// GET TOTAL USERS IN DB 
$total_result=mysql_query("SELECT * FROM userInfo WHERE hubID='$hubID'");
$total_users=mysql_num_rows($total_result);

// LIMIT $defaultLogEntries ORDER BY uiNick
//$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption ORDER BY uiIsAdmin  DESC,uiNick");

$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra ORDER BY uiIsAdmin  DESC,$parseorder");

$total_selection=mysql_num_rows($userresult);

?>
<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Selected users: [ <?php echo "$parse"; ?> ] &nbsp; Selected:
					[ <?php echo "$total_selection / $total_users"; ?> ] &nbsp;  for hub <?php echo "$hcName [ ID: $hubID ]"; ?></font></LEGEND>	
	
	<table class="userdb">
	<tr nowrap>
		<td><!-- Online / Offline icon --></td>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, uiNick); ?>
			<input type="submit" value="User Nick" class="userdbcol"></form>
		</td>
		<th>Rank</th>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, uiClient); ?>
			<input type="submit" value="Client" class="userdbcol"></form>
		</td>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, uiSpeed); ?>
			<input type="submit" value="Connection" class="userdbcol"></form>
		</td>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, uiIp); ?>
			<input type="submit" value="IP" class="userdbcol"></form>
		</td>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, date); ?>
			<input type="submit" value="CheckIn" class="userdbcol"></form>
		</td>
		<td>
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
			<?php hidden_value(hubID, $hubID); ?>
			<?php hidden_value(parse, $parse); ?>
			<?php hidden_value(parseorder, uiShare); ?>
			<input type="submit" value="Shared Bytes" class="userdbcol"></form>
		</td>
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


// CONVERSIONS FOR GRAPHICS& DATE

	$conv_time=mysql_result($userresult,$i,"date");
	if ($uiClient == "Unknown") { $uiClient = "<img src=\"img/clients/NoTag.gif\" alt=\"\" title=\"$uiTag\">"; }
	if ($uiClient == "DCGUI") { $uiClient = "<img src=\"img/clients/DCGUI.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if ($uiClient == "++") { $uiClient = "<img src=\"img/clients/DCpp.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if ($uiClient == "DC") { $uiClient = "<img src=\"img/clients/DC.gif\" alt=\"$uiClient\" title=\"$uiTag\">"; }
	if ($uiNick == "$bcName") { $uiClient = "BOT"; }
	if  ($uiStatus == "1") { $uiStatus ="<img src=\"img/Online.gif\" alt=\"Online\">";}
	if  ($uiStatus == "0") { $uiStatus ="<img src=\"img/Offline.gif\" alt=\"Offline\">";}

if ($uiIsAdmin == "1") { $class = "userdbnickop"; }
else {$class = "userdbnicknormal"; }

	echo "<tr>
		<td>$uiStatus</td>
		<td nowrap>
			<form action=\"userinfo.php\" method=\"post\">
			<input type=\"hidden\" name=\"hubID\" value=\"$hubID\">
			<input type=\"hidden\" name=\"uiIp\" value=\"$uiIp\">
			<input type=\"hidden\" name=\"uiNick\" value=\"$uiNick\">
			<input type=\"submit\" value=\"$uiNick\" class=\"$class\"></form>
		</td>
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