<?php 
$page_title="Connection Slots Settings";
include("header.ini");
?>
<?php 
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
if($function == update) {
	$sql = "UPDATE connection_slots SET min_slots='$ud_min_slots', max_slots='$ud_max_slots' WHERE rowID='$ud_id'";
	$result = mysql_query($sql) or die(mysql_error());}
$query="SELECT * FROM connection_slots";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();

function show_slots_box($name,$select=0) {
        echo '<select name="'.$name.'" size="1">';
        $boxlist = array('1'=>'0',
			  '2'=>'1',
                          '3'=>'2',
                          '4'=>'3',
                          '5'=>'4',
                          '6'=>'5',
                          '7'=>'6',
                          '8'=>'7',
                          '9'=>'8',
                          '10'=>'9',
                          '11'=>'10',
                          '12'=>'15',
                          '13'=>'20',
                          '14'=>'25',
                          '15'=>'50');
 
        for ($i=1; $i<=count($boxlist); $i++) 
	{
        	if ($boxlist[$i] == $select) 
		{	
			echo '<option selected value="'.$boxlist[$i].'">'.$boxlist[$i]; 
		} else {
			echo '<option value="'.$boxlist[$i].'">'.$boxlist[$i];
                }
	}
}
?>
<center>
<?php  echo "<center><table border=\"$tableBorders\" cellspacing=\"2\" cellpadding=\"2\">";?>
<tr>
	<th></th>
	<th><?php  echo "$font";?>Connection<?php  echo "$fontend";?></th>
	<th><?php  echo "$font";?>Min Slots<?php  echo "$fontend";?></th>
	<th><?php  echo "$font";?>Max Slots<?php  echo "$fontend";?></th>
</tr><?php 
$i=0;
while ($i < $num) {
$id=mysql_result($result,$i,"rowID");
$connection=mysql_result($result,$i,"connection");
$min_slots=mysql_result($result,$i,"min_slots");
$max_slots=mysql_result($result,$i,"max_slots");
$description=mysql_result($result,$i,"description");


	if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor="; echo "$rowColour"; echo ">\n";
    	} else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor="; echo "$rowColourAlt"; echo ">\n"; }
?>
		<form action="<?php  echo "client-cslots-edit.php?function=update" ?>" method="post">
		<td><input type="hidden" name="ud_id" value="<?php  echo "$id"; ?>"></t>
		<td><?php  echo "$font$description$fontend"?></td>
		<td><?php  echo "$font"; show_slots_box(ud_min_slots,$min_slots); echo "$fontend"; ?></td>
		<td><?php  echo "$font"; show_slots_box(ud_max_slots,$max_slots); echo "$fontend"; ?></td>
		<td><input type="Submit" value="Update"></td>
		</form>
	</tr>

<?php  ++$i; }  ?>
</table>
<p>You must click update for each config in turn</p>
</center>

</body>
</html>
