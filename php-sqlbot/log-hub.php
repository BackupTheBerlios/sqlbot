<?
$page_title="Hub Log";
include("header.ini");
?>
<br>
	<div align="center"><form action="log-hub.php" method="post">
	<input class="button" type="Submit" value="Refresh"></form><br>
<div align="center"><?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if (!empty($nicksearch))  
	{$wheresearch="nick LIKE '%$nicksearch%'";
	$ipsearch = "";
	$clisearch = "";
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
{	$sql = "DELETE FROM hubLog WHERE rowID=$id"; 
	$result = mysql_query($sql) or die(mysql_error());}
// Delete ALL rows
if ($delete == log)
{	$sql = "DELETE FROM hubLog $where";
	$result = mysql_query($sql) or die(mysql_error());}
$query="SELECT * FROM hubLog $where ORDER by rowID DESC LIMIT 50";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();?>
<b>Apply Filers</b>
<table> 
	<td nowrap><form method="get" class='inline' action="log-hub.php">
		Nick Search<input  TYPE="text" VALUE="<? echo "$nicksearch";?>" NAME="nicksearch" SIZE="30" MAXLENGTH="50" >
	
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
				<option value="Restart">Reload Scripts</option>
				<option value="un-Ban">Un Ban</option>
				<option value="Add/Edit User">Add/Edit User</option>
				<option value="Del User">Del User</option>
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
				<option value="Limiter">Limiter</option>
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
		{echo "<b> $afilter $rfilter $nicksearch </b>";}
echo "<br>Total Number of Matching Entries <b>$numrows</b><br>"; ?>

<table border="<? echo "$tableborders";?> cellspacing="2" cellpadding="2"> 
<tr>  
	<th><? echo "$font";?>Nick<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Date Time<? echo "$fontend";?></th>
	<th><? echo "$font";?>Action<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Reason<? echo "$fontend";?></th> 
	<th>
	<form action="<? echo "log-hub.php?delete=log&offset=$offset&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form></th>
</tr> 
<? 
while ($data=mysql_fetch_array($result)) 
{ 	// include code to display results as you see fit
	
	$id=mysql_result($result,$i,"rowID");
	$nick=mysql_result($result,$i,"nick");
	$logTime=mysql_result($result,$i,"logTime");
	$action=mysql_result($result,$i,"action");
	$reason=mysql_result($result,$i,"reason");

	
	// Colour Rows
	if(($action == "T-Banned") || ($action== "P-Banned")) {echo "<TR bgcolor="; echo "$BanRowColour"; echo ">\n";}
	else if($action == Kicked) {echo "<TR bgcolor="; echo "$KickRowColour"; echo ">\n";}
	else if($i % 2)	{echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";}
	else{echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n";}

	?>
	<form action="<? echo "log-hub.php?delete=logrow&id=$id&offset=$offset&nicksearch=$nicksearch&afilter=$afilter&rfilter=$rfilter" ?>" method="post"> 
	<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$nick$fontend"; ?></a></td>
	<td nowrap><? echo "$font$logTime$fontend"; ?></td> 
	<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$action$fontend"; ?></a></td>
	<td nowrap><? echo "$font$reason$fontend"; ?></td>
	<td nowrap><center><input type="Submit" value="Delete"></center></td>
	</form>
	</tr>
	<? $i++; } 
echo "</table>";
?></div>


<? if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"user-manage.php?offset=$prevoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"user-manage.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"user-manage.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">NEXT</a><p>\n";
}

?></div>


<? echo "$fontend";?>
</body>
</html>
