<?php
/*
Plugin Name: Wordpress Font Uploader
Plugin URI: http://pippinspages.com/plugins/font-uploader
Description: A custom font upload plugin for Wordpress allowing you to use any font anywhere you wish.
Version: 1.7.1
Author: Pippin Williamson
Author URI: http://pippinspages.com
*/

      
$baseFontDir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
$fontDirectoryPath = WP_PLUGIN_DIR . '/font-uploader/fonts';
$fontURL = $baseFontDir . 'fonts';
$fontDir = opendir($fontDirectoryPath);
$fontExtensions = 'oft' && 'ttf' && 'eot';
$fontList = array();
while(($font = readdir($fontDir)) !== false)
	{
		if($font != '.' && $font != '..' && !is_file($font) && $font != '.htaccess' && !is_dir($font) && $font != 'resource.frk' && !eregi('^Icon',$font))
			{
				// $fontURL."/".
				$fontList[$font] = $font;
			}
	}
closedir($fontDir);
array_unshift($fontList, "Choose a font");

$fontSizes = array('8px','9px','10px','11px','12px','13px','14px','15px','16px',
					'17px','18px','19px','20px','21px','22px','23px','24px','25px',
					'26px','27px','28px','29px','30px','31px','32px','33px','34px',
					'35px','36px','37px','38px','39px','40px');
array_unshift($fontSizes, "Choose a size");
$fontUploaderName = 'Font Uploader';
$sn = 'fu';

// load all of the options
include('includes/options.php');

// load the admin page
include('font-uploader-admin.php');

function fontAddAdmin()
{
    global $fontUploaderName, $sn, $fontOptions;

    if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) )
    {
        if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] )
        {
            foreach ($fontOptions as $value)
            {
                update_option( $value['id'], $_REQUEST[ $value['id'] ] );
            }

            foreach ($fontOptions as $value)
            {
                if( isset( $_REQUEST[ $value['id'] ] ) )
                {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
                }
                else
                {
                    delete_option( $value['id'] );
                }
            }

            header('Location: ' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=font-uploader.php&saved=true');
            exit;

        }
        else if( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] )
        {

            foreach ($fontOptions as $value)
            {
                delete_option( $value['id'] );
            }

            header('Location: ' . get_bloginfo('wpurl') . '/admin.php?page=font-uploader.php&saved=true');
            exit;

        }
    }
	 $baseFontDir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    add_menu_page($fontUploaderName, $fontUploaderName, 'administrator', basename(__FILE__), 'fontAdmin', $baseFontDir . 'font-uploader-icon.png');
}

function fontInit()
{
    $fontFileDir= WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    wp_enqueue_style("fontFunctions", $fontFileDir."/fontFunctions/fontFunctions.css", false, "1.1", "all");
    wp_enqueue_script("fu_script", $fontFileDir."/fontFunctions/fu_script.js", false, "1.0");
}
 
function addGoogleFonts()
{
	echo strip_tags(stripslashes(get_option('fu_google_font_urls')), '<link>');

}
add_action('wp_head', 'addGoogleFonts'); 

