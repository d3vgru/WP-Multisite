<?php
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
?>

[s2Member-Pro-AuthNet-Form level="%%level%%" ccaps="" desc="%%level_label%% / <?php echo esc_attr (_x ("Description and pricing details here.", "s2member-admin", "s2member")); ?>" cc="USD" custom="%%custom%%" ta="0" tp="0" tt="D" ra="0.01" rp="1" rt="M" rr="1" accept="visa,mastercard,amex,discover" coupon="" accept_coupons="0" default_country_code="US" captcha="0" /]