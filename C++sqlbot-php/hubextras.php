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
	$update_into_hubExtra = "UPDATE hubExtras SET
	hxHubRules='$hxHubRules',
	hxBanNicks='$hxBanNicks',
	hxBanSearch='$hxBanSearch',
	hxBanChat='$hxBanChat',
	hxBanSharedFiles='$hxBanSharedFiles'
	WHERE hubID='$hubID'";
	$result = mysql_query($update_into_hubExtra) or die(mysql_error());
}
// CHECK IF hubEXTRAS HAS AN ENTRY
$checkExtras_query=mysql_query("SELECT * FROM hubExtras WHERE hubID='$hubID'");
$checkExtras=mysql_num_rows($checkExtras_query);

if ($checkExtras == "0") {
// INSERT Row for hub
$add_to_hubExtras = "INSERT INTO hubExtras VALUES('$hubID','','','','','','','')";
$result_add = mysql_query($add_to_hubExtras) or die(mysql_error());
}

// MySQL Connection
$result=mysql_query("SELECT * FROM hubExtras WHERE hubID='$hubID'");
	$hubID=mysql_result($result,$i,"hubID");
	$hxHubRules=htmlentities(mysql_result($result,$i,"hxHubRules"));
	$hxBanNicks=htmlentities(mysql_result($result,$i,"hxBanNicks"));
	$hxBanSearch=mysql_result($result,$i,"hxBanSearch");
	$hxBanChat=htmlentities(mysql_result($result,$i,"hxBanChat"));
	$hxBanSharedFiles=htmlentities(mysql_result($result,$i,"hxBanSharedFiles"));

// MySQL Connection
$result=mysql_query("SELECT * FROM hubConfig WHERE hubID='$hubID'");
	$hubID=htmlentities(mysql_result($result,$i,"hubID"));
	$hcStatus=htmlentities(mysql_result($result,$i,"hcStatus"));
	$hcName=htmlentities(mysql_result($result,$i,"hcName"));
	$hcHost=mysql_result($result,$i,"hcHost");
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
				<table>
				<!-- HUB CONFIG MENU -->
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<form action="<?php echo "hubconfig.php"; ?>" method="post">
									<?php hidden_value(hubID, $hubID); ?>
									<input type="submit" value="Hub Config" class="menubutton"></form>
								</td>
								<td>
									<form action="<?php echo "hubverbosity.php"; ?>" method="post">
									<?php hidden_value(hubID, $hubID); ?>
									<input type="submit" value="Hub Verbosity" class="menubutton"></form>
								</td>
								<td>
									<form action="<?php echo "hubextras.php"; ?>" method="post">
									<?php hidden_value(hubID, $hubID); ?>
									<input type="submit" value="Hub Extras" class="menubuttonselected"></form>
								</td>
							<tr>
						</table>
					</td>
					<td></td>
				</tr>
				<!-- END HUB CONFIG MENU -->
				<form action="<?php echo "$PHP_SELF"; ?>" method="post">
				<tr>
					<td valign="top">
				<!-- HUB RULES -->
						<table cellpadding="0" cellspacing="0" class="config">
						<input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>">
						<input type="hidden" name="action" value="update">
						<tr><?php textarea_form_long("<strong><u>Hub Rules</u></strong><br>
						Add your rules for your hub in here. The user will see it when he types
						+rules", hxHubRules, $hxHubRules); ?></tr>
						</table>
					</td>
					<td valign="top">
				<!-- HUB NICK BANS -->
						<table cellpadding="0" cellspacing="0" class="config">
							<tr><td>TODO</td><!--<?php textarea_form_long("<strong><u>Nick Ban</u></strong><br>
						Here you can insert partial or full names that the bot will place an
						immediate ban on when a user mathing such a nick enters the hub.
						", hxBanNicks, $hxBanNicks); ?> --></tr>
						</table>
						<!-- END HUB DETAILS -->
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr><td>TODO</td><!--<?php textarea_form_long("<strong><u>Search Ban</u></strong><br>
						Here you can insert words you don't want users searching for on your
						hub (like rape, child-porn, etc...).<p>
						", hxBanSearch, $hxBanSearch); ?>--></tr>
						</table>
					</td>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr><td>TODO</td><!--<?php textarea_form_long("<strong><u>Chat Ban</u></strong><br>
						Add words you don't want users to say in your main chat. This will ban them
						so be carefull what you add here. This feature is mainly used for spam-bots with 
						certain web addresses.<p>
						", hxBanChat, $hxBanChat); ?>--></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr><td>TODO</td><!--<?php textarea_form_long("<strong><u>Ban Shared Files</u></strong><br>
						The bot will eventualyl be able todownload filelists of users and check their files.
						Insert here words of common files you do not want them to share, and if found, they
						will be banned.<p>
						", hxBanSharedFiles, $hxBanSharedFiles); ?>--></tr>
						</table>
					</td>
					<td valign="top">
						<table cellpadding="0" cellspacing="0" class="config">
							<tr>
								<td align="right"><input type="submit" value="Update" class="button">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table></form>
		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close(); } ?>
</body>
</html>