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
		<!-- LAYOUT STATS TABLES -->
		<table>
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td>
								<form action="<?php echo "statssearch.php"; ?>" method="post">
								<?php hidden_value(hubID, $hubID); ?>
								<input type="submit" value="Search Stats" class="menubutton"></form>
							</td>
							<td>
								<form action="<?php echo "statsclient.php"; ?>" method="post">
								<?php hidden_value(hubID, $hubID); ?>
								<input type="submit" value="Client Stats" class="menubuttonselected"></form>
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
						<!-- CLIENT STATS LAYOUT -->
<?php
// STATS CLIENT TYPE
$totalClientsQuery=mysql_query("SELECT * from userInfo where hubID='$hubID'");
$total_clients=mysql_num_rows($totalClientsQuery);
?>
							<FIELDSET>
								<LEGEND><font color="#FFFFFF"> &nbsp; Summary of all Client Types Recorded &nbsp;</font></LEGEND>	
									<table width="100%" cellspacing="0" border="0" class="stats">
										<tr>
											<td><strong>Client</strong></td>
											<td><strong>Count</strong></td>
											<th>% of <?php echo "$total_clients"; ?> logged users</th>
										</tr>
<?php
$clientType=mysql_query("SELECT uiClient,COUNT(uiClient) from userInfo where hubID='$hubID' GROUP BY uiClient");

while ($data=mysql_fetch_array($clientType)) 
{
	$uiClient=mysql_result($clientType,$j,"uiClient");
	$COUNT=mysql_result($clientType,$j,"COUNT(uiClient)");
	if (($COUNT > "0") && ($total_clients > "0")) { $percentage = round(((100 / $total_clients) * $COUNT), 2); }
	else { $percentage = 0; }
	
$percentage_width = ($percentage * 2);

if ($percentage > "1") { $percentage_pic = "<img src=\"conf/image.php?w=${percentage_width}\" alt=\"\">"; }
else { $percentage_pic = ""; }

echo "<tr>
			<td>$uiClient</td>
			<td>$COUNT</td>
			<td class=\"stats\">$percentage_pic $percentage%</td>
		</tr>";
$j++;
}
?>
									</table>
							</FIELDSET>
						</td>
					</tr>
				</table>
				</td>
			<!-- CLIENT STATS LAYOUT -->
				<td valign="top">
				<table cellpadding="0" cellspacing="0" class="statsarea">
					<tr>
						<td>
							<FIELDSET>
								<LEGEND><font color="#FFFFFF"> &nbsp; Summary top 20 Countries of <?php echo "$total_clients users logged"; ?> &nbsp;</font></LEGEND>	
									<table width="100%" cellspacing="0" border="0" class="stats">
										<tr>
											<td><strong>Country</strong></td>
											<td><strong>Count</strong></td>
											<td><strong>Flag</strong></td>
											<td><strong>Percent of total users</strong></td>
										</tr>
<?php
$countrystats=mysql_query("SELECT uiCountry,COUNT(uiCountry) AS count FROM userInfo WHERE hubID='$hubID' GROUP BY uiCountry ORDER BY count DESC LIMIT 20");
while ($data=mysql_fetch_array($countrystats)) 
{
	$uiCountry=mysql_result($countrystats,$k,"uiCountry");
	$countryCount=mysql_result($countrystats,$k,"count");

if (($countryCount > "0") && ($total_clients > "0")) { $ctrypercentage = round(((100 / $total_clients) * $countryCount), 2); }
else { $ctrypercentage = 0; }

$ctrypercentage_width = ($ctrypercentage * 2);

if ($ctrypercentage > "0") { $ctrypercentage_pic = "<img src=\"conf/image.php?w=${ctrypercentage_width}\" alt=\"\">"; }
else { $ctrypercentage_pic = ""; }

if (!empty($uiCountry)) {$flag = "<img src=\"img/flags/${uiCountry}.PNG\" alt=\"$uiCountry\">"; }
	else {$flag = "";}

if ($ctrypercentage > "0") {
echo "	<tr>
			<td>$uiCountry</td>
			<td>$countryCount</td>
			<td>$flag</td>
			<td class=\"stats\">$ctrypercentage_pic $ctrypercentage%</td>
		</tr>";
$k++;}
else {$k++;}
}
?>
									</table>
							</FIELDSET>
						</td>
					</tr>
				</table>
				</td>
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