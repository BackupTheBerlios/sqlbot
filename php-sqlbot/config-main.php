<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>ODCH Admin Control Center</title>
</head>
<body>
<?

include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Current Config for $hubname</center></h3><br><br>";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

// Delete a Row if requested
if ($function == configupdate)
{	if (empty($ud_check_opadmin)) {$ud_check_opadmin = "off";}
	$sql = "UPDATE hub_config SET value='$ud_check_opadmin' WHERE rule='check_opadmin'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_check_op)) {$ud_check_op = "off";}
	$sql = "UPDATE hub_config SET value='$ud_check_op' WHERE rule='check_op'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_check_reg)) {$ud_check_reg = "off";}
	$sql = "UPDATE hub_config SET value='$ud_check_reg' WHERE rule='check_reg'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_kick_notags)) {$ud_kick_notags = "off";}
	$sql = "UPDATE hub_config SET value='$ud_kick_notags' WHERE rule='kick_notags'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_check_mldonkey)) {$ud_check_mldonkey = "off";}
	$sql = "UPDATE hub_config SET value='$ud_check_mldonkey' WHERE rule='check_mldonkey'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_check_kicks)) {$ud_check_kicks = "off";}
	$sql = "UPDATE hub_config SET value='$ud_check_kicks' WHERE rule='check_kicks'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_post_client_check)) {$ud_post_client_check = "off";}
	$sql = "UPDATE hub_config SET value='$ud_post_client_check' WHERE rule='post_client_check'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_post_client_check)) {$ud_post_client_check = "off";}
	$sql = "UPDATE hub_config SET value='$ud_post_client_check' WHERE rule='clone_check'";
	$result = mysql_query($sql) or die(mysql_error());
	
	if (empty($ud_clone_check)) {$ud_clone_check = "off";}
	$sql = "UPDATE hub_config SET value='$ud_clone_check' WHERE rule='clone_check'";
	$result = mysql_query($sql) or die(mysql_error());

//	if (empty($ud_log_con_and_discon)) {$ud_log_con_and_discon = "off";}
//	$sql = "UPDATE hub_config SET value='$ud_log_con_and_discon' WHERE rule='log_con_and_discon'";
//	$result = mysql_query($sql) or die(mysql_error());


}
if ($function == logupdate)
{	if (empty($ud_log_kicks)) {$ud_log_kicks = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_kicks' WHERE rule='log_kicks'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_no_tags_kicks)) {$ud_log_no_tags_kicks = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_no_tags_kicks' WHERE rule='log_no_tags_kicks'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_bans)) {$ud_log_bans = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_bans' WHERE rule='log_bans'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_nukes)) {$ud_log_nukes = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_nukes' WHERE rule='log_nukes'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_connects)) {$ud_log_connects = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_connects' WHERE rule='log_connects'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_disconnects)) {$ud_log_disconnects = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_disconnects' WHERE rule='log_disconnects'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_logons)) {$ud_log_logons = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_logons' WHERE rule='log_logons'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_log_logoffs)) {$ud_log_logoffs = "off";}
	$sql = "UPDATE log_config SET value='$ud_log_logoffs' WHERE rule='log_logoffs'";
	$result = mysql_query($sql) or die(mysql_error());
}
if ($function == verbosityupdate)
{	if (empty($ud_hub_timer)) {$ud_hub_timer = "off";}
$sql = "UPDATE verbosity SET value='$ud_hub_timer' WHERE rule='hub_timer'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_kicks)) {$ud_verbose_kicks = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_kicks' WHERE rule='verbose_kicks'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_op_connect)) {$ud_verbose_op_connect = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_op_connect' WHERE rule='verbose_op_connect'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_notagkicks)) {$ud_verbose_notagkicks = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_notagkicks' WHERE rule='verbose_notagkicks'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_records)) {$ud_verbose_records = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_records' WHERE rule='verbose_records'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_banned)) {$ud_verbose_banned = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_banned' WHERE rule='verbose_banned'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_nukes)) {$ud_verbose_nukes = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_nukes' WHERE rule='verbose_nukes'";
	$result = mysql_query($sql) or die(mysql_error());

	if (empty($ud_verbose_botjoin)) {$ud_verbose_botjoin = "off";}
	$sql = "UPDATE verbosity SET value='$ud_verbose_botjoin' WHERE rule='verbose_botjoin'";
	$result = mysql_query($sql) or die(mysql_error());

}?>
<!-- Hub Config and Hub Verbosity tables -->
<table width="100%" cellspacing="4"><tr><td valign="top">
<?$query="SELECT * FROM hub_config ORDER by rowID ASC";
$result=mysql_query($query);
$num=mysql_num_rows($result);
echo "$font<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
<b>Hub Configuration</b>&nbsp;<form action=\"config-hub.php\" method=\"post\">
	<input type=\"Submit\" value=\"EDIT\"></form>";
$i=0;
while ($i < $num) {
	$rowID=mysql_result($result,$i,"rowID");
	$value=mysql_result($result,$i,"value");
	$description=mysql_result($result,$i,"description");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	?>
		<td nowrap><?echo "$font$description$fontend ";?></td>
		<td nowrap><?echo "$font( $value )$fontend";?></td>
</tr><? ++$i; } $fontend ?>
</table>
</td>
<td valign="top"><!-- Second columb -->
<?
$query="SELECT * FROM verbosity ORDER by rowID ASC";
$result=mysql_query($query);
$num=mysql_num_rows($result);

echo "$font<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
<b>Hub Verbostiy Configuration</b>&nbsp;<form action=\"config-verbosity.php\" method=\"post\">
	<input type=\"Submit\" value=\"EDIT\"></form>";
$i=0;
while ($i < $num) {
	$rowID=mysql_result($result,$i,"rowID");
	$value=mysql_result($result,$i,"value");
	$description=mysql_result($result,$i,"description");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	?>
		<td nowrap><?echo "$font$description $fontend";?></td>
		<td nowrap><?echo "$font( $value )$fontend";?></td>
	</tr>

<? ++$i; } $fontend ?></table></td>
</td><td valign="top"><!-- Third columb -->
<?$query="SELECT * FROM log_config ORDER by rowID ASC";
$result=mysql_query($query);
$num=mysql_num_rows($result);
mysql_close();
echo "$font<table border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
<b>Log Configuration</b>&nbsp;<form action=\"config-log.php\" method=\"post\">
	<input type=\"Submit\" value=\"EDIT\"></form>";
$i=0;
while ($i < $num) {
	$rowID=mysql_result($result,$i,"rowID");
	$value=mysql_result($result,$i,"value");
	$description=mysql_result($result,$i,"description");
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	?>
		<td nowrap><?echo "$font$description$fontend ";?></td>
		<td nowrap><?echo "$font( $value )$fontend";?></td>
	</tr>
<? ++$i; } $fontend ?></table></td></tr></tr></table>

<!-- End Hub Config and Hub Verbosity tables -->
<? echo "$fontend";?>
</body>
</html>