function addStyles()	{
	global $fontURL;
	echo '<style type="text/css" media="screen">';
			if (get_option('fu_header_font') != 'Choose a font' && get_option('fu_header_font') != null)
			{	echo '
				@font-face {
				  font-family: "header-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_header_font'); echo '");
				}';
			}
			if (get_option('fu_body_font') != 'Choose a font'  && get_option('fu_body_font') != null)
			{	echo '
				@font-face {
				  font-family: "body-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_body_font'); echo '");
				}';
			}
			if (get_option('fu_lists_font') != 'Choose a font'  && get_option('fu_lists_font') != null)
			{	echo '
				@font-face {
				  font-family: "lists-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_lists_font'); echo '");
				}';
			}
			if (get_option('fu_custom_one_font') != 'Choose a font'  && get_option('fu_custom_one') != null)			
			{	echo '
				@font-face {
				  font-family: "custom-one";
				  src: url("'; echo $fontURL . '/' . get_option('fu_custom_one_font'); echo '");
				}';
			}
			if (get_option('fu_custom_two_font') != 'Choose a font'  && get_option('fu_custom_two') != null)			
			{	echo '
				@font-face {
				  font-family: "custom-two";
				  src: url("'; echo $fontURL . '/' . get_option('fu_custom_two_font'); echo '");
				}';
			}
			if (get_option('fu_custom_three_font') != 'Choose a font'  && get_option('fu_custom_three') != null)			
			{	echo '
				@font-face {
				  font-family: "custom-three";
				  src: url("'; echo $fontURL . '/' . get_option('fu_custom_three_font'); echo '");
				}';
			}
			if (get_option('fu_custom_four_font') != 'Choose a font'  && get_option('fu_custom_four') != null)			
			{	echo '
				@font-face {
				  font-family: "custom-four";
				  src: url("'; echo $fontURL . '/' . get_option('fu_custom_four_font'); echo '");
				}';
			}
			if (get_option('fu_custom_five_font') != 'Choose a font'  && get_option('fu_custom_five') != null)			
			{	echo '
				@font-face {
				  font-family: "custom-five";
				  src: url("'; echo $fontURL . '/' . get_option('fu_custom_five_font'); echo '");
				}';
			}
			if (get_option('fu_ie_custom_one_font') != 'Choose a font'  && get_option('fu_ie_custom_one') != null)		
			{	echo '
				@font-face {
				  font-family: "ie_custom-one";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_custom_one_font'); echo '");
				}';
			}
			if (get_option('fu_ie_custom_two_font') != 'Choose a font'  && get_option('fu_ie_custom_two') != null)		
			{	echo '
				@font-face {
				  font-family: "ie_custom-two";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_custom_two_font'); echo '");
				}';
			}
			if (get_option('fu_ie_custom_three_font') != 'Choose a font'  && get_option('fu_ie_custom_three') != null)		
			{	echo '
				@font-face {
				  font-family: "ie_custom-three";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_custom_three_font'); echo '");
				}';
			}
			if (get_option('fu_ie_custom_four_font') != 'Choose a font'  && get_option('fu_ie_custom_four') != null)		
			{	echo '
				@font-face {
				  font-family: "ie_custom-four";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_custom_four_font'); echo '");
				}';
			}
			if (get_option('fu_ie_custom_five_font') != 'Choose a font'  && get_option('fu_ie_custom_five') != null)		
			{	echo '
				@font-face {
				  font-family: "ie_custom-five";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_custom_five_font'); echo '");
				}';
			}
			
			
		$fu_google_font_header = get_option('fu_google_header_font_name');
			if (!empty($fu_google_font_header))
			{	echo '		
				h1,h2,h3,h4,h5,h6	{
					font-family: "'; echo get_option('fu_google_header_font_name'); echo '"!important;
				}';
			}
		$fu_google_font_body = get_option('fu_google_body_font_name');
			if (!empty($fu_google_font_body))
			{	echo '		
				p, em, div	{
					font-family: "'; echo get_option('fu_google_body_font_name'); echo '"!important;
				}';
			}
		$fu_google_font_lists = get_option('fu_google_lists_font_name');
			if (!empty($fu_google_font_lists))
			{	echo '		
				li	{
					font-family: "'; echo get_option('fu_google_lists_font_name'); echo '"!important;
				}';
			}		
			if (get_option('fu_header_font') != 'Choose a font' && get_option('fu_header_font') != null)
			{
				echo	'h1, h2, h3, h4, h5, h6, h7	{
				font-family: "header-font"!important;
				}';
			}
			if (get_option('fu_body_font') != 'Choose a font'  && get_option('fu_body_font') != null)
			{
				echo 'p, em, div	{
					font-family: "body-font"!important;
				}';
			}
			if (get_option('fu_lists_font') != 'Choose a font'  && get_option('fu_lists_font') != null)
			{
				echo '
				li	{
					font-family: "lists-font"!important;
				}';
			}
			/*font sizes*/
			
			if (get_option('fu_header_font_size') != 'Choose a size' && get_option('fu_header_font_size') != null)
			{
				echo	'h1, h2, h3, h4, h5, h6, h7	{
					font-size: ' . get_option('fu_header_font_size') . '!important;
				}';
			}
			if (get_option('fu_body_font_size') != 'Choose a size'  && get_option('fu_body_font_size') != null)
			{
				echo 'p, em, div	{
					font-size: ' . get_option('fu_body_font_size') . '!important;
				}';
			}
			if (get_option('fu_lists_font_size') != 'Choose a size'  && get_option('fu_lists_font_size') != null)
			{
				echo '
				li	{
					font-size: ' . get_option('fu_lists_font_size') . '!important;
				}';
			}			
			if (get_option('fu_custom_one_size_element') != null && get_option('fu_custom_one_size') != 'Choose a size')
			{
				echo get_option('fu_custom_one_size_element') . ' {
					font-size: ' . get_option('fu_custom_one_size') . '!important;
				}';
			}
			if (get_option('fu_custom_two_size_element') != null && get_option('fu_custom_two_size') != 'Choose a size')
			{
				echo get_option('fu_custom_two_size_element') . ' {
					font-size: ' . get_option('fu_custom_two_size') . '!important;
				}';
			}
			if (get_option('fu_custom_three_size_element') != null && get_option('fu_custom_three_size') != 'Choose a size')
			{
				echo get_option('fu_custom_three_size_element') . ' {
					font-size: ' . get_option('fu_custom_three_size') . '!important;
				}';
			}	
			if (get_option('fu_custom_four_size_element') != null && get_option('fu_custom_four_size') != 'Choose a size')
			{
				echo get_option('fu_custom_four_size_element') . ' {
					font-size: ' . get_option('fu_custom_four_size') . '!important;
				}';
			}	
			if (get_option('fu_custom_five_size_element') != null && get_option('fu_custom_five_size') != 'Choose a size')
			{
				echo get_option('fu_custom_five_size_element') . ' {
					font-size: ' . get_option('fu_custom_five_size') . '!important;
				}';
			}	
			
			/*end sizes*/
			
			if (get_option('fu_custom_one_font') != 'Choose a font'  && get_option('fu_custom_one') != null)			
			{
				echo get_option('fu_custom_one'); echo '	{
					font-family: "custom-one"!important;
				}';
			}
			if (get_option('fu_custom_two_font') != 'Choose a font'  && get_option('fu_custom_two') != null)			
			{
				echo get_option('fu_custom_two'); echo '	{
					font-family: "custom-two"!important;
				}';
			}
			if (get_option('fu_custom_three_font') != 'Choose a font'  && get_option('fu_custom_three') != null)			
			{
				echo get_option('fu_custom_three'); echo '	{
					font-family: "custom-three"!important;
				}';
			}
			if (get_option('fu_custom_four_font') != 'Choose a font'  && get_option('fu_custom_four') != null)			
			{
				echo get_option('fu_custom_four'); echo '	{
					font-family: "custom-four"!important;
				}';
			}
			if (get_option('fu_custom_five_font') != 'Choose a font'  && get_option('fu_custom_five') != null)			
			{
				echo get_option('fu_custom_five'); echo '	{
					font-family: "custom-five"!important;
				}';
			}
	
			echo '
			</style>';
			echo '
				<!-- begin custom IE elements -->
				';
			echo '<!--[if IE]>
					<style type="text/css" media="screen">';
			if (get_option('fu_ie_custom_one_font') != 'Choose a font'  && get_option('fu_ie_custom_one') != null)			
			{
				echo get_option('fu_ie_custom_one'); echo '	{
					font-family: "ie_custom-one"!important;
				}';
			}
			if (get_option('fu_ie_custom_two_font') != 'Choose a font'  && get_option('fu_ie_custom_two') != null)			
			{
				echo get_option('fu_ie_custom_two'); echo '	{
					font-family: "ie_custom-two"!important;
				}';
			}
			if (get_option('fu_ie_custom_three_font') != 'Choose a font'  && get_option('fu_ie_custom_three') != null)			
			{
				echo get_option('fu_ie_custom_three'); echo '	{
					font-family: "ie_custom-three"!important;
				}';
			}
			if (get_option('fu_ie_custom_four_font') != 'Choose a font'  && get_option('fu_ie_custom_four') != null)			
			{
				echo get_option('fu_ie_custom_four'); echo '	{
					font-family: "ie_custom-four"!important;
				}';
			}
			if (get_option('fu_ie_custom_five_font') != 'Choose a font'  && get_option('fu_ie_custom_five') != null)			
			{
				echo get_option('fu_ie_custom_five'); echo '	{
					font-family: "ie_custom-five"!important;
				}';
			}
			echo '</style><![endif]-->';

			
}			
add_action('wp_head','addStyles');

