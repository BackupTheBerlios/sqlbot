<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
<SCRIPT TYPE="text/javascript">
<!--
function confirmDelete()
{
var agree=confirm("WARNING:\nThis will delete ALL Chat Logs from this hub!!\n Are you sure?");
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

if ($deleteAll == "1" ) {
	$delete_from_logChat = "DELETE FROM logChat WHERE hubID='$hubID'";
	$result = mysql_query($delete_from_logChat) or die(mysql_error());
}

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

$logtotal=mysql_query("SELECT * FROM logChat where hubID='$hubID'");
$total_chats=mysql_num_rows($logtotal);

$logresult=mysql_query("SELECT *,DATE_FORMAT(lcTime, '%H:%i:%S') AS time,
												DATE_FORMAT(lcTime, '%d/%m/%Y') AS date
												FROM logChat WHERE hubID='$hubID' ORDER BY lcTime DESC LIMIT $offset,$defaultLogEntries");


// DELETE BUTTON
						echo "<form action=\"$PHP_SELF\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(offset, 0);
							hidden_value(deleteAll, 1);
						echo "<input type=\"submit\" value=\"[ Delete All Chat Logs ]\" class=\"deldata\" title=\"Delete All Chat Logs\" onClick=\"return confirmDelete()\"></form>";
?>


<FIELDSET>
	<LEGEND><font color="#FFFFFF"> &nbsp; Chat log for  <?php echo "[ $hcName ] &nbsp; $total_chats Messages in total"; ?>	&nbsp; </font></LEGEND>
<table width="100%">
<tr>
<td>
<table class="chatlog">
	<tr>
		<th>Time</th>
		<th>Nick</th>
		<th></th>
		<th>Message</th>
	</tr>
<?php
// ENTRIES TO PARSE - 100% HACK!
if ($total_chats < $defaultLogEntries) { $i = ($total_chats - 1); }
else { $i = ($defaultLogEntries - 1); }
if (($offset + $defaultLogEntries) > $total_chats) { $i = ($total_chats - $offset -1 ); }

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
			<td nowrap valign=\"top\">
				<form action=\"userinfo.php\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(uiNick, $lcNick);
	echo "<input type=\"submit\" value=\"$lcNick\" class=\"userdbOP\" title=\"View User's Data\"></form></td>
			<td nowrap valign=\"top\"> &gt;&gt;</td>
			<td valign=\"top\" class=\"chatlog\">$Message</td>
		</tr>";



	$i--; }
}



?>
</table>
</td>
<td width="14" valign="top">
<!-- PAGE UP / DOWN BUTTONS -->
		<table cellpadding="0" cellspacing="0" width="14">
		<tr>
			<td height="20">
				<?php
				// CODE FOR FIRST PAGE BUTTON
				$last_divide = floor($total_chats / $defaultLogEntries);
				$last_offset = $last_divide * $defaultLogEntries;
				$is_there_a_last = $total_chats - $offset;
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
							<input type="image" value=">>>" src="img/allup.png" title="First Page"></form>
				<?php ;} ?>
			</td>
		</tr>
		<tr>
			<td height="15">
				<?php
				// CODE FOR PREVIOUS PAGE
				$offset_value = $offset + $defaultLogEntries;
				$is_there_next = ($total_chats -$offset_value) /$defaultLogEntries;
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
							<input type="image" value=">>" src="img/pageup.png" title="Previous Page"></form>
				<?php ;} ?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<?php
				// CODE FOR NEXT PAGE
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
							<input type="image" value="<<" src="img/pagedown.png" title="Next Page"></form>
				<?php ;} ?>
			</td>
		</tr>
			<tr>
			<td>
				<?php
				// CODE FOR LAST PAGE BUTTON
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
							<input type="image" value="<<<" src="img/alldown.png" title="Newest Page"></form>
				<?php ;} ?>
			</td>
		</tr>
	</table>
<!-- END PAGE UP / DOWN BUTTONS -->
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