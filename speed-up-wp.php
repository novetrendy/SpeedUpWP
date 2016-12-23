<?php
/*
* Plugin Name: Speed Up WP !
* Plugin URI: http://webstudionovetrendy.eu/
* Description: This plugin make your Wordpress instalation much faster! Disable some WP featured for unlock more speed admin backend and also frontend. Use some techniques like JavaScript defer loading, remove some query string, remove not used widgets etc.
* Version: 161223
* Text Domain: nt-speed-up-wp
* Domain Path: /languages/
* Author: Webstudio Nove Trendy
* Author URI: http://webstudionovetrendy.eu/
* GitHub Plugin URI: https://github.com/novetrendy/SpeedUpWP
* License: GPL2
*** Changelog ***
2016.12.23 - version 161223
* Add remove metabox Semper plugins RSS (All In One Seo Pack) from dashboard
2016.12.21 - version 161221
* Completely rewriting admin UI to new FLAT UI
* Add disable load from wistia (WooCommerce)
* Add disable WooCommerce Dashboard Status
* Add disable WooCommerce Recent Review Dashboard widget
* Add remove post type Portfolio for Envision theme
* Add admin interface for completely remove WordPress comments
* Update PO/MO Czech language
2016.12.15 - version 161215
* Small admin CSS changes
2016.12.06 - version 161206
* Completely remove WordPress comments - without admin interface (on/off)
2016.11.28 - version 161128
* Completely rewriting plugin - E_NOTICE - all variables are now being tested with isset()
2016.11.27 - version 161127
* Drobné opravy kvůli kompatibilitě s různými hostingy
* Přidání tlačítka uložit změny i do vrchní části stránky
* Přepsání kódu pro automatické aktualizace
* Oprava chyby v navigaci na stránku s nastavením
* Přidána podpora pro aktualizaci s GitHubu - nutno mít nainstalovaný GitHub Updater
*/
if ( ! defined( 'ABSPATH' ) )   {
    exit; // Exit if accessed directly
    }
/**
 * Backend CSS
*/
add_action('admin_enqueue_scripts', 'nt_admin_speed_up_wp_style');
add_action('login_enqueue_scripts', 'nt_admin_speed_up_wp_style');
    function nt_admin_speed_up_wp_style()   {
        wp_enqueue_style('speed_up_wp_admin', plugins_url('assets/css/speed-up-wp-admin.css', __FILE__));
    }
/**
 * Localization
 */
 add_action('plugins_loaded', 'nt_speed_up_wp_plugin_localization_init');
    function nt_speed_up_wp_plugin_localization_init()  {
        load_plugin_textdomain( 'nt-speed-up-wp', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
    }
/**
 * Add page to menu
 */
add_action( 'admin_menu', 'nt_speed_up_wp_admin_menu' );
    function nt_speed_up_wp_admin_menu() {
        if ( empty ( $GLOBALS['admin_page_hooks']['nt-admin-page'] ) )  {
            add_menu_page( __('New Trends','nt-speed-up-wp'), __('New Trends','nt-speed-up-wp'), 'manage_options', 'nt-admin-page', 'nt_speed_up_wp_page', 'dashicons-admin-tools', 3  );
        }
    }
    function nt_speed_up_wp_page() {
        echo '<h1>' . __( 'Mainpage for setting plugins from New Trends', 'nt-speed-up-wp' ) . '</h1>';
        echo '<a target="_blank" title="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" href="http://webstudionovetrendy.eu"><img alt="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" title="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" class="ntlogo" src=" '. plugin_dir_url( __FILE__ ) .'admin/images/logo.png" /><br /></a><hr />';
            do_action('nt_after_main_content_admin_page_loop_action');
     }
add_action('nt_after_main_content_admin_page_loop_action', 'speed_up_wp_print_details');
    function speed_up_wp_print_details()    {
        echo  '<br /><a href="' . admin_url(). 'admin.php?page=speed_up_wp">' . __('Speed Up WP', 'nt-speed-up-wp') . '</a><br /><p>'. __( 'This plugin make your Wordpress instalation much faster! Disable some WP featured for unlock more speed admin backend and also frontend. Use some techniques like JavaScript defer loading, remove some query string, remove not used widgets etc.', 'speed-up-wp' ) .'</p><br /><hr />';
    }
/**
 * Load class
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/class-speed-up-wp-admin.php';

if( is_admin() )
    $speed_up_wp_settings_page = new Speed_Up_WP_Page();

/*****************************************************************/

$sup_options = get_option('nt_speed_up_wp');
$wsup_options = get_option('nt_woocommerce_speed_up_wp');
global $sup_options,$wsup_options;

/* OPTIMIZE LOAD SPEED */

/* Remove Dashboard Widgets */
add_action( 'wp_dashboard_setup', 'nt_remove_dashboard_wordpress_meta_box');
    function nt_remove_dashboard_wordpress_meta_box()   {
        global $sup_options;
        if (isset($sup_options['dashboard_primary']) == 1) {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );}

        if ( isset ($sup_options['dashboard_activity']) == 1) {
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );}

        if ( isset ($sup_options['dashboard_quick_press']) == 1) {
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );}

        if ( isset ($sup_options['dashboard_right_now']) == 1) {
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'side' );}
	    
	if ( isset ($sup_options['semperplugins_rss_feed']) == 1) {
        remove_meta_box('semperplugins-rss-feed', 'dashboard', 'normal');}
};
    if (isset($sup_options['welcome_panel']) == 1) {
    add_action( 'wp_dashboard_setup', 'remove_welcome_panel' );
    function remove_welcome_panel() {global $wp_filter;unset( $wp_filter['welcome_panel'] );}
}

