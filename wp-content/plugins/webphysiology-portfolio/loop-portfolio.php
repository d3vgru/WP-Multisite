<?php
/**
 * The loop that displays portfolio entries in place of the [portfolio] shortcode.
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.0.0
 */

/*  UPDATES

	1.4.0 - consolidated many options into an array that is now populated via the get_webphys_port_options function
	1.3.2 - * better isolated navigation controls by adding "webphysport_nav_top" and "webphysport_nav_bottom" classes.
			  top and bottom classes will be deprecated in a later release.
			* better isolated portfolio odd/even stripe styling by adding new webphysport_odd_stripe and webphysport_even_stripe classes.
			  odd and even classes will be deprecated in a later release.
	1.3.0 - implemented new option that allows for positioning the Portfolio description after the Portfolio meta data
	1.2.7 - fixed an issue where $portfolio_open_empty was not being defined for non-grid styled portfolios
	1.2.4 - exchanged per page code that was grabbing the per page option to using the global var for the per page portfolio count defined in portfolio-main.php
	      - updated the custom post type from "Portfolio" to "webphys_portfolio" because v3.1 doesn't like caps and also to avoid contention with other plugins
		  - updated code to utilize the new $display_the_credit global variable that is now set by the "credit" shortcode parameter
	1.2.3 - did a little CSS validation fixing where there were some extra quotes that didn't belong
	      - updated some (!$x == '') logic to (!empty($x)) syntax and other similar updates
		  - updated post-id ID references to handle multiple [shortcodes] on one page as they were not necessarily unique in this instance
		  - added the new option that allows an admin to set the links to open in a new tab/window
	1.2.2 - added ability to sort portfolio items alphanumerically
	1.2.0 - found that if more than one [portfolio] shortcode is used on a page, and subsequent uses result in a nav control, it picks up the
			wrong page within the nav URL, so, only set the current page variable the first time into this script as it is now set in a global var.
	      - enhanced code to better handle instances of no portfolios being returned, it essentially creates the required empty divs and no nav control.
		    this was most helpful where more than one [portfolio] shortcode is used on a page and some have records and others don't, especially on
			subsequent portfolio pages
	1.1.5 - found a typo in a var name and also added new parm to nav_pages() method
	1.1.3 - Added grid styling and code to handle new ability to turn off portfolio title and description
    1.1.2 - Added apply_filters() method to content retrieved with get_the_content() as, unlike the_content() method,
    1.1.0 - Added the ability to turn off the display of all detail data items should you want to store the values but not display the data to the user
            it does not apply_filters to the retrieved data, which results in any embedded [shortcodes] not being parsed
	
*/

global $loop;
global $wp_query;
global $portfolio_types;
global $portfolio_output;
global $num_per_page;
global $currpageurl;
global $port;
global $display_the_credit;

$options = get_webphys_port_options();

