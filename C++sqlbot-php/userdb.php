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
if ($useSearch == "1") { 
$alteredsearchfiled = ereg_replace ("\*", "%", $searchvalue);
$parseoptionextra= "&& $searchfield LIKE '$alteredsearchfiled'";}

if (empty($offset)) { $offset = 0; }

// GET TOTAL USERS IN DB 
$total_result=mysql_query("SELECT * FROM userInfo WHERE hubID='$hubID'");
$total_users=mysql_num_rows($total_result);

$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra ORDER BY uiIsAdmin  DESC,$parseorder LIMIT $offset,$defaultLogEntries");

$total_userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra ORDER BY uiIsAdmin  DESC,$parseorder");

$total_selection=mysql_num_rows($total_userresult);

?>
<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Selected users: [ <?php echo "$parse"; ?> ] &nbsp; Selected:
					[ <?php echo "$total_selection / $total_users"; ?> ] &nbsp;  for hub <?php echo "$hcName [ ID: $hubID ]"; ?></font></LEGEND>
	<!-- SEARCH DIALOG -->
	<table width="100%">
		<tr>
			<td><?php if ($useSearch == "1") {Echo "<font color=\"#FFFFFF\">Searching for <em>$searchvalue</em> in <em>$parse....</em> </font>";} ?>
			</td>
			<td align="right">
			<form action="<?php echo "$PHP_SELF"; ?>" method="post">
				<?php hidden_value(hubID, $hubID); ?>
				<?php hidden_value(parse, $parse); ?>
				<?php hidden_value(parseorder, uiNick); ?>
				<?php hidden_value(useSearch, 1); ?>
				<input type="text" name="searchvalue"	value="<?php echo "$searchvalue"; ?>" class="search_input" title="Use * if needed">
				<select name="searchfield" class="form_select">
					<option value="uiNick"> Nick
					<option value="uiIp"> IP
					<option value="uiShare"> Share
				</select>
				<input type="submit" value="Search" class="userdbnicknormal" title="Search users"></form>
			</td>
		</tr>
	</table>
	<!-- END SEARCH DIALOG -->
	
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
	
	if  (($uiStatus == "1") && ($uiIsAway == "0")) { $uiStatus ="<img src=\"img/Online.gif\" alt=\"Online\" title=\"Online\">";}
	if  (($uiStatus == "1") && ($uiIsAway == "1")) { $uiStatus ="<img src=\"img/Away.gif\" alt=\"Away\" title=\"Away\">";}
	if  ($uiStatus == "0") { $uiStatus ="<img src=\"img/Offline.gif\" alt=\"Offline\" title=\"Offline\">";}

if ($uiIsAdmin == "1") { $class = "userdbnickop"; }
else {$class = "userdbnicknormal"; }

//CONVERSION FOR SHARE
	if (($uiShare / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024 / 1024 / 1024), 2); $Share="$Shared TB";}
	else if (($uiShare / 1024 / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024 / 1024), 2); $Share="$Shared GB";}
	else if (($uiShare / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024), 2); $Share="$Shared MB";}
	else if (($uiShare / 1024) > 1) { $Shared=round(($uiShare / 1024), 2); $Share="$Shared KB";}
	else if ($uiShare == "0") { $Share = "0 KB";}


	echo "<tr>
		<td width=\"5\">$uiStatus</td>
		<td nowrap>
			<form action=\"userinfo.php\" method=\"post\">
			<input type=\"hidden\" name=\"hubID\" value=\"$hubID\">
			<input type=\"hidden\" name=\"uiIp\" value=\"$uiIp\">
			<input type=\"hidden\" name=\"uiNick\" value=\"$uiNick\">
			<input type=\"submit\" value=\"$uiNick\" class=\"$class\" title=\"View info on $uiNick\"></form>
		</td>
		<td nowrap align=\"center\">$uiIsAdmin</td>
		<td nowrap align=\"center\">$uiClient</td>
		<td nowrap align=\"center\">$uiSpeed</td>
		<td nowrap align=\"center\">$uiIp</td>
		<td nowrap align=\"center\">$conv_time</td>
		<td nowrap align=\"center\"><a title=\"$Share\" style=\"cursor:help;\">$uiShare</a></td>
	</tr>";
	$i++;
}




?>
	</table>
<!-- PREVIOUS / NEXT PAGE -->
	<table width="100%">
		<tr>
			<td>
			<?php
			// CODE FOR PREVIOUS BUTTON
				if ($offset >= $defaultLogEntries){
				$offset_value = $offset - $defaultLogEntries
			?>
						<form action="<?php echo "$PHP_SELF"; ?>" method="post">
							<?php hidden_value(hubID, $hubID); ?>
							<?php hidden_value(parse, $parse); ?>
							<?php hidden_value(parseorder, $parseorder); ?>
							<?php hidden_value(offset, $offset_value); ?>
							<?php if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;} ?>
							<input type="submit" value="<< Previous Page" class="userdbnicknormal" title="Go to Previous Page"></form>
			<?php ;} ?>
			</td>
			<td align="right">
			<?php
			// CODE FOR NEXT BUTTON
			$offset_value = $offset + $defaultLogEntries;
			$is_there_next = ($total_selection -$offset_value) /$defaultLogEntries;
			if ($is_there_next > 0){ 
			?>
						<form action="<?php echo "$PHP_SELF"; ?>" method="post">
							<?php hidden_value(hubID, $hubID); ?>
							<?php hidden_value(parse, $parse); ?>
							<?php hidden_value(parseorder, $parseorder); ?>
							<?php hidden_value(offset, $offset_value); ?>
							<?php if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;} ?>
							<input type="submit" value="Next Page >>" class="userdbnicknormal" title="Go to Next Page"></form>
			<?php ;} ?>
			</td>
		</tr>
	</table>
<!-- END PREVIOUS / NEXT PAGE -->
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