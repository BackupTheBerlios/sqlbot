<?php 
$page_title="Hub Statistics";
include("header.ini");
?>



<?php 
///////////////////////////////////////////////////////
////Total Share figures
///////////////////////////////////////////////////////

mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$gigabyte = 1024 * 1024 * 1024;
$range = 5 * $gigabyte;
$limit = 150 * $gigabyte;
$lowRange = 0;
$highRange = $range;


	echo "<center>Hub Share<table border=\"$tableborders\" cellspacing=\"2\" cellpadding=\"2\">";
while ($limit > $highRange)
{
	$query="SELECT COUNT(nick) FROM userDB WHERE shareByte BETWEEN $lowRange AND $highRange";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	
	$count=mysql_result($result,0,"COUNT(nick)");
	$lowRangeGb=round(($lowRange / 1024 / 1024 / 1024), 2);
	$highRangGb=round(($highRange / 1024 / 1024 / 1024), 2);
	$tablewidth=$count *2;
	?>


	<td><?php  echo "$font$lowRangeGb - $highRangGb GB $fontend" ?></td>
	<td><?php  echo "$font$count$fontend" ?></td>
	<td><TABLE bgColor=red height=10 width=<?php  echo "$tablewidth" ?> cellSpacing=0 cellPadding=0 border= 0>
 <TR><TD></TD></TR>
</TABLE>
	</td>
	
	</tr><?php 
	++$i;
	 $lowRange  =  $lowRange + $range;
	 $highRange  =  $highRange + $range;
	
}
mysql_close();?>

</table>

</center>
</body>
</html>
