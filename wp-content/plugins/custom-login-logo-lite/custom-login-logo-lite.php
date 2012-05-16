<?
/**
 * @package Custom_Login_Logo
 * @version 0.1
 */
/*
Plugin Name: Custom Login Logo Lite
Plugin URI: http://wordpress.org/extend/plugins/custom-login-logo-lite/
Description: Change Login Logo
Author: Rafael Poveda - RaveN
Version: 0.2
Author URI: http://mecus.es/author/raven
*/



function custom_login_logo_lite_option_page() { ?>
	<div class="wrap" style="width:500px;">
		<h2>Custom login logo options</h2>
		<p>You can add your custom login logo image to your <a href="./media-new.php">Media gallery</a> and paste the URL here.</p>
		<form method="post" action="options.php">
	  		<?php wp_nonce_field('update-options'); ?>
	  		<table class="form-table">
			 <tr><td scope="row">Image URL </td>
	 		 <td><input type="text" name="custom_login_logo_lite_image" value="<?php echo get_option( 'custom_login_logo_lite_image' ); ?>" size="30" /></td></tr>
	  		</table>
	 		<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="custom_login_logo_lite_image" />
			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e( 'Save Changes' ); ?>" />
			</p>

			<p><img src="http://s.wordpress.org/extend/plugins/custom-login-logo-lite/screenshot-5.png"></p>

			<p><img src="http://s.wordpress.org/extend/plugins/custom-login-logo-lite/screenshot-6.png"></p>

			<p><img src="http://s.wordpress.org/extend/plugins/custom-login-logo-lite/screenshot-4.png"></p>

		</form>
  </div>
<?php
} // custom_login_logo_lite_option_page()

function custom_login_logo_lite_menu() {
	add_options_page( 'custom login logo', 'Custom Login Logo', 9, __FILE__, 'custom_login_logo_lite_option_page' );
} // custom_login_logo_lite_menu()

function custom_login_logo_lite() {
	$custom_login_logo_lite_image = get_option( 'custom_login_logo_lite_image' );
    
	if(empty($custom_login_logo_lite_image)) {

		$custom_logo = $custom_image;

	} else {

		$custom_logo = $custom_login_logo_lite_image;
	}
?>
  
  	<style type="text/css">
		.login h1 a {
			background:url('<?php echo $custom_logo; ?>') no-repeat top center;
			width:326px;
			height:67px;
			text-indent:-9999px;
			overflow:hidden;
			padding-bottom:25px;
			display:block;
		}
	</style>
<?php

} //custom_login_logo_lite()

add_action( 'admin_menu', 'custom_login_logo_lite_menu' );
add_action( 'login_form', 'custom_login_logo_lite' );


?>