/* Move jQuery to the footer */
if ( isset ($sup_options['jquery_to_footer']) == 1) {
add_filter( 'admin_enqueue_scripts', function( $hook ) {$GLOBALS['wp_scripts']->add_data( 'jquery', 'group', 1 );});
}

/* remove query string ver?xxx from static resources */
if ( isset($sup_options['remove_query_string_ver']) == 1) {
add_filter( 'script_loader_src', 'qsr_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'qsr_remove_script_version', 15, 1 );
function qsr_remove_script_version( $src ){
    $parts = explode( '?ver', $src );
        return $parts[0];
}
}
/* remove metatag generator slider revolution */
if ( isset($sup_options['revslider_meta_tag']) == 1) {
add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' );
function remove_revslider_meta_tag() {return '';}
}

if ( (isset( $sup_options['deregister_cf7'])) == 1) {
/* Deregister Contact Form 7 styles and scripts */
add_action( 'wp_print_styles', 'nt_deregister_cf7', 100 );
add_action( 'wp_print_scripts', 'nt_deregister_cf7', 100 );
function nt_deregister_cf7() {
    global $sup_options;
    $cf7_page = $sup_options['deregister_cf7_pages'];
    $cf7_pages = explode("," , $cf7_page);
    if ( !is_page($cf7_pages) ) {
        wp_deregister_style( 'contact-form-7' );
        wp_deregister_script( 'contact-form-7' );
    }
}
}
/* Deregister scripts */
if (isset( $sup_options['deregister_cf7'])) {
add_action( 'wp_print_scripts', 'nt_deregister_scripts', 100 );
function nt_deregister_scripts() {
    global $sup_options;
    $scripts = $sup_options['deregister_scripts'];
    $nt_scripts = explode("," , $scripts);
        wp_deregister_script($nt_scripts);
}
}
/* Optimize_heartbeat */
if ( isset($sup_options['optimize_heartbeat']) == 1) {
add_action( 'init', 'disable_heartbeat_unless_post_edit_screen', 1 );
add_filter( 'heartbeat_settings', 'optimize_heartbeat_settings' );
function optimize_heartbeat_settings( $settings ) {$settings['autostart'] = false;$settings['interval'] = 60;return $settings;}
function disable_heartbeat_unless_post_edit_screen() {global $pagenow;if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )wp_deregister_script('heartbeat');}
}

/* Remove comments */
// Disable support for comments and trackbacks in post types
if ( isset($sup_options['remove_comments']) == 1) {
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');
}

