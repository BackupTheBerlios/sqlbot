<?
$page_title="Add a new hub-rule";
include("header.ini");
?>

<?
include("dbinfo.inc.php");
?>
<center>
	<table>
	<tr><form action="rules-main.php?function=add" method="post">
		<td>New Rule</td>
		<td><input type="Text" name="rule"></td>
		<td></td>
	<td><input type="Submit" value="Add New Rule"></td>
	</form>
	</tr>
	</table>
</center>
</body>
</html> 
