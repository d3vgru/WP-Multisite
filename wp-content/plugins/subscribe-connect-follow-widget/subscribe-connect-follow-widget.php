<?php
/*
Plugin Name: Subscribe / Connect / Follow Widget
Plugin URI: http://srinig.com/wordpress/plugins/subscribe-connect-follow-widget/
Description: A widget to display image links (icon buttons) to subscription services and social networking sites.
Version: 0.5.6
Author: Srini G
Author URI: http://srinig.com/wordpress/
License: GPL2
*/


add_action( 'widgets_init', 'scfw_load_widgets' );

function scfw_load_widgets() {
	register_widget( 'SCFW_Widget' );
}

class SCFW_Widget extends WP_Widget {
	
	private $num_items = 5;

	private $services = array (
		"blogger" => array (
			"name" => "Blogger Blog",
			"description" => "Blogger Blog",
			"option_text" => "Blogger Blog (URL)",
			"image" => "blogger.png",
			"url" => "{user_input}"
		),
		"delicious" => array (
			"name" => "Delicious",
			"description" => "Delicious bookmarks",
			"option_text" => "Delicious (username)",
			"image" => "delicious.png",
			"url" => "http://delicious.com/{user_input}"
		),
		"diaspora" => array (
			"name" => "Diaspora",
			"description" => "{user_input} on DIASPORA*",
			"option_text" => "Diaspora (username)",
			"image" => "diaspora_asterisk.png",
			"url" => "https://joindiaspora.com/u/{user_input}"
		),
		"digg" => array (
			"name" => "Digg",
			"description" => "Follow {user_input} on Digg",
			"option_text" => "Digg (username)",
			"image" => "digg.png",
			"url" => "http://digg.com/{user_input}"
		),
		"deviant-art" => array (
			"name" => "deviantART",
			"description" => "Watch {user_input} on deviantART",
			"option_text" => "deviantART (username)",
			"image" => "deviant-art.png",
			"url" => "http://{user_input}.deviantart.com/"
		),
		"facebook" => array (
			"name" => "Facebook",
			"description" => "Connect on Facebook",
			"option_text" => "Facebook (URL)",
			"image" => "facebook.png",
			"url" => "{user_input}"
		),
		"feedburner-email" => array (
			"name" => "Subscribe via Email",
			"description" => "Subscribe to posts via Email",
			"option_text" => "Feedburner Email Subscription (feed name)",
			"image" => "email.png",
			"url" => "http://feedburner.google.com/fb/a/mailverify?uri={user_input}"
		),
		"feedburner-feed" => array (
			"name" => "RSS Feed",
			"description" => "Subscribe to RSS Feed",
			"option_text" => "Feedburner feed (feed name)",
			"image" => "feedburner.png",
			"url" => "http://feeds.feedburner.com/{user_input}"
		),
		"flickr" => array (
			"name" => "Flickr",
			"description" => "Photos on Flickr",
			"option_text" => "Flickr (url)",
			"image" => "flickr.png",
			"url" => "{user_input}"
		),
		"friendfeed" => array (
			"name" => "FriendFeed",
			"description" => "{user_input} on FriendFeed",
			"option_text" => "FriendFeed (username)",
			"image" => "friendfeed.png",
			"url" => "http://friendfeed.com/{user_input}"
		),
		"google" => array (
			"name" => "Google Profile",
			"description" => "Google Profile",
			"option_text" => "Google Profile (username / user ID)",
			"image" => "google.png",
			"url" => "http://profiles.google.com/{user_input}"
		),
		"google-buzz" => array (
			"name" => "Google Buzz",
			"description" => "Google Buzz",
			"option_text" => "Google Buzz (username / user ID)",
			"image" => "google-buzz.png",
			"url" => "http://profiles.google.com/{user_input}#{user_input}/buzz"
		),
		"google-plus" => array (
			"name" => "Google+",
			"description" => "Google+",
			"option_text" => "Google + (user ID or page ID)",
			"image" => "GooglePlus-Gloss-Red.png",
			"url" => "https://plus.google.com/{user_input}"
		),
		"identi.ca" => array (
			"name" => "identi.ca",
			"description" => "Subscribe to {user_input} on identi.ca",
			"option_text" => "identi.ca (username)",
			"image" => "identi.png",
			"url" => "http://identi.ca/{user_input}"
		),
		"last.fm" => array (
			"name" => "Last.fm",
			"description" => "{user_input}'s Music Profile on Last.fm",
			"option_text" => "Last.fm (username)",
			"image" => "lastfm.png",
			"url" => "http://www.last.fm/user/{user_input}"
		),
		"linkedin" => array (
			"name" => "LinkedIn",
			"description" => "LinkedIn",
			"option_text" => "LinkedIn (Public Profile URL)",
			"image" => "linkedin.png",
			"url" => "{user_input}"
		),
		"myspace" => array (
			"name" => "Myspace",
			"description" => "{user_input} on Myspace",
			"option_text" => "Myspace (username)",
			"image" => "myspace.png",
			"url" => "http://www.myspace.com/{user_input}"
		),
		"picasa" => array (
			"name" => "Picasa Web Albums",
			"description" => "Picasa Web Albums - {user_input}",
			"option_text" => "Picasa Web Albums (username)",
			"image" => "picasa.png",
			"url" => "http://picasaweb.google.com/{user_input}"
		),
		"pinterest" => array (
			"name" => "Pinterest",
			"description" => "{user_input} on Pinterest",
			"option_text" => "Pinterest (username)",
			"image" => "pinterest.png",
			"url" => "http://pinterest.com/{user_input}/"
		),
		"podcast" => array (
			"name" => "Podcast",
			"description" => "Podcast",
			"option_text" => "Podcast (URL)",
			"image" => "podcast.png",
			"url" => "{user_input}"
		),
		"posterous" => array (
			"name" => "Posterous",
			"description" => "Posterous",
			"option_text" => "Posterous (URL)",
			"image" => "posterous.png",
			"url" => "{user_input}"
		),
		"reddit" => array (
			"name" => "reddit",
			"description" => "overview for {user_input} - on reddit",
			"option_text" => "reddit (username)",
			"image" => "reddit.png",
			"url" => "http://www.reddit.com/user/{user_input}"
		),
		"rss" => array (
			"name" => "RSS Feed",
			"description" => "Subscribe to RSS Feed",
			"option_text" => "RSS Feed (URL)",
			"image" => "rss.png",
			"url" => "{user_input}"
		),	
		"rss-posts" => array (
			"name" => "RSS Feed for Posts",
			"description" => "Subscribe to posts via RSS feed",
			"option_text" => "RSS Feed for Posts (URL)",
			"image" => "rss.png",
			"url" => "{user_input}"
		),	
		"rss-comments" => array (
			"name" => "RSS Feed for Comments",
			"description" => "Subscribe to comments via RSS feed",
			"option_text" => "RSS Feed for Comments (URL)",
			"image" => "rss.png",
			"url" => "{user_input}"
		),	
		"slashdot" => array (
			"name" => "Slashdot",
			"description" => "{user_input} - Slashdot User",
			"option_text" => "Slashdot (username)",
			"image" => "slashdot.png",
			"url" => "http://slashdot.com/~{user_input}"
		),
		"soundcloud" => array (
			"name" => "SoundCloud",
			"description" => "{user_input}'s page on SoundCloud",
			"option_text" => "SoundCloud (username)",
			"image" => "soundcloud.png",
			"url" => "http://soundcloud.com/{user_input}"
		),
		"stumbleupon" => array (
			"name" => "StumbleUpon",
			"description" => "Follow {user_input} on StumbleUpon",
			"option_text" => "StumbleUpon (username)",
			"image" => "stumbleupon.png",
			"url" => "http://www.stumbleupon.com/stumbler/{user_input}/"
		),
		"technorati" => array (
			"name" => "Technorati",
			"description" => "{user_input}'s profile - Technorati",
			"option_text" => "Technorati (username)",
			"image" => "technorati.png",
			"url" => "http://technorati.com/people/{user_input}"
		),
		"tumblr" => array (
			"name" => "Tumblr",
			"description" => "Follow on Tumblr",
			"option_text" => "Tumblr (URL)",
			"image" => "tumblr.png",
			"url" => "{user_input}"
		),
		"twitter" => array (
			"name" => "Twitter",
			"description" => "Follow {user_input} on Twitter",
			"option_text" => "Twitter (username)",
			"image" => "twitter-2.png",
			"url" => "http://twitter.com/{user_input}"
		),
		"vimeo" => array (
			"name" => "Vimeo",
			"description" => "{user_input} on Vimeo",
			"option_text" => "Vimeo (username)",
			"image" => "vimeo.png",
			"url" => "http://vimeo.com/{user_input}"
		),
		"wordpress-blog" => array (
			"name" => "WordPress.com Blog",
			"description" => "WordPress.com Blog",
			"option_text" => "WordPress.com Blog (URL)",
			"image" => "wordpress-blue.png",
			"url" => "{user_input}"
		),
		"xing" => array (
			"name" => "XING",
			"description" => "XING Profile",
			"option_text" => "XING (Profile URL)",
			"image" => "xing.png",
			"url" => "{user_input}"
		),
		"youtube" => array (
			"name" => "YouTube",
			"description" => "Subscribe to {user_input}'s channel on YouTube",
			"option_text" => "YouTube (username)",
			"image" => "youtube.png",
			"url" => "http://www.youtube.com/user/{user_input}"
		)
	);

