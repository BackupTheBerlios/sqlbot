<?
$page_title="Edit a DC Client";
include("header.ini");
?>

<?
mysql_connect($databasehost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT * FROM client_rules where rowID=$id";
$result=mysql_query($query);
$num=mysql_num_rows($result); 
mysql_close();


function show_allowed_box($name,$select=0) {
        echo '<select name="'.$name.'" size="1">';
        $boxlist = array('1'=>'YES',
                          '2'=>'NO');
 
        for ($i=1; $i<=count($boxlist); $i++) 
	{
        	if ($i == $select) 
		{	
			echo '<option selected value="'.$boxlist[$i].'">'.$boxlist[$i]; 
		} else {
			echo '<option value="'.$boxlist[$i].'">'.$boxlist[$i];
                }
	}
}
function show_slots_box($name,$select=0) {
        echo '<select name="'.$name.'" size="1">';
        $boxlist = array('1'=>'Use Connection Table',
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
function show_ratio_box($name,$select=0) {
        echo '<select name="'.$name.'" size="1">';
        $boxlist = array('1'=>'0',
			  '2'=>'0.25',
                          '3'=>'0.5',
                          '4'=>'0.75',
                          '5'=>'1',
                          '6'=>'2',
                          '7'=>'3',
                          '8'=>'4',
                          '9'=>'5',
                          '10'=>'6');
 
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
function show_hubs_box($name,$select=0) {
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
			'15'=>'50',
			'16'=>'75',
			'17'=>'100',
			'18'=>'200');

 
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
function show_share_box($name,$select=0) {
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
			'15'=>'30',
			'16'=>'35',
			'17'=>'40',
			'18'=>'50',
			'19'=>'60',
			'20'=>'70',
			'21'=>'80',
			'22'=>'90',
			'23'=>'100',
			'24'=>'150',
			'24'=>'200');

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
function show_connection_box($name,$select=0) {
        echo '<select name="'.$name.'" size="1">';
        $boxlist = array('1'=>'28.8k',
			  '2'=>'33.6k',
                          '3'=>'56k',
                          '4'=>'Sat',
                          '5'=>'ISDN',
                          '6'=>'DSL',
                          '7'=>'Cable',
                          '8'=>'T1',
                          '9'=>'T3');

        for ($i=1; $i<=count($boxlist); $i++) 
	{
        	if ($i == $select) 
		{	
			echo '<option selected value="'.$i.'">'.$boxlist[$i]; 
		} else {
			echo '<option value="'.$i.'">'.$boxlist[$i];
                }
	}
}
while ($i < $num) {
$rowID=mysql_result($result,$i,"rowID");
$client=mysql_result($result,$i,"client");
$min_version=mysql_result($result,$i,"min_version");
$allowed=mysql_result($result,$i,"allowed");
$min_slots=mysql_result($result,$i,"min_slots");
$max_slots=mysql_result($result,$i,"max_slots");
$slot_ratio=mysql_result($result,$i,"slot_ratio");
$max_hubs=mysql_result($result,$i,"max_hubs");
$min_share=mysql_result($result,$i,"min_share");
$min_connection=mysql_result($result,$i,"min_connection");
$client_name=mysql_result($result,$i,"client_name");
?>
<center>
<form action="<?echo "client-main.php?function=updateclient";?>" method="post">
<table>
	<tr>
		<td></td>	
		<td><input type="hidden" name="ud_rowID" value="<? echo "$rowID"; ?>"></t>
	</tr><tr>
		<td>Client Tag:</td>
		<td><input type="text" name="ud_client" value="<? echo "$client"?>"></td>
		<td>Client Tag</td>
	</tr><tr>
	</tr><tr>
		<td>Client name:</td>
		<td><input type="text" name="ud_client_name" value="<? echo "$client_name"?>"></td>
		<td>Full name of the Client</td>
	</tr><tr>
		<td>Version:</td>
		<td><input type="text" name="ud_min_version" value="<? echo "$min_version"?>"></td>
		<td>Minimum Client version to allow</td>
	</tr><tr>
		<td>Allowed</td>
		<td><?show_allowed_box(ud_allowed,$select=$allowed);?></td>
		<td>Allowed ( YES or NO)</td>
	</tr><tr>
		<td>Min Slots</td>
		<td><?show_slots_box(ud_min_slots,$min_slots);?></td>
		<td>Minimum number of Slots this client should have (int)</td>
	</tr><tr>
		<td>Max Slots</td>
		<td><?show_slots_box(ud_max_slots,$max_slots);?></td>
		<td>Minimum number of Slots this client should have (int)</td>
	</tr><tr>
		<td>Slot Ratio</td>
		<td><?show_ratio_box(ud_slot_ratio,$select=$slot_ratio);?></td>
		<td>Slot Ration,Minimum number of slots per hub</td>
	</tr><tr>
		<td>Max Hubs</td>
		<td><?show_hubs_box(ud_max_hubs,$select=$max_hubs);?></td>
		<td>Maximum Number of Hubs this client can be connected to (int)</td>
	</tr><tr>
		<td>Min Share</td>
		<td><?show_share_box(ud_min_share,$select=$min_share);?></td>
		<td>Minimum Share in Gb</td>
	</tr><tr>
		<td>Min Connection</td>
		<td><?show_connection_box(ud_min_connection,$select=$min_connection);?></td>
		<td>Minimum connection Type</td>
	</tr>
</table>
<hr>
<input type="Submit" value="Update">
<hr>
</form>
<? ++$i; }  ?>

</center>

</body>
</html>
