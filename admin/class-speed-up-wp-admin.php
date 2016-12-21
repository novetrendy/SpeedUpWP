<?php
class Speed_Up_WP_Page
{
    /** Holds the values to be used in the fields callbacks */
    private $options;
    /** Construct  */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_speed_up_wp_page' ) );
        add_action( 'admin_init', array( $this, 'speed_up_wp_page_init' ) );
    }
    /**
     * Add settings page
     */
    public function add_speed_up_wp_page()
    {
        // Add submenu page will be under "New Trends" page
        add_submenu_page(
            'nt-admin-page',
             __('Settings Speed Up WP','nt-speed-up-wp'),
            __('Speed Up WP !','nt-speed-up-wp'),
            'manage_options',
            'speed_up_wp',
            array( $this, 'nt_create_admin_speed_up_wp_page', )
        );

    }

    /**
     * Options page callback
     */
    public function nt_create_admin_speed_up_wp_page()
    {
        // Set class property
        $this->options = get_option( 'nt_speed_up_wp' );
        $this->options_wc = get_option( 'nt_woocommerce_speed_up_wp' );
        ?>

<?php settings_errors(); ?>

        <div class="wrap speedup">
            <?php echo '<a class="ntlogo" target="_blank" title="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" href="http://webstudionovetrendy.eu"><img alt="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" title="' .  __('WebStudio New Trends','nt-speed-up-wp') . '" src=" '. plugin_dir_url( __FILE__ ) .'images/logo.png" /><br /></a><hr />';?>
            <h1><?php echo '<span class="speed-h1">' . __('Speed Up WordPress !', 'nt-speed-up-wp').'</span>'. __('| Plugin settings', 'nt-speed-up-wp')?></h1>
            <?php
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wordpress_options';
        ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=speed_up_wp&tab=wordpress_options" class="nav-tab <?php echo $active_tab == 'wordpress_options' ? 'nav-tab-active' : ''; ?>">Wordpress options</a> <?php
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
            <a href="?page=speed_up_wp&tab=woocommerce_options" class="nav-tab <?php echo $active_tab == 'woocommerce_options' ? 'nav-tab-active' : ''; ?>">Woocommerce options</a>
        <?php } ?>
        </h2>
            <form method="post" action="options.php">
            <?php
            submit_button();
            if( $active_tab == 'wordpress_options' ) {
                settings_fields( 'nt_speed_up_wp_option_group' );
                do_settings_sections( 'nt_speed_up_wp' );
            } else if( $active_tab == 'woocommerce_options' ) {
                settings_fields( 'nt_woocommerce_speed_up_wp_option_group' );
                do_settings_sections( 'nt_woocommerce_speed_up_wp' );
            }
            submit_button(); ?>

            </form>
        </div>

        <?php  }

    /**
     * Register and add settings
     */
    public function speed_up_wp_page_init()
    {
        register_setting(
            'nt_speed_up_wp_option_group', // Group settings
            'nt_speed_up_wp', // Name of setting
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_nt_speed_up_wp', // ID
             __('Settings Dasboard Metabox','nt-speed-up-wp'), // Title
            array( $this, 'print_section_info' ), // Callback
            'nt_speed_up_wp' // Page

        );

        // Add fields
        add_settings_field(
            'dashboard_primary', // dashboard_primary WordPress.com Blog
            __('Remove Dashboard Meta Box WordPress.com Blog ?','nt-speed-up-wp'),
            array( $this, 'dashboard_primary_callback'),
            'nt_speed_up_wp',
            'setting_section_nt_speed_up_wp',
            array( 'class' => 'metaboxes' )
        );
        add_settings_field(
            'dashboard_activity', // dashboard_activity Activity
            __('Remove Dashboard Meta Box Activity ?','nt-speed-up-wp'),
            array( $this, 'dashboard_activity_callback'),
            'nt_speed_up_wp',
            'setting_section_nt_speed_up_wp',
            array( 'class' => 'metaboxes' )
        );
        add_settings_field(
            'dashboard_quick_press', // dashboard_quick_press Quick Press
            __('Remove Dashboard Meta Box Quick Press ?','nt-speed-up-wp'),
            array( $this, 'dashboard_quick_press_callback'),
            'nt_speed_up_wp',
            'setting_section_nt_speed_up_wp',
            array( 'class' => 'metaboxes' )
        );
        add_settings_field(
            'dashboard_right_now', // dashboard_right_now Right Now
            __('Remove Dashboard Meta Box Right Now ?','nt-speed-up-wp'),
            array( $this, 'dashboard_right_now_callback'),
            'nt_speed_up_wp',
            'setting_section_nt_speed_up_wp',
            array( 'class' => 'metaboxes' )
        );
        add_settings_field(
            'welcome_panel', // dashboard_right_now Right Now
            __('Remove Welcome panel ?','nt-speed-up-wp'),
            array( $this, 'welcome_panel_callback'),
            'nt_speed_up_wp',
            'setting_section_nt_speed_up_wp',
            array( 'class' => 'metaboxes' )
        );



        // JavaScript sections
        add_settings_section(
            'setting_section_javascript', // ID
             __('Settings JavaScript','nt-speed-up-wp'), // Title
            array( $this, 'print_section_javascript' ), // Callback
            'nt_speed_up_wp' // Page
        );
        add_settings_field(
            'jquery_to_footer', // Move jQuery to the footer
            __('Move jQuery script to the footer ?','nt-speed-up-wp'),
            array( $this, 'jquery_to_footer_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts' )
        );
        add_settings_field(
            'remove_query_string_ver', // Remove Query string ver
            __('Remove query string ver?xxx from source ?','nt-speed-up-wp'),
            array( $this, 'remove_query_string_ver_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts' )
        );
        add_settings_field(
            'revslider_meta_tag', // Remove revslider metatag
            __('Remove meta tag generator Slider Revolution ?','nt-speed-up-wp'),
            array( $this, 'revslider_meta_tag_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts' )
        );
        add_settings_field(
            'deregister_cf7', // Remove revslider metatag
            __('Deregister Contact Form7 styles and scripts ?','nt-speed-up-wp'),
            array( $this, 'deregister_cf7_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts' )
        );
        add_settings_field(
            'deregister_cf7_pages', // Run only - exclude pages
            __('Entry page slug with CF7 forms, comma separated.','nt-speed-up-wp'),
            array( $this, 'deregister_cf7_pages_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts deregister_cf7_pages' )
        );
        add_settings_field(
            'deregister_scripts', // Run only - exclude pages
            __('Entry script to deregister, comma separated.','nt-speed-up-wp'),
            array( $this, 'deregister_scripts_callback'),
            'nt_speed_up_wp',
            'setting_section_javascript',
            array( 'class' => 'javascripts deregister_cf7_pages' )
        );


        /* Other Sections */
        add_settings_section(
            'setting_section_other', // ID
             __('Other Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_other' ), // Callback
            'nt_speed_up_wp' // Page
        );
        add_settings_field(
            'optimize_heartbeat', // Optimize Heartbeat
            __('Optimize Heartbeat ?','nt-speed-up-wp'),
            array( $this, 'optimize_heartbeat_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'disable_oembed', // Disable oembed
            __('Disable Oembed ?','nt-speed-up-wp'),
            array( $this, 'disable_oembed_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'disable_emojis', // Disable Emojis
            __('Disable Emojis ?','nt-speed-up-wp'),
            array( $this, 'disable_emojis_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'disable_auto_updates', // Disable Emojis
            __('Disable check for updates?','nt-speed-up-wp'),
            array( $this, 'disable_auto_updates_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'disable_xmlrpc', // XMLRPC
            __('Disable XMLRPC ?','nt-speed-up-wp'),
            array( $this, 'disable_xmlrpc_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'disable_json', // JSON
            __('Disable JSON REST API ?','nt-speed-up-wp'),
            array( $this, 'disable_json_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'remove_comments', // Disable support for comments and trackbacks
            __('Disable Comments ?','nt-speed-up-wp'),
            array( $this, 'remove_comments_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section' )
        );
        add_settings_field(
            'jpeg_compression', // JPEG compression
            __('Entry percent compression JPEG images between 1% - 100%. Default is 82%.','nt-speed-up-wp'),
            array( $this, 'jpeg_compression_callback'),
            'nt_speed_up_wp',
            'setting_section_other',
            array( 'class' => 'other_section img_compress' )
        );


        /* Widget Sections */
        add_settings_section(
            'setting_section_wp_widget', // ID
             __('Wordpress Widgets Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_wp_widget' ), // Callback
            'nt_speed_up_wp' // Page
        );
        add_settings_field(
            'calendar', // Calendar widget
            __('Disable Calendar widget ?','nt-speed-up-wp'),
            array( $this, 'calendar_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'archives', // archives widget
            __('Disable Archives widget ?','nt-speed-up-wp'),
            array( $this, 'archives_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'links', // links widget
            __('Disable Links widget ?','nt-speed-up-wp'),
            array( $this, 'links_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'meta', // meta widget
            __('Disable Meta widget ?','nt-speed-up-wp'),
            array( $this, 'meta_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'pages', // pages widget
            __('Disable Pages widget ?','nt-speed-up-wp'),
            array( $this, 'pages_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'categories', // categories widget
            __('Disable Categories widget ?','nt-speed-up-wp'),
            array( $this, 'categories_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'recent_posts', // recent posts widget
            __('Disable Recent Posts widget ?','nt-speed-up-wp'),
            array( $this, 'recent_posts_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'recent_comments', // recent comments widget
            __('Disable Recent Comments ?','nt-speed-up-wp'),
            array( $this, 'recent_comments_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'rss', // rss widget
            __('Disable RSS Widget ?','nt-speed-up-wp'),
            array( $this, 'rss_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'tag_cloud', // tag cloud widget
            __('Disable Tag Cloud Widget ?','nt-speed-up-wp'),
            array( $this, 'tag_cloud_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'menu', // menu widget
            __('Disable Menu Widget ?','nt-speed-up-wp'),
            array( $this, 'menu_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'search', // search widget
            __('Disable Search Widget ?','nt-speed-up-wp'),
            array( $this, 'search_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );
        add_settings_field(
            'text', // search widget
            __('Disable Text Widget ?','nt-speed-up-wp'),
            array( $this, 'text_callback'),
            'nt_speed_up_wp',
            'setting_section_wp_widget',
            array( 'class' => 'wp_widgets' )
        );

        if ( get_option('template') == 'novetrendy') {

        /* CloudFW widgets sections */
        add_settings_section(
            'setting_section_cloudfw_widget', // ID
             __('Envision Theme Widgets Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_cloudfw_widget' ), // Callback
            'nt_speed_up_wp' // Page
        );
        add_settings_field(
            'blog_list',
            __('Disable Blog Posts widget ?','nt-speed-up-wp'),
            array( $this, 'blog_list_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'carousel',
            __('Disable Carousel widget ?','nt-speed-up-wp'),
            array( $this, 'carousel_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'get_content',
            __('Disable Get Page Content ?','nt-speed-up-wp'),
            array( $this, 'get_content_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'socialbar',
            __('Disable Socialbar widget ?','nt-speed-up-wp'),
            array( $this, 'socialbar_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'twitter',
            __('Disable Twitter Timeline ?','nt-speed-up-wp'),
            array( $this, 'twitter_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'mailchimp',
            __('Disable MailChimp widget ?','nt-speed-up-wp'),
            array( $this, 'mailchimp_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'subpost',
            __('Disable Sub Pages List ?','nt-speed-up-wp'),
            array( $this, 'subpost_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        add_settings_field(
            'portfolio',
            __('Disable Portfolio ?','nt-speed-up-wp'),
            array( $this, 'portfolio_callback'),
            'nt_speed_up_wp',
            'setting_section_cloudfw_widget',
            array( 'class' => 'cfw_widgets' )
        );
        }

        /* Other plugin widgets sections */
        if(class_exists('RevSliderFront')) {
        add_settings_section(
            'setting_section_other_widget', // ID
             __('Other Plugin Widgets Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_other_widget' ), // Callback
            'nt_speed_up_wp' // Page
        );
        add_settings_field(
            'revslider',
            __('Disable Slider Revolution ?','nt-speed-up-wp'),
            array( $this, 'revslider_callback'),
            'nt_speed_up_wp',
            'setting_section_other_widget',
            array( 'class' => 'other_plugin_widgets' )
        );
        }





        // Register and add settings for second tab CSS
        register_setting(
            'nt_woocommerce_speed_up_wp_option_group', // Group settings
            'nt_woocommerce_speed_up_wp', // Name of setting
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_wc_dashboard_speed_up_wp', // ID
            __('Woocommerce Dashboard Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_wc_dashboard' ), // Callback
            'nt_woocommerce_speed_up_wp' // Page
        );
        // Add Fields
        add_settings_field(
            'dashboard_woocommerce',
            __('Disable Woocommerce Status metabox ?','nt-speed-up-wp'),
            array( $this, 'dashboard_woocommerce_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_dashboard_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );

        add_settings_field(
            'wc_recent_review',
            __('Disable Woocommerce Recent Reviews ?','nt-speed-up-wp'),
            array( $this, 'wc_recent_review_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_dashboard_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );



        add_settings_section(
            'setting_section_wc_widgets_speed_up_wp', // ID
            __('Woocommerce Widgets Settings','nt-speed-up-wp'), // Title
            array( $this, 'print_section_wc_widgets' ), // Callback
            'nt_woocommerce_speed_up_wp' // Page
        );

        // Add Fields
        add_settings_field(
            'products_cats', // CSS Title Descriptions
            __('Disable Woocommerce Product Categories Widget ?','nt-speed-up-wp'),
            array( $this, 'products_cats_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'products', // Products Widget
            __('Disable Woocommerce Products Widget ?','nt-speed-up-wp'),
            array( $this, 'products_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'product_tag_clouds', // Product Tag Cloud Widget
            __('Disable Woocommerce Product Tag Cloud Widget ?','nt-speed-up-wp'),
            array( $this, 'product_tag_clouds_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'cart', // Cart Widget
            __('Disable Woocommerce Cart Widget ?','nt-speed-up-wp'),
            array( $this, 'cart_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'layered_nav', // Layered Navigation Widget
            __('Disable Woocommerce Layered Navigation Widget ?','nt-speed-up-wp'),
            array( $this, 'layered_nav_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'layered_nav_filters', // Layered Navigation Filters Widget
            __('Disable Layered Navigation Filters Widget ?','nt-speed-up-wp'),
            array( $this, 'layered_nav_filters_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'price_filter', // Price Filters Widget
            __('Disable Woocommerce Price Filters Widget ?','nt-speed-up-wp'),
            array( $this, 'price_filter_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'product_search', // Product Search Widget
            __('Disable Woocommerce Product Search Widget ?','nt-speed-up-wp'),
            array( $this, 'product_search_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'top_rated', // Top Rated Widget
            __('Disable Woocommerce Top Rated Widget ?','nt-speed-up-wp'),
            array( $this, 'top_rated_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'recent_reviews', // Recent Reviews Widget
            __('Disable Woocommerce Recent Reviews Widget ?','nt-speed-up-wp'),
            array( $this, 'recent_reviews_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'recently_viewed', // Recently Viewed Widget
            __('Disable Woocommerce Recently Viewed Widget ?','nt-speed-up-wp'),
            array( $this, 'recently_viewed_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );

        if (class_exists('WC_Brands')) {
        add_settings_field(
            'brand_description', // Brand Description Widget
            __('Disable Woocommerce Brand Description Widget ?','nt-speed-up-wp'),
            array( $this, 'brand_description_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'brand_nav', // Brand Layered Navigation Widget
            __('Disable Woocommerce Brand Layered Navigation Widget ?','nt-speed-up-wp'),
            array( $this, 'brand_nav_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'brand_thumb', // Brand Thumbnail Widget
            __('Disable Woocommerce Brand Thumbnail Widget ?','nt-speed-up-wp'),
            array( $this, 'brand_thumb_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        add_settings_field(
            'wistia', // Wistia
            __('Disable load help from wistia ?','nt-speed-up-wp'),
            array( $this, 'wistia_callback'),
            'nt_woocommerce_speed_up_wp',
            'setting_section_wc_widgets_speed_up_wp',
            array( 'class' => 'wc_widgets' )
        );
        }

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['dashboard_primary'] ) ) $new_input['dashboard_primary'] = (int)( $input['dashboard_primary'] );
        if( isset( $input['dashboard_activity'] ) ) $new_input['dashboard_activity'] = (int)( $input['dashboard_activity'] );
        if( isset( $input['dashboard_quick_press'] ) ) $new_input['dashboard_quick_press'] = (int)( $input['dashboard_quick_press'] );
        if( isset( $input['dashboard_right_now'] ) ) $new_input['dashboard_right_now'] = (int)( $input['dashboard_right_now'] );
        if( isset( $input['welcome_panel'] ) ) $new_input['welcome_panel'] = (int)( $input['welcome_panel'] );
        if( isset( $input['jquery_to_footer'] ) ) $new_input['jquery_to_footer'] = (int)( $input['jquery_to_footer'] );
        if( isset( $input['remove_query_string_ver'] ) ) $new_input['remove_query_string_ver'] = (int)( $input['remove_query_string_ver'] );
        if( isset( $input['revslider_meta_tag'] ) ) $new_input['revslider_meta_tag'] = (int)( $input['revslider_meta_tag'] );
        if( isset( $input['deregister_cf7'] ) ) $new_input['deregister_cf7'] = (int)( $input['deregister_cf7'] );
        if( isset( $input['deregister_cf7_pages'] ) ) $new_input['deregister_cf7_pages'] = sanitize_text_field( $input['deregister_cf7_pages'] );
        if( isset( $input['deregister_scripts'] ) ) $new_input['deregister_scripts'] = sanitize_text_field( $input['deregister_scripts'] );
        if( isset( $input['optimize_heartbeat'] ) ) $new_input['optimize_heartbeat'] = (int)( $input['optimize_heartbeat'] );
        if( isset( $input['disable_oembed'] ) ) $new_input['disable_oembed'] = (int)( $input['disable_oembed'] );
        if( isset( $input['disable_emojis'] ) ) $new_input['disable_emojis'] = (int)( $input['disable_emojis'] );
        if( isset( $input['disable_auto_updates'] ) ) $new_input['disable_auto_updates'] = (int)( $input['disable_auto_updates'] );
        if( isset( $input['jpeg_compression'] ) ) $new_input['jpeg_compression'] = absint( $input['jpeg_compression'] );
        if( isset( $input['disable_xmlrpc'] ) ) $new_input['disable_xmlrpc'] = (int)( $input['disable_xmlrpc'] );
        if( isset( $input['disable_json'] ) ) $new_input['disable_json'] = (int)( $input['disable_json'] );
        if( isset( $input['remove_comments'] ) ) $new_input['remove_comments'] = (int)( $input['remove_comments'] );
        if( isset( $input['calendar'] ) ) $new_input['calendar'] = (int)( $input['calendar'] );
        if( isset( $input['archives'] ) ) $new_input['archives'] = (int)( $input['archives'] );
        if( isset( $input['links'] ) ) $new_input['links'] = (int)( $input['links'] );
        if( isset( $input['meta'] ) ) $new_input['meta'] = (int)( $input['meta'] );
        if( isset( $input['pages'] ) ) $new_input['pages'] = (int)( $input['pages'] );
        if( isset( $input['categories'] ) ) $new_input['categories'] = (int)( $input['categories'] );
        if( isset( $input['recent_posts'] ) ) $new_input['recent_posts'] = (int)( $input['recent_posts'] );
        if( isset( $input['recent_comments'] ) ) $new_input['recent_comments'] = (int)( $input['recent_comments'] );
        if( isset( $input['rss'] ) ) $new_input['rss'] = (int)( $input['rss'] );
        if( isset( $input['tag_cloud'] ) ) $new_input['tag_cloud'] = (int)( $input['tag_cloud'] );
        if( isset( $input['menu'] ) ) $new_input['menu'] = (int)( $input['menu'] );
        if( isset( $input['search'] ) ) $new_input['search'] = (int)( $input['search'] );
        if( isset( $input['text'] ) ) $new_input['text'] = (int)( $input['text'] );
        if( isset( $input['blog_list'] ) ) $new_input['blog_list'] = (int)( $input['blog_list'] );
        if( isset( $input['carousel'] ) ) $new_input['carousel'] = (int)( $input['carousel'] );
        if( isset( $input['get_content'] ) ) $new_input['get_content'] = (int)( $input['get_content'] );
        if( isset( $input['socialbar'] ) ) $new_input['socialbar'] = (int)( $input['socialbar'] );
        if( isset( $input['twitter'] ) ) $new_input['twitter'] = (int)( $input['twitter'] );
        if( isset( $input['mailchimp'] ) ) $new_input['mailchimp'] = (int)( $input['mailchimp'] );
        if( isset( $input['subpost'] ) ) $new_input['subpost'] = (int)( $input['subpost'] );
        if( isset( $input['portfolio'] ) ) $new_input['portfolio'] = (int)( $input['portfolio'] );
        if( isset( $input['revslider'] ) ) $new_input['revslider'] = (int)( $input['revslider'] );
        // second tab
        if( isset( $input['dashboard_woocommerce'] ) ) $new_input['dashboard_woocommerce'] = (int)( $input['dashboard_woocommerce'] );
        if( isset( $input['wc_recent_review'] ) ) $new_input['wc_recent_review'] = (int)( $input['wc_recent_review'] );
        if( isset( $input['products_cats'] ) ) $new_input['products_cats'] = (int)( $input['products_cats'] );
        if( isset( $input['products'] ) ) $new_input['products'] = (int)( $input['products'] );
        if( isset( $input['product_tag_clouds'] ) ) $new_input['product_tag_clouds'] = (int)( $input['product_tag_clouds'] );
        if( isset( $input['cart'] ) ) $new_input['cart'] = (int)( $input['cart'] );
        if( isset( $input['layered_nav'] ) ) $new_input['layered_nav'] = (int)( $input['layered_nav'] );
        if( isset( $input['layered_nav_filters'] ) ) $new_input['layered_nav_filters'] = (int)( $input['layered_nav_filters'] );
        if( isset( $input['price_filter'] ) ) $new_input['price_filter'] = (int)( $input['price_filter'] );
        if( isset( $input['product_search'] ) ) $new_input['product_search'] = (int)( $input['product_search'] );
        if( isset( $input['top_rated'] ) ) $new_input['top_rated'] = (int)( $input['top_rated'] );
        if( isset( $input['recent_reviews'] ) ) $new_input['recent_reviews'] = (int)( $input['recent_reviews'] );
        if( isset( $input['recently_viewed'] ) ) $new_input['recently_viewed'] = (int)( $input['recently_viewed'] );
        if( isset( $input['brand_description'] ) ) $new_input['brand_description'] = (int)( $input['brand_description'] );
        if( isset( $input['brand_nav'] ) ) $new_input['brand_nav'] = (int)( $input['brand_nav'] );
        if( isset( $input['brand_thumb'] ) ) $new_input['brand_thumb'] = (int)( $input['brand_thumb'] );
        if( isset( $input['wistia'] ) ) $new_input['wistia'] = (int)( $input['wistia'] );

        return $new_input;
    }



    // Callback plugin option (first tab)
    public function dashboard_primary_callback(){?><label class="switch"><input name="nt_speed_up_wp[dashboard_primary]" type="checkbox" value="1" <?php checked( isset( $this->options['dashboard_primary'] ) );?> /><div class="slider"></div></label><?php }
    public function dashboard_activity_callback(){?><label class="switch"><input name="nt_speed_up_wp[dashboard_activity]" type="checkbox" value="1" <?php checked( isset( $this->options['dashboard_activity'] ) );?> /><div class="slider"></div></label><?php }
    public function dashboard_quick_press_callback(){?><label class="switch"><input name="nt_speed_up_wp[dashboard_quick_press]" type="checkbox" value="1" <?php checked( isset( $this->options['dashboard_quick_press'] ) );?> /><div class="slider"></div></label><?php }
    public function dashboard_right_now_callback(){?><label class="switch"><input name="nt_speed_up_wp[dashboard_right_now]" type="checkbox" value="1" <?php checked( isset( $this->options['dashboard_right_now'] ) );?> /><div class="slider"></div></label><?php }
    public function welcome_panel_callback(){?><label class="switch"><input name="nt_speed_up_wp[welcome_panel]" type="checkbox" value="1" <?php checked( isset( $this->options['welcome_panel'] ) );?> /><div class="slider"></div></label><?php }
    public function jquery_to_footer_callback(){?><label class="switch"><input name="nt_speed_up_wp[jquery_to_footer]" type="checkbox" value="1" <?php checked( isset( $this->options['jquery_to_footer'] ) );?> /><div class="slider"></div></label><?php  }
    public function remove_query_string_ver_callback(){?><label class="switch"><input name="nt_speed_up_wp[remove_query_string_ver]" type="checkbox" value="1" <?php checked( isset( $this->options['remove_query_string_ver'] ) );?> /><div class="slider"></div></label><?php }
    public function revslider_meta_tag_callback(){?><label class="switch"><input name="nt_speed_up_wp[revslider_meta_tag]" type="checkbox" value="1" <?php checked( isset( $this->options['revslider_meta_tag'] ) );?> /><div class="slider"></div></label><?php }
    public function deregister_cf7_callback(){?><label class="switch"><input name="nt_speed_up_wp[deregister_cf7]" type="checkbox" value="1" <?php checked( isset( $this->options['deregister_cf7'] ) );?> /><div class="slider"></div></label><?php }
    public function deregister_cf7_pages_callback(){$default = 'kontakt';
         printf('<textarea name="nt_speed_up_wp[deregister_cf7_pages]" type="textarea" cols="80" rows="1">%s</textarea>',
            empty( $this->options['deregister_cf7_pages'] ) ? $default : $this->options['deregister_cf7_pages']);
            echo '<br /><em>' . __('Default: ', 'nt-speed-up-wp') . $default . '</em>';   }
    public function deregister_scripts_callback(){$default = 'comment-reply';
         printf('<textarea name="nt_speed_up_wp[deregister_scripts]" type="textarea" cols="80" rows="1">%s</textarea>',
            empty( $this->options['deregister_scripts'] ) ? $default : $this->options['deregister_scripts']);   }
    public function optimize_heartbeat_callback(){?><label class="switch"><input name="nt_speed_up_wp[optimize_heartbeat]" type="checkbox" value="1" <?php checked( isset( $this->options['optimize_heartbeat'] ) );?> /><div class="slider"></div></label><?php }
    public function disable_oembed_callback(){?><label class="switch"><input name="nt_speed_up_wp[disable_oembed]" type="checkbox" value="1" <?php checked( isset( $this->options['disable_oembed'] ) );?> /><div class="slider"></div></label><?php }
    public function disable_emojis_callback(){?><label class="switch"><input name="nt_speed_up_wp[disable_emojis]" type="checkbox" value="1" <?php checked( isset( $this->options['disable_emojis'] ) );?> /><div class="slider"></div></label><?php }
    public function disable_auto_updates_callback(){?><label class="switch"><input name="nt_speed_up_wp[disable_auto_updates]" type="checkbox" value="1" <?php checked( isset( $this->options['disable_auto_updates'] ) );?> /><div class="slider"></div></label><?php }
    public function jpeg_compression_callback(){
    $jpeg_compression = isset( $this->options['jpeg_compression'] ) ? esc_attr( $this->options['jpeg_compression']) : '82';
    if ($jpeg_compression <= 0 || $jpeg_compression >= 101) {$jpeg_compression = 82;}?>
    <input name="nt_speed_up_wp[jpeg_compression]" type="number" min="1" max="100" value="<?php echo $jpeg_compression; ?>" style="width: 60px;" /><?php
    }
    public function disable_xmlrpc_callback(){?><label class="switch"><input name="nt_speed_up_wp[disable_xmlrpc]" type="checkbox" value="1" <?php checked( isset( $this->options['disable_xmlrpc'] ) );?> /><div class="slider"></div></label><?php }
    public function disable_json_callback(){?><label class="switch"><input name="nt_speed_up_wp[disable_json]" type="checkbox" value="1" <?php checked( isset( $this->options['disable_json'] ) );?> /><div class="slider"></div></label><?php }
    public function remove_comments_callback(){?><label class="switch"><input name="nt_speed_up_wp[remove_comments]" type="checkbox" value="1" <?php checked( isset( $this->options['remove_comments'] ) );?> /><div class="slider"></div></label><?php }
    public function calendar_callback(){?><label class="switch"><input name="nt_speed_up_wp[calendar]" type="checkbox" value="1" <?php checked( isset( $this->options['calendar'] ) );?> /><div class="slider"></div></label><?php }
    public function archives_callback(){?><label class="switch"><input name="nt_speed_up_wp[archives]" type="checkbox" value="1" <?php checked( isset( $this->options['archives'] ) );?> /><div class="slider"></div></label><?php }
    public function links_callback(){?><label class="switch"><input name="nt_speed_up_wp[links]" type="checkbox" value="1" <?php checked( isset( $this->options['links'] ) );?> /><div class="slider"></div></label><?php }
    public function meta_callback(){?><label class="switch"><input name="nt_speed_up_wp[meta]" type="checkbox" value="1" <?php checked( isset( $this->options['meta'] ) );?> /><div class="slider"></div></label><?php }
    public function pages_callback(){?><label class="switch"><input name="nt_speed_up_wp[pages]" type="checkbox" value="1" <?php checked( isset( $this->options['pages'] ) );?> /><div class="slider"></div></label><?php }
    public function categories_callback(){?><label class="switch"><input name="nt_speed_up_wp[categories]" type="checkbox" value="1" <?php checked( isset( $this->options['categories'] ) );?> /><div class="slider"></div></label><?php }
    public function recent_posts_callback(){?><label class="switch"><input name="nt_speed_up_wp[recent_posts]" type="checkbox" value="1" <?php checked( isset( $this->options['recent_posts'] ) );?> /><div class="slider"></div></label><?php }
    public function recent_comments_callback(){?><label class="switch"><input name="nt_speed_up_wp[recent_comments]" type="checkbox" value="1" <?php checked( isset( $this->options['recent_comments'] ) );?> /><div class="slider"></div></label><?php }
    public function rss_callback(){?><label class="switch"><input name="nt_speed_up_wp[rss]" type="checkbox" value="1" <?php checked( isset( $this->options['rss'] ) );?> /><div class="slider"></div></label><?php }
    public function tag_cloud_callback(){?><label class="switch"><input name="nt_speed_up_wp[tag_cloud]" type="checkbox" value="1" <?php checked( isset( $this->options['tag_cloud'] ) );?> /><div class="slider"></div></label><?php }
    public function menu_callback(){?><label class="switch"><input name="nt_speed_up_wp[menu]" type="checkbox" value="1" <?php checked( isset( $this->options['menu'] ) );?> /><div class="slider"></div></label><?php }
    public function search_callback(){?><label class="switch"><input name="nt_speed_up_wp[search]" type="checkbox" value="1" <?php checked( isset( $this->options['search'] ) );?> /><div class="slider"></div></label><?php }
    public function text_callback(){?><label class="switch"><input name="nt_speed_up_wp[text]" type="checkbox" value="1" <?php checked( isset( $this->options['text'] ) );?> /><div class="slider"></div></label><?php }
    public function blog_list_callback(){?><label class="switch"><input name="nt_speed_up_wp[blog_list]" type="checkbox" value="1" <?php checked( isset( $this->options['blog_list'] ) );?> /><div class="slider"></div></label><?php }
    public function carousel_callback(){?><label class="switch"><input name="nt_speed_up_wp[carousel]" type="checkbox" value="1" <?php checked( isset( $this->options['carousel'] ) );?> /><div class="slider"></div></label><?php }
    public function get_content_callback(){?><label class="switch"><input name="nt_speed_up_wp[get_content]" type="checkbox" value="1" <?php checked( isset( $this->options['get_content'] ) );?> /><div class="slider"></div></label><?php }
    public function socialbar_callback(){?><label class="switch"><input name="nt_speed_up_wp[socialbar]" type="checkbox" value="1" <?php checked( isset( $this->options['socialbar'] ) );?> /><div class="slider"></div></label><?php }
    public function twitter_callback(){?><label class="switch"><input name="nt_speed_up_wp[twitter]" type="checkbox" value="1" <?php checked( isset( $this->options['twitter'] ) );?> /><div class="slider"></div></label><?php }
    public function mailchimp_callback(){?><label class="switch"><input name="nt_speed_up_wp[mailchimp]" type="checkbox" value="1" <?php checked( isset( $this->options['mailchimp'] ) );?> /><div class="slider"></div></label><?php }
    public function subpost_callback(){?><label class="switch"><input name="nt_speed_up_wp[subpost]" type="checkbox" value="1" <?php checked( isset( $this->options['subpost'] ) );?> /><div class="slider"></div></label><?php }
    public function portfolio_callback(){?><label class="switch"><input name="nt_speed_up_wp[portfolio]" type="checkbox" value="1" <?php checked( isset( $this->options['portfolio'] ) );?> /><div class="slider"></div></label><?php }
    public function revslider_callback(){?><label class="switch"><input name="nt_speed_up_wp[revslider]" type="checkbox" value="1" <?php checked( isset( $this->options['revslider'] ) );?> /><div class="slider"></div></label><?php }
    // Second Tab
    public function dashboard_woocommerce_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[dashboard_woocommerce]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['dashboard_woocommerce'] ) );?> /><div class="slider"></div></label><?php }
    public function wc_recent_review_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[wc_recent_review]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['wc_recent_review'] ) );?> /><div class="slider"></div></label><?php }
    public function products_cats_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[products_cats]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['products_cats'] ) );?> /><div class="slider"></div></label><?php }
    public function products_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[products]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['products'] ) );?> /><div class="slider"></div></label><?php }
    public function  product_tag_clouds_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[product_tag_clouds]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['product_tag_clouds'] ) );?> /><div class="slider"></div></label><?php }
    public function  cart_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[cart]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['cart'] ) );?> /><div class="slider"></div></label><?php }
    public function  layered_nav_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[layered_nav]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['layered_nav'] ) );?> /><div class="slider"></div></label><?php }
    public function  layered_nav_filters_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[layered_nav_filters]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['layered_nav_filters'] ) );?> /><div class="slider"></div></label><?php }
    public function  price_filter_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[price_filter]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['price_filter'] ) );?> /><div class="slider"></div></label><?php }
    public function  product_search_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[product_search]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['product_search'] ) );?> /><div class="slider"></div></label><?php }
    public function  top_rated_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[top_rated]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['top_rated'] ) );?> /><div class="slider"></div></label><?php }
    public function  recent_reviews_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[recent_reviews]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['recent_reviews'] ) );?> /><div class="slider"></div></label><?php }
    public function  recently_viewed_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[recently_viewed]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['recently_viewed'] ) );?> /><div class="slider"></div></label><?php }
    public function  brand_description_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[brand_description]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['brand_description'] ) );?> /><div class="slider"></div></label><?php }
    public function  brand_nav_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[brand_nav]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['brand_nav'] ) );?> /><div class="slider"></div></label><?php }
    public function  brand_thumb_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[brand_thumb]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['brand_thumb'] ) );?> /><div class="slider"></div></label><?php }
    public function  wistia_callback(){?><label class="switch"><input name="nt_woocommerce_speed_up_wp[wistia]" type="checkbox" value="1" <?php checked( isset( $this->options_wc['wistia'] ) );?> /><div class="slider"></div></label><?php }


    /** Print Dashboard Settings Section*/
    public function print_section_info()
    {echo '<p>'; _e('Here you can remove some Dashboard metaboxs.','nt-speed-up-wp'); echo '</p>';}

    /** Print JavaScripts Settings Section*/
    public function print_section_javascript()
    {echo '<p>'; _e('Here you can manipulate some JavaScript files.','nt-speed-up-wp'); echo '</p>';}

    /** Print Other Settings Section*/
    public function print_section_other()
    {echo '<p>'; _e('Here you can disable few Wordpress functions.','nt-speed-up-wp'); echo '</p>';}

    /** Print WP Widget Section*/
    public function print_section_wp_widget()
    {echo '<p>'; _e('Here you can disable some Wordpress widgets.','nt-speed-up-wp'); echo '</p>';}

    /** Print CloudFW Widget Section*/
    public function print_section_cloudfw_widget()
    {echo '<p>'; _e('Here you can disable some CloudFW widgets from Envision Theme.','nt-speed-up-wp'); echo '</p>';}

    /** Print Other Plugin Widget Section*/
    public function print_section_other_widget()
    {echo '<p>'; _e('Here you can disable some widgets from few plugins.','nt-speed-up-wp'); echo '</p>';}


    /** Print Woocommerce Widgets Section */
    public function print_section_wc_widgets()
    {echo '<p>'; _e('Here you can disable some Woocommerce widgets.','nt-speed-up-wp'); echo '</p>';}

    /** Print Woocommerce Dashboard Section */
    public function print_section_wc_dashboard()
    {echo '<p>'; _e('Here you can disable some Woocommerce dashboard metaboxes.','nt-speed-up-wp'); echo '</p>';}
}
?>