/* Disable oembed - since WP 4.4 */
if ( isset ($sup_options['disable_oembed']) == 1) {
function disable_embeds_init() {
	global $wp;	$wp->public_query_vars = array_diff( $wp->public_query_vars, array('embed',	) );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'init', 'disable_embeds_init', 9999 );
function disable_embeds_tiny_mce_plugin( $plugins ) {return array_diff( $plugins, array( 'wpembed' ) );}
function disable_embeds_rewrites( $rules ) {foreach ( $rules as $rule => $rewrite ) {if ( false !== strpos( $rewrite, 'embed=true' ) ) {unset( $rules[ $rule ]);}}return $rules;}
function disable_embeds_remove_rewrite_rules() {add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );flush_rewrite_rules();}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );
}



/* Disable Emojis */
if ( isset($sup_options['disable_emojis']) == 1) {
add_action( 'init', 'disable_emojis' );
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
}

/* Disable Auto check updates */
if ( isset ($sup_options['disable_auto_updates']) == 1) {
/**
 * Disable Update Filters
 */
add_filter( 'auto_update_plugin', '__return_zero' );
add_filter( 'allow_dev_auto_core_updates', '__return_zero' );
add_filter( 'allow_major_auto_core_updates', '__return_zero' );
add_filter( 'pre_transient_update_plugins', '__return_zero' );
add_filter( 'pre_site_transient_update_plugins', '__return_zero' );
/**
 * Remove Update Actions
 */
remove_action('admin_init', '_maybe_update_plugins');
remove_action('load-plugins.php', 'wp_update_plugins');
remove_action('load-update.php', 'wp_update_plugins');
remove_action('wp_update_plugins', 'wp_update_plugins');
remove_action('load-update-core.php', 'wp_update_plugins');
remove_action('init', 'cloudfw_module_register_portfolio');
remove_action('admin_init', '_maybe_update_core');
remove_action('admin_init', '_maybe_update_themes');
remove_action('init', 'RightPress_Updates::on_wp_init');
remove_action('init', '_mw_adminimize_remove_admin_bar');
}

/* Settings JPEG compression for new images */
if ( isset ($sup_options['jpeg_compression']) == 1) {
function nt_jpeg_quality( $quality, $context ) {
    $jpeg_quality = get_option('nt_speed_up_wp')['jpeg_compression'];
	return $jpeg_quality;
}
add_filter( 'jpeg_quality', 'nt_jpeg_quality', 10, 2 );
}
/* Disable XMLRPC */
if ( isset ($sup_options['disable_xmlrpc']) == 1) {
add_filter('xmlrpc_enabled', '__return_false');
}
/* Disable JSON REST API */
if ( isset ($sup_options['disable_json']) == 1) {
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');
}
/* Remove default WP widget */
function remove_wp_widget() {
    global $sup_options;
    // Wordpress Widgets
    if ( isset($sup_options['calendar']) == 1) {unregister_widget('WP_Widget_Calendar');}
    if ( isset($sup_options['archives']) == 1) {unregister_widget('WP_Widget_Archives');}
    if ( isset($sup_options['links']) == 1) {unregister_widget('WP_Widget_Links');}
    if ( isset($sup_options['meta']) == 1) {unregister_widget('WP_Widget_Meta');}
    if ( isset($sup_options['pages']) == 1) {unregister_widget('WP_Widget_Pages');}
    if ( isset($sup_options['categories']) == 1) {unregister_widget('WP_Widget_Categories');}
    if ( isset($sup_options['recent_posts']) == 1) {unregister_widget('WP_Widget_Recent_Posts');}
    if ( isset($sup_options['recent_comments']) == 1) {unregister_widget('WP_Widget_Recent_Comments');}
    if ( isset($sup_options['rss']) == 1) {unregister_widget('WP_Widget_RSS');}
    if ( isset($sup_options['tag_cloud']) == 1) {unregister_widget('WP_Widget_Tag_Cloud');}
    if ( isset($sup_options['menu']) == 1) {unregister_widget('WP_Nav_Menu_Widget');}
    if ( isset($sup_options['search']) == 1) {unregister_widget('WP_Widget_Search');}
    if ( isset($sup_options['text']) == 1) {unregister_widget('WP_Widget_Text');}
  // CloudFW Widgets
  if ( isset ($sup_options['blog_list']) == 1) {unregister_widget( 'CloudFw_Widget_Blog_List' );}
  if ( isset ($sup_options['carousel']) == 1) {unregister_widget('CloudFw_Widget_Carousel');}
  if ( isset ($sup_options['get_content']) == 1) {unregister_widget('CloudFw_Widget_Get_Content');}
  if ( isset ($sup_options['socialbar']) == 1) {unregister_widget('CloudFw_Widget_Socialbar');}
  if ( isset ($sup_options['twitter']) == 1) {unregister_widget('CloudFw_Widget_Twitter');}
  if ( isset ($sup_options['mailchimp']) == 1) {unregister_widget('CloudFw_MailChimp');}
  if ( isset ($sup_options['subpost']) == 1) {unregister_widget('CloudFw_Widget_Subpost');}
  // Other Widgets
  if ( isset ($sup_options['revslider']) == 1) {unregister_widget('RevSliderWidget');}
}
add_action( 'widgets_init', 'remove_wp_widget');




