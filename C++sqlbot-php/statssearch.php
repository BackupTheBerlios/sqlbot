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
 function utime (){
 // WORK OUT LOAD TIME (FOR INTEREST)
$time = explode( " ", microtime());
$usec = (double)$time[0];
$sec = (double)$time[1];
return $sec + $usec;
}
$start = utime();

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
		<table>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<form action="<?php echo "statssearch.php"; ?>" method="post">
								<?php hidden_value(hubID, $hubID); ?>
								<input type="submit" value="Search Stats" class="menubuttonselected"></form>
							</td>
							<td>
								<form action="<?php echo "statsclient.php"; ?>" method="post">
								<?php hidden_value(hubID, $hubID); ?>
								<input type="submit" value="Client Stats" class="menubutton"></form>
							</td>
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
			<tr>
			<td valign="top">
					<table cellpadding="0" cellspacing="0" class="statsarea">
						<tr>
							<td>
	<!-- START USER DATABACE SPACE -->
	<?php
		
		// GET TOTALS FOR SEARCH
		$searchtotal=mysql_query("SELECT * FROM logSearch WHERE hubID='$hubID'");
		$total_search=mysql_num_rows($searchtotal);
		
		// GET FIRST ENTRY FOR SEARCH
		$first_query=mysql_query("SELECT *,DATE_FORMAT(lsTime, '%d/%m/%Y %H:%i') AS date FROM logSearch WHERE hubID='$hubID' LIMIT 1");
		$first_query_total=mysql_num_rows($first_query);
		if ($first_query_total > "0") { $firstSearchDate=mysql_result($first_query,$i,"date"); }

// RESET BUTTON
						echo "<form action=\"$PHP_SELF\" method=\"post\">";
							hidden_value(hubID, $hubID);
							hidden_value(offset, 0);
							hidden_value(deleteAll, 1);
						echo "<input type=\"submit\" value=\"[ Delete All Search Logs] \" class=\"deldata\" title=\"Delete All Search Logs\" onClick=\"return confirmDelete()\"></form>";
?>
							</td>
						</tr>
						<tr>
							<td> &nbsp; </td>
						</tr>
						<tr>
							<td>
						<!-- FILE SEARCH STATS SEARCH TYPE -->
							<FIELDSET>
								<LEGEND><font color="#FFFFFF"> &nbsp; Summary Search Types since <?php echo "$firstSearchDate"; ?> &nbsp;</font></LEGEND>	
									<table width="100%" cellspacing="0" border="0" class="stats">
										<tr>
											<td><strong>Type</strong></td>
											<td><strong>Count</strong></td>
											<th>% of <?php echo "$total_search"; ?> searches</th>
										</tr>
<?php
// STATS SEARCH TYPE
$searchType=mysql_query("SELECT lsType,COUNT(lsType) from logSearch where hubID='$hubID' GROUP BY lsType");

while ($data=mysql_fetch_array($searchType)) 
{
	$lsType=mysql_result($searchType,$i,"lsType");
	$COUNT=mysql_result($searchType,$i,"COUNT(lsType)");
	if (($COUNT > "0") && ($total_search > "0")) { $percentage = round(((100 / $total_search) * $COUNT), 2); }
	else { $percentage = 0; }
	
// DEFINE SEARCH TYPES
if ($lsType == "0") {$Type = "";}
else if ($lsType == "1") {$Type = "Any";}
else if ($lsType == "2") {$Type = "Audio";}
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
			<!-- FILE SEARCH STATS LAYOUT -->
			</td>
			<td valign="top">
				<!-- TABLE TOP SEARCHES -->
				<table cellpadding="0" cellspacing="0" class="statsarea">
					<tr>
						<td>
							<FIELDSET>
<?php //DEFINE SEARCH TYPES
if ($searchfield == "") {$SearchType = "All"; $parse_option = "";}
else if ($searchfield == "1") {$SearchType = "Any"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "2") {$SearchType = "Audio"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "3") {$SearchType = "Compressed"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "4") {$SearchType = "Document"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "5") {$SearchType = "Executable"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "6") {$SearchType = "Picture"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "7") {$SearchType = "Video"; $parse_option = "&& lsType='$searchfield'";}
else if ($searchfield == "8") {$SearchType = "Folder"; $parse_option = "&& lsType='$searchfield'";}

function isselected($searchfield, $number, $meaning) {
if ($number == "$searchfield") { echo "\n<option value=\"$number\" selected>$meaning"; }
else { echo "\n<option value=\"$number\">$meaning";}
}

?>
								<LEGEND><font color="#FFFFFF"> &nbsp; Top 10 Searches
												since <?php echo "$firstSearchDate"; ?> &nbsp;</font></LEGEND>
									<table width="100%" cellspacing="0" border="0" class="stats">
										<tr>
											<td><strong>Count</strong></td>
											<td align="right" nowrap>
<?php
// DEFINE SEARCH TYPES
	echo "<form action=\"$PHP_SELF\" method=\"post\">";
				hidden_value(hubID, $hubID);
	echo 	"<select name=\"searchfield\" class=\"form_select_long\" onchange=\"submit();\">";
					isselected($searchfield, "", "- All Combined");
					isselected($searchfield, 1, Any);
					isselected($searchfield, 2, Audio);
					isselected($searchfield, 3, Compressed);
					isselected($searchfield, 4, Document);
					isselected($searchfield, 5, Executable);
					isselected($searchfield, 6, Picture);
					isselected($searchfield, 7, Video);
					isselected($searchfield, 8, Folder);
		echo "</select>
				<input type=\"submit\" value=\"Go\" class=\"userdbnicknormal\" title=\"View Selection\"></form>";

?>
											</td>
										</tr>
<?php
//GET TOP SEARCHES
$topSearchResults=mysql_query("SELECT lsSearch,COUNT(lsSearch) as count from logSearch WHERE hubID='$hubID' $parse_option GROUP BY lsSearch ORDER BY count DESC LIMIT 10");
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
		</td>
	</tr>
</table>
<!-- END MAIN TABLE -->
<?php mysql_close();
// SHOW LOAD TIME
$end = utime(); $run = $end - $start; echo "<em>Loaded in " . substr($run, 0, 5) . "s</em>";
}?>
</body>
</html>