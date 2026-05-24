<?php
/**
 * Babarida Dive Center Theme Functions
 *
 * @package Babarida_Dive_Center
 * @author Iqbal Tombinawa <tombinawaiqbal@gmail.com>
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/* ============================================
   THEME CONSTANTS
   ============================================ */
define('BBR_VERSION', '1.0.0');
define('BBR_DIR', get_template_directory());
define('BBR_URI', get_template_directory_uri());
define('BBR_WHATSAPP', '62895801960359');
define('BBR_EMAIL', 'info@babaridadive.com');

/* ============================================
   THEME SETUP
   ============================================ */
function bbr_theme_setup() {
    load_theme_textdomain('babarida-dive', BBR_DIR . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Image sizes
    add_image_size('bbr-hero', 1920, 1080, true);
    add_image_size('bbr-card', 600, 450, true);
    add_image_size('bbr-gallery', 800, 600, true);
    add_image_size('bbr-thumb', 400, 300, true);
    add_image_size('bbr-avatar', 150, 150, true);

    // Nav menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Navigation', 'babarida-dive'),
        'bunaken'   => esc_html__('Bunaken Submenu', 'babarida-dive'),
        'siladen'   => esc_html__('Siladen Submenu', 'babarida-dive'),
        'bangka'    => esc_html__('Bangka Submenu', 'babarida-dive'),
        'lembeh'    => esc_html__('Lembeh Submenu', 'babarida-dive'),
        'liveaboard'=> esc_html__('Liveaboard Submenu', 'babarida-dive'),
        'info'      => esc_html__('Info Submenu', 'babarida-dive'),
        'footer'    => esc_html__('Footer Navigation', 'babarida-dive'),
    ));

    // Sidebars
    register_sidebar(array(
        'name'          => esc_html__('Blog Sidebar', 'babarida-dive'),
        'id'            => 'sidebar-blog',
        'description'   => esc_html__('Appears on blog pages.', 'babarida-dive'),
        'before_widget' => '<div id="%1$s" class="bbr-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="bbr-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('after_setup_theme', 'bbr_theme_setup');

/* ============================================
   ENQUEUE SCRIPTS & STYLES
   ============================================ */
function bbr_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style('bbr-google-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);

    // Tailwind CSS
    wp_enqueue_style('bbr-tailwind', 'https://cdn.tailwindcss.com', array(), null);

    // Theme stylesheet
    wp_enqueue_style('bbr-style', get_stylesheet_uri(), array('bbr-google-fonts'), BBR_VERSION);

    // Lucide Icons
    wp_enqueue_script('bbr-lucide', 'https://unpkg.com/lucide@latest/dist/umd/lucide.js', array(), null, true);

    // Main JS
    wp_enqueue_script('bbr-main', BBR_URI . '/js/main.js', array('bbr-lucide'), BBR_VERSION, true);

    // Localize script
    wp_localize_script('bbr-main', 'bbrData', array(
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bbr_nonce'),
        'siteUrl'  => home_url('/'),
        'whatsapp' => BBR_WHATSAPP,
        'email'    => BBR_EMAIL,
        'i18n'     => array(
            'loading'    => esc_html__('Loading...', 'babarida-dive'),
            'searching'  => esc_html__('Searching...', 'babarida-dive'),
            'noResults'  => esc_html__('No results found.', 'babarida-dive'),
            'bookNow'    => esc_html__('Book Now', 'babarida-dive'),
            'sending'    => esc_html__('Sending...', 'babarida-dive'),
            'sent'       => esc_html__('Message sent!', 'babarida-dive'),
            'error'      => esc_html__('Something went wrong.', 'babarida-dive'),
        ),
    ));

    // Comments reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Admin styles for dashboard template
    if (is_page_template('template-dashboard.php')) {
        wp_enqueue_style('bbr-dashboard', BBR_URI . '/css/dashboard.css', array('bbr-style'), BBR_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'bbr_enqueue_assets');

/* ============================================
   ADMIN ENQUEUE
   ============================================ */
function bbr_admin_assets($hook) {
    wp_enqueue_style('bbr-admin', BBR_URI . '/css/admin.css', array(), BBR_VERSION);
    wp_enqueue_script('bbr-admin-js', BBR_URI . '/js/admin.js', array('jquery'), BBR_VERSION, true);
    wp_localize_script('bbr-admin-js', 'bbrAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('bbr_admin_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'bbr_admin_assets');

/* ============================================
   REGISTER CUSTOM POST TYPES
   ============================================ */
function bbr_register_cpts() {
    // --- DESTINATIONS ---
    register_post_type('destination', array(
        'labels' => array(
            'name'               => __('Destinations', 'babarida-dive'),
            'singular_name'      => __('Destination', 'babarida-dive'),
            'add_new'            => __('Add Destination', 'babarida-dive'),
            'add_new_item'       => __('Add New Destination', 'babarida-dive'),
            'edit_item'          => __('Edit Destination', 'babarida-dive'),
            'view_item'          => __('View Destination', 'babarida-dive'),
            'all_items'          => __('All Destinations', 'babarida-dive'),
            'search_items'       => __('Search Destinations', 'babarida-dive'),
            'not_found'          => __('No destinations found.', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'destinations', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields'),
        'menu_icon'    => 'dashicons-location-alt',
        'show_in_rest' => true,
        'menu_position'=> 5,
    ));

    // --- TRIPS ---
    register_post_type('trip', array(
        'labels' => array(
            'name'               => __('Trips', 'babarida-dive'),
            'singular_name'      => __('Trip', 'babarida-dive'),
            'add_new_item'       => __('Add New Trip', 'babarida-dive'),
            'edit_item'          => __('Edit Trip', 'babarida-dive'),
            'all_items'          => __('All Trips', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'trips', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-clipboard',
        'show_in_rest' => true,
        'menu_position'=> 6,
    ));

    // --- LIVEABOARDS ---
    register_post_type('liveaboard', array(
        'labels' => array(
            'name'               => __('Liveaboards', 'babarida-dive'),
            'singular_name'      => __('Liveaboard', 'babarida-dive'),
            'add_new_item'       => __('Add New Liveaboard', 'babarida-dive'),
            'edit_item'          => __('Edit Liveaboard', 'babarida-dive'),
            'all_items'          => __('All Liveaboards', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'liveaboards', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-sailboat',
        'show_in_rest' => true,
        'menu_position'=> 7,
    ));

    // --- HOTELS ---
    register_post_type('hotel', array(
        'labels' => array(
            'name'               => __('Hotels', 'babarida-dive'),
            'singular_name'      => __('Hotel', 'babarida-dive'),
            'add_new_item'       => __('Add New Hotel', 'babarida-dive'),
            'all_items'          => __('All Hotels', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'hotels', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-building',
        'show_in_rest' => true,
        'menu_position'=> 8,
    ));

    // --- TESTIMONIALS ---
    register_post_type('testimonial', array(
        'labels' => array(
            'name'               => __('Testimonials', 'babarida-dive'),
            'singular_name'      => __('Testimonial', 'babarida-dive'),
            'add_new_item'       => __('Add New Testimonial', 'babarida-dive'),
            'all_items'          => __('All Testimonials', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => array('slug' => 'testimonials', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon'    => 'dashicons-format-quote',
        'show_in_rest' => true,
        'menu_position'=> 9,
    ));

    // --- PARTNERS ---
    register_post_type('partner', array(
        'labels' => array(
            'name'               => __('Partners', 'babarida-dive'),
            'singular_name'      => __('Partner', 'babarida-dive'),
            'add_new_item'       => __('Add New Partner', 'babarida-dive'),
            'all_items'          => __('All Partners', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => array('slug' => 'partners', 'with_front' => false),
        'supports'     => array('title', 'thumbnail', 'custom-fields'),
        'menu_icon'    => 'dashicons-handshake',
        'show_in_rest' => true,
        'menu_position'=> 10,
    ));

    // --- FAQ ---
    register_post_type('faq', array(
        'labels' => array(
            'name'               => __('FAQ', 'babarida-dive'),
            'singular_name'      => __('FAQ', 'babarida-dive'),
            'add_new_item'       => __('Add New FAQ', 'babarida-dive'),
            'all_items'          => __('All FAQs', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => false,
        'rewrite'      => array('slug' => 'faq', 'with_front' => false),
        'supports'     => array('title', 'editor', 'custom-fields'),
        'menu_icon'    => 'dashicons-editor-help',
        'show_in_rest' => true,
        'menu_position'=> 11,
    ));

    // --- BOOKINGS ---
    register_post_type('booking', array(
        'labels' => array(
            'name'               => __('Bookings', 'babarida-dive'),
            'singular_name'      => __('Booking', 'babarida-dive'),
            'add_new_item'       => __('Add New Booking', 'babarida-dive'),
            'edit_item'          => __('Edit Booking', 'babarida-dive'),
            'all_items'          => __('All Bookings', 'babarida-dive'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'supports'     => array('title', 'editor', 'custom-fields'),
        'menu_icon'    => 'dashicons-calendar-alt',
        'show_in_rest' => true,
        'menu_position'=> 12,
    ));

    // --- WATER SPORTS ---
    register_post_type('water_sport', array(
        'labels' => array(
            'name'               => __('Water Sports', 'babarida-dive'),
            'singular_name'      => __('Water Sport', 'babarida-dive'),
            'add_new_item'       => __('Add New Water Sport', 'babarida-dive'),
            'all_items'          => __('All Water Sports', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'water-sports', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-admin-site',
        'show_in_rest' => true,
        'menu_position'=> 13,
    ));

    // --- DIVE COURSES ---
    register_post_type('dive_course', array(
        'labels' => array(
            'name'               => __('Dive Courses', 'babarida-dive'),
            'singular_name'      => __('Dive Course', 'babarida-dive'),
            'add_new_item'       => __('Add New Course', 'babarida-dive'),
            'all_items'          => __('All Dive Courses', 'babarida-dive'),
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'dive-courses', 'with_front' => false),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-welcome-learn-more',
        'show_in_rest' => true,
        'menu_position'=> 14,
    ));
}
add_action('init', 'bbr_register_cpts');

/* ============================================
   REGISTER TAXONOMIES
   ============================================ */
function bbr_register_taxonomies() {
    // Destination Category
    register_taxonomy('destination_cat', 'destination', array(
        'labels' => array(
            'name'          => __('Destination Categories', 'babarida-dive'),
            'singular_name' => __('Category', 'babarida-dive'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'destination-category'),
        'show_in_rest' => true,
    ));

    // Trip Type
    register_taxonomy('trip_type', array('trip', 'liveaboard'), array(
        'labels' => array(
            'name'          => __('Trip Types', 'babarida-dive'),
            'singular_name' => __('Trip Type', 'babarida-dive'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'trip-type'),
        'show_in_rest' => true,
    ));

    // Activity Type
    register_taxonomy('activity_type', array('trip', 'water_sport', 'dive_course'), array(
        'labels' => array(
            'name'          => __('Activity Types', 'babarida-dive'),
            'singular_name' => __('Activity Type', 'babarida-dive'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'activity'),
        'show_in_rest' => true,
    ));

    // Difficulty Level
    register_taxonomy('difficulty', array('trip', 'dive_course'), array(
        'labels' => array(
            'name'          => __('Difficulty Levels', 'babarida-dive'),
            'singular_name' => __('Difficulty', 'babarida-dive'),
        ),
        'hierarchical' => false,
        'public'       => true,
        'rewrite'      => array('slug' => 'difficulty'),
        'show_in_rest' => true,
    ));

    // Partner Category
    register_taxonomy('partner_cat', 'partner', array(
        'labels' => array(
            'name'          => __('Partner Categories', 'babarida-dive'),
            'singular_name' => __('Partner Category', 'babarida-dive'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'partner-category'),
        'show_in_rest' => true,
    ));

    // Hotel Category
    register_taxonomy('hotel_cat', 'hotel', array(
        'labels' => array(
            'name'          => __('Hotel Categories', 'babarida-dive'),
            'singular_name' => __('Hotel Category', 'babarida-dive'),
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'hotel-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'bbr_register_taxonomies');

/* ============================================
   CUSTOM USER ROLES
   ============================================ */
function bbr_register_roles() {
    // General Manager
    remove_role('bbr_general_manager');
    add_role('bbr_general_manager', __('General Manager', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_others_posts'      => true,
        'edit_private_posts'     => true,
        'read_private_posts'     => true,
        'publish_posts'          => true,
        'upload_files'           => true,
        'manage_bookings'        => true,
        'view_reports'           => true,
        'manage_trips'           => true,
    ));

    // Booking Staff
    remove_role('bbr_booking_staff');
    add_role('bbr_booking_staff', __('Booking Staff', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_others_posts'      => false,
        'publish_posts'          => false,
        'upload_files'           => true,
        'manage_bookings'        => true,
        'view_reports'           => false,
    ));

    // Dive Guide
    remove_role('bbr_dive_guide');
    add_role('bbr_dive_guide', __('Dive Guide', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => false,
        'upload_files'           => true,
    ));

    // Hotel Partner
    remove_role('bbr_hotel_partner');
    add_role('bbr_hotel_partner', __('Hotel Partner', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => false,
        'upload_files'           => true,
        'manage_hotel'           => true,
    ));

    // Liveaboard Partner
    remove_role('bbr_liveaboard_partner', __('Liveaboard Partner', 'babarida-dive'));
    add_role('bbr_liveaboard_partner', __('Liveaboard Partner', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => false,
        'upload_files'           => true,
        'manage_liveaboard'      => true,
    ));

    // Content Editor
    remove_role('bbr_content_editor');
    add_role('bbr_content_editor', __('Content Editor', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_others_posts'      => true,
        'edit_published_posts'   => true,
        'publish_posts'          => true,
        'upload_files'           => true,
        'manage_categories'      => true,
    ));

    // Finance Staff
    remove_role('bbr_finance_staff');
    add_role('bbr_finance_staff', __('Finance Staff', 'babarida-dive'), array(
        'read'                   => true,
        'edit_posts'             => true,
        'view_reports'           => true,
        'manage_bookings'        => true,
        'export_reports'         => true,
    ));
}
add_action('init', 'bbr_register_roles');

/* ============================================
   META BOXES
   ============================================ */
function bbr_register_meta_boxes() {
    // Trip meta box
    add_meta_box('bbr_trip_details', __('Trip Details', 'babarida-dive'), 'bbr_trip_meta_box_callback', 'trip', 'normal', 'high');
    // Liveaboard meta box
    add_meta_box('bbr_liveaboard_details', __('Liveaboard Details', 'babarida-dive'), 'bbr_liveaboard_meta_box_callback', 'liveaboard', 'normal', 'high');
    // Hotel meta box
    add_meta_box('bbr_hotel_details', __('Hotel Details', 'babarida-dive'), 'bbr_hotel_meta_box_callback', 'hotel', 'normal', 'high');
    // Testimonial meta box
    add_meta_box('bbr_testimonial_details', __('Testimonial Details', 'babarida-dive'), 'bbr_testimonial_meta_box_callback', 'testimonial', 'normal', 'high');
    // Booking meta box
    add_meta_box('bbr_booking_details', __('Booking Details', 'babarida-dive'), 'bbr_booking_meta_box_callback', 'booking', 'normal', 'high');
    // Destination meta box
    add_meta_box('bbr_destination_details', __('Destination Details', 'babarida-dive'), 'bbr_destination_meta_box_callback', 'destination', 'normal', 'high');
    // Water Sport meta box
    add_meta_box('bbr_watersport_details', __('Water Sport Details', 'babarida-dive'), 'bbr_watersport_meta_box_callback', 'water_sport', 'normal', 'high');
    // Dive Course meta box
    add_meta_box('bbr_course_details', __('Course Details', 'babarida-dive'), 'bbr_course_meta_box_callback', 'dive_course', 'normal', 'high');
    // Partner meta box
    add_meta_box('bbr_partner_details', __('Partner Details', 'babarida-dive'), 'bbr_partner_meta_box_callback', 'partner', 'side', 'high');
    // SEO meta box
    $post_types = array('post', 'page', 'destination', 'trip', 'liveaboard', 'hotel', 'water_sport', 'dive_course');
    foreach ($post_types as $pt) {
        add_meta_box('bbr_seo_details', __('SEO Settings', 'babarida-dive'), 'bbr_seo_meta_box_callback', $pt, 'side', 'low');
    }
}
add_action('add_meta_boxes', 'bbr_register_meta_boxes');

/* --- Meta Box Callbacks --- */
function bbr_trip_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_trip_nonce');
    $fields = array(
        'destination'   => __('Destination', 'babarida-dive'),
        'trip_type'     => __('Trip Type', 'babarida-dive'),
        'duration'      => __('Duration (days)', 'babarida-dive'),
        'price_usd'     => __('Price (USD)', 'babarida-dive'),
        'price_idr'     => __('Price (IDR)', 'babarida-dive'),
        'max_guests'    => __('Max Guests', 'babarida-dive'),
        'min_cert'      => __('Min Certification', 'babarida-dive'),
        'includes'      => __('Includes (one per line)', 'babarida-dive'),
        'excludes'      => __('Excludes (one per line)', 'babarida-dive'),
        'itinerary'     => __('Itinerary (one per line)', 'babarida-dive'),
        'gallery_ids'   => __('Gallery Image IDs (comma separated)', 'babarida-dive'),
        'availability'  => __('Available Slots', 'babarida-dive'),
        'booking_link'  => __('Booking Link', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'trip');
}

function bbr_liveaboard_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_liveaboard_nonce');
    $fields = array(
        'boat_length'     => __('Boat Length (meters)', 'babarida-dive'),
        'cabins'          => __('Number of Cabins', 'babarida-dive'),
        'max_guests'      => __('Max Guests', 'babarida-dive'),
        'crew'            => __('Crew Size', 'babarida-dive'),
        'price_usd'       => __('Price per Night (USD)', 'babarida-dive'),
        'price_idr'       => __('Price per Night (IDR)', 'babarida-dive'),
        'routes'          => __('Routes (one per line)', 'babarida-dive'),
        'amenities'       => __('Amenities (one per line)', 'babarida-dive'),
        'specifications'  => __('Specifications (one per line)', 'babarida-dive'),
        'gallery_ids'     => __('Gallery Image IDs (comma separated)', 'babarida-dive'),
        'availability'    => __('Available Cabins', 'babarida-dive'),
        'schedule'        => __('Schedule (one per line)', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'liveaboard');
}

function bbr_hotel_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_hotel_nonce');
    $fields = array(
        'hotel_location'  => __('Location', 'babarida-dive'),
        'hotel_stars'     => __('Star Rating', 'babarida-dive'),
        'price_from_usd'  => __('Price From (USD)', 'babarida-dive'),
        'price_from_idr'  => __('Price From (IDR)', 'babarida-dive'),
        'room_types'      => __('Room Types (one per line)', 'babarida-dive'),
        'facilities'      => __('Facilities (one per line)', 'babarida-dive'),
        'hotel_phone'     => __('Phone', 'babarida-dive'),
        'hotel_email'     => __('Email', 'babarida-dive'),
        'hotel_website'   => __('Website', 'babarida-dive'),
        'hotel_map_lat'   => __('Map Latitude', 'babarida-dive'),
        'hotel_map_lng'   => __('Map Longitude', 'babarida-dive'),
        'gallery_ids'     => __('Gallery Image IDs (comma separated)', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'hotel');
}

function bbr_testimonial_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_testimonial_nonce');
    $fields = array(
        'customer_name'  => __('Customer Name', 'babarida-dive'),
        'customer_loc'   => __('Location / Country', 'babarida-dive'),
        'rating'         => __('Rating (1-5)', 'babarida-dive'),
        'trip_date'      => __('Trip Date', 'babarida-dive'),
        'trip_type'      => __('Trip Type', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'testimonial');
}

function bbr_booking_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_booking_nonce');
    $fields = array(
        'booking_status'    => __('Status (pending/confirmed/paid/checked-in/completed/cancelled)', 'babarida-dive'),
        'guest_name'        => __('Guest Full Name', 'babarida-dive'),
        'guest_email'       => __('Guest Email', 'babarida-dive'),
        'guest_phone'       => __('Guest Phone', 'babarida-dive'),
        'guest_nationality' => __('Nationality', 'babarida-dive'),
        'guest_passport'    => __('Passport Number', 'babarida-dive'),
        'trip_id'           => __('Trip Post ID', 'babarida-dive'),
        'trip_name'         => __('Trip Name', 'babarida-dive'),
        'check_in_date'     => __('Check-in Date', 'babarida-dive'),
        'check_out_date'    => __('Check-out Date', 'babarida-dive'),
        'num_guests'        => __('Number of Guests', 'babarida-dive'),
        'total_price'       => __('Total Price (USD)', 'babarida-dive'),
        'currency'          => __('Currency (USD/IDR/EUR/SGD/AUD)', 'babarida-dive'),
        'deposit_paid'      => __('Deposit Paid (USD)', 'babarida-dive'),
        'payment_method'    => __('Payment Method', 'babarida-dive'),
        'payment_status'    => __('Payment Status (unpaid/partial/paid/refunded)', 'babarida-dive'),
        'hotel_pickup'      => __('Hotel Pickup Location', 'babarida-dive'),
        'special_requests'  => __('Special Requests', 'babarida-dive'),
        'assigned_guide'    => __('Assigned Dive Guide (User ID)', 'babarida-dive'),
        'assigned_boat'     => __('Assigned Boat (Post ID)', 'babarida-dive'),
        'qr_code'           => __('QR Code Text', 'babarida-dive'),
        'notes'             => __('Internal Notes', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'booking');
}

function bbr_destination_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_destination_nonce');
    $fields = array(
        'dest_subtitle'    => __('Subtitle', 'babarida-dive'),
        'dest_distance'    => __('Distance from Manado', 'babarida-dive'),
        'dest_travel_time' => __('Travel Time', 'babarida-dive'),
        'dest_best_season' => __('Best Season', 'babarida-dive'),
        'dest_water_temp'  => __('Water Temperature', 'babarida-dive'),
        'dest_visibility'  => __('Visibility', 'babarida-dive'),
        'dest_depth'       => __('Depth Range', 'babarida-dive'),
        'dest_current'     => __('Current', 'babarida-dive'),
        'dest_marine_life' => __('Marine Life (one per line)', 'babarida-dive'),
        'dest_dive_sites'  => __('Dive Sites (one per line)', 'babarida-dive'),
        'gallery_ids'      => __('Gallery Image IDs (comma separated)', 'babarida-dive'),
        'map_lat'          => __('Map Latitude', 'babarida-dive'),
        'map_lng'          => __('Map Longitude', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'destination');
}

function bbr_watersport_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_watersport_nonce');
    $fields = array(
        'ws_destination'   => __('Destination', 'babarida-dive'),
        'ws_price_usd'     => __('Price (USD)', 'babarida-dive'),
        'ws_price_idr'     => __('Price (IDR)', 'babarida-dive'),
        'ws_duration'      => __('Duration', 'babarida-dive'),
        'ws_min_age'       => __('Min Age', 'babarida-dive'),
        'ws_includes'      => __('Includes (one per line)', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'water_sport');
}

function bbr_course_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_course_nonce');
    $fields = array(
        'course_level'     => __('Course Level', 'babarida-dive'),
        'course_org'       => __('Certification Organization (SSI/PADI)', 'babarida-dive'),
        'course_duration'  => __('Duration', 'babarida-dive'),
        'course_price_usd' => __('Price (USD)', 'babarida-dive'),
        'course_price_idr' => __('Price (IDR)', 'babarida-dive'),
        'course_prereq'    => __('Prerequisites', 'babarida-dive'),
        'course_includes'  => __('Includes (one per line)', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'dive_course');
}

function bbr_partner_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_partner_nonce');
    $fields = array(
        'partner_url'  => __('Partner URL', 'babarida-dive'),
        'partner_cat'  => __('Category', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'partner');
}

function bbr_seo_meta_box_callback($post) {
    wp_nonce_field('bbr_save_meta', 'bbr_seo_nonce');
    $fields = array(
        'seo_title'       => __('Meta Title', 'babarida-dive'),
        'seo_description' => __('Meta Description', 'babarida-dive'),
        'seo_keywords'    => __('Meta Keywords (comma separated)', 'babarida-dive'),
        'og_image'        => __('OG Image URL', 'babarida-dive'),
        'noindex'         => __('No Index (true/false)', 'babarida-dive'),
        'canonical'       => __('Canonical URL', 'babarida-dive'),
    );
    bbr_render_meta_fields($post, $fields, 'seo');
}

/* --- Render Meta Fields Helper --- */
function bbr_render_meta_fields($post, $fields, $prefix) {
    echo '<div class="bbr-meta-fields" style="display:grid;gap:12px">';
    foreach ($fields as $key => $label) {
        $meta_key = '_bbr_' . $prefix . '_' . $key;
        $value = get_post_meta($post->ID, $meta_key, true);
        $is_textarea = (false !== strpos($label, 'one per line'));
        echo '<div>';
        echo '<label style="display:block;font-weight:600;margin-bottom:4px;font-size:13px;color:#374151">' . esc_html($label) . '</label>';
        if ($is_textarea) {
            echo '<textarea name="' . esc_attr($meta_key) . '" rows="4" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="text" name="' . esc_attr($meta_key) . '" value="' . esc_attr($value) . '" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" />';
        }
        echo '</div>';
    }
    echo '</div>';
}

/* ============================================
   SAVE META BOXES
   ============================================ */
function bbr_save_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['post_type'])) return;

    $nonce_fields = array(
        'trip'        => 'bbr_trip_nonce',
        'liveaboard'  => 'bbr_liveaboard_nonce',
        'hotel'       => 'bbr_hotel_nonce',
        'testimonial' => 'bbr_testimonial_nonce',
        'booking'     => 'bbr_booking_nonce',
        'destination' => 'bbr_destination_nonce',
        'water_sport' => 'bbr_watersport_nonce',
        'dive_course' => 'bbr_course_nonce',
        'partner'     => 'bbr_partner_nonce',
        'seo'         => 'bbr_seo_nonce',
    );

    foreach ($nonce_fields as $prefix => $nonce_name) {
        if (!isset($_POST[$nonce_name])) continue;
        if (!wp_verify_nonce($_POST[$nonce_name], 'bbr_save_meta')) continue;
        if (!current_user_can('edit_post', $post_id)) continue;

        $field_map = array(
            'trip'        => array('destination','trip_type','duration','price_usd','price_idr','max_guests','min_cert','includes','excludes','itinerary','gallery_ids','availability','booking_link'),
            'liveaboard'  => array('boat_length','cabins','max_guests','crew','price_usd','price_idr','routes','amenities','specifications','gallery_ids','availability','schedule'),
            'hotel'       => array('hotel_location','hotel_stars','price_from_usd','price_from_idr','room_types','facilities','hotel_phone','hotel_email','hotel_website','hotel_map_lat','hotel_map_lng','gallery_ids'),
            'testimonial' => array('customer_name','customer_loc','rating','trip_date','trip_type'),
            'booking'     => array('booking_status','guest_name','guest_email','guest_phone','guest_nationality','guest_passport','trip_id','trip_name','check_in_date','check_out_date','num_guests','total_price','currency','deposit_paid','payment_method','payment_status','hotel_pickup','special_requests','assigned_guide','assigned_boat','qr_code','notes'),
            'destination' => array('dest_subtitle','dest_distance','dest_travel_time','dest_best_season','dest_water_temp','dest_visibility','dest_depth','dest_current','dest_marine_life','dest_dive_sites','gallery_ids','map_lat','map_lng'),
            'water_sport' => array('ws_destination','ws_price_usd','ws_price_idr','ws_duration','ws_min_age','ws_includes'),
            'dive_course' => array('course_level','course_org','course_duration','course_price_usd','course_price_idr','course_prereq','course_includes'),
            'partner'     => array('partner_url','partner_cat'),
            'seo'         => array('seo_title','seo_description','seo_keywords','og_image','noindex','canonical'),
        );

        if (!isset($field_map[$prefix])) continue;

        foreach ($field_map[$prefix] as $key) {
            $meta_key = '_bbr_' . $prefix . '_' . $key;
            if (isset($_POST[$meta_key])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field(wp_unslash($_POST[$meta_key])));
            }
        }
    }
}
add_action('save_post', 'bbr_save_meta_boxes');

/* ============================================
   HELPER: GET POST META WITH FALLBACK
   ============================================ */
function bbr_get_meta($post_id, $prefix, $key, $default = '') {
    $val = get_post_meta($post_id, '_bbr_' . $prefix . '_' . $key, true);
    return $val ? $val : $default;
}

/* ============================================
   HELPER: FORMAT PRICE
   ============================================ */
function bbr_format_price($amount, $currency = 'USD') {
    if (empty($amount) || !is_numeric($amount)) return __('Contact Us', 'babarida-dive');
    $symbols = array('USD' => '$', 'IDR' => 'Rp', 'EUR' => '€', 'SGD' => 'S$', 'AUD' => 'A$');
    $sym = isset($symbols[$currency]) ? $symbols[$currency] : $currency . ' ';
    if ($currency === 'IDR') {
        return $sym . number_format((float)$amount, 0, ',', '.');
    }
    return $sym . number_format((float)$amount, 2, '.', ',');
}

/* ============================================
   AJAX: SEARCH & FILTER
   ============================================ */
function bbr_ajax_search() {
    check_ajax_referer('bbr_nonce', 'nonce');

    $dest   = sanitize_text_field(wp_unslash($_POST['destination'] ?? ''));
    $date   = sanitize_text_field(wp_unslash($_POST['date'] ?? ''));
    $type   = sanitize_text_field(wp_unslash($_POST['type'] ?? ''));
    $cert   = sanitize_text_field(wp_unslash($_POST['certification'] ?? ''));
    $minp   = floatval($_POST['min_price'] ?? 0);
    $maxp   = floatval($_POST['max_price'] ?? 999999);

    $args = array(
        'post_type'      => array('trip', 'liveaboard', 'water_sport', 'dive_course'),
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $meta_query = array();
    if (!empty($dest)) {
        $meta_query[] = array('key' => '_bbr_trip_destination', 'value' => $dest, 'compare' => 'LIKE');
    }
    if ($minp > 0 || $maxp < 999999) {
        $meta_query[] = array(
            'key'     => '_bbr_trip_price_usd',
            'value'   => array($minp, $maxp),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    if (!empty($type)) {
        $args['tax_query'] = array(array(
            'taxonomy' => 'trip_type',
            'field'    => 'slug',
            'terms'    => $type,
        ));
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            $post_type = get_post_type();
            $price = bbr_get_meta(get_the_ID(), 'trip', 'price_usd', bbr_get_meta(get_the_ID(), 'liveaboard', 'price_usd', ''));
            echo '<div class="bbr-search-result-item" style="display:flex;gap:1rem;padding:1rem;border:1px solid #e5e7eb;border-radius:12px;margin-bottom:.75rem;align-items:center">';
            if (has_post_thumbnail()) {
                echo '<div style="width:120px;height:80px;border-radius:8px;overflow:hidden;flex-shrink:0">' . get_the_post_thumbnail(get_the_ID(), 'bbr-thumb') . '</div>';
            }
            echo '<div style="flex:1">';
            echo '<span class="bbr-badge bbr-badge-blue" style="margin-bottom:.25rem">' . esc_html(ucfirst($post_type)) . '</span>';
            echo '<h4 style="font-size:1rem;margin:.25rem 0">' . esc_html(get_the_title()) . '</h4>';
            echo '<p style="font-size:.8rem;color:#6b7280;margin:0">' . wp_trim_words(get_the_excerpt(), 15) . '</p>';
            echo '</div>';
            echo '<div style="text-align:right;flex-shrink:0">';
            if ($price) echo '<div style="font-weight:700;color:#023E8A;font-family:var(--font-display);font-size:1.1rem">' . bbr_format_price($price) . '</div>';
            echo '<a href="' . get_permalink() . '" class="bbr-btn bbr-btn-primary" style="padding:.4rem 1rem;font-size:.75rem;margin-top:.35rem">View</a>';
            echo '</div></div>';
        }
        wp_reset_postdata();
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_success('<p style="text-align:center;color:#6b7280;padding:2rem">' . esc_html__('No results found.', 'babarida-dive') . '</p>');
    }
}
add_action('wp_ajax_bbr_search', 'bbr_ajax_search');
add_action('wp_ajax_nopriv_bbr_search', 'bbr_ajax_search');

/* ============================================
   AJAX: BOOKING SUBMISSION
   ============================================ */
function bbr_ajax_booking() {
    check_ajax_referer('bbr_nonce', 'nonce');

    $data = array_map('sanitize_text_field', wp_unslash($_POST['form'] ?? array()));

    if (empty($data['guest_name']) || empty($data['guest_email'])) {
        wp_send_json_error(array('message' => __('Please fill in required fields.', 'babarida-dive')));
    }

    $post_id = wp_insert_post(array(
        'post_title'  => sprintf(__('Booking: %s — %s', 'babarida-dive'), $data['guest_name'], $data['trip_name'] ?? 'N/A'),
        'post_type'   => 'booking',
        'post_status' => 'publish',
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => __('Booking failed. Please try again.', 'babarida-dive')));
    }

    // Save all booking meta
    $booking_fields = array('booking_status','guest_name','guest_email','guest_phone','guest_nationality','guest_passport','trip_id','trip_name','check_in_date','check_out_date','num_guests','total_price','currency','deposit_paid','payment_method','payment_status','hotel_pickup','special_requests','notes');
    foreach ($booking_fields as $key) {
        if (isset($data[$key])) {
            update_post_meta($post_id, '_bbr_booking_' . $key, $data[$key]);
        }
    }

    // Generate QR code text
    $qr_text = 'BBR-' . strtoupper(substr(md5($post_id . time()), 0, 8));
    update_post_meta($post_id, '_bbr_booking_qr_code', $qr_text);
    update_post_meta($post_id, '_bbr_booking_booking_status', 'pending');

    // Send notification email
    $to = BBR_EMAIL;
    $subject = sprintf(__('New Booking: %s', 'babarida-dive'), $data['guest_name']);
    $body = sprintf(
        __("New booking received:\n\nName: %s\nEmail: %s\nPhone: %s\nTrip: %s\nDate: %s\nGuests: %s\n\nBooking ID: %s", 'babarida-dive'),
        $data['guest_name'],
        $data['guest_email'],
        $data['guest_phone'] ?? 'N/A',
        $data['trip_name'] ?? 'N/A',
        $data['check_in_date'] ?? 'N/A',
        $data['num_guests'] ?? '1',
        $qr_text
    );
    wp_mail($to, $subject, $body);

    // Confirmation to guest
    if (!empty($data['guest_email'])) {
        wp_mail($data['guest_email'], __('Booking Received — Babarida Dive Center', 'babarida-dive'),
            sprintf(__("Thank you %s!\n\nYour booking has been received.\nBooking ID: %s\nWe will confirm your booking shortly.\n\nBabarida Dive Center", 'babarida-dive'), $data['guest_name'], $qr_text)
        );
    }

    wp_send_json_success(array(
        'message'   => __('Booking submitted successfully!', 'babarida-dive'),
        'booking_id'=> $qr_text,
    ));
}
add_action('wp_ajax_bbr_booking', 'bbr_ajax_booking');
add_action('wp_ajax_nopriv_bbr_booking', 'bbr_ajax_booking');

/* ============================================
   AJAX: CONTACT FORM
   ============================================ */
function bbr_ajax_contact() {
    check_ajax_referer('bbr_nonce', 'nonce');

    $name    = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => __('Please fill in all required fields.', 'babarida-dive')));
    }

    $sent = wp_mail(BBR_EMAIL, "Contact: $subject — $name", "From: $name <$email>\n\n$message");

    if ($sent) {
        wp_send_json_success(array('message' => __('Message sent successfully!', 'babarida-dive')));
    } else {
        wp_send_json_error(array('message' => __('Failed to send message.', 'babarida-dive')));
    }
}
add_action('wp_ajax_bbr_contact', 'bbr_ajax_contact');
add_action('wp_ajax_nopriv_bbr_contact', 'bbr_ajax_contact');

/* ============================================
   AJAX: CURRENCY SWITCH
   ============================================ */
function bbr_ajax_currency() {
    check_ajax_referer('bbr_nonce', 'nonce');
    $currency = sanitize_text_field(wp_unslash($_POST['currency'] ?? 'USD'));
    setcookie('bbr_currency', $currency, time() + (86400 * 30), '/', '', is_ssl(), true);
    wp_send_json_success(array('currency' => $currency));
}
add_action('wp_ajax_bbr_currency', 'bbr_ajax_currency');
add_action('wp_ajax_nopriv_bbr_currency', 'bbr_ajax_currency');

function bbr_get_current_currency() {
    return isset($_COOKIE['bbr_currency']) ? sanitize_text_field($_COOKIE['bbr_currency']) : 'USD';
}

/* ============================================
   DASHBOARD AJAX: GET STATS
   ============================================ */
function bbr_ajax_dashboard_stats() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!current_user_can('read')) wp_send_json_error('Unauthorized');

    $today = date('Y-m-d');
    $this_month = date('Y-m-01');

    $total_bookings = wp_count_posts('booking')->publish ?? 0;

    $pending = new WP_Query(array('post_type'=>'booking','meta_key'=>'_bbr_booking_booking_status','meta_value'=>'pending','posts_per_page'=>-1,'fields'=>'ids'));
    $confirmed = new WP_Query(array('post_type'=>'booking','meta_key'=>'_bbr_booking_booking_status','meta_value'=>'confirmed','posts_per_page'=>-1,'fields'=>'ids'));
    $paid = new WP_Query(array('post_type'=>'booking','meta_key'=>'_bbr_booking_booking_status','meta_value'=>'paid','posts_per_page'=>-1,'fields'=>'ids'));
    $completed = new WP_Query(array('post_type'=>'booking','meta_key'=>'_bbr_booking_booking_status','meta_value'=>'completed','posts_per_page'=>-1,'fields'=>'ids'));

    // Revenue from paid bookings
    $revenue = 0;
    if ($paid->posts) {
        foreach ($paid->posts as $pid) {
            $price = get_post_meta($pid, '_bbr_booking_total_price', true);
            $revenue += floatval($price);
        }
    }
    if ($completed->posts) {
        foreach ($completed->posts as $pid) {
            $price = get_post_meta($pid, '_bbr_booking_total_price', true);
            $revenue += floatval($price);
        }
    }

    wp_send_json_success(array(
        'total_bookings' => $total_bookings,
        'pending'        => $pending->found_posts,
        'confirmed'      => $confirmed->found_posts,
        'paid'           => $paid->found_posts,
        'completed'      => $completed->found_posts,
        'revenue'        => $revenue,
    ));
}
add_action('wp_ajax_bbr_dashboard_stats', 'bbr_ajax_dashboard_stats');

/* ============================================
   DASHBOARD AJAX: GET BOOKINGS TABLE
   ============================================ */
function bbr_ajax_bookings_table() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!current_user_can('read')) wp_send_json_error('Unauthorized');

    $status = sanitize_text_field(wp_unslash($_GET['status'] ?? ''));
    $paged  = absint($_GET['paged'] ?? 1);
    $per_page = 20;

    $args = array(
        'post_type'      => 'booking',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if (!empty($status) && $status !== 'all') {
        $args['meta_query'] = array(array('key'=>'_bbr_booking_booking_status','value'=>$status));
    }

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $guest = get_post_meta($id, '_bbr_booking_guest_name', true);
            $email = get_post_meta($id, '_bbr_booking_guest_email', true);
            $trip  = get_post_meta($id, '_bbr_booking_trip_name', true);
            $date  = get_post_meta($id, '_bbr_booking_check_in_date', true);
            $price = get_post_meta($id, '_bbr_booking_total_price', true);
            $st    = get_post_meta($id, '_bbr_booking_booking_status', true);
            $qr    = get_post_meta($id, '_bbr_booking_qr_code', true);

            echo '<tr>';
            echo '<td><strong>' . esc_html($qr ?: '#' . $id) . '</strong></td>';
            echo '<td>' . esc_html($guest) . '<br><small style="color:#9ca3af">' . esc_html($email) . '</small></td>';
            echo '<td>' . esc_html($trip) . '</td>';
            echo '<td>' . esc_html($date) . '</td>';
            echo '<td>' . ($price ? bbr_format_price($price) : '—') . '</td>';
            echo '<td><span class="bbr-status ' . esc_attr($st) . '">' . esc_html(ucfirst(str_replace('-', ' ', $st))) . '</span></td>';
            echo '<td>';
            echo '<button onclick="bbrChangeStatus(' . $id . ')" class="bbr-btn bbr-btn-primary" style="padding:.3rem .7rem;font-size:.7rem">Update</button> ';
            echo '<a href="' . admin_url('post.php?post=' . $id . '&action=edit') . '" class="bbr-btn bbr-btn-outline" style="padding:.3rem .7rem;font-size:.7rem">Edit</a>';
            echo '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af">No bookings found.</td></tr>';
    }

    wp_reset_postdata();
    wp_send_json_success(array('html' => ob_get_clean(), 'total' => $query->found_posts, 'pages' => $query->max_num_pages));
}
add_action('wp_ajax_bbr_bookings_table', 'bbr_ajax_bookings_table');

/* ============================================
   DASHBOARD AJAX: CHANGE BOOKING STATUS
   ============================================ */
function bbr_ajax_change_status() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error('Unauthorized');

    $id     = absint($_POST['booking_id'] ?? 0);
    $status = sanitize_text_field(wp_unslash($_POST['status'] ?? ''));

    if (!$id || !$status) wp_send_json_error('Invalid data');

    $allowed = array('pending','confirmed','paid','checked-in','completed','cancelled');
    if (!in_array($status, $allowed)) wp_send_json_error('Invalid status');

    update_post_meta($id, '_bbr_booking_booking_status', $status);
    wp_send_json_success(array('message' => 'Status updated.'));
}
add_action('wp_ajax_bbr_change_status', 'bbr_ajax_change_status');

/* ============================================
   AJAX: NEWSLETTER SUBSCRIBE
   ============================================ */
function bbr_ajax_newsletter() {
    check_ajax_referer('bbr_nonce', 'nonce');
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    if (!is_email($email)) wp_send_json_error(__('Invalid email.', 'babarida-dive'));

    $subs = get_option('bbr_newsletter_subscribers', array());
    if (in_array($email, $subs)) wp_send_json_error(__('Already subscribed.', 'babarida-dive'));

    $subs[] = $email;
    update_option('bbr_newsletter_subscribers', $subs);
    wp_send_json_success(__('Subscribed successfully!', '
