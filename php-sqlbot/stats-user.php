<? $page_title="Overall User Stats";include("header.ini");?>

<div align="center"><?
$entry=0;$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$where = "";
if (!empty($nicksearch))  
	{$where="WHERE name LIKE '%$nicksearch%'";$ipsearch = "";}
else if (!empty($ipsearch)) 
	{$where="WHERE ip LIKE '%$ipsearch%'";$nicksearch = "";}
else {$where = ""; }
if ($function == delete)
	{$sql = "DELETE FROM user_stats $where";$result = mysql_query($sql) or die(mysql_error());}

$numresults=mysql_query("SELECT * FROM user_stats $where");
$numrows=mysql_num_rows($numresults);
if (empty($offset)) {$offset=0;}

$result=mysql_query("SELECT * FROM user_stats $where ORDER by total_logins DESC LIMIT $offset,$defaultLogEntries");
?>
<b>Filter Users Stats</b>
<table> 
	<tr><td nowrap><form method="get" class='inline' action="stats-user.php">
		Nick Search<input  TYPE="text" VALUE="<? echo "$nicksearch";?>" NAME="nicksearch" SIZE="30" MAXLENGTH="50" >
		IP Search<input  TYPE="text" VALUE="<? echo "$ipsearch";?>" NAME="ipsearch" SIZE="20" MAXLENGTH="20" >
		<input type="submit" value="Apply"></form>
		</td>
	<td nowrap>
		
<form action="<? echo "stats-user.php?function=delete&ipsearch=$ipsearch&nicksearch=$nicksearch" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form>
</td></tr>
</form>
</td>
</table>		
<?
echo "Totals :Users $numrows<br>";
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
    print "<a href=\"stats-user.php?offset=$prevoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"stats-user.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"stats-user.php?offset=$newoffset&ipsearch=$ipsearch&nicksearch=$nicksearch\">NEXT</a><p>\n";
}
mysql_close();
?></div>
<? echo "$fontend";?>
</body>
</html>
