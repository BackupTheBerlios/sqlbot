<?
$page_title="Hub Log";
include("header.ini");
?>
<br>
	<div align="center"><form action="log-hub.php" method="post">
	<input class="button" type="Submit" value="Refresh"></form><br>
<div align="center"><?
$entry=0;
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$where = "";
if (!empty($field))  
	{$where="WHERE $field LIKE '%$search%'";}

// Delete a Row if requested
if ($f == row)
{	$sql = "DELETE FROM hubLog WHERE rowID=$id"; 
	$result = mysql_query($sql) or die(mysql_error());}
// Delete ALL rows
if ($f == delete)
{	$sql = "DELETE FROM hubLog $where";
	$result = mysql_query($sql) or die(mysql_error());}

$entry=0;$limit=$defaultLogEntries; 
if (empty($offset)) {$offset=0;}

$numresults=mysql_query("SELECT * FROM hubLog $where ");
$numrows=mysql_num_rows($numresults);
$result=mysql_query("SELECT * FROM hubLog $where ORDER by rowID DESC  LIMIT $offset,$defaultLogEntries");
mysql_close();
?>

<table border="<? echo "$tableborders";?> cellspacing="2" cellpadding="2">
<tr>
	<th><? echo " Filters Applied $font$field $search$fontend";?></th>
	<th><form action="<? echo "log-hub.php" ?>" method="post">
	<input type="Submit" value="Reset Filters"></form></th>
	<th><form action="<? echo "log-hub.php?f=delete&field=$field&search=$search" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form></th>
	</tr><tr>
	<th><form action="<? echo "log-hub.php?field=nick&search=$search" ?>" method="post">
	<input type="text" name="search" value=""><? echo "$font";?></th><th>
	<input type="Submit" value="Nick Search"></form></th>
</tr>
</table>
<?
echo "<br>Total Number of Matching Entries <b>$numrows</b><br>"; ?>

<table border="<? echo "$tableborders";?> cellspacing="2" cellpadding="2"> 
<tr>  
	<th><? echo "$font";?>Nick<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Date Time<? echo "$fontend";?></th>
	<th><? echo "$font";?>Action<? echo "$fontend";?></th> 
	<th><? echo "$font";?>Reason<? echo "$fontend";?></th> 
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
	<form action="<? echo "log-hub.php?f=row&id=$id&offset=$offset&search=$search" ?>" method="post"> 
	<td nowrap><a href="<? echo "log-hub.php?field=nick&search=$nick" ?>"><? echo "$font$nick$fontend"; ?></a></td>
	<td nowrap><? echo "$font$logTime$fontend"; ?></td> 
	<td nowrap><a href="<? echo "log-hub.php?field=action&search=$action"; ?>"><? echo "$font$action$fontend"; ?></a></td>
	<td nowrap><a href="<? echo "log-hub.php?field=reason&search=$reason"; ?>"><? echo "$font$reason$fontend"; ?></a></td>
	</form>
	</tr>
	<? $i++; } 
echo "</table></div>";

if ($offset!=0) { 
    $prevoffset=$offset-$defaultLogEntries;
    print "<a href=\"log-hub.php?offset=$prevoffset&field=$field&search=$search\">PREV</a> &nbsp; \n";
}
$pages=intval($numrows/$limit);

if ($numrows%$limit) {
    $pages++; }

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<a href=\"log-hub.php?offset=$newoffset&field=$field&search=$search\">$i</a> &nbsp; \n"; }

if (!(($offset/$limit)==$pages) && $pages!=1) {
    $newoffset=$offset+$limit;
    print "<a href=\"log-hub.php?offset=$newoffset&field=$field&search=$search\">NEXT</a><p>\n";
}
?></div>


<? echo "$fontend";?>
</body>
</html>