	/**
	 * Widget setup.
	 */
	function SCFW_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'scfw', 'description' => __('Image links to subscription services and social networking sites.', 'scfw') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 320, 'height' => 700, 'id_base' => 'scfw' );

		/* Create the widget. */
		$this->WP_Widget( 'scfw', __('Subscribe / Connect / Follow Widget', 'scfw'), $widget_ops, $control_ops );
	}
	

		

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );

		$services = $this->services;

		$item_template = $this->item_template($instance['format'], $instance['window']);
		
		$output = "";		
			
		for($i = 0; $i < $this->num_items; $i++) {
			if(!$instance["itemval-{$i}"] || !$instance["item-{$i}"] || $instance["item-{$i}"] == "----SELECT----") continue;
			$url = str_replace('{user_input}', $instance["itemval-{$i}"], $services[$instance["item-{$i}"]]['url']);
			$item = str_replace('{url}', $url, $item_template);
			$item = str_replace('{description}', $services[$instance["item-{$i}"]]['description'], $item);
			$item = str_replace('{user_input}', $instance["itemval-{$i}"], $item);
			$item = str_replace('{image}', $services[$instance["item-{$i}"]]['image'], $item);
			$item = str_replace('{name}', $services[$instance["item-{$i}"]]['name'], $item);
			$item = str_replace('{link_text}', $services[$instance["item-{$i}"]]['name'], $item);
			$output .= $item;
				
		}
		
		if(!$output) return;

		if($instance['format'] == 'text')
			$output = '<ul class="scfw_'.$instance['format'].'">'.$output.'</ul>'; 
		else if($instance['format'] == 'text_img')
			$output = '<ul class="scfw_'.$instance['format'].'" style="list-style:none;margin:0;padding:0;">'.$output.'</ul>';
		else {
			if($instance['align'] != 'default')
				$css_align = "text-align:".$instance['align'].";";
			else $css_align = "";

			$output = '<ul class="scfw_'.$instance['format'].'" style="list-style:none;margin:0;padding:0;'.$css_align.'">'.$output.'</ul>';
		}	

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo $output;
		
		echo $after_widget;
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['format'] = $new_instance['format'];
		$instance['align'] = $new_instance['align'];
		$instance['window'] = $new_instance['window'];
		
		for($i = 0; $i < $this->num_items; $i++) {
			$instance["item-{$i}"] = $new_instance["item-{$i}"];
			$instance["itemval-{$i}"] = strip_tags($new_instance["itemval-{$i}"]);
		}

		return $instance;
	}

	function form( $instance ) {

		$defaults = array( 'title' => __('Subscribe', 'scfw'), 'format' => "32px", 'align' => 'default', 'window' => 'same');
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$format_selected = array('32px' => '', '24px' => '', '16px' => '', 'text_img' => '', 'text' => '');
		$align_selected = array('default' => '',  'left' => '', 'center' => '', 'right' => '');
		$window_selected = array('same' => '', 'new' => '');
		
		$format_selected[$instance['format']] = ' selected="selected"'; 
		$align_selected[$instance['align']] = ' selected="selected"';
		$window_selected[$instance['window']] = ' selected="selected"';
		
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>


		<p>
			<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e('Display format:', 'scfw'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>">
				<option value="32px"<?php echo $format_selected['32px']; ?>>32px image</option>
				<option value="24px"<?php echo $format_selected['24px']; ?>>24px image</option>
				<option value="16px"<?php echo $format_selected['16px']; ?>>16px image</option>
				<option value="text_img"<?php echo $format_selected['text_img']; ?>>Text links with image</option>
				<option value="text"<?php echo $format_selected['text']; ?>>Text links</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e('Alignment for images:', 'scfw'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
				<option value="default"<?php echo $align_selected['default']; ?>>Theme default</option>
				<option value="left"<?php echo $align_selected['left']; ?>>Left</option>
				<option value="center"<?php echo $align_selected['center']; ?>>Center</option>
				<option value="right"<?php echo $align_selected['right']; ?>>Right</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'window' ); ?>"><?php _e('Open links in:', 'scfw'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'window' ); ?>" name="<?php echo $this->get_field_name( 'window' ); ?>">
				<option value="same"<?php echo $window_selected['same']; ?>>Same window</option>
				<option value="new"<?php echo $window_selected['new']; ?>>New window</option>
			</select>
		</p>

		<p><strong>Services</strong></p>
		<?php for($i = 0; $i < $this->num_items; $i++) { 
		
			$item_i = "item-".$i;
			$itemval_i = "itemval-".$i;
			
			if(!isset($instance[$item_i])) $instance[$item_i] = "";
			if(!isset($instance[$itemval_i])) $instance[$itemval_i] = "";
			
			
		?>
		
		<p>
			<select id="<?php echo $this->get_field_id( $item_i ); ?>"name="<?php echo $this->get_field_name( $item_i ); ?>">
			<option value="0">----SELECT----</option>
			<?php echo $this->optionlist($instance[$item_i]); ?>
			</select>
			<input class="widefat" id="<?php echo $this->get_field_id( $itemval_i ); ?>" name="<?php echo $this->get_field_name( $itemval_i ); ?>" value="<?php echo $instance[$itemval_i]; ?>" style="width:100%;" />
		</p>
		
		<?php } // for loop ?>

	<?php
	}
	
	
	function optionlist($default = "")
	{
		$services = $this->services;
		$list = "";
		foreach ($services as $key => $value) {
			if($default == $key) $selected = ' selected="selected"';
			else $selected = '';
			$list .= '<option value="'.$key.'"'.$selected.'>'.$value['option_text'].'</option>';
		}
		return $list;
	}
	
	function item_template($format = "32px", $window = "same")
	{
		$target = ($window == 'new')?' target="_blank"':'';
		switch($format) {
			case("text"): {
				return '<li><a href="{url}" title="{description}"'.$target.'>{link_text}</a></li>';
			}
			case("text_img"): {
				return '<li style="background:url(\''.WP_PLUGIN_URL.'/subscribe-connect-follow-widget/images/16px/{image}\') no-repeat 0% 50%;padding-left:20px;font-size:14px;"><a href="{url}" title="{description}"'.$target.'>{link_text}</a></li>';
			}
			case("16px"): {
				return '<li><a href="{url}" title="{description}"'.$target.'><img src="'.WP_PLUGIN_URL.'/subscribe-connect-follow-widget/images/16px/{image}" alt="{name}" height="16px" width="16px" /></a></li>';
			}
			case("24px"): {
				return '<li><a href="{url}" title="{description}"'.$target.'><img src="'.WP_PLUGIN_URL.'/subscribe-connect-follow-widget/images/24px/{image}" alt="{name}" height="24px" width="24px" /></a></li>';
			}
			case("32px"): {
				return '<li><a href="{url}" title="{description}"'.$target.'><img src="'.WP_PLUGIN_URL.'/subscribe-connect-follow-widget/images/32px/{image}" alt="{name}" height="32px" width="32px" /></a></li>';
			}
		}
	}

}


function scfw_head() 
{
	?>
<style type="text/css">
ul.scfw_16px li, ul.scfw_24px li, ul.scfw_32px li, ul.scfw_16px li a, ul.scfw_24px li a, ul.scfw_32px li a {
	display:inline !important;
	float:none !important;
	border:0 !important;
	background:transparent none !important;
	margin:0 !important;
	padding:0 !important;
}
ul.scfw_16px li {
	margin:0 2px 0 0 !important;
}
ul.scfw_24px li {
	margin:0 3px 0 0 !important;
}
ul.scfw_32px li {
	margin:0 5px 0 0 !important;
}
ul.scfw_text_img li:before, ul.scfw_16px li:before, ul.scfw_24px li:before, ul.scfw_32px li:before {
	content:none !important;
}
.scfw img {
	float:none !important;
}
</style>	
	<?php
}


add_action('wp_head', 'scfw_head' );

?>
