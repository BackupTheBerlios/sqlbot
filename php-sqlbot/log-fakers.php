<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Fakers Log</title>
</head>
<body>
<? 
echo "$font";
include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Share fakers detected On $hubname</center></h3><br><br>";
$limit=$defaultLogEntries; 

mysql_connect($databasehost,$username,$password); 
@mysql_select_db($database) or die( "Unable to select database");


$numresults=mysql_query("SELECT * FROM fakers ");
$numrows=mysql_num_rows($numresults);
if (empty($offset)) {
	$offset=0;}

$result=mysql_query("SELECT * FROM fakers ORDER by rowID DESC  LIMIT $offset,$defaultLogEntries"); 

//Say which entries from the log we are displaying
 
echo "Total Number of Fakers $numrows<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2"> 
<tr>
<th><? echo "$font";?>Date<? echo "$fontend";?></th>
<th><? echo "$font";?>Time<? echo "$fontend";?></th> 
<th><? echo "$font";?>Nick<? echo "$fontend";?></th>
<th><? echo "$font";?>IP<? echo "$fontend";?></th> 
<th><? echo "$font";?>Country<? echo "$fontend";?></th>
<th><? echo "$font";?>Client<? echo "$fontend";?></th> 
<th><? echo "$font";?>Version<? echo "$fontend";?></th>
<th><? echo "$font";?>Description<? echo "$fontend";?></th>
<th><? echo "$font";?># Hubs<? echo "$fontend";?></th> 
<th><? echo "$font";?># Slots<? echo "$fontend";?></th>
<th><? echo "$font";?>Shared Bytes<? echo "$fontend";?></th>
<th><? echo "$font";?>Shared [Gb]<? echo "$fontend";?></th>
</tr> 
<? 
while ($data=mysql_fetch_array($result)) 
{	$date=mysql_result($result,$i,"date");
	$time=mysql_result($result,$i,"time");
	$name=mysql_result($result,$i,"name");
	$ip=mysql_result($result,$i,"ip");
	$country=mysql_result($result,$i,"country");
	$client=mysql_result($result,$i,"client");
	$client_version=mysql_result($result,$i,"client_version");
	$fulldescription=htmlentities(mysql_result($result,$i,"fulldescription"));
	$connected_hubs=mysql_result($result,$i,"connected_hubs");
	$upload_slots=mysql_result($result,$i,"upload_slots");
	$shared_bytes=mysql_result($result,$i,"shared_bytes");
	$shared_gigs=mysql_result($result,$i,"shared_gigs");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; } 
	?>
	<td nowrap><? echo "$font$date$fontend"; ?></td> 
	<td nowrap><? echo "$font$time$fontend"; ?></td> 
	<td nowrap><? echo "$font$name$fontend"; ?></td>
	<td nowrap><? echo "$font$ip$fontend"; ?></td>
	<td nowrap><? echo "$font$country$fontend"; ?></td> 
	<td nowrap><? echo "$font$client$fontend"; ?></td> 
	<td nowrap><? echo "$font$client_version$fontend"; ?></td> 
	<td nowrap><? echo "$font$fulldescription$fontend"; ?></td> 
	<td nowrap><? echo "$font$connected_hubs$fontend"; ?></td>
	<td nowrap><? echo "$font$upload_slots$fontend"; ?></td>
	<td nowrap><? echo "$font$shared_bytes$fontend"; ?></td>
	<td nowrap><? echo "$font$shared_gigs$fontend"; ?></td> 
	</tr>
	<?
	$i++; 
} 
echo "</table>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"?m=$menuname&p=$path/hubfakers&offset=$prevoffset\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"?m=$menuname&p=$path/hubfakers&offset=$newoffset\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages-1) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<a href=\"?m=$menuname&p=$path/hubfakers&offset=$newoffset\">NEXT</a><p>\n";
}
mysql_close();
?>
<? echo "$fontend";?>
</body>
</html>
