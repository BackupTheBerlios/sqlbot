<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
</head>
<body>
<DIV ID="dek"></div>
<SCRIPT TYPE="text/javascript">
<!--
Xoffset= 10;    // modify these values to ...
Yoffset= -20;    // change the popup position.

var old,skn,iex=(document.all),yyy=-1000;

var ns4=document.layers
var ns6=document.getElementById&&!document.all
var ie4=document.all

if (ns4)
skn=document.dek
else if (ns6)
skn=document.getElementById("dek").style
else if (ie4)
skn=document.all.dek.style
if(ns4)document.captureEvents(Event.MOUSEMOVE);
else{
skn.visibility="visible"
skn.display="none"
}
document.onmousemove=get_mouse;

function popup(msg,bak){
var content="<TABLE  BORDER=1 BORDERCOLOR=\"black\" CELLPADDING=2 CELLSPACING=0 "+
"BGCOLOR="+bak+" CLASS=\"popup\"><TR><TD>"+msg+"</TD></TR></TABLE>";
yyy=Yoffset;
 if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"}
 if(ns6){document.getElementById("dek").innerHTML=content;skn.display=''}
 if(ie4){document.all("dek").innerHTML=content;skn.display=''}
}

function get_mouse(e){
var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft;
skn.left=x+Xoffset;
var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop;
skn.top=y+yyy;
}

function kill(){
yyy=-1000;
if(ns4){skn.visibility="hidden";}
else if (ns6||ie4)
skn.display="none"
}

function confirmDeleteAll()
{
var agree=confirm("WARNING:\nThis will delete all users only (no VIP/Op/Op-Admins) in your selection!\nVIP/OP/Op-Admins (etc.) need to be deleted individually via their\n detailed info page.\n\n Are you sure you want to do this?");
if (agree)
	return true ;
else
	return false ;
}
//-->
</script>

<?php
	include("conf/dbinfo.inc.php");
	include("conf/forms.php");
