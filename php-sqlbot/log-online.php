<?
$page_title="View Online clients";
include("header.ini");
?>
<br>
	<div align="center"><form action="log-online.php" method="post">
	<input class="button" type="Submit" value="Refresh"></form><br>
<?
$limit=$defaultLogEntries; 
echo "$font";
if (empty($offset)) {$offset=0;}

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$result=mysql_query("SELECT * FROM userDB WHERE status='Online' ORDER by uType,nick LIMIT $offset,$defaultLogEntries ");
$numrows=mysql_num_rows($result);
mysql_close();
echo "Total Number of users online $numrows<br>";
?>
<table border="$tableborders" cellspacing="2" cellpadding="2">
<tr> 
<th><? echo "$font";?>Date Time<? echo "$fontend";?></th>
<th><? echo "$font";?>Nick<? echo "$fontend";?></th>
<th><? echo "$font";?>User Type<? echo "$fontend";?></th>
<th><? echo "$font";?>IP<? echo "$fontend";?></th>
<th><? echo "$font";?>Country<? echo "$fontend";?></th>
<th><? echo "$font";?>Client<? echo "$fontend";?></th>
<th><? echo "$font";?>Version<br>[hover=Full tag]<? echo "$fontend";?></th>
<!-- <th><? echo "$font";?>Description<? echo "$fontend";?></th> -->
<th><? echo "$font";?>Connection<? echo "$fontend";?></th>
<th><? echo "$font";?>Connection Mode<? echo "$fontend";?></th>
<th><? echo "$font";?># Hubs<? echo "$fontend";?></th>
<th><? echo "$font";?># Slots<? echo "$fontend";?></th>
<th><? echo "$font";?>Shared_bytes<br>[hover]<? echo "$fontend";?></th>
</tr>
<?
while ($data=mysql_fetch_array($result)) 
{ 	// include code to display results as you see fit
	$id=mysql_result($result,$entry,"rowID");
	$inTime=mysql_result($result,$entry,"inTime");
	$nick=mysql_result($result,$entry,"nick");
	$uType=mysql_result($result,$entry,"uType");
	$IP=mysql_result($result,$entry,"IP");
	$country=mysql_result($result,$entry,"country");
	$dcClient=mysql_result($result,$entry,"dcClient");
	$dcVersion=mysql_result($result,$entry,"dcVersion");
	$fullDescription=htmlentities(mysql_result($result,$entry,"fullDescription"));
	$connection=mysql_result($result,$entry,"connection");
	$connectionMode=mysql_result($result,$entry,"connectionMode");
	$hubs=mysql_result($result,$entry,"hubs");
	$slots=mysql_result($result,$entry,"slots");
	$byteShare=mysql_result($result,$entry,"shareByte");
	
	if (($byteShare / 1024 / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024 / 1024), 2); $Share="$Shared TB";}
	else if (($byteShare / 1024 / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024 / 1024), 2); $Share="$Shared GB";}
	else if (($byteShare / 1024 / 1024) > 1) { $Shared=round(($byteShare / 1024 / 1024), 2); $Share="$Shared MB";}
	else if (($byteShare / 1024) > 1) { $Shared=round(($byteShare / 1024), 2); $Share="$Shared KB";};
	
	// Colour Rows
	if(($uType == Operator) || ($uType == "Op-Admin")) {echo "<TR bgcolor="; echo "$OpRowColour"; echo ">\n";}
	else if($kickCount != 0) {echo "<TR bgcolor="; echo "$KickRowColour"; echo ">\n";}
	else if($i % 2)	{echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";}
	else{echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n";}

	?>
	<td nowrap><? echo "$font$bold$inTime$boldend$fontend"; ?></td>
	<td nowrap><a href="<? echo "user-type.php?nicksearch=$nick" ?>"<? echo "$font$nick$fontend"; ?></a></td>
	<td nowrap><? echo "$font$uType$fontend"; ?></td>
	<td nowrap><div align="center"><? echo "$font$bold$IP$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$country$boldend$fontend"; ?></div></td>
	<td nowrap><? echo "$font$bold$dcClient$boldend$fontend"; ?></td>
	<td nowrap><a title="<? echo "$fullDescription" ?>" style="cursor:help"><? echo "$font$bold$dcVersion$boldend$fontend"; ?></a></td>
<!-- 	<td nowrap><? echo "$font$bold$fullDescription$boldend$fontend"; ?></td> -->
	<td nowrap><div align="center"><? echo "$font$bold$connection$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$connectionMode$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$hubs$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><? echo "$font$bold$slots$boldend$fontend"; ?></div></td>
	<td nowrap><div align="center"><a title="<? echo "$Share" ?>" style="cursor:help"><? echo "$font$bold$byteShare$boldend$fontend"; ?></a></div></td>
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

?></div>
<? echo "$fontend";?>
</body>
</html>
