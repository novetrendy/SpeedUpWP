<?php
/*
*Plugin Name: Speed Up WP !
*Plugin URI: http://webstudionovetrendy.eu/
*Description: This plugin make your Wordpress instalation much faster! Disable some WP featured for unlock more speed admin backend and also frontend. Use some techniques like JavaScript defer loading, remove some query string, remove not used widgets etc.
*Version: 161126
* Text Domain: nt-speed-up-wp
* Domain Path: /languages/
*Author: Webstudio Nove Trendy
*Author URI: http://webstudionovetrendy.eu/
License: License:     GPL2
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
        echo  '<br /><a href="' . admin_url(). 'admin.php?page=speed-up-wp">' . __('Speed Up WP', 'nt-speed-up-wp') . '</a><br /><p>'. __( 'This plugin make your Wordpress instalation much faster! Disable some WP featured for unlock more speed admin backend and also frontend. Use some techniques like JavaScript defer loading, remove some query string, remove not used widgets etc.', 'speed-up-wp' ) .'</p><br /><hr />';
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
        if ( $sup_options['dashboard_primary'] == 1) {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );}

        if ( $sup_options['dashboard_activity'] == 1) {
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );}

        if ( $sup_options['dashboard_quick_press'] == 1) {
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );}

        if ( $sup_options['dashboard_right_now'] == 1) {
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'side' );}
};


/* Move jQuery to the footer */
if ( $sup_options['jquery_to_footer'] == 1) {
add_filter( 'admin_enqueue_scripts', function( $hook ) {$GLOBALS['wp_scripts']->add_data( 'jquery', 'group', 1 );});
}