// Woocommerce Widgets
add_action( 'widgets_init', 'remove_wc_widget',50 );
function remove_wc_widget() {
    global $wsup_options;
    if ( isset ($wsup_options['products_cats']) == 1) {unregister_widget( 'WC_Widget_Product_Categories' ); }
    if ( isset ($wsup_options['products']) == 1) {unregister_widget( 'WC_Widget_Products' ); }
    if ( isset ($wsup_options['product_tag_clouds']) == 1) {unregister_widget( 'WC_Widget_Product_Tag_Cloud' );  }
    if ( isset ($wsup_options['cart']) == 1) {unregister_widget( 'WC_Widget_Cart' );  }
    if ( isset ($wsup_options['layered_nav']) == 1) {unregister_widget( 'WC_Widget_Layered_Nav' ); }
    if ( isset ($wsup_options['layered_nav_filters']) == 1) {unregister_widget( 'WC_Widget_Layered_Nav_Filters' ); }
    if ( isset ($wsup_options['price_filter']) == 1) {unregister_widget( 'WC_Widget_Price_Filter' ); }
    if ( isset ($wsup_options['product_search']) == 1) {unregister_widget( 'WC_Widget_Product_Search' ); }
    if ( isset ($wsup_options['top_rated']) == 1) {unregister_widget( 'WC_Widget_Top_Rated_Products' ); }
    if ( isset ($wsup_options['recent_reviews']) == 1) {unregister_widget( 'WC_Widget_Recent_Reviews' ); }
    if ( isset ($wsup_options['recently_viewed']) == 1) {unregister_widget( 'WC_Widget_Recently_Viewed' ); }
    if ( isset ($wsup_options['brand_description']) == 1) {unregister_widget('WC_Widget_Brand_Description');}
    if ( isset ($wsup_options['brand_nav']) == 1) {unregister_widget('WC_Widget_Brand_Nav');}
    if ( isset ($wsup_options['brand_thumb']) == 1) {unregister_widget('WC_Widget_Brand_Thumbnails');}
}

/* zakáže načítání z wistia */
    if ( isset ($wsup_options['wistia']) == 1){add_filter( 'woocommerce_enable_admin_help_tab', '__return_false');}

    if ( isset ($sup_options['portfolio']) == 1){
    add_action('init','delete_portfolio');
    remove_action('init', 'cloudfw_module_register_portfolio', 0);
    function delete_portfolio(){unregister_post_type( 'portfolio' );}
    }

/** woo dashboard */
    if ( isset ($wsup_options['dashboard_woocommerce']) == 1){
    add_action( 'wp_dashboard_setup', 'nt_remove_dashboard_woocommerce_meta_box');
    function nt_remove_dashboard_woocommerce_meta_box() {
    remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
    };
}

    if ( isset ($wsup_options['wc_recent_review']) == 1){
    add_action( 'wp_dashboard_setup', 'nt_remove_wc_recent_review');
    function nt_remove_wc_recent_review() {
    remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
    };
}
