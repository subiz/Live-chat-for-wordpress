<?php
	/*
	Plugin Name: Subiz Live Chat
	Plugin URI: http://support.subiz.com/support/solutions/articles/76904-subiz-plugins
	Description: A Plugin to deploy the Subiz widget on your self-hosted Wordpress blog and easy Live chat support software for business in real time
	Version: 1.0
	Author: mrsubiz
	Author URI: http://subiz.com
	License: GPL2
	*/
	# Init && Load plugin
	$sfw_plugin 	= 'subiz-live-chat';
	$sfw_plugin_url = get_option('siteurl').'/'.PLUGINDIR.'/subiz-live-chat/';
	$sfw_domain 	= 'SubizForWP'; 
	load_plugin_textdomain($sfw_domain, 'wp-content/plugins/subiz-live-chat');
	add_action('init', 'sfw_init');
	add_action('wp_footer', 'sfw_insert');
	add_action('wp_ajax_my_action', 'subiz_action_callback');
	# Init subiz for wordpress 
	function sfw_init() {
		if(function_exists('current_user_can') && current_user_can('manage_options')) {
			add_action('admin_menu', 'sfw_add_settings_page');		
		}
		if ( !function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		# Subiz check verified email && subiz widget code
		$subiz_verified_email = get_option('subiz_verified_email');	
		$subiz_widget_code	  = get_option('subiz_widget_code'); 
		if($subiz_verified_email === false && $subiz_widget_code === false) {
			add_option( 'subiz_verified_email', '', $deprecated=null, 'yes' );
			add_option( 'subiz_widget_code', '', $deprecated=null, 'yes' );
		}
	}

	# Insert subiz widget code into footer
	function sfw_insert() {
		$subiz_widget_code = get_option('subiz_widget_code'); 
		$subiz_widget_code = str_replace('\\', '', $subiz_widget_code);
		echo $subiz_widget_code;
	}

	# Subiz plugin with some settings 
	function sfw_settings(){
		register_setting( 'subiz-live-chat-group', 'subiz_verified_email' );
		add_settings_section( 'subiz-live-chat', "Subiz for WP","", 'subiz-live-chat-group' );
	}

	# Update subiz widget code options 
	function subiz_action_callback() {
		$subiz_widget_code  = trim($_POST['subiz_code']);
		update_option( 'subiz_widget_code', $subiz_widget_code, $deprecated=null, 'yes' );
		setcookie ("subiz_client_id", "", time() - 3600, "/");
		die('yes');
	}

	function sfw_add_settings_page() {
	function sfw_settings_page() {
		$subiz_verified_email_chk = get_option('subiz_verified_email');
		global $sfw_domain, $sfw_plugin_url, $subiz_widget_code; ?>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="<?php echo $sfw_plugin_url.'subiz-for-wp.js'?>"></script>
		<div class="wrap">
			<?php screen_icon() ?>
			<h2>Subiz Live Chat</h2>
			<div class="metabox-holder meta-box-sortables ui-sortable pointer">
				<div class="postbox" id="subizActivateBox" style="float:left;width:30em;margin-right:20px;">
					<h3 class="hndle" align="center"><span>Subiz Live chat - Set up your Subiz Account</span></h3>
					<div class="inside" style="padding: 0 10px;">
						<p style="text-align:center"><a target="_blank" href="http://subiz.com/?utm_source=plugin_wp&utm_medium=link&utm_campaign=plugin_wp1.0" title="Subiz Live chat - Live support Solution for Business websites"><img src="<?php echo($sfw_plugin_url) ?>subiz-logo.png" width="173" height="49" alt="Subiz Logo" /></a></p>
						<form  name="subiz_install_widget" id="subiz_install_widget" method="post" action="options.php">
							<?php settings_fields('subiz-live-chat-group'); ?>
							<p align="center">
								<strong><?php if(isset($subiz_verified_email_chk) && $subiz_verified_email_chk != ''){ echo '<font color="green">Subiz Live chat has installed successfully</font>';}else{ echo 'Enter subiz account email to install the widget';} ?></strong>
								</p>
							<p align="center">
							Email: <input type="text" name="subiz_verified_email" id="subiz_verified_email" value="<?php echo get_option('subiz_verified_email'); ?>" style="width:85%" />
							</p>
							<p class="submit" align="center">
								<input id="subiz_activate_bt" type="submit" class="button-primary" value="<?php if(isset($subiz_verified_email_chk) && $subiz_verified_email_chk != ''){ echo 'Save Changes';}else{ echo 'Install Subiz Widget';} ?>" />
							</p>
							<p align="center">Don’t have an account? <a href="javascript: void(0)" id="subizBtRegister" title="Register for free subiz account">Register for FREE</a></p>
						</form>
						<img style="margin-left: 35%; display: none;" id="subiz_loading_action" src="<?php echo $sfw_plugin_url.'loader.gif';?>">
						<p align="center"><small class="nonessential">Entering incorrect account will result in an error!</small></p>
					</div>
				</div>
				<div class="postbox" id="subizDisActivateBox" style="float:left;width:30em;margin-right:20px;display: none;">
					<h3 class="hndle"><span>Subiz Live chat - Live support Solution for Business</span></h3>
					<div class="inside" style="padding: 0 10px">
						<p style="text-align:center"><a target="_blank" href="http://subiz.com/?utm_source=plugin_wp&utm_medium=link&utm_campaign=plugin_wp1.0" title="Subiz Live chat - Live support Solution for Business websites"><img src="<?php echo($sfw_plugin_url) ?>subiz-logo.png" width="173" height="49" alt="Subiz Logo" /></a></p>
						<form name="subiz_register_widget" id="subiz_register_widget" method="post">
							<p align="center"><strong>Register for free subiz account</strong></p>
							<p>
							Email: (<font style="color: red;">*</font>)<input type="text" name="subiz_register_email" id="subiz_register_email" value="" style="width:100%" />
							</p>
							<p>
							Username: (<font style="color: red;">*</font>)<input type="text" name="subiz_register_name" id="subiz_register_name" value="" style="width:100%" />
							</p>
							<p class="submit" align="center">
								<input type="submit" class="button-primary" value="Register Account" />
							</p>
							<p align="center">You have an account?  <a href="javascript: void(0)" id="subizBtActivate" title="Activate your subiz account">Install subiz widget</a></p>
						</form>
						<img style="margin-left: 35%; display: none;" id="subiz_loading_register" src="<?php echo $sfw_plugin_url.'loader.gif';?>">
						<p align="center"><small class="nonessential">You must login in your email account to activate and complete register subiz account!</small></p>
					</div>
				</div>
			</div>
		</div>
			<?php }
		add_action('admin_init', 'sfw_settings' );
		add_submenu_page('options-general.php', 'Subiz Live Chat', 'Subiz Live Chat', 'manage_options', 'subiz-for-wp', 'sfw_settings_page');
	}
	?>
