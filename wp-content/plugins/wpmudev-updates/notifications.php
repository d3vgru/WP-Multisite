<?php

class WPMUDEV_Notifications_Output {

	function WPMUDEV_Notifications_Output() {
		add_action( 'admin_notices', array( &$this, 'upgrade_notice_output' ), 2);
		add_action( 'network_admin_notices', array( &$this, 'upgrade_notice_output' ), 2); //for 3.1
		add_action( 'admin_notices', array( &$this, 'old_plugin_check' ) );
		add_action( 'network_admin_notices', array( &$this, 'old_plugin_check' ) ); //for 3.1
		add_action( 'admin_notices', array( &$this, 'apikey_notice_output' ) );
		add_action( 'network_admin_notices', array( &$this, 'apikey_notice_output' ) ); //for 3.1
		add_action( 'admin_notices', array( &$this, 'admin_notice_output' ) );
		add_action( 'network_admin_notices', array( &$this, 'admin_notice_output' ) ); //for 3.1

		add_action( 'admin_footer', array( &$this, 'admin_footer_scripts' ) );
		add_action( 'wp_ajax_wpmudev-dismiss', array( &$this, 'ajax_dismiss' ) );

		$this->admin_url = $GLOBALS['wpmudev_notifications']->admin_url;
	}

	function upgrade_notice_output() {
		if ( current_user_can('edit_users') ) {
			//temporary dismissing
			if ( get_site_option('wdp_un_dismissed_upgrade') && get_site_option('wdp_un_dismissed_upgrade') > time() )
				return;

			$updates = get_site_option('wdp_un_updates_available');
			$count = ( is_array($updates) ) ? count( $updates ) : 0;

			if ( $count > 0 && $_GET['page'] != 'wpmudev' && !get_site_option('wdp_un_hide_upgrades') ) {
				$data = get_site_option('wdp_un_last_response');
				$msg = ($data['text_admin_notice']) ? $data['text_admin_notice'] : __('<strong>WPMU DEV updates are available</strong>: These may be critical for the security or performance of this site so please review your available updates today &raquo;', 'wpmudev');

				echo '
					<div class="update-nag">
						<a href="' . $this->admin_url . '">' . $msg . '</a>
						<a class="wpmudev-dismiss" data-key="upgrade-dismiss" data-id="1" style="float:right;margin-right:5px;" title="'.__('Dismiss this notice for this session', 'wpmudev').'" href="' . $this->admin_url . '&upgrade-dismiss=1"><small>'.__('Dismiss', 'wpmudev').'</small></a>
					</div>';
			}
		}
	}

	function apikey_notice_output() {
		if ( current_user_can('edit_users') ) {
			if ( !get_site_option('wpmudev_apikey') && $_GET['page'] != 'wpmudev' ) {
				$link = $this->admin_url . '#wpmudev_apikey';
				echo '<div class="error fade"><p>' . sprintf( __('Please <a href="%s">enter your free API Key</a> to enable auto-updates and special members-only offers from WPMU DEV.', 'wpmudev'), $link) . '</a></p></div>';
			}
		}
	}

	function admin_notice_output() {
		if ( !current_user_can('edit_users') || $_GET['page'] == 'wpmudev' )
			return;

		//handle ad messages
		$data = get_site_option('wdp_un_last_response');
		$dismissed = get_site_option('wdp_un_dismissed');
		if ( $data['membership'] == 'full' ) { //full member
			if ( false == ($dismissed['id'] == $data['full_notice']['id'] && $dismissed['expire'] > time()) ) {
				$msg = $data['full_notice']['msg'];
				$id = $data['full_notice']['id'];
			}
		} else if ( is_numeric($data['membership']) ) { //single member
			if ( false == ($dismissed['id'] == $data['single_notice']['id'] && $dismissed['expire'] > time()) ) {
				$msg = $data['single_notice']['msg'];
				$id = $data['single_notice']['id'];
			}
		} else { //free member
			if ( false == ($dismissed['id'] == $data['free_notice']['id'] && $dismissed['expire'] > time()) ) {
				$msg = $data['free_notice']['msg'];
				$id = $data['free_notice']['id'];
			}
		}

		if ($msg && !get_site_option('wdp_un_hide_notices') )
			echo '<div class="updated"><p>' . stripslashes($msg) . ' <a class="wpmudev-dismiss" data-key="dismiss" data-id="' . $id  . '" style="float:right;" title="'.__('Dismiss this notice for one month', 'wpmudev').'" href="' . $this->admin_url . '&dismiss=' . $id . '"><small>'.__('Dismiss', 'wpmudev').'</small></a></p><br /></div>';

		//show latest project information
		if ( !get_site_option('wdp_un_hide_releases') && is_array($data['latest_release']) ) {
			$dismissed_release = get_site_option('wdp_un_dismissed_release');
			if ( $dismissed_release != $data['latest_release']['id'] ) {
				echo '<div class="updated fade">';
				echo '<h3 style="margin: .1em 0 0 0;">' . __('New WPMU DEV Release:', 'wpmudev') . '</h3>';
				echo "<p><a target='_blank' title='" . __('More Information &raquo;', 'wpmudev') . "' href='{$data['latest_release']['url']}'><img src='http://premium.wpmudev.org/wp-content/projects/{$data['latest_release']['id']}/listing-image-thumb.png' width='80' height='60' style='float:left; padding: 5px' /></a>";
				echo "<strong>{$data['latest_release']['title']}</strong><br />";
				echo $data['latest_release']['short_description'] . "<br />";
				echo "<a target='_blank' title='" . __('More Information &raquo;', 'wpmudev') . "' href='{$data['latest_release']['url']}'>" . __('More Information &raquo;', 'wpmudev') . "</a><br />";
				echo '<a class="wpmudev-dismiss" data-key="dismiss-release" data-id="' . $data['latest_release']['id'] . '" style="float:right;" title="'.__('Dismiss this announcement', 'wpmudev').'" href="' . $this->admin_url . '&dismiss-release=' . $data['latest_release']['id'] . '"><small>'.__('Dismiss', 'wpmudev').'</small></a></p><br /></div>';
			}
		}

	}

	function old_plugin_check() {
		if ( !current_user_can('edit_users') )
			return;

		if ( function_exists( 'update_notificiations_process' ) ) {
			echo '<div class="error fade"><p>' . __('Please delete the old version of the WPMU DEV Update Notifications plugin! Check for the update-notifications.php file in the /mu-plugins/ folder and delete it.', 'wpmudev') . '</p></div>';
		}
	}

	function admin_footer_scripts() {
?>
		<script type="text/javascript">
		jQuery(function($) {
			$('.wpmudev-dismiss').click(function() {
				var $link = $(this), data = { 'action': 'wpmudev-dismiss' };

				$link.closest('.updated, .update-nag').fadeOut('fast');

				data[ $link.attr('data-key') ] = $link.attr('data-id');

				$.post(ajaxurl, data);

				return false;
			});
		});
		</script>
<?php
	}

	function ajax_dismiss() {
		if ( !current_user_can('edit_users') )
			return;

		global $wpmudev_notifications;

		$wpmudev_notifications->handle_dismiss();

		die;
	}
}

new WPMUDEV_Notifications_Output;
?>