if ($options['sort_numerically'] == 'True') {
	$options['sort_numerically'] = "_num";
} else {
	$options['sort_numerically'] = "";
}
if ($options['legacy_even_odd_class'] != 'True') {
	$odd_class = 'webphysport_odd_stripe';
	$even_class = 'webphysport_even_stripe';
	$odd_grid_class = 'webphysport_odd_cell';
	$even_grid_class = ' webphysport_even_cell';
} else {
	$odd_class = 'webphysport_odd_stripe odd';
	$even_class = 'webphysport_even_stripe even';
	$odd_grid_class = 'webphysport_odd_cell odd';
	$even_grid_class = 'webphysport_even_cell';
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$portfolio_open_empty = "";
$ul_open_empty = "";
$li_open_odd_empty = "";
$post_class = "portfolio_entry ".$odd_class;

if (!isset($currpageurl)) {
	$currpageurl = get_permalink();
	$port = 0;
	$portnum = "";
} else {
	$port ++;
	$portnum = "-" . $port;
}

// grid styling defaults
$ul_open = '';
$ul_close = '';
$li_open_even = '';
$li_open_odd = '';
$li_close = '';
$gridclass = '';

$portfolio_open = '<div id="portfolios' . $portnum . '" class="webphysiology_portfolio">';
$portfolio_open_empty = '<div id="portfolios' . $portnum . '" class="webphysiology_portfolio empty">';

if ($options['gridstyle'] == 'True') {
	$ul_open = '<ul class="grid">';
	$ul_open_empty = '<ul class="grid empty">';
	$ul_close = '</ul>';
	$li_open_odd = '<li class="' . $odd_grid_class . '">';
	$li_open_even = '<li class="' . $even_grid_class . '">';
	$li_open_odd_empty = '<li class="' . $odd_grid_class . ' empty">';
	$li_close = '</li>';
	$gridclass = ' grid';
}
$type_label = $options['detail_labels']["Type"];
if ( !empty($type_label) ) $type_label .= ": ";
$created_label = $options['detail_labels']["Created"];
if ( !empty($created_label) ) $created_label .= ": ";
$client_label = $options['detail_labels']["Client"];
if ( !empty($client_label) ) $client_label .= ": ";
$siteURL_label = $options['detail_labels']["SiteURL"];
if ( !empty($siteURL_label) ) $siteURL_label .= ": ";
$tech_label = $options['detail_labels']["Tech"];
if ( !empty($tech_label) ) $tech_label .= ": ";

// if the portfolio shortcode had no portfolio types defined
if ( empty($portfolio_types) ) {
	$loop = new WP_Query( array( 'post_type' => 'webphys_portfolio', 'posts_per_page' => $num_per_page, 'orderby' => 'meta_value' . $options['sort_numerically'], 'meta_key' => '_sortorder', 'order' => 'ASC', 'paged'=> $paged ) );
} else {
	$wp_query->query_vars['portfoliotype'] = $portfolio_types;
	$loop = new WP_Query( array( 'post_type' => 'webphys_portfolio', 'portfoliotype' => $portfolio_types, 'posts_per_page' => $num_per_page, 'orderby' => 'meta_value' . $options['sort_numerically'], 'meta_key' => '_sortorder', 'order' => 'ASC', 'paged'=> $paged ) );
}

if ( $loop->have_posts() ) {
	
//	echo $loop->request . '<br />';  //asterisk
	
	if ( ! isset($options['url_target']) || ($options['url_target'] == "False") ) {
		$target = '';
	} else {
		$target = ' target="_blank"';
	}
	
	$portfolio_output .= $portfolio_open;

	// Display page navigation when applicable
	nav_pages($loop, $currpageurl, "webphysport_nav_top");
	
	// set odd/even indicator for portfolio background highlighting
	$odd = true;
	
	$portfolio_output .= $ul_open;
	
	while ( $loop->have_posts() ) {
		
		$loop->the_post();
		
		if ($odd==true) {
			$post_class = 'portfolio_entry ' . $odd_class;
			$portfolio_output .= $li_open_odd;
			$odd = false;
		} else {
			$post_class = 'portfolio_entry ' . $even_class;
			$portfolio_output .= $li_open_even;
			$odd = true;
		}
		if (!empty($portnum)) {
			$post_multi_port = '-' . str_replace("-", "", $portnum);
		} else {
			$post_multi_port = '';
		}
		
		$description = '';
		if ($options['display_portfolio_desc'] == 'True') {
			$description = get_the_content();
		}
		$type = get_post_meta(get_the_ID(), "_portfolio_type", true);
		$portfolio_type = get_term_by( 'slug', $type, 'portfolio_type' );
		if (isset($portfolio_type->name)) {
			$type = $portfolio_type->name;
		} else {
			$type = "";
		}
		$datecreate = get_post_meta(get_the_ID(), "_createdate", true);
		$client = get_post_meta(get_the_ID(), "_clientname", true);
		$technical_details = get_post_meta(get_the_ID(), "_technical_details", true);
		$siteurl = get_post_meta(get_the_ID(), "_siteurl", true);
		$sortorder = get_post_meta(get_the_ID(), "_sortorder", true);
	
		$portfolio_output .= '<div id="post-' . get_the_ID() . $post_multi_port . '" class="' . implode(" ", get_post_class($post_class)) . '">';
		$portfolio_output .= '    <div class="portfolio_page_img' . $gridclass . '">';
		$portfolio_output .= '    	' . get_Loop_Site_Image();
		$portfolio_output .= '    </div>';
		
		if ($options['gridstyle'] != 'True') {
			$portfolio_output .= '	<div class="portfolio_details">';
		}
		
		if ($options['display_portfolio_title'] == 'True') {
			$portfolio_output .= '        <div class="portfolio_title">';
//asterisk test link			$portfolio_output .= '<a href="'.the_permalink().'">link</a>';
			$portfolio_output .= '            ' . get_Loop_Portfolio_Title();
			$portfolio_output .= '        </div><!-- .entry-meta -->';
		}
		
		if ( (!empty($description)) && ($options['display_desc_first'] == 'True') ) {
			$description = apply_filters('the_content', $description);
			$description = str_replace(']]>', ']]>', $description);
			$portfolio_output .= '            <div class="portfolio_description"><div class="value">' . $description . '</div></div>';
		}
		
		$portfolio_output .= '		<div class="portfolio_meta">';
		
		if ( !empty($type) && ($options['display_portfolio_type'] == 'True') ) {
			$portfolio_output .= '            <div class="portfolio_type"><div class="key">' . $type_label . '</div><div class="value">' . $type . '</div></div>';
		}
		
		if ( !empty($datecreate) && ($options['display_created_on'] == 'True') ) {
			$portfolio_output .= '            <div class="portfolio_datecreate"><div class="key">' . $created_label . '</div><div class="value">' .$datecreate . '</div></div>';
		}
		
		if ( !empty($client) && ($options['display_clientname'] == 'True') ) {
			$portfolio_output .= '            <div class="portfolio_client"><div class="key">' . $client_label . '</div><div class="value">' .$client . '</div></div>';
		}
		
		if ( !empty($siteurl) && ($options['display_siteurl'] == 'True') ) {
			$portfolio_output .= '            <div class="portfolio_siteurl"><div class="key">' . $siteURL_label . '</div><div class="value"><a href="' . $siteurl . '"' . $target . '>' . $siteurl . '</a></div></div>';
		}
		
		if ( !empty($technical_details) && ($options['display_tech'] == 'True') ) {
			$portfolio_output .= '            <div class="portfolio_techdetails"><div class="key">' . $tech_label . '</div><div class="value">' . $technical_details . '</div></div>';
		}
		$portfolio_output .= '            ' . wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'webphysiology_portfolio' ), 'after' => '</div>' ) );
		if ($options['gridstyle'] != 'True') {
			$portfolio_output .= '        </div>';
		}
		if ( (!empty($description)) && ($options['display_desc_first'] != 'True') ) {
			$description = apply_filters('the_content', $description);
			$description = str_replace(']]>', ']]>', $description);
			$portfolio_output .= '            <div class="portfolio_description after_meta_data"><div class="value">' . $description . '</div></div>';
		}
		$portfolio_output .= '    </div>';
		
		$portfolio_output .= '</div><!-- #post-## -->';
		$portfolio_output .= $li_close;
		
	} // endwhile;
	

	$portfolio_output .= $ul_close;
	
	// Credit link
	if ( strtolower($display_the_credit) == 'true' ) {
		$portfolio_output .= '<div class="portfolio_credit"><em>powered by <a href="http://webphysiology.com/redir/webphysiology-portfolio/" target="_blank">WEBphysiology Portfolio</a></em></div>';
	}
	
	// Display page navigation when applicable
	nav_pages($loop, $currpageurl, "webphysport_nav_bottom");
	
} else {
	
	$portfolio_output .= $portfolio_open_empty;
	$portfolio_output .= $ul_open_empty;
	$portfolio_output .= $li_open_odd_empty;
	$portfolio_output .= '<div class="' . implode(" ", get_post_class($post_class)) . ' empty">';
	$portfolio_output .= '	<div class="portfolio_page_img">&nbsp;</div>';
	$portfolio_output .= '</div>';
	$portfolio_output .= $li_close;
	$portfolio_output .= $ul_close;
	
}
$portfolio_output .= '</div><!-- #portfolios -->';
?>