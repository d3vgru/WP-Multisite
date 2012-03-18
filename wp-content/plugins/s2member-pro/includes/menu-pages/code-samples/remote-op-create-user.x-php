<?php
$op["op"] = "create_user"; /* The Remote Operation you're calling upon. */
/**/
$op["api_key"] = "[API Key]"; /* Your Remote Ops API Key. */
/* Check your Dashboard for this value, under: `s2Member -> API Scripting -> Remote Operations API`. */
/**/
$op["data"] = array (/**/
"user_login" => "johndoe22", /* Required. A unique Username. Lowercase alphanumerics/underscores. */
"user_email" => "johndoe22@example.com", /* Required. A valid/unique Email Address for the new User. */
"user_pass" => "456DkaIjsd!", /* Optional. Plain text Password. If empty, one will be generated. */
"first_name" => "John", /* Optional. First Name for the new User. */
"last_name" => "Doe", /* Optional. Last Name for the new User. */
"s2member_level" => "2", /* Optional. Defaults to Level #0 ( a Free Subscriber ). */
"s2member_ccaps" => "music,videos", /* Optional. Comma-delimited list of Custom Capabilities. */
"s2member_registration_ip" => "123.456.789.100", /* Optional. User's IP Address. If empty, s2Member will fill this upon first login. */
"s2member_subscr_gateway" => "paypal", /* Optional. User's Paid Subscr. Gateway Code. One of: (paypal|alipay|authnet|ccbill|clickbank|google). */
"s2member_subscr_id" => "I-DJASODJF8933J", /* Optional. User's Paid Subscr. ID. For PayPal®, use their Subscription ID, or Recurring Profile ID. */
"s2member_custom" => "www.example.com", /* Optional. If provided, should always start with your installation domain name ( i.e. $_SERVER["HTTP_HOST"] ). */
"s2member_auto_eot_time" => "2030-12-25", /* Optional. Can be any value that PHP's ``strtotime()`` function will understand ( i.e. YYYY-MM-DD ). */
"opt_in" => "1", /* Optional. A non-zero value tells s2Member to attempt to process List Servers that you've configured in the Dashboard area. */
"custom_fields" => array ("my_field_id" => "Some value."), /* Optional. An array of Custom Registration Field ID's, with associative values. */
"s2member_notes" => "Administrative notation. Created this User via API call.", /* Optional. Administrative notations. */
"notification" => "1", /* Optional. A non-zero value tells s2Member to email the new User/Member their Username/Password. */
/* The "notification" parameter also tells s2Member to notify the site Administrator about this new account. */
);
/**/
$result = trim (file_get_contents ("http://www.example.com/?s2member_pro_remote_op=1", false, stream_context_create (array ("http" => array ("method" => "POST", "header" => "Content-type: application/x-www-form-urlencoded", "content" => "s2member_pro_remote_op=" . urlencode (serialize ($op)))))));
/**/
if (!empty ($result) && !preg_match ("/^Error\:/i", $result) && is_array ($user = @unserialize ($result)))
	{
		echo "Success. New User created with ID: " . $user["ID"];
	}
else
	echo "API error reads: " . $result;
?>