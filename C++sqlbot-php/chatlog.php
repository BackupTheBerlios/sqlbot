<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
<SCRIPT TYPE="text/javascript">
<!--
function confirmDelete()
{
var agree=confirm("WARNING:\nThis will delete <?php echo "$uiNick"; ?> only, regardless of Level or Status!\n\n Are you sure you want to do this?");
if (agree)
	return true ;
else
	return false ;
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
echo "Error... information parsed!<p>Please return to the <a href=\"index.php\"><font color=\"blue\">index.php</font></a>";}
else {

// GET BOT NAME
$botresult=mysql_query("SELECT * FROM botConfig");
	$bcName=htmlentities(mysql_result($botresult,$i,"bcName"));
	$bcMaster=htmlentities(mysql_result($botresult,$i,"bcMaster"));



// MySQL Connection
$result=mysql_query("SELECT * FROM hubConfig WHERE hubID='$hubID'");
	$hubID=htmlentities(mysql_result($result,$i,"hubID"));
	$hcStatus=htmlentities(mysql_result($result,$i,"hcStatus"));
	$hcName=htmlentities(mysql_result($result,$i,"hcName"));
	$hcHost=mysql_result($result,$i,"hcHost");


// ADD ONLINE/OFFLINE COLOUR

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
		</td>
		<td class="main"><!-- MAIN BODY -->
		
<table cellpadding="0" cellspacing="0" class="userdbarea">
<tr>
<td>
	<!-- START USER DATABACE SPACE -->
	<?php
// GET CHAT LOGS
if (empty($offset)) { $offset = 0; }

$logtotal=mysql_query("SELECT * FROM logChat");
$total_chats=mysql_num_rows($logtotal);

$logresult=mysql_query("SELECT *,DATE_FORMAT(lcTime, '%H:%i:%S') AS time,
												DATE_FORMAT(lcTime, '%d/%m/%Y') AS date
												FROM logChat WHERE hubID='$hubID' ORDER BY lcTime DESC LIMIT $offset,$defaultLogEntries");
												
?>
<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Chat log for  <?php echo "[ $hcName ] &nbsp; $total_chats Messages in total"; ?>	&nbsp; </font></LEGEND>	
<table class="chatlog">
	<tr>
		<th>Time</th>
		<th>Nick</th>
		<th></th>
		<th>Message</th>
	</tr>
<?php

$i = ($defaultLogEntries - 1);

while ($data=mysql_fetch_array($logresult)) 
{
while ($i > -1) {

	$date=mysql_result($logresult,$i,"date");
	$time=mysql_result($logresult,$i,"time");
	$lcNick=htmlentities(mysql_result($logresult,$i,"lcNick"));
	$lcMessage=htmlentities(mysql_result($logresult,$i,"lcMessage"));
	$Message=nl2br($lcMessage);

echo "<tr>
			<td nowrap valign=\"top\">[ <a title=\"$date\" style=\"cursor:help\">$time</a> ]</td>
			<td nowrap valign=\"top\"><strong> $lcNick </strong></td>
			<td nowrap valign=\"top\"> &gt;&gt;</td>
			<td valign=\"top\" class=\"chatlog\">$Message</td>
		</tr>";



	$i--; }
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