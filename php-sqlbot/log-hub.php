<?
$page_title="Hub Log";
include("header.ini");
?>



<div align="center"><?
$entry=0; 
include("dbinfo.inc.php");
echo "$font";
$limit=$defaultLogEntries; 

mysql_connect($databasehost,$username,$password); 
@mysql_select_db($database) or die( "Unable to select database");

$wheresearch = "";
$wherefilter = "";
$and = 0;

if (!empty($nicksearch))  {
	$wheresearch="name LIKE '%$nicksearch%'";
	$ipsearch = "";
	$clisearch = "";
}
else if (!empty($ipsearch)) {
	$wheresearch="ip LIKE '%$ipsearch%'";
	$nicksearch = "";
	$clisearch = "";
}
else if (!empty($clisearch))  {
	$wheresearch="client LIKE '%$clisearch%'";
	$ipsearch = "";
	$nicksearch = "";
}

if ((!empty($rfilter)) && (!empty($afilter))){
	$wherefilter="reason='$rfilter' AND action='$afilter'";}
else if (!empty($afilter))  {
	$wherefilter="action='$afilter'";}
else if (!empty($rfilter)){
	$wherefilter="reason='$rfilter'";}

if ((!empty($wheresearch)) && (!empty($wherefilter))){$where = "WHERE $wheresearch AND $wherefilter"; }
else if (!empty($wheresearch)) {$where = "WHERE $wheresearch";}
else if (!empty($wherefilter)) {$where = "WHERE $wherefilter";}
else {$where = ""; }
// Delete a Row if requested
if ($delete == logrow)
{	$sql = "DELETE FROM log WHERE rowID=$id"; 
	$result = mysql_query($sql) or die(mysql_error());}
// Delete ALL rows
if ($delete == log)
{	$sql = "DELETE FROM log $where";
	$result = mysql_query($sql) or die(mysql_error());}

$numresults=mysql_query("SELECT * FROM log $where ");
$numrows=mysql_num_rows($numresults);

if (empty($offset)) {$offset=0;}
$result=mysql_query("SELECT * FROM log $where  ORDER by rowID DESC  LIMIT $offset,$defaultLogEntries");?> 

<b>Apply Filers</b>
<table> 
	<td nowrap><form method="get" class='inline' action="log-hub.php">
		Nick Search<input  TYPE="text" VALUE="<? echo "$nicksearch";?>" NAME="nicksearch" SIZE="30" MAXLENGTH="50" >
		IP Search<input  TYPE="text" VALUE="<? echo "$ipsearch";?>" NAME="ipsearch" SIZE="20" MAXLENGTH="20" >
		Client Search<input  TYPE="text" VALUE="<? echo "$clisearch";?>" NAME="clisearch" SIZE="20" MAXLENGTH="20" >
	
	
		<tr>Action Filter</tr>
		<tr>
			<select name='afilter'>
				<option value="">No Filter</option>
				<option value="Kicked">Kicked</option>
				<option value="Banned">Banned</option>
				<option value="Nuked">Nuked</option>
				<option value="LogOn">Log On</option>
				<option value="LogOff">Log Off</option>
				<option value="Connect">Connect</option>
				<option value="Disconnect">Disconnect</option>
			</select>
		</tr>
		<tr>Reason Filter</tr>
		<tr>
			<select name='rfilter'>
				<option value="">No Filter</option>
				<option value="NoTags">No Tags</option>
				<option value="10 kicks">10 Kicks</option>
				<option value="Version">Version</option>
				<option value="Hubs">Hubs</option>
				<option value="Slots(min)">Slots (Min)</option>
				<option value="Slots(max)">Slots (Max)</option>
				<option value="Share">Share</option>
				<option value="SlotRatio">Slot Ratio</option>
				<option value="Connection">Connection</option>
				<option value="Clone">Clone</option>
			</select>
		</tr>
	</td>
	<td nowrap>
		<tr><input type="submit" value="Apply"></tr>
	</td>