// CONNECT TO MYSQL SERVER
	mysql_connect($databasehost,$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");

if ($hubID == "") {
echo "Error... no hubID parsed!<p>Please return to the <a href=\"index.php\"><font color=\"blue\">index.php</font></a>";}
else {

// DEFINE SEARCH / SORT OPTIONS (for deletion / parsing)
if ($parse == "All") { $parseoption = "";}
if ($parse == "Online") { $parseoption = "&& uiStatus='1'"; }
if ($parse == "Banned") { $parseoption = "&& uiBanFlag > '0'"; }
if ($useSearch == "1") { 
$alteredsearchfiled = ereg_replace ("\*", "%", $searchvalue);
$parseoptionextra= "&& $searchfield LIKE '$alteredsearchfiled'";}


if ($action == "deleteUser")
{
	$delete_from_userInfo = "DELETE FROM userInfo WHERE hubID='$hubID' && uiNick='$uiNick' && uiIp='$uiIp'";
	$result = mysql_query($delete_from_userInfo) or die(mysql_error());
}

if ($action == "deleteAll") {
$deleteAll_from_userInfo="DELETE FROM userInfo WHERE hubID='$hubID' && uiUserLevel='0' $parseoption $parseoptionextra";
$result = mysql_query($deleteAll_from_userInfo) or die(mysql_error());
}

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
				<input type="submit" value="Back to bot..." class="button">
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
if (empty($offset)) { $offset = 0; }

// GET TOTAL USERS IN DB 
$total_result=mysql_query("SELECT * FROM userInfo WHERE hubID='$hubID'");
$total_users=mysql_num_rows($total_result);

$userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date,
DATE_FORMAT(uiBanTime, '%d/%m/%Y %H:%i') AS BanTime,
DATE_FORMAT(uiBanExpire, '%d/%m/%Y %H:%i') AS BanExpire
FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra ORDER BY uiUserLevel  DESC,$parseorder LIMIT $offset,$defaultLogEntries");

$total_userresult=mysql_query("SELECT *,DATE_FORMAT(uiLastSeenTime, '%d/%m/%Y %H:%i') AS date FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra ORDER BY uiUserLevel  DESC,$parseorder");

$total_selection=mysql_num_rows($total_userresult);

$total_bytes_q=mysql_query("SELECT SUM(uiShare) FROM userInfo WHERE hubID='$hubID' $parseoption $parseoptionextra");

$totshared_bytes=mysql_result($total_bytes_q,$i);

	if (($totshared_bytes / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($totshared_bytes / 1024 / 1024 / 1024 / 1024), 2); $totalShare="$Shared TB";}
	else if (($totshared_bytes / 1024 / 1024 / 1024) > 1) { $Shared=round(($totshared_bytes / 1024 / 1024 / 1024), 2); $totalShare="$Shared GB";}
	else if (($totshared_bytes / 1024 / 1024) > 1) { $Shared=round(($totshared_bytes / 1024 / 1024), 2); $Share="$totalShared MB";}
	else if (($totshared_bytes / 1024) > 1) { $Shared=round(($totshared_bytes / 1024), 2); $totalShare="$Shared KB";};


?>
<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Selected users: [ <?php echo "$parse"; ?> ] &nbsp; Selected:
					[ <?php echo "$total_selection / $total_users ($totalShare)"; ?> ] &nbsp;  for hub <?php echo "$hcName [ ID: $hubID ]"; ?></font></LEGEND>
	<!-- SEARCH DIALOG -->
	<table width="100%">
		<tr nowrap>
			<td width="120">
					<!-- Delete ALL button -->
							<form action="<?php echo "$PHP_SELF"; ?>" method="post">
							<?php hidden_value(hubID, $hubID);
							hidden_value(parseoption, $parseoption);
							hidden_value(useSearch, $useSearch);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							hidden_value(parse, $parse);
							hidden_value(parseorder, $parseorder);
							hidden_value(action, deleteAll); ?>
						<input type="submit" value="[ Delete Selection ]" class="deldata" onClick="return confirmDeleteAll()"></form>
			</td>
			<td>
					<?php if ($useSearch == "1") {Echo "<font color=\"#FFFFFF\">Searching for \"<em>$searchvalue</em>\" in <em>$parse....</em> </font>";} ?>
			</td>
			<td align="right" width="300" nowrap>			
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
					<option value="uiClient"> Client
					<option value="uiVersion"> Client Version
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
			<?php hidden_value(parseorder, uiCountry); ?>
			<input type="submit" value="Country" class="userdbcol"></form>
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
		<th>Del</td>
	</tr>
<?php
while ($data=mysql_fetch_array($userresult)) 
{

	$uiNick=htmlentities(mysql_result($userresult,$i,"uiNick"));
	$uiIp=mysql_result($userresult,$i,"uiIp");
	$uiHost=mysql_result($userresult,$i,"uiHost");
	$uiIsAway=mysql_result($userresult,$i,"uiIsAway");
	$uiStatus=mysql_result($userresult, $i, "uiStatus");
	$HubID=mysql_result($userresult,$i,"HubID");
	$uiCountry=mysql_result($userresult,$i,"uiCountry");
	$uiIsAdmin=mysql_result($userresult,$i,"uiIsAdmin");
	$uiUserLevel=mysql_result($userresult,$i,"uiUserLevel");
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
	$BanTime=mysql_result($userresult,$i,"BanTime");
	$BanExpire=mysql_result($userresult,$i,"BanExpire");
	if ($uiClient == "Unknown") { $CLIENT = "<img src=\"img/clients/NoTag.gif\" alt=\"\">"; }
	if ($uiClient == "DCGUI") { $CLIENT = "<img src=\"img/clients/DCGUI.gif\" alt=\"$uiClient\">"; }
	if ($uiClient == "++") { $CLIENT = "<img src=\"img/clients/DCpp.gif\" alt=\"$uiClient\">"; }
	if ($uiClient == "DC") { $CLIENT = "<img src=\"img/clients/DC.gif\" alt=\"$uiClient\">"; }
	if ($uiClient == "DCTC") { $CLIENT = "<img src=\"img/clients/DCTC.gif\" alt=\"$uiClient\">"; }
	if ($uiUserLevel == "5") { $CLIENT = "<img src=\"img/clients/Bot.gif\" alt=\"Bot\">"; }
	
	if  (($uiStatus == "1") && ($uiIsAway == "0")) { $Status ="<img src=\"img/Online.gif\" alt=\"Online\" title=\"Online\">";}
	if  (($uiStatus == "1") && ($uiIsAway == "1")) { $Status ="<img src=\"img/Away.gif\" alt=\"Away\" title=\"Away\">";}
	if  ($uiStatus == "0") { $Status ="<img src=\"img/Offline.gif\" alt=\"Offline\" title=\"Offline\">";}
	if  (($uiBanFlag > "0") && ($uiStatus == "0")) { $Status ="<img src=\"img/Ban.gif\" alt=\"Offline\" title=\"Banned\">";}

	if (!empty($uiCountry)) {$flag = "<img src=\"img/flags/${uiCountry}.PNG\" alt=\"$uiCountry\" ONMOUSEOVER=\"popup('$uiCountry</td>','yellow')\"; ONMOUSEOUT=\"kill()\">"; }
	else {$flag = "";}

//CONVERSION FOR SHARE
	if (($uiShare / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024 / 1024 / 1024), 2); $Share="$Shared TB";}
	else if (($uiShare / 1024 / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024 / 1024), 2); $Share="$Shared GB";}
	else if (($uiShare / 1024 / 1024) > 1) { $Shared=round(($uiShare / 1024 / 1024), 2); $Share="$Shared MB";}
	else if (($uiShare / 1024) > 1) { $Shared=round(($uiShare / 1024), 2); $Share="$Shared KB";}
	else if ($uiShare == "0") { $Share = "0 KB";}

if ($uiMode == "Passive") { $uiMode = "P"; }
if ($uiMode == "Active") { $uiMode = "A"; }


//DECLARE POPUPS FOR USER-NICK
if ($uiClient == "Unknown") { $clientHover = "ONMOUSEOVER=\"popup('Unknown</td>','yellow')\"; ONMOUSEOUT=\"kill()\""; }
if ($uiUserLevel == "5") { $clientHover = "ONMOUSEOVER=\"popup('Bot</td>','yellow')\"; ONMOUSEOUT=\"kill()\""; }
elseif ($uiClient != "Unknown")  { $clientHover = "ONMOUSEOVER=\"popup('Client</td><td>$uiClient</td></tr><tr><td>Version</td><td>$uiVersion</td></tr><tr><td>Details</td><td>M:$uiMode,H:$uiHubs,S:$uiSlots,L:$uiLimiter</td>','yellow')\"; ONMOUSEOUT=\"kill()\""; }


// DECLARE USER-STATES
if ($uiUserLevel == "5") { $class = "userdbBot"; $Level = "Bot"; }
if ($uiUserLevel == "4") { $class = "userdbBotMaster"; $Level = "Bot Master"; }
if ($uiUserLevel == "3") { $class = "userdbOPADM"; $Level = "Op Admin"; }
if ($uiUserLevel == "2") { $class = "userdbOP"; $Level = "Operator";  }
if ($uiUserLevel == "1") { $class = "userdbVIP"; $Level = "VIP";  }
if (($uiUserLevel > "1") && ($uiIsAdmin == "0")) { $class = "userdbERROR"; $Level = "Misconfigured"; }
if (($uiUserLevel < "2") && ($uiIsAdmin == "1")) { $class = "userdbERROR"; $Level = "Misconfigured"; }
if ((($uiUserLevel == "0") || ($uiUserLevel == "")) && ($uiIsAdmin == "0")) { $class = "userdbnicknormal"; $Level = "User";}


//USER INFO / BANS

if ($uiBanFlag == "0") { $user_info = "Login/Srchs</td><td>$uiLoginCount / $uiTotalSearches</td></tr><tr><td>Kicks</td><td>$uiKickTotal</td></tr><tr><td>Bans</td><td>$uiBanTotal</td>"; }

if ($uiBanFlag > "0") { $user_info = "Login</td><td>$uiLoginCount</td></tr><tr><td>Kicks</td><td>$uiKickTotal</td></tr><tr><td valign=top>Bans</td><td>Total: $uiBanTotal<br>Banned: $BanTime<br>Expires: $BanExpire</td>"; }


// PAGE DATA
echo "<tr>
		<td width=\"5\">$Status</td>
		<td>
			<form action=\"userinfo.php\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(parse, $parse);
							hidden_value(parseorder, $parseorder);
							hidden_value(offset, $offset);
							hidden_value(uiNick, $uiNick);
							hidden_value(uiIp, $uiIp);
						if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;}
	echo "<input type=\"submit\" value=\"$uiNick\" class=\"$class\" nowrap
			ONMOUSEOVER=\"popup('$user_info','yellow')\"; ONMOUSEOUT=\"kill()\"></form>
		</td>
		<td nowrap align=\"center\">$Level</td>
		<td nowrap align=\"center\" $clientHover>$CLIENT</td>
		<td nowrap align=\"center\">$uiSpeed</td>
		<td nowrap align=\"center\">$flag</td>
		<td nowrap align=\"center\">$uiIp</td>
		<td nowrap align=\"center\">$conv_time</td>
		<td nowrap align=\"center\"
		ONMOUSEOVER=\"popup('$Share</td>','yellow')\"; ONMOUSEOUT=\"kill()\">$uiShare</td>
		<td>";
		if ($uiUserLevel == "0") {
						echo "<form action=\"$PHP_SELF\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(parse, $parse);
							hidden_value(parseorder, $parseorder);
							hidden_value(offset, $offset);
							hidden_value(uiNick, $uiNick);
							hidden_value(uiIp, $uiIp);
							hidden_value(action, deleteUser);
						if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;}
						echo "<input type=\"submit\" value=\"X\" class=\"userdbnicknormal\" title=\"Delete $uiNick\"></form>";}
		echo "</td>
	</tr>";
	$i++;
}




