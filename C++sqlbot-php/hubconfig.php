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
echo "Error... no hubID parsed!<p>Please return to the <a href=\"index.php\">index.php</a>";}
else {
	

// UPDATE MYSQL VALUES
if ($action == "update")
{
	$update_into_hubConfig = "UPDATE hubConfig SET
		hcPwd='$hcPwd',
		hcAutoConnect='$hcAutoConnect',
		hcName='$hcName',
		hcDescription='$hcDescription',
		hcHost='$hcHost',
		hcMaxUsers='$hcMaxUsers',
		hcMinShare='$hcMinShare',
		hcMinShareMultiplier='$hcMinShareMultiplier',
		hcRedirectHost='$hcRedirectHost',
		hcSBan='$hcSBan',
		hcSBanMultiplier='$hcSBanMultiplier',
		hcLBan='$hcLBan',
		hcLBanMultiplier='$hcLBanMultiplier',
		hcSBansBeforeLBans='$hcSBansBeforeLBans',
		hcShareCheckTimeout='$hcShareCheckTimeout',
		hcShareCheckTimeoutMultiplier='$hcShareCheckTimeoutMultiplier',
		hcFileListDl='$hcFileListDl',
		hcOwner='$hcOwner',
		hcMotd='$hcMotd',
		hcMinSpeed='$hcMinSpeed',
		hcMinSlots='$hcMinSlots',
		hcMaxSlots='$hcMaxSlots',
		hcMaxHubs='$hcMaxHubs',
		hcMinLimiter='$hcMinLimiter',
		hcSlotRatio='$hcSlotRatio',
		hcEnableTagCheck='$hcEnableTagCheck',
		hcKickNoTag='$hcKickNoTag',
		hcEnableCloneCheck='$hcEnableCloneCheck',
		hcVerboseKickNoTag='$hcVerboseKickNoTag',
		hcVerboseKick='$hcVerboseKick',
		hcVerboseBan='$hcVerboseBan',
		hcLogChat='$hcLogChat',
		hcLogSearches='$hcLogSearches',
		hcLogSystem='$hcLogSystem'		
	WHERE hubID='$hubID'";
	$result = mysql_query($update_into_hubConfig) or die(mysql_error());
}



if ($action == "delete") { ?>
<br><br>
<div align="center">Are you sure you want to delete <strong><?php echo "$hcName</strong> with hubID = $hubID ?<br>
This will delete all your logs and users for that hub too!"; ?>
<table>
<tr>
<td>
<FORM action="index.php" method="post">
<input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>">
<input type="hidden" name="action" value="delete_hub">
<INPUT type="submit" value="Yes" class="button"> </FORM>
</td>
<td>
<FORM action="index.php" method="post">
<input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>">
<input type="hidden" name="action" value="nothing_at_all">
<INPUT type="submit" value="No" class="button"> </FORM>
</td>
</tr>
</table></div>
</body>
</html>
<?php }
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
	$hcSBan=mysql_result($result,$i,"hcSBan");
	$hcSBanMultiplier=mysql_result($result,$i,"hcSBanMultiplier");
	$hcLBan=mysql_result($result,$i,"hcLBan");
	$hcLBanMultiplier=mysql_result($result,$i,"hcLBanMultiplier");
	$hcSBansBeforeLBans=mysql_result($result,$i,"hcSBansBeforeLBans");
	$hcShareCheckTimeout=mysql_result($result,$i,"hcShareCheckTimeout");
	$hcShareCheckTimeoutMultiplier=mysql_result($result,$i,"hcShareCheckTimeoutMultiplier");
	$hcFileListDl=mysql_result($result,$i,"hcFileListDl");
	$hcOwner=htmlentities(mysql_result($result,$i,"hcOwner"));
	$hcSoftware=htmlentities(mysql_result($result,$i,"hcSoftware"));
	$hcVersion=htmlentities(mysql_result($result,$i,"hcVersion"));
	$hcMotd=htmlentities(mysql_result($result,$i,"hcMotd"));
	$hcMinSpeed=mysql_result($result,$i,"hcMinSpeed");
	$hcMinSlots=mysql_result($result,$i,"hcMinSlots");
	$hcMaxSlots=mysql_result($result,$i,"hcMaxSlots");
	$hcMaxHubs=mysql_result($result,$i,"hcMaxHubs");
	$hcMinLimiter=mysql_result($result,$i,"hcMinLimiter");
	$hcSlotRatio=mysql_result($result,$i,"hcSlotRatio");
	$hcEnableCloneCheck=mysql_result($result,$i,"hcEnableCloneCheck");
	$hcEnableTagCheck=mysql_result($result,$i,"hcEnableTagCheck");
	$hcKickNoTag=mysql_result($result,$i,"hcKickNoTag");
	$hcEnableCloneCheck=mysql_result($result,$i,"hcEnableCloneCheck");
	$hcVerboseKickNoTag=mysql_result($result,$i,"hcVerboseKickNoTag");
	$hcVerboseKick=mysql_result($result,$i,"hcVerboseKick");
	$hcVerboseBan=mysql_result($result,$i,"hcVerboseBan");
	$hcLogChat=mysql_result($result,$i,"hcLogChat");
	$hcLogSearches=mysql_result($result,$i,"hcLogSearches");
	$hcLogSystem=mysql_result($result,$i,"hcLogSystem");
	

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
				<?php include("conf/mainmenu.php"); ?>
		</td>
		<td class="main"><!-- MAIN BODY -->
				<form action="<?php echo "$PHP_SELF"; ?>" method="post">
				<table>
				<tr>
					<td valign="top">
				<!-- HUB DETAILS -->
						<table cellpadding="0" cellspacing="0" class="config">
							<tr>
								<td><input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>">
									<input type="hidden" name="action" value="update"></td>
								<td align="right"><font size="+1"><strong><u>Bot Details</u></strong></font>
								</td>
							</tr>
							<tr><?php hidden_form("HubID", $hubID); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Auto-Connect", hcAutoConnect, $hcAutoConnect); ?></tr>
							<tr><?php noedit_form("Hub ID", $hubID); ?></tr>
							<tr><?php noedit_form("Hub Status", $hcStatus); ?></tr>
							<tr><?php line_form("Hub Name (short)", 20, hcName, $hcName); ?></tr>
							<tr><?php line_form("Hub Address", 40, hcHost, $hcHost); ?></tr>
							<tr><?php line_form("Bot Password", 40, hcPwd, $hcPwd); ?></tr>
							<tr><?php line_form("Hub Owner", 40, hcOwner, $hcOwner); ?></tr>
						</table>
							<!-- END HUB DETAILS -->
					</td>
					<td valign="top">
							<!-- HUB INFO -->
						<table cellpadding="0" cellspacing="0" class="config">
							<tr>
								<td></td>
								<td align="right"><font size="+1"><strong><u>Hub Details</u></strong></font>
								</td>
							</tr>
							<tr><?php noedit_form("Software", $hcSoftware); ?></tr>
							<tr><?php noedit_form("Software Version", $hcVersion); ?></tr>
							<tr><?php textarea_form("MOTD", hcMotd, $hcMotd); ?></tr>
						</table>
						<!-- END HUB DETAILS -->
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr>
								<td></td>
								<td align="right"><font size="+1"><strong><u>Bot Configuration</u></strong></font>
								</td>
							</tr>
							<tr><?php logging_options("Log System", hcLogSystem, $hcLogSystem); ?></tr>
							<tr><?php logging_options("Log Main Chat", hcLogChat, $hcLogChat); ?></tr>
							<tr><?php logging_options("Log Searches", hcLogSearches, $hcLogSearches); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Enable Clone Check", hcEnableCloneCheck, $hcEnableCloneCheck); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Check Filelists", hcFileListDl, $hcFileListDl); ?></tr>
							<tr><?php line_form("Max Users", 5, hcMaxUsers, $hcMaxUsers); ?></tr>
							<tr><?php line_form("Redirect Host", 40, hcRedirectHost, $hcRedirectHost); ?></tr>
							<tr><?php size_form("Min Share", "Minimum share to enter hub", 3, hcMinShare, $hcMinShare, hcMinShareMultiplier, $hcMinShareMultiplier); ?></tr>
							<tr><?php time_form("Share Check Timeout", "Set this low", 3, hcShareCheckTimeout, $hcShareCheckTimeout, hcShareCheckTimeoutMultiplier, $hcShareCheckTimeoutMultiplier); ?></tr>
							<tr><?php line_form("Kicks before Bans", 2, hcSBansBeforeLBans, $hcSBansBeforeLBans); ?></tr>
							<tr><?php time_form("Short Ban", "How long you want to Short-Ban kicked clients", 3, hcSBan, $hcSBan, hcSBanMultiplier, $hcSBanMultiplier); ?></tr>
							<tr><?php longtime_form("Long Ban", "How long you want to Long-Ban kicked clients", 3, hcLBan, $hcLBan, hcLBanMultiplier, $hcLBanMultiplier); ?></tr>
						</table>
					</td>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr>
								<td></td>
								<td align="right"><font size="+1"><strong><u>Client Control</u></strong></font>
								</td>
							</tr>
							<tr><?php dual_select_form(Yes,No, "Enable Tag Check", hcEnableTagCheck, $hcEnableTagCheck); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Kick No-Tags", hcKickNoTag, $hcKickNoTag); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Verbose No-Tag Kicks", hcVerboseKickNoTag, $hcVerboseKickNoTag); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Verbose Kicks", hcVerboseKick, $hcVerboseKick); ?></tr>
							<tr><?php dual_select_form(Yes,No, "Verbose Bans", hcVerboseBan, $hcVerboseBan); ?></tr>
							<tr><?php connection_choice("Minimum Connection", hcMinSpeed, $hcMinSpeed); ?></tr>
							<tr><?php line_form("Minimum Slots", 2, hcMinSlots, $hcMinSlots); ?></tr>
							<tr><?php line_form("Maximum Slots", 3, hcMaxSlots, $hcMaxSlots); ?></tr>
							<tr><?php line_form("Maximum Hubs", 3, hcMaxHubs, $hcMaxHubs); ?></tr>
							<tr><?php line_form("Minimal Limiter (KB/s)", 2, hcMinLimiter, $hcMinLimiter); ?></tr>
							<tr><?php line_form("Slot Ratio", 4, hcSlotRatio, $hcSlotRatio); ?></tr>					
							<tr>
								<td></td>
								<td align="right"><input type="submit" value="Update" class="button"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close(); } }?>
</body>
</html>