</form>
</td>
</table>
<p>Filters Applied <? 
	if ((empty($rfilter)) && (empty($afilter)) && (empty($nicksearch))&& (empty($ipsearch))&& (empty($clisearch)) )
		{echo "None";}
	else
		{echo "<b> $afilter $rfilter $nicksearch $ipsearch $clisearch </b>";}
echo "<br>Total Number of Matching Entries <b>$numrows</b><br>"; ?>

<table border="<? echo "$tableborders";?> cellspacing="2" cellpadding="2"> 
<tr>  
	<th><? echo "$font";?>Entry<? echo "$fontend";?></th>
	<th><? echo "$font";?>Date<? echo "$fontend";?></th>
	<th><? echo "$font";?>Time<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Action<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Reason<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Nick<? echo "$fontend";?></th> 
	<th><? echo "$font";?>IP<? echo "$fontend";?></th>
	<th><? echo "$font";?>Country<? echo "$fontend";?></th>
	<th><? echo "$font";?>Client<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Version<? echo "$fontend";?></th>
	<th><? echo "$font";?>Description<? echo "$fontend";?></th>
	<th><? echo "$font";?>Connection<? echo "$fontend";?></th> 
	<th><? echo "$font";?># Hubs<? echo "$fontend";?></th> 
	<th><? echo "$font";?># Slots<? echo "$fontend";?></th>
	<th><? echo "$font";?>Shared [Gb]<? echo "$fontend";?></th>
	<th>
	<form action="<? echo "log-hub.php?delete=log&offset=$offset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form></th>
</tr> 
<? 
while ($data=mysql_fetch_array($result)) 
{ 	// include code to display results as you see fit
	
	$id=mysql_result($result,$i,"rowID");
	$date=mysql_result($result,$i,"date");
	$time=mysql_result($result,$i,"time");
	$action=mysql_result($result,$i,"action");
	$reason=mysql_result($result,$i,"reason");
	$name=mysql_result($result,$i,"name");
	$ip=mysql_result($result,$i,"ip");
	$country=mysql_result($result,$i,"country");
	$client=mysql_result($result,$i,"client");
	$client_version=mysql_result($result,$i,"client_version");
	$fulldescription=htmlentities(mysql_result($result,$i,"fulldescription"));
	$connection=mysql_result($result,$i,"connection");
	$connected_hubs=mysql_result($result,$i,"connected_hubs");
	$upload_slots=mysql_result($result,$i,"upload_slots");
	$shared_gigs=mysql_result($result,$i,"shared_gigs");
	
	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; } 
	?>
<!-- 		<form action="<? echo "log-hub.php?delete=log&offset=$offset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter" ?>" method="post"> -->
	<td nowrap><? echo "$font$id$fontend"; ?></td>
	<td nowrap><? echo "$font$date$fontend"; ?></td> 
	<td nowrap><? echo "$font$time$fontend"; ?></td> 
	<td nowrap><? echo "$font$action$fontend"; ?></td> 
	<td nowrap><? echo "$font$reason$fontend"; ?></td> 
	<td nowrap><? echo "$font$name$fontend"; ?></td>
	<td nowrap><? echo "$font$ip$fontend"; ?></td> 
	<td nowrap><? echo "$font$country$fontend"; ?></td> 
	<td nowrap><? echo "$font$client$fontend"; ?></td> 
	<td nowrap><? echo "$font$client_version$fontend"; ?></td> 
	<td nowrap><? echo "$font$fulldescription$fontend"; ?></td> 
	<td nowrap><? echo "$font$connection$fontend"; ?></td>
	<td nowrap><? echo "$font$connected_hubs$fontend"; ?></td>
	<td nowrap><? echo "$font$upload_slots$fontend"; ?></td>
	<td nowrap><? echo "$font$shared_gigs$fontend"; ?></td> 
	<td nowrap><center><input type="Submit" value="Delete"></center></td>
	</form>
	</tr>
	<? $i++; } 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"$hublog?offset=$prevoffset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"log-hub.php?offset=$newoffset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages) && $pages!=1) {
    $newoffset=$offset+$limit;
    print "<a href=\"log-hub?offset=$newoffset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter\">NEXT</a><p>\n";
}
mysql_close();
?></div>
<? echo "$fontend";?>
</body>
</html>