?>
	</table>
<!-- PREVIOUS / NEXT PAGE -->
	<table width="100%">
		<tr>
			<td width="15">
				<?php
				// CODE FOR FIRST-PAGE BUTTON
				$last_divide = floor($total_selection / $defaultLogEntries);
				$last_offset = $last_divide * $defaultLogEntries;
				$is_there_a_last = $total_selection - $offset;
				if ($offset > "0") { 
				?>
						<form action="<?php echo "$PHP_SELF"; ?>" method="post">
							<?php hidden_value(hubID, $hubID); ?>
							<?php hidden_value(parse, $parse); ?>
							<?php hidden_value(parseorder, $parseorder); ?>
							<?php hidden_value(offset, 0); ?>
							<?php if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;} ?>
							<input type="submit" value="<<<" class="userdbnicknormal" title="Go to First Page"></form>
				<?php ;} ?>
			</td>
			<td width="*">
				<?php
				// CODE FOR PREVIOUS BUTTON
				if ($offset >= $defaultLogEntries){
				$offset_value = $offset - $defaultLogEntries ?>
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
							<input type="submit" value=" << " class="userdbnicknormal" title="Go to Previous Page"></form>
				<?php ;} ?>
			</td>
			<td width="*" align="right">
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
							<input type="submit" value=" >> " class="userdbnicknormal" title="Go to Next Page"></form>
				<?php ;} ?>
			</td>
			<td align="right" width="10">
				<?php
				// CODE FOR LAST-PAGE BUTTON
				$last_divide = floor($total_selection / $defaultLogEntries);
				$last_offset = $last_divide * $defaultLogEntries;
				$is_there_a_last = $total_selection - $offset;
				if ($is_there_a_last > $defaultLogEntries) { 
				?>
						<form action="<?php echo "$PHP_SELF"; ?>" method="post">
							<?php hidden_value(hubID, $hubID); ?>
							<?php hidden_value(parse, $parse); ?>
							<?php hidden_value(parseorder, $parseorder); ?>
							<?php hidden_value(offset, $last_offset); ?>
							<?php if ($useSearch == "1") {
							hidden_value(useSearch, 1);
							hidden_value(useSearch, 1);
							hidden_value(searchvalue, $searchvalue);
							hidden_value(searchfield, $searchfield);
							;} ?>
							<input type="submit" value=">>>" class="userdbnicknormal" title="Go to Last Page"></form>
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
