<FIELDSET>
	<LEGEND><font color="#FFFFFF">User DB</font></LEGEND>
		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Online); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="Online" class="menubutton" title="Show Online users"></form>

		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, All); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="All" class="menubutton" title="Show All users"></form>

		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Banned); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="Banned" class="menubutton" title="Show Banned users"></form>
</FIELDSET>