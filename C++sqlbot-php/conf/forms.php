<?php	//FUNCTIONS FOR FORMS
/* USAGE
<tr><?php hidden_form("HubID", $hubID); ?></tr>
<tr><?php dual_select_form(Yes,No, "Auto-Connect",hubAutoConnect,$hubAutoConnect); ?></tr>
<tr><?php noedit_form("Hub ID", $hubID); ?></tr>
<tr><?php line_form("Bot Name", <max_length>, hubNick, $hubNick); ?></tr>
*/


// INPUT LINE
function line_form($value_description, $maxlength, $value, $value_orig) {
echo "<td>$value_description &nbsp;</td>
		<td align=\"right\"><input type=\"text\" class=\"form_input\" name=\"$value\" value=\"$value_orig\" maxlength=\"$maxlength\"></td>";
}

// PASSWORD LINE
function password_form($value_description, $maxlength, $value, $value_orig) {
echo "<td>$value_description &nbsp;</td>
		<td align=\"right\"><input type=\"password\" class=\"form_input\" name=\"$value\" value=\"$value_orig\" maxlength=\"$maxlength\"></td>";
}


// DUAL SELECTION
function dual_select_form($yes_value, $no_value, $value_description, $value, $value_orig) {
echo "<td>$value_description &nbsp;</td>
		<td align=\"right\"><select name=\"$value\" class=\"form_select\">";
	if ($value_orig == 1) {
		echo "\n<option selected value=\"1\">$yes_value</option>
			<option value=\"0\">$no_value</option>";}
	else {echo "\n<option value=\"1\">$yes_value</option>
			<option selected value=\"0\">$no_value</option>";}
			echo "</select></td>";}

// TEXT AREA FORM
function textarea_form($value_description, $value, $value_orig) {
echo "<td valign=\"top\">$value_description &nbsp;</td>
		<td align=\"right\"><textarea rows=\"19\" cols=\"19\" name=\"$value\" class=\"form_textarea\">$value_orig</textarea></td>";}


// NON-EDITABLE FORM
function noedit_form($value_description, $value_orig) {
if ($value_orig == "") {$value_orig = "*Unknown*";}
echo "<td>$value_description &nbsp;</td>
		<td class=\"form_noedit\" width=\"10\">$value_orig</td>";}

// HIDDEN FORM
function hidden_form($value, $value_orig) {
echo "<td><input type=\"hidden\" name=\"$value\" value=\"$value_orig\"></td><td></td>";}

// CHECKBOX
function checkbox($value_description, $value, $value_orig) {
if ($value_orig == "1"){ $if_checked = " checked"; }
if ($value_orig == "0"){ $if_checked = "not_checked"; }
echo "<td valign=\"top\">$value_description ($value_orig) &nbsp;</td>
	<td><input type=\"checkbox\" name=\"$value\" value=\"1\"$if_checked class=\"form_checkbox\">";}

// TABLE CONNECTIONS
function list_connection($con_variable, $value) {
	if ($con_variable == "$value"){ echo "<option selected value=\"$con_variable\">$con_variable";}
	else { echo "<option value=\"$con_variable\">$con_variable";}
}

function connection_choice($value_description, $value,$value_orig) {
echo "<td>$value_description &nbsp;</td>\n<td align=\"right\">";
echo "<select name=\"$value\" class=\"form_select\">";

		list_connection("28.8Kbps", $value_orig);
		list_connection("33.6Kbps", $value_orig);
		list_connection("56Kbps", $value_orig);
		list_connection("Satellite", $value_orig);
		list_connection("ISDN", $value_orig);
		list_connection("DSL", $value_orig);
		list_connection("Cable", $value_orig);
		list_connection("LAN(T1)", $value_orig);
		list_connection("LAN(T3)", $value_orig);
		echo "</select></td>";
}


// TIME LIMITS

function list_times($time_variable, $value) {
		if ($time_variable == "$value"){ echo "<option selected value=\"$time_variable\">$time_variable";}
		else { echo "<option value=\"$time_variable\">$time_variable";}
}

function time_form($value_description, $shorthint, $maxlength, $value,$value_orig, $value_multiplier, $value_orig_multiplier) {
echo "<td>$value_description</td>
		<td align=\"right\"><input type=\"text\" name=\"$value\" value=\"$value_orig\" maxlength=\"$maxlength\" class=\"form_time\" title=\"$shorthint\"><select name=\"$value_multiplier\" class=\"form_time\">";

			list_times("minutes", $value_orig_multiplier);
			list_times("hours", $value_orig_multiplier);
			list_times("days", $value_orig_multiplier);

echo "</select></td>";
}


// TIME LIMITS

function list_sizes($size_variable, $value) {
		if ($time_variable == "$value"){ echo "<option selected value=\"$size_variable\">$size_variable";}
		else { echo "<option value=\"$size_variable\">$size_variable";}
}

function size_form($value_description, $shorthint, $maxlength, $value,$value_orig, $value_multiplier, $value_orig_multiplier) {
echo "<td>$value_description</td>
		<td align=\"right\"><input type=\"text\" name=\"$value\" value=\"$value_orig\" maxlength=\"$maxlength\" class=\"form_time\" title=\"$shorthint\"><select name=\"$value_multiplier\" class=\"form_time\">";

			list_times("KB", $value_orig_multiplier);
			list_times("MB", $value_orig_multiplier);
			list_times("GB", $value_orig_multiplier);

echo "</select></td>";
}


?>