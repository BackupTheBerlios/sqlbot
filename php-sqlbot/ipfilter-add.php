<?
$page_title="Add a filtered IP";
include("header.ini");
?>

<center><form action="ipfilter-main.php?f=add" method="post">
	<table>
	
	<tr>
	<td>IP Range Start</td>
		<td><input type="Text" name="ipStart"></td>
		<td></td>
	</tr><tr>
		<td>IP Mask</td>
		<td><input type="Text" name="ipMask"></td>
		<td></td>
	</tr><tr>
		<td>Disallow (Ban)</td>
		<td><input type="radio" name="function" value="ban" checked="true">Ban<br></td>
		<td></td>
	</tr><tr>
		<td>Allow with no checks</td>
		<td><input type="radio" name="function" value="allow">Allow<br></td>
		<td></td>
	</tr><tr>
		<td>Information</td>
		<td><input type="Text" name="information"></td>
		<td></td>
	</tr><tr>
		<td>Log this user</td>
		<td><input type="checkbox" name="log" ></td>
		<td></td>
	</tr></table>
	<hr>
<input type="Submit" value="Add to Filter">
</form>
</center>
</body>
</html> 
