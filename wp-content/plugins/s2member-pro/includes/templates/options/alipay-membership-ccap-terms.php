<?php
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
?>

<optgroup label="<?php echo esc_attr (_x ("AliPay® ( Buy Now )", "s2member-admin", "s2member")); ?>">
<option value="1-L"><?php echo esc_html (_x ("One Time ( for lifetime access )", "s2member-admin", "s2member")); ?></option>
</optgroup>