/* remove query string ver?xxx from static resources */
if ( $sup_options['remove_query_string_ver'] == 1) {
add_filter( 'script_loader_src', 'qsr_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'qsr_remove_script_version', 15, 1 );
function qsr_remove_script_version( $src ){
    $parts = explode( '?ver', $src );
        return $parts[0];
}
}
/* remove metatag generator slider revolution */
if ( $sup_options['revslider_meta_tag'] == 1) {
add_filter( 'revslider_meta_generator', 'remove_revslider_meta_tag' );
function remove_revslider_meta_tag() {return '';}
}

if ( $sup_options['deregister_cf7'] == 1) {
/* Deregister Contact Form 7 styles and scripts */
add_action( 'wp_print_styles', 'nt_deregister_cf7', 100 );
add_action( 'wp_print_scripts', 'nt_deregister_cf7', 100 );
function nt_deregister_cf7() {
    $cf7_page = get_option('nt_speed_up_wp')['deregister_cf7_pages'];
    $cf7_pages = explode("," , $cf7_page);
    if ( !is_page($cf7_pages) ) {
        wp_deregister_style( 'contact-form-7' );
        wp_deregister_script( 'contact-form-7' );
    }
}
}
/* Deregister scripts */
if (!empty( $sup_options['deregister_cf7'])) {
add_action( 'wp_print_scripts', 'nt_deregister_scripts', 100 );
function nt_deregister_scripts() {
    $scripts = get_option('nt_speed_up_wp')['deregister_scripts'];
    $nt_scripts = explode("," , $scripts);
        wp_deregister_script($nt_scripts);
}
}
/* Optimize_heartbeat */
if ( $sup_options['optimize_heartbeat'] == 1) {
add_action( 'init', 'disable_heartbeat_unless_post_edit_screen', 1 );
add_filter( 'heartbeat_settings', 'optimize_heartbeat_settings' );
function optimize_heartbeat_settings( $settings ) {$settings['autostart'] = false;$settings['interval'] = 60;return $settings;}
function disable_heartbeat_unless_post_edit_screen() {global $pagenow;if ( $pagenow != 'post.php' && $pagenow != 'post-new.php' )wp_deregister_script('heartbeat');}
}

/* Disable oembed - since WP 4.4 */
if ( $sup_options['disable_oembed'] == 1) {
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
if ( $sup_options['disable_emojis'] == 1) {
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
if ( $sup_options['disable_auto_updates'] == 1) {
/**
 * Disable Update Filters
 */
foreach([
    'auto_update_plugin'                => '__return_zero',
    'allow_dev_auto_core_updates'       => '__return_zero',
    'allow_major_auto_core_updates'     => '__return_zero',
    'pre_transient_update_plugins'      => '__return_zero',
    'pre_site_transient_update_plugins' => '__return_zero',
] as $filter => $callback) add_filter($filter, $callback);
/**
 * Remove Update Actions
 */
foreach([
    'admin_init'            => '_maybe_update_plugins',
    'load-plugins.php'      => 'wp_update_plugins',
    'load-update.php'       => 'wp_update_plugins',
    'wp_update_plugins'     => 'wp_update_plugins',
    'load-update-core.php'  => 'wp_update_plugins',
    'init'  => 'cloudfw_module_register_portfolio',
] as $action => $callback) remove_action($action, $callback);

remove_action('admin_init', '_maybe_update_core');
remove_action('admin_init', '_maybe_update_themes');
remove_action('admin_menu', 'aioseop_welcome::add_menus');
remove_action('init', 'RightPress_Updates::on_wp_init');
remove_action('init', '_mw_adminimize_remove_admin_bar');
}

/* Settings JPEG compression for new images */
if ( $sup_options['jpeg_compression'] == 1) {
function nt_jpeg_quality( $quality, $context ) {
    $jpeg_quality = get_option('nt_speed_up_wp')['jpeg_compression'];
	return $jpeg_quality;
}
add_filter( 'jpeg_quality', 'nt_jpeg_quality', 10, 2 );
}
/* Disable XMLRPC */
if ( $sup_options['disable_xmlrpc'] == 1) {
add_filter('xmlrpc_enabled', '__return_false');
}
/* Disable JSON REST API */
if ( $sup_options['disable_json'] == 1) {
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');
}
/* Remove default WP widget */
function remove_wp_widget() {
    global $sup_options;
    // Wordpress Widgets
    if ( $sup_options['calendar'] == 1) {
    unregister_widget('WP_Widget_Calendar');    }
    if ($sup_options['archives'] == 1) {
    unregister_widget('WP_Widget_Archives');    }
    if ( $sup_options['links'] == 1) {
    unregister_widget('WP_Widget_Links');    }
    if ( $sup_options['meta'] == 1) {
    unregister_widget('WP_Widget_Meta');    }
    if ( $sup_options['pages'] == 1) {
    unregister_widget('WP_Widget_Pages');   }
    if ( $sup_options['categories'] == 1) {
    unregister_widget('WP_Widget_Categories');  }
    if ( $sup_options['recent_posts'] == 1) {
    unregister_widget('WP_Widget_Recent_Posts'); }
    if ( $sup_options['recent_comments'] == 1) {
    unregister_widget('WP_Widget_Recent_Comments'); }
    if ( $sup_options['rss'] == 1) {
    unregister_widget('WP_Widget_RSS');  }
    if ( $sup_options['tag_cloud'] == 1) {
    unregister_widget('WP_Widget_Tag_Cloud'); }
    if ( $sup_options['menu'] == 1) {
    unregister_widget('WP_Nav_Menu_Widget'); }
    if ( isset ($sup_options['search']) && $sup_options['search'] == 1) {
  unregister_widget('WP_Widget_Search');}
    if ( !empty($sup_options['text']) == 1) {
  unregister_widget('WP_Widget_Text');}
  // CloudFW Widgets
  if ( $sup_options['blog_list'] == 1) {
  unregister_widget( 'CloudFw_Widget_Blog_List' ); }
  if ( $sup_options['carousel'] == 1) {
  unregister_widget('CloudFw_Widget_Carousel'); }
  if ( $sup_options['get_content'] == 1) {
  unregister_widget('CloudFw_Widget_Get_Content'); }
  if ( $sup_options['socialbar'] == 1) {
  unregister_widget('CloudFw_Widget_Socialbar'); }
  if ( $sup_options['twitter'] == 1) {
  unregister_widget('CloudFw_Widget_Twitter'); }
  if ( $sup_options['mailchimp'] == 1) {
  unregister_widget('CloudFw_MailChimp'); }
  if ( $sup_options['subpost'] == 1) {
  unregister_widget('CloudFw_Widget_Subpost');}
  // Other Widgets
  if ( $sup_options['revslider'] == 1) {
  unregister_widget('RevSliderWidget');}
}
add_action( 'widgets_init', 'remove_wp_widget');




// Woocommerce Widgets
add_action( 'widgets_init', 'remove_wc_widget',50 );
function remove_wc_widget() {
    global $wsup_options;
    if ( $wsup_options['products_cats'] == 1) {
    unregister_widget( 'WC_Widget_Product_Categories' ); }
    if ( $wsup_options['products'] == 1) {
    unregister_widget( 'WC_Widget_Products' ); }
    if ( $wsup_options['product_tag_clouds'] == 1) {
    unregister_widget( 'WC_Widget_Product_Tag_Cloud' );  }
    if ( $wsup_options['cart'] == 1) {
    unregister_widget( 'WC_Widget_Cart' );  }
    if ( $wsup_options['layered_nav'] == 1) {
    unregister_widget( 'WC_Widget_Layered_Nav' ); }
    if ( $wsup_options['layered_nav_filters'] == 1) {
    unregister_widget( 'WC_Widget_Layered_Nav_Filters' ); }
    if ( $wsup_options['price_filter'] == 1) {
    unregister_widget( 'WC_Widget_Price_Filter' ); }
    if ( $wsup_options['product_search'] == 1) {
    unregister_widget( 'WC_Widget_Product_Search' ); }
    if ( $wsup_options['top_rated'] == 1) {
    unregister_widget( 'WC_Widget_Top_Rated_Products' ); }
    if ( $wsup_options['recent_reviews'] == 1) {
    unregister_widget( 'WC_Widget_Recent_Reviews' ); }  
    if ( $wsup_options['recently_viewed'] == 1) {
    unregister_widget( 'WC_Widget_Recently_Viewed' ); }
    if ( $wsup_options['brand_description'] == 1) {
    unregister_widget('WC_Widget_Brand_Description');}
    if ( $wsup_options['brand_nav'] == 1) {
    unregister_widget('WC_Widget_Brand_Nav');}
    if ( $wsup_options['brand_thumb'] == 1) {
    unregister_widget('WC_Widget_Brand_Thumbnails');}
}

/* zakáže načítání z wistia */
add_filter( 'woocommerce_enable_admin_help_tab', '__return_false');
/** woo dashboard */
add_action( 'wp_dashboard_setup', 'nt_remove_dashboard_woocommerce_meta_box');
function nt_remove_dashboard_woocommerce_meta_box() {
/*remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );*/
remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
};
add_action( 'wp_dashboard_setup', 'remove_welcome_panel' );
function remove_welcome_panel() {global $wp_filter;unset( $wp_filter['welcome_panel'] );}


//remove_action('init', 'cloudfw_module_register_portfolio', 0);
