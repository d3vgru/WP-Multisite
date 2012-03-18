<?php
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
?>

<optgroup label="<?php echo esc_attr (_x ("Expires In Days", "s2member-admin", "s2member")); ?>">
<option value="48"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 2 days )", "s2member-admin", "s2member")); ?></option>
<option value="72" selected="selected"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 3 days )", "s2member-admin", "s2member")); ?></option>
<option value="96"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 4 days )", "s2member-admin", "s2member")); ?></option>
<option value="120"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 5 days )", "s2member-admin", "s2member")); ?></option>
<option value="144"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 6 days )", "s2member-admin", "s2member")); ?></option>
</optgroup>

<option disabled="disabled"></option>

<optgroup label="<?php echo esc_attr (_x ("Expires In Weeks", "s2member-admin", "s2member")); ?>">
<option value="168"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 1 week )", "s2member-admin", "s2member")); ?></option>
<option value="336"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 2 weeks )", "s2member-admin", "s2member")); ?></option>
<option value="504"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 3 weeks )", "s2member-admin", "s2member")); ?></option>
</optgroup>

<option disabled="disabled"></option>

<optgroup label="<?php echo esc_attr (_x ("Expires In Months", "s2member-admin", "s2member")); ?>">
<option value="720"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 1 month )", "s2member-admin", "s2member")); ?></option>
<option value="1440"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 2 months )", "s2member-admin", "s2member")); ?></option>
<option value="2190"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 3 months )", "s2member-admin", "s2member")); ?></option>
<option value="4380"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 6 months )", "s2member-admin", "s2member")); ?></option>
</optgroup>

<option disabled="disabled"></option>

<optgroup label="<?php echo esc_attr (_x ("Expires In Years", "s2member-admin", "s2member")); ?>">
<option value="8760"><?php echo esc_html (_x ("Buy Now ( Specific Post/Page, link valid for 1 year )", "s2member-admin", "s2member")); ?></option>
</optgroup>