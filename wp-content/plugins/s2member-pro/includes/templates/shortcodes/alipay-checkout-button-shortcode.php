<?php
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");
?>

[s2Member-Pro-AliPay-Button level="%%level%%" ccaps="" desc="%%level_label%% / <?php echo esc_attr (_x ("Description and pricing details here.", "s2member-admin", "s2member")); ?>" custom="%%custom%%" ra="0.01" rp="1" rt="M" image="default" output="anchor" /]