<FIELDSET>
	<LEGEND><font color="#FFFFFF">User DB</font></LEGEND>
		<form action="<?php echo "$PHP_SELF"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, All); ?>
		<input type="submit" value="All" class="menubutton"></form>
	
		<form action="<?php echo "$PHP_SELF"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Online); ?>
		<input type="submit" value="Online" class="menubutton"></form>
		
		<form action="<?php echo "$PHP_SELF"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Fakers); ?>
		<input type="submit" value="Fakers" class="menubutton"></form>
</FIELDSET>