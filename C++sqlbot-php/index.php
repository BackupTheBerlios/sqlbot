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

// UPDATE MYSQL BOT VALUES
if ($action == "update_bot")
{
	$update_into_botConfig = "UPDATE botConfig SET
		bcName='$bcName',
		bcMaster='$bcMaster',
		bcIP='$bcIP',
		bcTCPport='$bcTCPport',
		bcUDPport='$bcUDPport',
		bcWWW='$bcWWW',
		bcConnection='$bcConnection',
		bcDescription='$bcDescription',
		bcSharePath='$bcSharePath',
		bcLogDir='$bcLogDir'
	WHERE rowID='1'";
	$result = mysql_query($update_into_botConfig) or die(mysql_error());
}
if ($action == "delete_hub")
{
	$delete_from_hubConfig = "DELETE FROM hubConfig WHERE hubID='$hubID'";
	$result = mysql_query($delete_from_hubConfig) or die(mysql_error());
	$delete_from_logSearch = "DELETE FROM logSearch WHERE hubID='$hubID'";
	$result1 = mysql_query($delete_from_logSearch) or die(mysql_error());
	$delete_from_logChat = "DELETE FROM logChat WHERE hubID='$hubID'";
	$result2 = mysql_query($delete_from_logChat) or die(mysql_error());
	$delete_from_userInfo = "DELETE FROM userInfo WHERE hubID='$hubID'";
	$result3 = mysql_query($delete_from_userInfo) or die(mysql_error());
	$delete_from_hubExtras = "DELETE FROM hubExtras WHERE hubID='$hubID'";
	$result4 = mysql_query($delete_from_hubExtras) or die(mysql_error());
}
if ($action == "add_hub")
{
	$add_to_hubConfig = "INSERT INTO hubConfig VALUES('','','','0','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')";
	$result = mysql_query($add_to_hubConfig) or die(mysql_error());
}




// Get botConfig values 
$result=mysql_query("SELECT * FROM botConfig WHERE rowID='1'");
	$rowID=mysql_result($result,$i,"rowID");
	$bcName=htmlentities(mysql_result($result,$i,"bcName"));
	$bcMaster=htmlentities(mysql_result($result,$i,"bcMaster"));
	$bcIP=mysql_result($result,$i,"bcIP");
	$bcTCPport=mysql_result($result,$i,"bcTCPport");
	$bcUDPport=mysql_result($result,$i,"bcUDPport");
	$bcWWW=htmlentities(mysql_result($result,$i,"bcWWW"));
	$bcConnection=mysql_result($result,$i,"bcConnection");
	$bcDescription=htmlentities(mysql_result($result,$i,"bcDescription"));
	$bcSharePath=htmlentities(mysql_result($result,$i,"bcSharePath"));
	$bcLogDir=htmlentities(mysql_result($result,$i,"bcLogDir"));
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
<font color="#FFFFFF">This is an overview of your configured SqlBot.</font>
		</td>
		<td class="main"><!-- MAIN BODY -->
				<table>
				<tr>
					<td valign="top">
				<!-- HUB DETAILS -->
				<form action="<?php echo "$PHP_SELF"; ?>" method="post">
				<table cellpadding="0" cellspacing="0" class="config">
					<tr>
						<td><input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>"></td>
						<td align="right"><font size="+1"><strong><u>Main Config</u></strong></font>
						</td>
					</tr>
					<tr><?php hidden_form("rowID", $rowID); ?>
					<td><input type="hidden" name="action" value="update_bot"></td></tr>
					<tr><?php line_form("Bot Name", 20, bcName, $bcName); ?></tr>
					<tr><?php line_form("Bot Owner", 20, bcMaster, $bcMaster); ?></tr>
					<tr><?php line_form("Bot IP / hostname", 40, bcIP, $bcIP); ?></tr>
					<tr><?php line_form("TCP Port", 6, bcTCPport, $bcTCPport); ?></tr>
					<tr><?php line_form("UDP Port", 6, bcUDPport, $bcUDPport); ?></tr>
					<tr><?php line_form("Bot WWW", 40, bcWWW, $bcWWW); ?></tr>
					<tr><?php line_form("Tag Description", 40, bcDescription, $bcDescription); ?></tr>
					<tr><?php line_form("Share path", 50, bcSharePath, $bcSharePath); ?></tr>
					<tr><?php line_form("Log Directory", 75, bcLogDir, $bcLogDir); ?></tr>
					<tr><?php connection_choice("Bot Connection", bcConnection, $bcConnection); ?></tr>
					<tr><td></td>
						<td align="right"><input type="submit" value="Update Changes" class="button">
					</td>
					</tr>
				</table>
			</form>
