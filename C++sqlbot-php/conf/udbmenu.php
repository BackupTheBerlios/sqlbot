<FIELDSET>
	<LEGEND><font color="#FFFFFF">User DB</font></LEGEND>
		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, All); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="All" class="menubutton"></form>
	
		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Online); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="Online" class="menubutton"></form>
		
		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Fakers); ?>
		<?php hidden_value(parseorder, uiNick); ?>
		<input type="submit" value="Fakers" class="menubutton"></form>
</FIELDSET>