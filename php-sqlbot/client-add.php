<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Add a DC Client</title>
</head>
<body>

<?
include("dbinfo.inc.php");
echo "<h3><center>ODCH Admin - Add New client for $hubname</center></h3><br><br>";
?>
<center><form action="client-main.php?function=addclient" method="post">
	<table>
	<tr>
		<td>Client</td>
		<td><input type="Text" name="client"></td>
		<td>Client Tag</td>
	</tr><tr>
	<td>Client</td>
		<td><input type="Text" name="client_name"></td>
		<td>Client Name</td>
	</tr><tr>
		<td>Version</td>
		<td><input type="Text" name="min_version"></td>
		<td>Minimum Client version to allow</td>
	</tr><tr>
		<td>Allowed</td>
		<td><select name='allowed'>
			<option value="YES">Yes</option>
			<option value="NO">No</option>
		</select></td>
		<td>Allowed ( YES or NO)</td>
	</tr><tr>
		<td>Min Slots</td>
		<td><select name='min_slots'>
			<option value="0">Use Connection Table</option>
			<option value="1">1</option>         
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
			<option value="50">50</option>
		</select></td>
		<td>Minimum number of Slots this client should have (int)</td>
	</tr><tr>
		<td>Max Slots</td>
		<td><select name='max_slots'>
			<option value="0">Use Connection Table</option>
			<option value="1">1</option>         
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
			<option value="50">50</option>
		</select></td>
		<td>Maximum number of Slots this client should have (int)</td>
	</tr><tr>
		<td>Slot Ratio</td>
		<td><select name='slot_ratio'>
			<option value="0">0(off)</option>         
			<option value="0.25">1:4</option>
			<option value="0.50">1:2</option>
			<option value=0.75">3:4</option>
			<option value="1">1:1</option>
			<option value="2">2:1</option>
			<option value="3">3:1</option>
			<option value="4">4:1</option>
			<option value="5">5:1</option>
		</select></td>
		<td>Slot Ratio, Number slots / Number of hubs</td>
	</tr><tr>
		<td>Max Hubs</td>
		<td><select name='max_hubs'>
			<option value="0">0(off)</option>
			<option value="1">1</option>         
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
			<option value="50">50</option>
			<option value="75">75</option>
			<option value="100">100</option>
			<option value="200">200</option>
		</select></td>
		<td>Maximum Number of Hubs this client can be connected to (int)</td>
	</tr><tr>
		<td>Min Share</td>
		<td><select name='min_share'>
			<option value="0">0(off)</option>
			<option value="1">1</option>         
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
			<option value="30">30</option>
			<option value="35">35</option>
			<option value="40">40</option>
			<option value="45">45</option>
			<option value="50">50</option>
			<option value="60">60</option>
			<option value="70">70</option>
			<option value="80">80</option>
			<option value="90">90</option>
			<option value="100">100</option>
			<option value="150">150</option>
			<option value="200">200</option>
		</select></td>
		<td>Minimum Share in Gb</td>
	</tr><tr>
		<td>Min Connection</td>
		<td><select name='min_connection'>
			<option value="1">28.8k</option>         
			<option value="2">33.6k</option>
			<option value="3">56k</option>
			<option value="4">Sat</option>
			<option value="5">ISDN</option>
			<option value="6">DSL</option>
			<option value="7">Cable</option>
			<option value="8">T1</option>
			<option value="9">T3</option>
		</select></td>
		<td>Minimum connection Type</td>
	</tr>
	</table>
	<hr>
<input type="Submit" value="Add Client to rules">
</form>
</center>
</body>
</html>
