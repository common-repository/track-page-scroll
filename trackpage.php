<?php
/*
Plugin Name: Track Page Scroll
Plugin URI: https://wordpress.org/plugins/track-page-scroll/
Description: This Plugin Will track events happened by viewer on your webpages.
Version: 1.0.2
Author: mndpsingh287
Author URI: https://profiles.wordpress.org/mndpsingh287/
License: GPLv2
Text Domain: trackpage
*/
if (!defined("track_page")) define("track_page", "track_page");
if(!class_exists('trackpage'))
{
class trackpage
	{
		const PLUGVER = '1.0.2';
		var $trackpage_tbl;
		var $trackpageoptions2;
		var $trackpageoptions;
		/*
		* Initialize Hooks
		*/
		public function __construct()
		{
			global $wpdb;
			 //default options
			    $this->trackpageoptions2 = array(
					"isPro"=> 'no'
				);
			$this->trackpage_tbl = $wpdb->prefix.'trackpage_tbl';
			register_activation_hook(__FILE__, array(&$this, 'trackpage_tbl_install'));
			add_action('init', array(&$this, 'trackpage_scripts'));
			add_action('wp_head', array(&$this,'trackpage_google_js'));
			add_action('admin_menu', array(&$this,'trackpage_custom_menu_page'));
			add_shortcode('trackpage', array(&$this,'trackpage_shrt_fxn'));
		}
		/*
		* Track Page Admin JS / CSS
		*/
		public function trackpage_scripts()
		{
		   $currentScreen = !empty($_GET['page']) ? $_GET['page'] : '';	
		   if(is_admin() && $currentScreen == 'trackpage'):
		    wp_enqueue_style( 'bootstrap-min', plugins_url('/css/bootstrap.min.css' , __FILE__ ));
			wp_enqueue_style( 'dataTables-bootstrap-min',  plugins_url('/css/dataTables.bootstrap.min.css', __FILE__ ) );
			wp_enqueue_script( 'jquery-dataTables-min', plugins_url('/js/jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ) );
		    wp_enqueue_script( 'dataTables-bootstrap-min', plugins_url('/js/dataTables.bootstrap.min.js', __FILE__ ), array( 'jquery' ) );	
		    wp_enqueue_script( 'trackpage', plugins_url('/js/trackpage.js', __FILE__), array( 'jquery' ) );		
		   endif;
		   if(is_admin()):
			wp_enqueue_style( 'trackpage', plugins_url('/css/trackpage.css', __FILE__));
		   endif;	
		}
		/*
		* Track Page DB Table Create
		*/
		public function trackpage_tbl_install()
		{
			include('inc/install.php');
			flush_rewrite_rules();
			$opt = get_option('track_page_scroll_options2');
			if(!$opt['isPro'] || !empty($opt['isPro'])) {
				update_option('track_page_scroll_options2', $this->trackpageoptions2);
			}           	
		}
		/*
		* JS in wp head
		*/
		public function trackpage_google_js()
		{
		  wp_enqueue_script( 'ga', 'http://google-analytics.com/ga.js', array(), '', true );
		}
		/*
		* Trackpage Menus
		*/
		public function trackpage_custom_menu_page()
		{
			//main menu
		   add_menu_page(
          __( 'Track Page Scroll', 'trackpage' ),
			'Track Page Scroll',
			'manage_options',
			'trackpage',
			array(&$this, 'trackpagemainpage'),
			'',
			6
           );
		   //sub menu
		   add_submenu_page(
			'trackpage',
			'Add New',
			'Add New',
			'manage_options',
			'addnewtrackpage',
			array(&$this, 'addnewtrackpagesubmenu')
		  );
		  //settings
		   add_submenu_page(
			'trackpage',
			'Settings',
			'Settings',
			'manage_options',
			'trackpage_settings',
			array(&$this, 'trackpage_settings')
		  );
		  //upgrade
		   add_submenu_page(
			'trackpage',
			'Upgrade To Pro',
			'Upgrade To Pro',
			'manage_options',
			'trackpage_upgrade_pro',
			array(&$this, 'trackpage_upgrade_pro')
		  );			
		}
		/*
		* Trackpage Dashboard
		*/
		public function trackpagemainpage()
		{
			require_once('inc/mainpage.php');
		}
		/*
		* Add new Page 
		*/
		public function addnewtrackpagesubmenu()
		{
			require_once('inc/addnew.php');
		}
		/*
		* Trackpage Settings
		*/
		public function trackpage_settings()
		{
			require_once('inc/settings.php');
		}
		/*
		* Upgrade to Pro
		*/
		public function trackpage_upgrade_pro()
		{
		   require_once('inc/upgrade_pro.php');	
		}
		/*
		* Shortcode
		*/	
		public function trackpage_shrt_fxn($atts)
		{
			require_once('inc/shortcode.php');
		}
		/*
		* Redirection Function
		*/	
		public function redirectme($url)
		{
			return '<script>window.location.href="'.$url.'"</script>';
		}
	}
	new trackpage;
}