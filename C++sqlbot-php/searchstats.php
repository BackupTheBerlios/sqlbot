<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>C++ SqlBOT</title>
<link href="conf/sqlbot.css" rel="stylesheet" type="text/css">
<SCRIPT TYPE="text/javascript">
<!--
function confirmDelete()
{
var agree=confirm("WARNING:\nThis will delete ALL Search Logs from this hub!!\n Are you sure?");
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
	$delete_from_logChat = "DELETE FROM logSearch WHERE hubID='$hubID'";
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
			<table cellpadding="0" cellspacing="0" class="statsarea">
				<tr>
					<td>
	<!-- START USER DATABACE SPACE -->
	<?php
		
		// GET TOTALS FOR SEARCH
		$searchtotal=mysql_query("SELECT * FROM logSearch WHERE hubID='$hubID'");
		$total_search=mysql_num_rows($searchtotal);
		
		// GET FIRST ENTRY FOR SEARCH
		$first_query=mysql_query("SELECT *,DATE_FORMAT(lsTime, '%d/%m/%Y %H:%i') AS date FROM logSearch LIMIT 1");
		$firstSearchDate=mysql_result($first_query,$i,"date");

// RESET BUTTON
						echo "<form action=\"$PHP_SELF\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(offset, 0);
							hidden_value(deleteAll, 1);
						echo "<input type=\"submit\" value=\"Delete All Search Logs\" class=\"userdbnicknormal\" title=\"Delete All Search Logs\" onClick=\"return confirmDelete()\"></form>";
?>
					</td>
				</tr>
				<tr>
					<td> &nbsp; </td>
				</tr>
				<tr>
					<td>
						<!-- STATS SEARCH TYPE -->
						<FIELDSET>
							<LEGEND><font color="#FFFFFF"> &nbsp; Summary Search Types since <?php echo "$firstSearchDate"; ?> &nbsp;</font></LEGEND>	
								<table width="100%" cellspacing="0" border="0" class="stats">
									<tr>
										<th>Type</th>
										<th>Count</th>
										<th>% of <?php echo "$total_search"; ?> searches</th>
									</tr>
<?php
// STATS SEARCH TYPE
$searchType=mysql_query("SELECT lsType,COUNT(lsType) from logSearch GROUP BY lsType");

while ($data=mysql_fetch_array($searchType)) 
{
	$lsType=mysql_result($searchType,$i,"lsType");
	$COUNT=mysql_result($searchType,$i,"COUNT(lsType)");
	if ($COUNT > "0") {	$percentage = round(((100 / $total_search) * $COUNT), 2); }
	else {$percentage = 0; }
	
// DEFINE SEARCH TYPES
if ($lsType == "0") {$Type = "";}
else if ($lsType == "1") {$Type = "Any";}
else if ($lsType == "2") {$Type = "MP3";}
else if ($lsType == "3") {$Type = "Compressed";}
else if ($lsType == "4") {$Type = "Document";}
else if ($lsType == "5") {$Type = "Executable";}
else if ($lsType == "6") {$Type = "Picture";}
else if ($lsType == "7") {$Type = "Video";}
else if ($lsType == "8") {$Type = "Folder";}

$percentage_width = ($percentage * 2);

if ($percentage > "1") { $percentage_pic = "<img src=\"conf/image.php?w=${percentage_width}\" alt=\"\">"; }
else { $percentage_pic = ""; }

echo "<tr>
			<td>$Type</td>
			<td>$COUNT</td>
			<td class=\"stats\">$percentage_pic $percentage%</td>
		</tr>";
$i++;
}
?>
								</table>
						</FIELDSET>
					</td>
				</tr>
			</table>
		</td>
		<td class="main">
			<!-- TABLE TOP SEARCHES -->
			<table cellpadding="0" cellspacing="0" class="statsarea">
				<tr>
					<td>
						<FIELDSET>
							<LEGEND><font color="#FFFFFF"> &nbsp; Summary Top 10 Searches since <?php echo "$firstSearchDate"; ?> &nbsp;</font></LEGEND>
								<table width="100%" cellspacing="0" border="0" class="stats">
									<tr>
										<th>#</th>
										<th>File</th>
									</tr>
<?php
//GET TOP SEARCHES
$topSearchResults=mysql_query("SELECT lsSearch,COUNT(lsSearch) as count from logSearch WHERE hubID='$hubID' GROUP BY lsSearch ORDER BY count DESC LIMIT 10");
while ($data=mysql_fetch_array($topSearchResults)) 
{
	$COUNT=mysql_result($topSearchResults,$ts,"count");
	$lsSearch=htmlentities(mysql_result($topSearchResults,$ts,"lsSearch"));
	echo "<tr><td>$COUNT &nbsp; </td><td>$lsSearch</td></tr>";
	$ts++;
}
?>
									
								</table>
						</FIELDSET>
					</td>
				</tr>
			</table>
			<!-- END TABLE TOP SEARCHES -->
		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close(); }?>
</body>
</html>