<!-- END HUB DETAILS -->
					</td>
					<td valign="top">
				<!-- HUB INFO -->
				<table cellpadding="0" cellspacing="0" class="config">
					<tr>
						<td valign="top">
						<FORM action="index.php" method="post">
						<input type="hidden" name="action" value="add_hub">
						<INPUT type="submit" value="Add a hub" class="button"> </FORM>
						</td>
						<td align="right" valign="top"><font size="+1"><strong><u>Configured Hubs</u><br>&nbsp;</strong></font>
						</td>
					</tr>

<?php
// LIST CONFIGURED HUBS
$result=mysql_query("SELECT * FROM hubConfig ORDER BY 'huiID'");
while ($data=mysql_fetch_array($result)) 
{
	$hubID=htmlentities(mysql_result($result,$d,"hubID"));
	$hcStatus=htmlentities(mysql_result($result,$d,"hcStatus"));
	$hcAutoConnect=mysql_result($result,$d,"hcAutoConnect");
	$hcName=htmlentities(mysql_result($result,$d,"hcName"));
	$hcHost=mysql_result($result,$d,"hcHost");
	$hcOwner=htmlentities(mysql_result($result,$d,"hcOwner"));

	
// CHECK IF CONFIGURED ~ ELSE SPECIFY
if ($hcName == "" || $hcHost == "" ){
	$hcName = "<blink>Configure</blink>";
}

// ADD ONLINE/OFFLINE COLOUR
if ($hcStatus == "Online"){
$total_users_online=mysql_query("SELECT * FROM userInfo where hubid='$hubID' AND uiStatus='1'");
$users_online=mysql_num_rows($total_users_online);
$hcStatus = "<font color=\"#000000\"><strong>Online</strong></font> &nbsp;  ($users_online online)";
}
if ($hcStatus == "Offline"){
$hcStatus = "<font color=\"#FF1D28\"><strong>Offline</strong></font>";
}


// ADD AUTOCONNECT ALIAS
if ($hcAutoConnect == "1") {
$aliasHcAutoconnect = "Yes";}
if ($hcAutoConnect == "0") {
$aliasHcAutoconnect = "No";}

echo "<tr>
				<td></td>
				<td><hr width=\"100%\"></td>
			</tr>
			<tr>
				<td valign=\"top\"><strong>$hcName</strong></td>
				<td valign=\"top\">
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td><form action=\"hubconfig.php\" method=\"post\">
						<input type=\"hidden\" name=\"hubID\" value=\"$hubID\">
						<input type=\"hidden\" name=\"hcName\" value=\"$hcName\">
						<select name=\"action\" class=\"form_select\">
							<option selected value=\"edit\">Edit/View</option>
							<option value=\"delete\">Delete</option>
							</select><input type=\"submit\" value=\"Go\" class=\"button\"></form>
						</td>
						<td>
							<form action=\"userdb.php\" method=\"post\">
							<input type=\"hidden\" name=\"hubID\" value=\"$hubID\">
							<input type=\"hidden\" name=\"parse\" value=\"Online\">
							<input type=\"hidden\" name=\"parseorder\" value=\"uiNick\">
							<input type=\"submit\" value=\"Online Users\" class=\"button\" title=\"View Online Users\"></form>
						</td>
					</tr>
				</table>
				</td>
			</tr>"; ?>
			<tr><?php noedit_form("Hub status", $hcStatus); ?></tr>
			<tr><?php noedit_form("Hub address", $hcHost); ?></tr>
			<tr><?php noedit_form("Autoconnect", $aliasHcAutoconnect); ?></tr>
<?php
$d++; }
?>
				</table>
				<!-- END HUB DETAILS -->
					</td>
				</tr>
				<tr>
					<td valign="top"><!-- EXTRAS? --></td>
					<td valign="top"><!-- EXTRAS? --></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close(); ?>
</body>
</html>