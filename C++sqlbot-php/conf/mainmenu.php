<FIELDSET>
	<LEGEND><font color="#FFFFFF"><?php echo " $hcName "; ?></font></LEGEND>
		<form action="<?php echo "hubconfig.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<input type="submit" value="Hub Config" class="menubutton"></form>
		
		<form action="<?php echo "userdb.php"; ?>" method="post">
		<?php hidden_value(hubID, $hubID); ?>
		<?php hidden_value(parse, Online); ?>
		<input type="submit" value="User DB" class="menubutton"></form>
</FIELDSET>