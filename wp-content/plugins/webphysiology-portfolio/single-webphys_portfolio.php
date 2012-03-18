<?php
/**
 * The template that displays a single portfolio record.
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.4.0
*/

/*  UPDATES

	1.4.1 - Added commenting to help an adventurous soul in copying out necessary code to paste into a single-webphys_portfolio.php file created within their theme's directory
	
*/

get_header();

?>

		<div id="container" class="webphysiology_portfolio_container">
			<div id="content" role="main">



<?php
	//  ****************************************************** //
	//                                                         //
	//    If you will be creating your own Single Portfolio    //
	//    page template then the section between these two     //
	//    tags is the main Portfolio content that you will     //
	//    likely just want to copy and paste as the content    //
	//    loop area of your template.                          //
	//                                                         //
	//                        ** START **                      //
	//                                                         //
	//  ****************************************************** //
	
	
	$options = get_webphys_port_options();
	
	// sets $click_behavior global variable
	get_click_behavior($options['img_click_behavior']);
	
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
	
	if ( ! isset($options['url_target']) || ($options['url_target'] == "False") ) {
		$target = '';
	} else {
		$target = ' target="_blank"';
	}
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();

		$description = '';
		if ($options['display_portfolio_desc'] == 'True') {
			$description = get_the_content();
			$description = apply_filters('the_content', $description);
		}
		$type = get_post_meta(get_the_ID(), "_portfolio_type", true);
		$portfolio_type = get_term_by( 'slug', $type, 'portfolio_type' );
		if (isset($portfolio_type->name)) {
			$type = $portfolio_type->name;
		} else {
			$type = "";
		}
		$datecreate =  get_post_meta(get_the_ID(), "_createdate", true);
		$client = get_post_meta(get_the_ID(), "_clientname", true);
		$technical_details = get_post_meta(get_the_ID(), "_technical_details", true);
		$siteurl = get_post_meta(get_the_ID(), "_siteurl", true);
?>
				<div id="webphysiology_portfolio" class="single_portfolio_page">
					<div id="post-<?php echo get_the_ID(); ?>" class="<?php echo implode(" ", get_post_class('webphy_portfolio_entry')) ?>">
						<h1 class="portfolio_title"><?php echo the_title_attribute('echo=0'); ?></h1>
						<div>
							<div class="portfolio_page_img">
								<?php echo get_Loop_Site_Image(); ?>
							</div>
							<div class="portfolio_details">
<?php
		if ( (!empty($description)) && ($options['display_desc_first'] == 'True') ) {
?>
								<div class="portfolio_description pdesc_top"><div class="value"><?php echo $description; ?></div></div>
<?php
		}
?>
								<div class="portfolio_meta">
<?php if ( !empty($type) && ($options['display_portfolio_type'] == 'True') ) { ?>
									<div class="portfolio_type"><div class="key"><?php echo $type_label . '</div><div class="value">' . $type; ?></div></div>
<?php } ?>
<?php if ( !empty($datecreate) && ($options['display_created_on'] == 'True') ) { ?>
									<div class="portfolio_datecreate"><div class="key"><?php echo $created_label . '</div><div class="value">' . $datecreate; ?></div></div>
<?php } ?>
<?php if ( !empty($client) && ($options['display_clientname'] == 'True') ) { ?>
									<div class="portfolio_client"><div class="key"><?php echo $client_label . '</div><div class="value">' . $client; ?></div></div>
<?php } ?>
<?php if ( !empty($siteurl) && ($options['display_siteurl'] == 'True') ) { ?>
									<div class="portfolio_siteurl"><div class="key"><?php echo $siteURL_label . '</div><div class="value">'; ?><a href="<?php echo $siteurl; ?>"<?php echo $target; ?>><?php echo $siteurl; ?></a></div></div>
<?php } ?>
<?php if ( !empty($technical_details) && ($options['display_tech'] == 'True') ) { ?>
									<div class="portfolio_techdetails"><div class="key"><?php echo $tech_label . '</div><div class="value">' . $technical_details; ?></div></div>
<?php } ?>
                                </div>
<?php
		if ( (!empty($description)) && ($options['display_desc_first'] != 'True') ) {
?>
								<div class="portfolio_description pdesc_bottom"><div class="value"><?php echo $description; ?></div></div>
<?php
		}
?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div><!-- #portfolios -->
		
		<?php comments_template( '', true ); ?>

<?php	endwhile; endif; // end of the loop.



	//  ****************************************************** //
	//                                                         //
	//                         ** END **                       //
	//                                                         //
	//    If you will be creating your own Single Portfolio    //
	//    page template then the section between these two     //
	//    tags is the main Portfolio content that you will     //
	//    likely just want to copy and paste as the content    //
	//    loop area of your template.                          //
	//                                                         //
	//  ****************************************************** //
?>



			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
