<?
$page_title="Overall User Stats";
include("header.ini");
?>

<div align="center"><?
$entry=0;
$limit=$defaultLogEntries; 
echo "$font";
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
$numresults=mysql_query("SELECT * FROM user_stats $where");
$numrows=mysql_num_rows($numresults);
if (empty($offset)) {
	$offset=0;}

$result=mysql_query("SELECT * FROM user_stats $where ORDER by total_logins DESC  LIMIT $offset,$defaultLogEntries");
?>
<b>Apply Filers</b>
<table> 
	<tr><td nowrap><form method="get" class='inline' action="stats-user.php">
		Nick Search<input  TYPE="text" VALUE="<? echo "$nicksearch";?>" NAME="nicksearch" SIZE="30" MAXLENGTH="50" >
		IP Search<input  TYPE="text" VALUE="<? echo "$ipsearch";?>" NAME="ipsearch" SIZE="20" MAXLENGTH="20" >
		Client Search<input  TYPE="text" VALUE="<? echo "$clisearch";?>" NAME="clisearch" SIZE="20" MAXLENGTH="20" >
	</td>
	<td nowrap>
		<tr><input type="submit" value="Apply"></tr>
	</td></tr>
</form>
</td>
</table>		
<?
echo "Totals :Users $numrows, Share TODO [GB]<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2">
<tr>
<th><? echo "$font";?>First Date<? echo "$fontend";?></th>
<th><? echo "$font";?>First Time<? echo "$fontend";?></th>
<th><? echo "$font";?>Nick<? echo "$fontend";?></th>
<th><? echo "$font";?>Last Ip<? echo "$fontend";?></th>
<th><? echo "$font";?>Total Connects<? echo "$fontend";?></th>
<th><? echo "$font";?>Average Share [Gb]<? echo "$fontend";?></th>
<th><? echo "$font";?>Last seen Date<? echo "$fontend";?></th>
<th><? echo "$font";?>& Time<? echo "$fontend";?></th>
</tr>
<?
while ($data=mysql_fetch_array($result)) 
{
	$first_date=mysql_result($result,$i,"first_date");
	$first_time=mysql_result($result,$i,"first_time");
	$name=mysql_result($result,$i,"name");
	$last_ip=mysql_result($result,$i,"last_ip");
	$total_logins=mysql_result($result,$i,"total_logins");
	$average_shared_gigs=mysql_result($result,$i,"average_shared_gigs");	
	$last_date=mysql_result($result,$i,"last_date");
	$last_time=mysql_result($result,$i,"last_time");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
	?>
	<td nowrap><? echo "$font$first_date$fontend"; ?></td>
	<td nowrap><? echo "$font$first_time$fontend"; ?></td>
	<td nowrap><? echo "$font$name$fontend"; ?></td>
	<td nowrap><? echo "$font$last_ip$fontend"; ?></td>
	<td nowrap><? echo "$font$total_logins$fontend"; ?></td>
	<td nowrap><? echo "$font$average_shared_gigs$fontend"; ?></td>
	<td nowrap><? echo "$font$last_date$fontend"; ?></td>
	<td nowrap><? echo "$font$last_time$fontend"; ?></td>
	</tr>
	<?
	$i++;
} 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"?m=$menuname&p=$path/userstats&offset=$prevoffset&offset=$offset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"?m=$menuname&p=$path/userstats&offset=$newoffset&offset=$offset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"?m=$menuname&p=$path/userstats&offset=$newoffset&offset=$offset&ipsearch=$ipsearch&clisearch=$clisearch&nicksearch=$nicksearch\">NEXT</a><p>\n";
}
mysql_close();
?></div>
<? echo "$fontend";?>
</body>
</html>
