<?php 
$page_title="Hub Log";
include("header.ini");
?>
<br>
	<div align="center"><form action="log-hub.php" method="post">
	<input class="button" type="Submit" value="Refresh"></form><br>
<div align="center"><?php 
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

<table border="<?php  echo "$tableborders";?> cellspacing="2" cellpadding="2">
<tr>
	<th><?php  echo " Filters Applied $font$field $search$fontend";?></th>
	<th><form action="<?php  echo "log-hub.php" ?>" method="post">
	<input type="Submit" value="Reset Filters"></form></th>
	<th><form action="<?php  echo "log-hub.php?f=delete&field=$field&search=$search" ?>" method="post">
	<input type="Submit" value="Delete ALL" onClick="return confirmDelete()"></form></th>
	</tr><tr>
	<th><form action="<?php  echo "log-hub.php?field=nick&search=$search" ?>" method="post">
	<input type="text" name="search" value=""><?php  echo "$font";?></th><th>
	<input type="Submit" value="Nick Search"></form></th>
</tr>
</table>
<?php 
echo "<br>Total Number of Matching Entries <b>$numrows</b><br>"; ?>

<table border="<?php  echo "$tableborders";?> cellspacing="2" cellpadding="2"> 
<tr>  
	<th><?php  echo "$font";?>Nick<?php  echo "$fontend";?></th> 
	<th><?php  echo "$font";?>Date Time<?php  echo "$fontend";?></th>
	<th><?php  echo "$font";?>Action<?php  echo "$fontend";?></th> 
	<th><?php  echo "$font";?>Reason<?php  echo "$fontend";?></th> 
</tr> 
<?php  
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
	<form action="<?php  echo "log-hub.php?f=row&id=$id&offset=$offset&search=$search" ?>" method="post"> 
	<td nowrap><a href="<?php  echo "user-manage.php?field=nick&search=$nick" ?>"><?php  echo "$font$nick$fontend"; ?></a></td>
	<td nowrap><?php  echo "$font$logTime$fontend"; ?></td> 
	<td nowrap><a href="<?php  echo "log-hub.php?field=action&search=$action"; ?>"><?php  echo "$font$action$fontend"; ?></a></td>
	<td nowrap><a href="<?php  echo "log-hub.php?field=reason&search=$reason"; ?>"><?php  echo "$font$reason$fontend"; ?></a></td>
	</form>
	</tr>
	<?php  $i++; } 
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


<?php  echo "$fontend";?>
</body>
</html>
