<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
<script TYPE="text/javascript">
<!--
function doselectAll(theBox){
  xState=theBox.checked;
  elm=theBox.form.elements;
  for(i=0;i<elm.length;i++)
   if(elm[i].type=="checkbox")
     elm[i].checked=xState;
}
//-->
</script>
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

if ($update == "1") {
$total_kick = 	$kick1+$kick2+$kick3+$kick4+$kick5+$kick6+$kick7+$kick8+
				$kick9+$kick10+$kick11+$kick12+$kick13+$kick14;

$total_ban = 	$ban1+$ban2+$ban3+$ban4+$ban5+$ban6+$ban7+$ban8+
				$ban9+$ban10+$ban11+$ban12+$ban13+$ban14;

$total_join = 	$join1+$join2+$join3+$join4+$join5+$join6;

$update_into_hubConfig = "UPDATE hubConfig SET
		hcVerboseJoin='$total_join',
		hcVerboseKick='$total_kick',
		hcVerboseBan='$total_ban'
	WHERE hubID='$hubID'";
$result = mysql_query($update_into_hubConfig) or die(mysql_error());
}




// MySQL Connection
$result=mysql_query("SELECT * FROM hubConfig WHERE hubID='$hubID'");
	$hubID=htmlentities(mysql_result($result,$i,"hubID"));
	$hcName=htmlentities(mysql_result($result,$i,"hcName"));
	$hcVerboseJoin=mysql_result($result,$i,"hcVerboseJoin");
	$hcVerboseKick=mysql_result($result,$i,"hcVerboseKick");
	$hcVerboseBan=mysql_result($result,$i,"hcVerboseBan");



	function check($name, $value, $description, $hcVerboseKick) {
	if($hcVerboseKick&$value) { $isset = "Checked"; }
	echo" 		<td>$description</td>
		<td><input type=\"checkbox\" name=\"$name\" $isset value=\"$value\"></td>";
	}
/*	
	ehik_None           = 0x00, 		0
     ehik_UnTagged       = 0x01, 		1
     ehik_Share          = 0x02, 			2
     ehik_MxHubs         = 0x04, 		4
     ehik_MxSlots        = 0x08, 			8
     ehik_MnSlots        = 0x10, 			16
     ehik_SlotRatio      = 0x20, 			32
     ehik_HackedTag      = 0x40, 		64
     ehil_MinConnection  = 0x80, 		128
     ehik_BadWord        = 0x100, 		256
     ehik_IllegalSearch  = 0x200, 		512
     ehik_IllegalShare   = 0x400, 		1024
     ehil_BanNick        = 0x800, 		2048
     ehik_Clone          = 0x1000, 			4096
     ehik_Operator       = 0x2000		8192
*/
?>
<!-- TOP BANNER -->
<table class="top">
	<tr>
		<td align="center" >C++ SqlBOT</td>
	</tr>
</table>
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
									<input type="submit" value="Hub Verbosity" class="menubuttonselected"></form>
								</td>
								<td>
									<form action="<?php echo "hubextras.php"; ?>" method="post">
									<?php hidden_value(hubID, $hubID); ?>
									<input type="submit" value="Hub Extras" class="menubutton"></form>
								</td>
							<tr>
						</table>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
<form action="<?php echo "$PHP_SELF"; ?>" method="post">
<table>
	<tr>
		<td valign="top">
			<table class="statsarea">
				<tr>
					<td>
					<FIELDSET><LEGEND><font color="#FFFFFF"> &nbsp; Kick Verbosity in Main Chat &nbsp;</font></LEGEND>
						<table class="stats">
							<tr>
								<td align="center">Select All Options<input type="hidden" name="hubID" value="<?php echo "$hubID"; ?>"></td>
								<td><input type="hidden" name="update" value="1">
								<INPUT type="checkbox" id="SelectALL" name="SelectALL" onClick='doselectAll(this)' ></td>
							</tr>
							<tr><?php check(kick1, 1, "Show No-Tag Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick2, 2, "Show Share Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick3, 4, "Show Max-Hubs Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick4, 8, "Show Max-Slots Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick5, 16, "Show Min-Slots Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick6, 32, "Show Slot-Ratio Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick7, 64, "Show Hacked-Tag Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick8, 128, "Show Min-Connetion Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick9, 256, "Show Bad-Word Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick10, 512, "Show Illegal-Search Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick11, 1024, "Show Illegal-Share Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick12, 2048, "Show Illegal-Nick Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick13, 4096, "Show Clone Kicks", $hcVerboseKick); ?></tr>
							<tr><?php check(kick14, 8192, "Show Operator's Kicks", $hcVerboseKick); ?></tr>
						</table>
					</FIELDSET>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table class="statsarea">
				<tr>
					<td>
					<FIELDSET><LEGEND><font color="#FFFFFF"> &nbsp; Ban Verbosity in Main Chat &nbsp;</font></LEGEND>
						<table class="stats">
							<tr>
								<td></td>
								<td></td>
							</tr>
							<tr><?php check(ban1, 1, "Show No-Tag Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban2, 2, "Show Share Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban3, 4, "Show Max-Hubs Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban4, 8, "Show Max-Slots Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban5, 16, "Show Min-Slots Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban6, 32, "Show Slot-Ratio Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban7, 64, "Show Hacked-Tag Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban8, 128, "Show Min-Connetion Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban9, 256, "Show Bad-Word Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban10, 512, "Show Illegal-Search Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban11, 1024, "Show Illegal-Share Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban12, 2048, "Show Illegal-Nick Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban13, 4096, "Show Clone Bans", $hcVerboseBan); ?></tr>
							<tr><?php check(ban14, 8192, "Show Operator's Bans", $hcVerboseBan); ?></tr>
						</table>
					</FIELDSET>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table class="statsarea">
				<tr>
					<td>
					<FIELDSET><LEGEND><font color="#FFFFFF"> &nbsp; Join Verbosity in Main Chat &nbsp;</font></LEGEND>
						<table class="stats">
							<tr><?php check(join1, 1, "Show User Joins", $hcVerboseJoin); ?></tr>
							<tr><?php check(join2, 2, "Show VIP Joins", $hcVerboseJoin); ?></tr>
							<tr><?php check(join3, 4, "Show Operator Joins", $hcVerboseJoin); ?></tr>
							<tr><?php check(join4, 8, "Show Op-Admin Joins", $hcVerboseJoin); ?></tr>
							<tr><?php check(join5, 16, "Show Bot-Master Joins", $hcVerboseJoin); ?></tr>
							<tr><?php check(join6, 32, "Show Bot Joins", $hcVerboseJoin); ?></tr>
							<tr><td><a href="javascript:CheckAll();">Check All</a></td><td></td></tr>
							<tr>
								<td><input type="submit" value="Update" class="menubutton"></td>
								<td></td>
							</tr>	
						</table>
					</FIELDSET>
					</td>
				</tr>
			</table>
		</td>
		<td></td>
	</tr>
</table>
</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>









<?php mysql_close(); } ?>
</body>
</html>