<?
$page_title="View logged Fakers";
include("header.ini");
?>

<div align="center"><? 
echo "$font";
$limit=$defaultLogEntries; 
if (empty($offset)) {$offset=0;}
mysql_connect($databasehost,$username,$password); 
@mysql_select_db($database) or die( "Unable to select database");
$result=mysql_query("SELECT * FROM userDB WHERE lastReason='Faker' ORDER by rowID DESC  LIMIT $offset,$defaultLogEntries"); 
$numrows=mysql_num_rows($result);
//Say which entries from the log we are displaying
 
echo "Total Number of Fakers $numrows<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2"> 
<tr>
<th><? echo "$font";?>Date Time<? echo "$fontend";?></th>
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
<th><? echo "$font";?>Shared [Gb] (bytes)<? echo "$fontend";?></th>
</tr> 
<? 
while ($data=mysql_fetch_array($result)) 
{	$id=mysql_result($result,$i,"rowID");
	$inTime=mysql_result($result,$i,"inTime");
	$nick=mysql_result($result,$i,"nick");
	$uType=mysql_result($result,$i,"uType");
	$IP=mysql_result($result,$i,"IP");
	$country=mysql_result($result,$i,"country");
	$dcClient=mysql_result($result,$i,"dcClient");
	$dcVersion=mysql_result($result,$i,"dcVersion");
	$fullDescription=htmlentities(mysql_result($result,$i,"fullDescription"));
	$connection=mysql_result($result,$i,"connection");
	$connectionMode=mysql_result($result,$i,"connectionMode");
	$hubs=mysql_result($result,$i,"hubs");
	$slots=mysql_result($result,$i,"slots");
	$shareBytes=mysql_result($result,$i,"shareByte");

	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; } 
	?>
	<td nowrap><? echo "$font$bold$inTime$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$nick$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$uType$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$IP$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$country$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$dcClient$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$dcVersion$boldend$fontend"; ?></td>
	<td nowrap><? echo "$font$bold$fullDescription$boldend$fontend"; ?></td>
	<td nowrap><div align="center"><? echo "$font$bold$connection$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$connectionMode$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$hubs$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$slots$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><a title="<? echo "$shareBytes bytes" ?>"><? echo "$font$bold$shareBigs$boldend$fontend"; ?></a></div></td>
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
</div>
<? echo "$fontend";?>
</body>
</html>
