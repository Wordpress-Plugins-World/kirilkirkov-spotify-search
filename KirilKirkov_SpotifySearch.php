<?php
/*
Plugin Name: Spotify Search
Plugin URI: https://github.com/kirilkirkov
Description: This plugin search in spotify for tracks, albums and artists.
Version: 1.0
Author: Kiril Kirkov
Author URI: https://github.com/kirilkirkov/
*/

if(!class_exists('SpotifyWebAPI\SpotifyWebApi')) {
	require rtrim(plugin_dir_path( __FILE__ ), '/') . '/Spotify-WebApi/SpotifyWebApi.php';
}

if(!class_exists('KirilKirkov_SpotifySearch')) {
	class KirilKirkov_SpotifySearch 
	{
		// singleton
		private static $instance;

		private $spotify_redirect_url = null;
		private $spotify_api_error = false;
		private $redirect_url = null;
		private $settings_url = null;
		private $has_public_permission = false;

		private function __construct()
		{
			$this->constants(); // Defines any constants used in the plugin
			$this->init(); // Sets up all the actions and filters
		}

		public static function getInstance()
		{
			if ( !self::$instance ) {
				self::$instance = new KirilKirkov_SpotifySearch();
			}

			return self::$instance;
		}

		private function constants()
		{
			define('KIRILKIRKOV_SPOTIFY_SEARCH_CACHE_GROUP', 'SpotifySearch');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_CACHE_TIME', 86400); // one day

			define('KIRILKIRKOV_SPOTIFY_SEARCH_TEXT_DOMAIN', 'Spotify Search');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_PLUGIN_SHORTCODE', 'spotify-search');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_SETTING_GET_PARAM', 'kirilkirkov-spotify-search-settings');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX', 'kirilkirkov_spotify_search_');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP', 'kirilkirkov-spotify-search-update-options');
			define('KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX', 'kirilkirkov_spotify_search_');
		}

		private function init()
		{
			// Register the options with the settings API
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Add the menu page
			add_action( 'admin_menu', array( $this, 'setup_admin' ) );

			// admin scripts
			add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));

			// plugin ajax
			add_action('wp_enqueue_scripts', array($this, 'load_public_assets'));
			add_action('wp_ajax_get_spotify_search_results', array($this, 'get_spotify_search_results') );
			add_action('wp_ajax_nopriv_get_spotify_search_results', array($this, 'get_spotify_search_results') );

			add_shortcode(KIRILKIRKOV_SPOTIFY_SEARCH_PLUGIN_SHORTCODE, array($this, 'load_public_form'));
		}

		/**
		 * Public form shortcode
		 */
		public function load_public_form()
		{
			// check has entered tokens before show the public search
			if(!$this->has_public_permission()) {
				return ''; // empty public string
			}
			
			ob_start();
			require 'Includes/Public/PublicSearchForm.php';
			return ob_get_clean();
		}

		public function load_admin_assets($hook)
		{
			$current_screen = get_current_screen();
			if (strpos($current_screen->base, KIRILKIRKOV_SPOTIFY_SEARCH_SETTING_GET_PARAM) === false) {
				return;
			}
			wp_enqueue_style(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'boot_core_css', plugins_url('Includes/Admin/core.css', __FILE__ ));
			wp_enqueue_style(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'boot_admin_css', plugins_url('Includes/Admin/admin.css', __FILE__ ));
			wp_enqueue_script(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'boot_admin_js', plugins_url('Includes/Admin/admin.js', __FILE__ ), array(), false, true);
		}

		/**
		 * Public Search Form.
		 * Return response from Spotify
		 */
		public function get_spotify_search_results()
		{
			if(!class_exists('KirilKirkov_SpotifyXhr')) {
				require 'Includes/Public/KirilKirkov_SpotifyXhr.php';
			}
			
			try {

				$xhr = new KirilKirkov_SpotifyXhr();
				echo json_encode(['result' => $xhr->getResults($_POST)]);
				exit;

			} catch(\Exception $e) {
				echo json_encode(['error' => $e->getMessage()]);
				exit;
			} catch (\SpotifyWebAPI\SpotifyWebAPIException $e) {
				echo json_encode(['error' => $e->getMessage()]);
				exit;
			}
		}

		public function load_public_assets()
		{
			// load js
			wp_enqueue_script(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'script_public_js', plugins_url( '/Includes/Public/spotify_search.js', __FILE__ ), array('jquery'), false, true);
			// Pass ajax_url to scripts
			wp_localize_script(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'script_public_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ));
			
			// load styles if they are not exluded from the settings
			if(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_default_styles') === false || trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_default_styles')) === '' || get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_default_styles') === '1') {
				wp_enqueue_style(KIRILKIRKOV_SPOTIFY_SEARCH_SCRIPTS_PREFIX.'public_css', plugin_dir_url( __FILE__ ) . 'Includes/Public/spotify_search.css');
			}
		}

		/**
		 * Headers and content are not send yet in admin_init func.
		 * Good way to handle events and make redirects
		 */
		public function admin_init()
		{
			if (!is_admin()) {
				wp_die( __('This code is for admin area only', KIRILKIRKOV_SPOTIFY_SEARCH_TEXT_DOMAIN) );
			}

			$this->spotify_redirect_url = null;
			$this->spotify_api_error = false;
			$this->settings_url = $this->get_setting_url();
			$this->has_public_permission = $this->has_public_permission();

			// if user has entered client and secret, lets show him the button for obtain the access and refresh tokens from spotify.
			if($this->has_client_and_secret()) {

				$redirect_url = $this->settings_url;
				// show button to get code for tokens
				if(!isset($_GET['code'])) {
					// not throwable
					$s = new SpotifyWebAPI\SpotifyWebApi();
					$this->spotify_redirect_url = $s->getUrlForCodeToken($redirect_url, get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_id'));
				} else {
					try {
						// if user is returned from spotify with the code, lets get the real tokens and save them to database
						$s = new SpotifyWebAPI\SpotifyWebApi([
							'clientId' => get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_id'),
							'clientSecret' => get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_secret'),
						]);
						// Remove specific parameter from query string
						$redirect_url = $this->strip_param_from_url($redirect_url, 'code');
						$tokens = $s->getAccessTokenWithCode($_GET['code'], $redirect_url);
						if(is_object($tokens)) {
							update_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_token', $tokens->access_token);
							update_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_refresh_token', $tokens->refresh_token);
						}
						wp_redirect($redirect_url);
						exit;
					} catch(\Exception $e) {
						$this->spotify_api_error = $e->getMessage();
					} catch(\SpotifyWebAPI\SpotifyWebAPIException $e) {
						$this->spotify_api_error = $e->getMessage();
					}
				}
			}

			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_id' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_secret' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_search_type' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_limit' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_default_styles' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_styles' );
			register_setting(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_GROUP, KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_absolute_results' );
		}

		public function setup_admin()
		{
			add_options_page( __( 'Spotify Search Plugin', KIRILKIRKOV_SPOTIFY_SEARCH_TEXT_DOMAIN ), __( 'Spotify Search', KIRILKIRKOV_SPOTIFY_SEARCH_TEXT_DOMAIN ), 'administrator', KIRILKIRKOV_SPOTIFY_SEARCH_SETTING_GET_PARAM, array( $this, 'admin_page' ) );
		}

		public function admin_page()
		{
			require 'Includes/Admin/SettingsForm.php';
		}

		private function get_setting_url()
		{
			return admin_url("options-general.php?page=" . KIRILKIRKOV_SPOTIFY_SEARCH_SETTING_GET_PARAM);
		}

		/**
		 * Has all settings to show public search and extended admin settings
		 */
		private function has_public_permission()
		{
			if(	get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_refresh_token') 
				&& trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_refresh_token')) != ''
				&& $this->has_client_and_secret()) {
				return true;
			}
			return false;
		}

		/**
		 * Has entered client and secred to show Get Token button
		 */
		private function has_client_and_secret()
		{
			if(	get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_id') 
				&& trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_id')) != '' 
				&& get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_secret') 
				&& trim(get_option(KIRILKIRKOV_SPOTIFY_SEARCH_INPUTS_PREFIX.'spotify_search_client_secret')) != '') {
				return true;
			}
			return false;
		}
		
		// Just helper
		private function strip_param_from_url($url, $param)
		{
			$base_url = strtok($url, '?');              // Get the base url
			$parsed_url = parse_url($url);              // Parse it 
			$query = $parsed_url['query'];              // Get the query string
			parse_str( $query, $parameters );           // Convert Parameters into array
			unset($parameters[$param]);               // Delete the one you want
			$new_query = http_build_query($parameters); // Rebuilt query string
			return $base_url.'?'.$new_query;            // Finally url is ready
		}
	}

	$spotify_search = KirilKirkov_SpotifySearch::getInstance();
}