function addIEStyles()	{
	global $fontURL;

	echo '<!--[if IE]>
			<style type="text/css" media="screen">';
			if (get_option('fu_ie_header_font') != 'Choose a font'  && get_option('fu_ie_header_font') != null)
			{	echo '
				@font-face {
				  font-family: "ie-header-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_header_font'); echo '");
				}';
			}
			if (get_option('fu_ie_lists_font') != 'Choose a font'  && get_option('fu_ie_lists_font') != null)
			{	echo '
				@font-face {
				  font-family: "ie-lists-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_lists_font'); echo '");
				}
				';
			}
			if (get_option('fu_ie_body_font') != 'Choose a font'  && get_option('fu_ie_body_font') != null)
			{	echo '
				@font-face {
				  font-family: "ie-body-font";
				  src: url("'; echo $fontURL . '/' . get_option('fu_ie_body_font'); echo '");
				}';
			}
			if (get_option('fu_ie_header_font') != 'Choose a font'  && get_option('fu_ie_header_font') != null)
			{
				echo	'
				h1, h2, h3, h4, h5, h6, h7	{
					font-family: "ie-header-font"!important;
				}';
			}
			if (get_option('fu_ie_lists_font') != 'Choose a font'  && get_option('fu_ie_lists_font') != null)
			{
				echo '
				li	{
					font-family: "ie-lists-font"!important;
				}';
			}
			if (get_option('fu_ie_body_font') != 'Choose a font'  && get_option('fu_ie_body_font') != null)
			{
				echo '
				p, em, div	{
					font-family: "ie-body-font"!important;
				}';
			}
			echo '
			</style>
			<![endif]-->';
}			
add_action('wp_head','addIEStyles');
?>