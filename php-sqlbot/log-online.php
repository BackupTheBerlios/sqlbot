<?
$page_title="View Online clients";
include("header.ini");
?>
<br>
	<div align="center"><form action="log-online.php" method="post">
	<input class="button" type="Submit" value="Refresh"></form><br>
<?
include("dbinfo.inc.php");
$limit=$defaultLogEntries; 
echo "$font";
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");


$numresults=mysql_query("SELECT * FROM online ");
$numrows=mysql_num_rows($numresults);
if (empty($offset)) {$offset=0;}

// Delete a Row if requested
if ($delete == onlinerow)
{	$sql = "DELETE FROM online WHERE rowID=$id"; 
	$result = mysql_query($sql) or die(mysql_error());}

$result=mysql_query("SELECT * FROM online ORDER by name LIMIT $offset,$defaultLogEntries");
echo "Total Number of users online $numrows<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2">
<tr> 
<th><? echo "$font";?>#<? echo "$fontend";?></th>
<th><? echo "$font";?>Date<? echo "$fontend";?></th>
<th><? echo "$font";?>Time<? echo "$fontend";?></th>
<th><? echo "$font";?>Nick<? echo "$fontend";?></th>
<th><? echo "$font";?>User Type<? echo "$fontend";?></th>
<th><? echo "$font";?>IP<? echo "$fontend";?></th>
<th><? echo "$font";?>Country<? echo "$fontend";?></th>
<th><? echo "$font";?>Client<? echo "$fontend";?></th>
<th><? echo "$font";?>Version<? echo "$fontend";?></th>
<th><? echo "$font";?>Description<? echo "$fontend";?></th>
<th><? echo "$font";?>Connection<? echo "$fontend";?></th>
<th><? echo "$font";?>Connection Mode<? echo "$fontend";?></th>
<th><? echo "$font";?># Hubs<? echo "$fontend";?></th>
<th><? echo "$font";?># Slots<? echo "$fontend";?></th>
<th><? echo "$font";?>Shared [bytes]<? echo "$fontend";?></th>
<th><? echo "$font";?>Shared [Gb]<? echo "$fontend";?></th>
<th><? echo "$font";?>Email<? echo "$fontend";?></th>
</tr>
<?
while ($data=mysql_fetch_array($result)) 
{ 	// include code to display results as you see fit
	$id=mysql_result($result,$entry,"rowID");
	$date=mysql_result($result,$entry,"date");
	$time=mysql_result($result,$entry,"time");
	$name=mysql_result($result,$entry,"name");
	$user_type=mysql_result($result,$entry,"user_type");
	$ip=mysql_result($result,$entry,"ip");
	$country=mysql_result($result,$entry,"country");
	$client=mysql_result($result,$entry,"client");
	$client_version=mysql_result($result,$entry,"client_version");
	$fulldescription=htmlentities(mysql_result($result,$entry,"fulldescription"));
	$connection=mysql_result($result,$entry,"connection");
	$connection_mode=mysql_result($result,$entry,"connection_mode");
	$connected_hubs=mysql_result($result,$entry,"connected_hubs");
	$upload_slots=mysql_result($result,$entry,"upload_slots");
	$shared_bytes=mysql_result($result,$entry,"shared_bytes");
	$shared_gigs=mysql_result($result,$entry,"shared_gigs");
	$email=mysql_result($result,$entry,"email");
	
	
	if(($user_type == Operator) || ($user_type == "Op-Admin"))
	{ 	if($entry % 2) { 
	        echo "<TR bgcolor="; echo "$OprowColour"; echo ">\n";
	    	} else { 
	        echo "<TR bgcolor="; echo "$OprowColourAlt"; echo ">\n"; }
		$bold = $highlight; $boldend = $highlightstop;}
	else{
		if($entry % 2) { 
	        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
	    	} else { 
	        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
		$bold = ""; $boldend = "";}
	?>
	<form action="<? echo "log-online.php?delete=onlinerow&id=$id" ?>" method="post">

	<td nowrap><? echo "$font$bold$id$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$date$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$time$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$name$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$user_type$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$ip$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$country$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$client$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$client_version$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$fulldescription$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$connection$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$connection_mode$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$connected_hubs$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$upload_slots$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$shared_bytes$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$shared_gigs$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$email$boldend$fontend"; ?></td>
	<td nowrap><input type="Submit" value="Delete" onClick="return confirmDelete()"></td>
	
	</form>
	</tr>
	<?
	$entry++;
} 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"?m=$menuname&p=$path/hubonline&offset=$prevoffset\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"?m=$menuname&p=$path/hubonline&offset=$newoffset\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"?m=$menuname&p=$path/hubonline&offset=$newoffset\">NEXT</a><p>\n";
}
mysql_close();
?></div>
<? echo "$fontend";?>
</body>
</html>
