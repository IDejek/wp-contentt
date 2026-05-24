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
   wp_send_json_success(__('Subscribed successfully!', 'babarida-dive'));
}
add_action('wp_ajax_bbr_newsletter', 'bbr_ajax_newsletter');
add_action('wp_ajax_nopriv_bbr_newsletter', 'bbr_ajax_newsletter');

/* ============================================
   AJAX: AVAILABILITY CHECK
   ============================================ */
function bbr_ajax_availability() {
    check_ajax_referer('bbr_nonce', 'nonce');

    $trip_id  = absint($_POST['trip_id'] ?? 0);
    $date     = sanitize_text_field(wp_unslash($_POST['date'] ?? ''));
    $guests   = absint($_POST['guests'] ?? 1);

    if (!$trip_id) wp_send_json_error('Invalid trip');

    $max_guests  = absint(get_post_meta($trip_id, '_bbr_trip_max_guests', true));
    $availability = get_post_meta($trip_id, '_bbr_trip_availability', true);

    // Count existing bookings for this trip and date
    $existing = new WP_Query(array(
        'post_type'      => 'booking',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => '_bbr_booking_trip_id', 'value' => $trip_id),
            array('key' => '_bbr_booking_check_in_date', 'value' => $date),
            array('key' => '_bbr_booking_booking_status', 'value' => array('pending','confirmed','paid','checked-in'), 'compare' => 'IN'),
        ),
    ));

    $booked = 0;
    foreach ($existing->posts as $bid) {
        $booked += absint(get_post_meta($bid, '_bbr_booking_num_guests', true)) ?: 1;
    }

    $slots = $max_guests - $booked;

    if ($slots >= $guests) {
        wp_send_json_success(array('available' => true, 'slots' => $slots));
    } else {
        wp_send_json_success(array('available' => false, 'slots' => max(0, $slots)));
    }
}
add_action('wp_ajax_bbr_availability', 'bbr_ajax_availability');
add_action('wp_ajax_nopriv_bbr_availability', 'bbr_ajax_availability');

/* ============================================
   DYNAMIC PRICING ENGINE
   ============================================ */
function bbr_get_dynamic_price($trip_id, $date = '', $currency = 'USD') {
    $base_usd = floatval(get_post_meta($trip_id, '_bbr_trip_price_usd', true));
    $base_idr = floatval(get_post_meta($trip_id, '_bbr_trip_price_idr', true));

    if (empty($date)) $date = date('Y-m-d');

    $month = date('n', strtotime($date));
    $day   = date('N', strtotime($date));

    // Seasonal multipliers
    $seasons = get_option('bbr_seasonal_pricing', array());
    $multiplier = 1.0;

    if (!empty($seasons) && is_array($seasons)) {
        foreach ($seasons as $s) {
            if (!isset($s['start_month'], $s['end_month'], $s['multiplier'])) continue;
            $sm = absint($s['start_month']);
            $em = absint($s['end_month']);
            if ($sm <= $em) {
                if ($month >= $sm && $month <= $em) {
                    $multiplier = floatval($s['multiplier']);
                    break;
                }
            } else {
                // Wraps around year end
                if ($month >= $sm || $month <= $em) {
                    $multiplier = floatval($s['multiplier']);
                    break;
                }
            }
        }
    }

    // Weekend multiplier
    $weekend_mult = floatval(get_option('bbr_weekend_multiplier', 1.0));
    if (in_array($day, array(6, 7))) {
        $multiplier *= $weekend_mult;
    }

    $final_usd = round($base_usd * $multiplier, 2);
    $final_idr = round($base_idr * $multiplier);

    $rates = array(
        'USD' => $final_usd,
        'IDR' => $final_idr,
        'EUR' => round($final_usd * 0.92, 2),
        'SGD' => round($final_usd * 1.34, 2),
        'AUD' => round($final_usd * 1.53, 2),
    );

    return isset($rates[$currency]) ? $rates[$currency] : $final_usd;
}

/* ============================================
   SCHEMA MARKUP
   ============================================ */
function bbr_schema_output() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(),
    );

    // Organization
    $schema['@graph'][] = array(
        '@type'              => 'Organization',
        '@id'                => home_url('/#organization'),
        'name'               => get_bloginfo('name'),
        'url'                => home_url('/'),
        'logo'               => array('@type' => 'ImageObject', 'url' => BBR_URI . '/assets/images/logo.png'),
        'description'        => get_bloginfo('description'),
        'email'              => BBR_EMAIL,
        'telephone'          => '+62895801960359',
        'sameAs'             => array_filter(array(
            get_option('bbr_social_instagram', ''),
            get_option('bbr_social_facebook', ''),
            get_option('bbr_social_youtube', ''),
            get_option('bbr_social_tiktok', ''),
        )),
        'address'            => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Bunaken',
            'addressLocality' => 'Manado',
            'addressRegion'   => 'North Sulawesi',
            'addressCountry'  => 'ID',
        ),
    );

    // WebSite
    $schema['@graph'][] = array(
        '@type'       => 'WebSite',
        '@id'         => home_url('/#website'),
        'url'         => home_url('/'),
        'name'        => get_bloginfo('name'),
        'publisher'   => array('@id' => home_url('/#organization')),
        'inLanguage'  => get_locale(),
    );

    // LocalBusiness (DiveCenter)
    $schema['@graph'][] = array(
        '@type'              => 'DiveCenter',
        '@id'                => home_url('/#divecenter'),
        'name'               => get_bloginfo('name'),
        'url'                => home_url('/'),
        'telephone'          => '+62895801960359',
        'email'              => BBR_EMAIL,
        'address'            => array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => 'Bunaken Island',
            'addressLocality' => 'Manado',
            'addressRegion'   => 'North Sulawesi',
            'postalCode'      => '95122',
            'addressCountry'  => 'ID',
        ),
        'geo'                => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => '1.6231',
            'longitude' => '124.7636',
        ),
        'openingHoursSpecification' => array(
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
            'opens'     => '07:00',
            'closes'    => '18:00',
        ),
        'priceRange'         => '$$',
        'image'              => BBR_URI . '/assets/images/og-default.jpg',
    );

    // Single post/page schema
    if (is_singular(array('post', 'trip', 'liveaboard', 'destination', 'hotel', 'water_sport', 'dive_course'))) {
        global $post;
        $seo_title = get_post_meta($post->ID, '_bbr_seo_seo_title', true);
        $seo_desc  = get_post_meta($post->ID, '_bbr_seo_seo_description', true);
        $og_image  = get_post_meta($post->ID, '_bbr_seo_og_image', true);

        if (!$og_image && has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url($post->ID, 'large');
        }

        $item = array(
            '@type'            => is_singular('post') ? 'Article' : 'TouristTrip',
            '@id'              => get_permalink() . '#page',
            'isPartOf'         => array('@id' => home_url('/#website')),
            'author'           => array('@id' => home_url('/#organization')),
            'headline'         => $seo_title ?: get_the_title(),
            'description'      => $seo_desc ?: wp_trim_words(get_the_excerpt(), 30),
            'datePublished'    => get_the_date('c'),
            'dateModified'     => get_the_modified_date('c'),
            'mainEntityOfPage' => array('@type' => 'WebPage', '@id' => get_permalink()),
        );
        if ($og_image) $item['image'] = $og_image;
        $schema['@graph'][] = $item;
    }

    // Breadcrumb
    if (!is_front_page()) {
        $crumbs = array(
            array('name' => __('Home', 'babarida-dive'), 'item' => home_url('/')),
        );
        if (is_singular()) {
            if (is_singular('trip')) $crumbs[] = array('name' => __('Trips', 'babarida-dive'), 'item' => get_post_type_archive_link('trip'));
            if (is_singular('liveaboard')) $crumbs[] = array('name' => __('Liveaboards', 'babarida-dive'), 'item' => get_post_type_archive_link('liveaboard'));
            $crumbs[] = array('name' => get_the_title(), 'item' => get_permalink());
        } elseif (is_post_type_archive()) {
            $crumbs[] = array('name' => post_type_archive_title('', false), 'item' => get_post_type_archive_link(get_post_type()));
        }
        $schema['@graph'][] = array(
            '@type'           => 'BreadcrumbList',
            '@id'             => home_url('/#breadcrumb'),
            'itemListElement' => array_map(function($c, $i) {
                return array(
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'name'     => $c['name'],
                    'item'     => $c['item'],
                );
            }, $crumbs, array_keys($crumbs)),
        );
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'bbr_schema_output');

/* ============================================
   DYNAMIC SEO HEAD TAGS
   ============================================ */
function bbr_dynamic_seo_head() {
    if (is_singular()) {
        global $post;
        $seo_title = get_post_meta($post->ID, '_bbr_seo_seo_title', true);
        $seo_desc  = get_post_meta($post->ID, '_bbr_seo_seo_description', true);
        $seo_kw    = get_post_meta($post->ID, '_bbr_seo_seo_keywords', true);
        $og_img    = get_post_meta($post->ID, '_bbr_seo_og_image', true);
        $noindex   = get_post_meta($post->ID, '_bbr_seo_noindex', true);
        $canonical = get_post_meta($post->ID, '_bbr_seo_canonical', true);

        if ($noindex === 'true') {
            echo '<meta name="robots" content="noindex,nofollow">' . "\n";
        }

        if (!empty($canonical)) {
            echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
        }

        if (!empty($seo_title)) {
            echo '<meta name="title" content="' . esc_attr($seo_title) . '">' . "\n";
        }
        if (!empty($seo_desc)) {
            echo '<meta name="description" content="' . esc_attr($seo_desc) . '">' . "\n";
        }
        if (!empty($seo_kw)) {
            echo '<meta name="keywords" content="' . esc_attr($seo_kw) . '">' . "\n";
        }

        // Open Graph
        $og_title = $seo_title ?: get_the_title();
        $og_desc  = $seo_desc ?: wp_trim_words(get_the_excerpt(), 25);
        if (!$og_img && has_post_thumbnail()) {
            $og_img = get_the_post_thumbnail_url($post->ID, 'large');
        }
        if (!$og_img) {
            $og_img = BBR_URI . '/assets/images/og-default.jpg';
        }

        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_desc) . '">' . "\n";
        echo '<meta property="og:image" content="' . esc_url($og_img) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";

        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($og_desc) . '">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_img) . '">' . "\n";
    }
}
add_action('wp_head', 'bbr_dynamic_seo_head', 5);

/* ============================================
   CUSTOM LOGIN PAGE
   ============================================ */
function bbr_custom_login() {
    if (isset($_GET['bbr-login']) || (isset($_GET['action']) && $_GET['action'] === 'bbr_login')) {
        include BBR_DIR . '/template-custom-login.php';
        exit;
    }
}
add_action('init', 'bbr_custom_login');

function bbr_login_url($url, $redirect, $force_reauth) {
    return home_url('/?bbr-login=1' . ($redirect ? '&redirect_to=' . urlencode($redirect) : ''));
}
add_filter('login_url', 'bbr_login_url', 10, 3);

/* ============================================
   SECURITY: LIMIT LOGIN ATTEMPTS
   ============================================ */
function bbr_check_login_attempts($user, $username, $password) {
    if (empty($username)) return $user;

    $attempts = get_transient('bbr_login_attempts_' . md5($username));
    if ($attempts !== false && (int)$attempts >= 5) {
        $blocked_until = get_transient('bbr_login_blocked_' . md5($username));
        if ($blocked_until !== false) {
            return new WP_Error('too_many_attempts', sprintf(__('Too many login attempts. Please try again in %s minutes.', 'babarida-dive'), ceil(($blocked_until - time()) / 60)));
        }
    }
    return $user;
}
add_filter('authenticate', 'bbr_check_login_attempts', 30, 3);

function bbr_track_login_failure($username) {
    if (empty($username)) return;
    $key = md5($username);
    $attempts = (int)get_transient('bbr_login_attempts_' . $key);
    $attempts++;
    set_transient('bbr_login_attempts_' . $key, $attempts, 900); // 15 min window

    if ($attempts >= 5) {
        set_transient('bbr_login_blocked_' . $key, time() + 1800, 1800); // Block 30 min
    }
}
add_action('wp_login_failed', 'bbr_track_login_failure');

function bbr_clear_login_attempts($user_login) {
    delete_transient('bbr_login_attempts_' . md5($user_login));
    delete_transient('bbr_login_blocked_' . md5($user_login));
}
add_action('wp_login', 'bbr_clear_login_attempts');

/* ============================================
   ACTIVITY LOG
   ============================================ */
function bbr_log_activity($action, $details = '') {
    $logs = get_option('bbr_activity_logs', array());
    array_unshift($logs, array(
        'time'     => current_time('mysql'),
        'user'     => get_current_user_id() ?: 0,
        'user_name'=> wp_get_current_user()->display_name ?: 'System',
        'action'   => $action,
        'details'  => $details,
        'ip'       => $_SERVER['REMOTE_ADDR'] ?? '',
    ));
    // Keep last 500 entries
    if (count($logs) > 500) $logs = array_slice($logs, 0, 500);
    update_option('bbr_activity_logs', $logs);
}

function bbr_log_post_changes($post_id, $post_after, $post_before) {
    $changed = array();
    $fields = array('post_title', 'post_status', 'post_content');
    foreach ($fields as $f) {
        if ($post_after->$f !== $post_before->$f) {
            $changed[] = $f;
        }
    }
    if (!empty($changed)) {
        bbr_log_activity('post_updated', sprintf('Post #%d (%s) — Changed: %s', $post_id, $post_after->post_type, implode(', ', $changed)));
    }
}
add_action('post_updated', 'bbr_log_post_changes', 10, 3);

function bbr_log_login_activity($user_login) {
    bbr_log_activity('login', 'User logged in: ' . $user_login);
}
add_action('wp_login', 'bbr_log_login_activity');

/* ============================================
   CUSTOMIZER SETTINGS
   ============================================ */
function bbr_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('bbr_hero', array('title' => __('Hero Section', 'babarida-dive'), 'priority' => 30));
    $wp_customize->add_setting('bbr_hero_video', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'bbr_hero_video', array('label' => __('Hero Video/BG Image', 'babarida-dive'), 'section' => 'bbr_hero')));
    $wp_customize->add_setting('bbr_hero_title', array('default' => 'Babarida Dive Center', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_hero_title', array('label' => __('Hero Title', 'babarida-dive'), 'section' => 'bbr_hero', 'type' => 'text'));
    $wp_customize->add_setting('bbr_hero_slogan', array('default' => 'The quality of your dive adventure depends on who guides you!', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_hero_slogan', array('label' => __('Hero Slogan', 'babarida-dive'), 'section' => 'bbr_hero', 'type' => 'text'));

    // Contact Info
    $wp_customize->add_section('bbr_contact', array('title' => __('Contact Information', 'babarida-dive'), 'priority' => 31));
    $wp_customize->add_setting('bbr_whatsapp', array('default' => BBR_WHATSAPP, 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_whatsapp', array('label' => __('WhatsApp Number', 'babarida-dive'), 'section' => 'bbr_contact'));
    $wp_customize->add_setting('bbr_email', array('default' => BBR_EMAIL, 'sanitize_callback' => 'sanitize_email'));
    $wp_customize->add_control('bbr_email', array('label' => __('Email Address', 'babarida-dive'), 'section' => 'bbr_contact'));
    $wp_customize->add_setting('bbr_phone', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_phone', array('label' => __('Phone Number', 'babarida-dive'), 'section' => 'bbr_contact'));

    // Social Media
    $wp_customize->add_section('bbr_social', array('title' => __('Social Media', 'babarida-dive'), 'priority' => 32));
    $socials = array('instagram', 'facebook', 'youtube', 'tiktok', 'tripadvisor');
    foreach ($socials as $s) {
        $wp_customize->add_setting('bbr_social_' . $s, array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
        $wp_customize->add_control('bbr_social_' . $s, array('label' => ucfirst($s) . ' URL', 'section' => 'bbr_social', 'type' => 'url'));
    }

    // SEO
    $wp_customize->add_section('bbr_seo', array('title' => __('SEO Settings', 'babarida-dive'), 'priority' => 33));
    $wp_customize->add_setting('bbr_ga_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_ga_id', array('label' => __('Google Analytics 4 ID', 'babarida-dive'), 'section' => 'bbr_seo'));
    $wp_customize->add_setting('bbr_gtm_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_gtm_id', array('label' => __('Google Tag Manager ID', 'babarida-dive'), 'section' => 'bbr_seo'));
    $wp_customize->add_setting('bbr_gsc_verify', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_gsc_verify', array('label' => __('Google Search Console Verification', 'babarida-dive'), 'section' => 'bbr_seo'));
    $wp_customize->add_setting('bbr_bing_verify', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_bing_verify', array('label' => __('Bing Webmaster Verification', 'babarida-dive'), 'section' => 'bbr_seo'));

    // Payment Settings
    $wp_customize->add_section('bbr_payment', array('title' => __('Payment Settings', 'babarida-dive'), 'priority' => 34));
    $wp_customize->add_setting('bbr_midtrans_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_midtrans_key', array('label' => __('Midtrans Server Key', 'babarida-dive'), 'section' => 'bbr_payment', 'type' => 'password'));
    $wp_customize->add_setting('bbr_xendit_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_xendit_key', array('label' => __('Xendit Secret Key', 'babarida-dive'), 'section' => 'bbr_payment', 'type' => 'password'));
    $wp_customize->add_setting('bbr_stripe_key', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_stripe_key', array('label' => __('Stripe Secret Key', 'babarida-dive'), 'section' => 'bbr_payment', 'type' => 'password'));
    $wp_customize->add_setting('bbr_stripe_pub', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_stripe_pub', array('label' => __('Stripe Publishable Key', 'babarida-dive'), 'section' => 'bbr_payment'));
    $wp_customize->add_setting('bbr_paypal_client', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_paypal_client', array('label' => __('PayPal Client ID', 'babarida-dive'), 'section' => 'bbr_payment'));
    $wp_customize->add_setting('bbr_bank_accounts', array('default' => '', 'sanitize_callback' => 'wp_kses_post'));
    $wp_customize->add_control('bbr_bank_accounts', array('label' => __('Bank Transfer Details (HTML)', 'babarida-dive'), 'section' => 'bbr_payment', 'type' => 'textarea'));

    // Weather API
    $wp_customize->add_section('bbr_weather', array('title' => __('Weather API', 'babarida-dive'), 'priority' => 35));
    $wp_customize->add_setting('bbr_weather_api', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_weather_api', array('label' => __('OpenWeatherMap API Key', 'babarida-dive'), 'section' => 'bbr_weather'));
    $wp_customize->add_setting('bbr_weather_lat', array('default' => '1.4748', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_weather_lat', array('label' => __('Latitude', 'babarida-dive'), 'section' => 'bbr_weather'));
    $wp_customize->add_setting('bbr_weather_lng', array('default' => '124.8421', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('bbr_weather_lng', array('label' => __('Longitude', 'babarida-dive'), 'section' => 'bbr_weather'));
}
add_action('customize_register', 'bbr_customize_register');

/* ============================================
   GOOGLE ANALYTICS / GTM / GSC
   ============================================ */
function bbr_tracking_scripts() {
    // Google Tag Manager
    $gtm = get_option('bbr_gtm_id', get_theme_mod('bbr_gtm_id', ''));
    if ($gtm) {
        echo "<!-- Google Tag Manager -->\n";
        echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . esc_js($gtm) . "');</script>\n";
        echo "<!-- End Google Tag Manager -->\n";
    }

    // GA4
    $ga = get_option('bbr_ga_id', get_theme_mod('bbr_ga_id', ''));
    if ($ga && !$gtm) {
        echo "<!-- Google Analytics 4 -->\n";
        echo "<script async src='https://www.googletagmanager.com/gtag/js?id=" . esc_js($ga) . "'></script>\n";
        echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js($ga) . "');</script>\n";
        echo "<!-- End GA4 -->\n";
    }

    // GSC Verification
    $gsc = get_option('bbr_gsc_verify', get_theme_mod('bbr_gsc_verify', ''));
    if ($gsc) {
        echo '<meta name="google-site-verification" content="' . esc_attr($gsc) . '">' . "\n";
    }

    // Bing Verification
    $bing = get_option('bbr_bing_verify', get_theme_mod('bbr_bing_verify', ''));
    if ($bing) {
        echo '<meta name="msvalidate.01" content="' . esc_attr($bing) . '">' . "\n";
    }
}
add_action('wp_head', 'bbr_tracking_scripts', 1);

/* ============================================
   XML SITEMAP
   ============================================ */
function bbr_generate_sitemap() {
    if (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        echo '<url><loc>' . home_url('/') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";

        // Pages
        $pages = get_posts(array('post_type'=>'page','posts_per_page'=>-1,'post_status'=>'publish'));
        foreach ($pages as $p) {
            echo '<url><loc>' . get_permalink($p->ID) . '</loc><lastmod>' . get_the_modified_date('Y-m-d', $p->ID) . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        }

        // CPTs
        $cpts = array('destination','trip','liveaboard','hotel','water_sport','dive_course');
        foreach ($cpts as $cpt) {
            $posts = get_posts(array('post_type'=>$cpt,'posts_per_page'=>-1,'post_status'=>'publish'));
            foreach ($posts as $p) {
                $noindex = get_post_meta($p->ID, '_bbr_seo_noindex', true);
                if ($noindex === 'true') continue;
                echo '<url><loc>' . get_permalink($p->ID) . '</loc><lastmod>' . get_the_modified_date('Y-m-d', $p->ID) . '</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>' . "\n";
            }
        }

        // Blog posts
        $blog_posts = get_posts(array('post_type'=>'post','posts_per_page'=>-1,'post_status'=>'publish'));
        foreach ($blog_posts as $p) {
            echo '<url><loc>' . get_permalink($p->ID) . '</loc><lastmod>' . get_the_modified_date('Y-m-d', $p->ID) . '</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>' . "\n";
        }

        echo '</urlset>';
        exit;
    }
}
add_action('init', 'bbr_generate_sitemap');

/* ============================================
   ROBOTS.TXT REWRITE
   ============================================ */
function bbr_robots_txt($output) {
    $output  = "User-agent: *\n";
    $output .= "Allow: /\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /?s=\n";
    $output .= "Sitemap: " . home_url('/?sitemap=xml') . "\n";
    return $output;
}
add_filter('robots_txt', 'bbr_robots_txt');

/* ============================================
   WEATHER API WIDGET DATA
   ============================================ */
function bbr_get_weather_data() {
    $api_key = get_theme_mod('bbr_weather_api', '');
    $lat     = get_theme_mod('bbr_weather_lat', '1.4748');
    $lng     = get_theme_mod('bbr_weather_lng', '124.8421');

    if (empty($api_key)) return false;

    $cache_key = 'bbr_weather_' . md5($lat . $lng);
    $cached = get_transient($cache_key);
    if ($cached !== false) return $cached;

    $url = 'https://api.openweathermap.org/data/2.5/weather?lat=' . $lat . '&lon=' . $lng . '&appid=' . $api_key . '&units=metric';
    $response = wp_remote_get($url, array('timeout' => 10));

    if (is_wp_error($response)) return false;

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['cod']) && $data['cod'] !== 200) return false;

    set_transient($cache_key, $data, 1800); // Cache 30 min
    return $data;
}

/* ============================================
   PWA SUPPORT
   ============================================ */
function bbr_pwa_manifest() {
    $manifest = array(
        'name'             => get_bloginfo('name'),
        'short_name'       => 'Babarida',
        'description'      => get_bloginfo('description'),
        'start_url'        => home_url('/'),
        'display'          => 'standalone',
        'background_color' => '#03045E',
        'theme_color'      => '#0077B6',
        'orientation'      => 'portrait-primary',
        'icons'            => array(
            array('src' => BBR_URI . '/assets/images/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'),
            array('src' => BBR_URI . '/assets/images/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png'),
        ),
    );
    echo '<link rel="manifest" href="' . esc_url(home_url('/?manifest=1')) . '">' . "\n";
    echo '<meta name="theme-color" content="#0077B6">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
}
add_action('wp_head', 'bbr_pwa_manifest');

function bbr_pwa_manifest_output() {
    if (isset($_GET['manifest'])) {
        header('Content-Type: application/json');
        $manifest = array(
            'name'             => get_bloginfo('name'),
            'short_name'       => 'Babarida',
            'description'      => get_bloginfo('description'),
            'start_url'        => home_url('/'),
            'display'          => 'standalone',
            'background_color' => '#03045E',
            'theme_color'      => '#0077B6',
            'icons'            => array(
                array('src' => BBR_URI . '/assets/images/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'),
                array('src' => BBR_URI . '/assets/images/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png'),
            ),
        );
        echo wp_json_encode($manifest);
        exit;
    }
}
add_action('init', 'bbr_pwa_manifest_output');

/* ============================================
   SERVICE WORKER
   ============================================ */
function bbr_service_worker() {
    if (isset($_GET['sw'])) {
        header('Content-Type: application/javascript');
        $cache_name = 'bbr-v' . BBR_VERSION;
        echo "const CACHE_NAME='" . $cache_name . "';\n";
        echo "const URLS_TO_CACHE=[\n";
        echo "  '" . home_url('/') . "',\n";
        echo "  '" . get_stylesheet_uri() . "',\n";
        echo "  '" . BBR_URI . "/js/main.js',\n";
        echo "];\n";
        echo "self.addEventListener('install',e=>{e.waitUntil(caches.open(CACHE_NAME).then(c=>c.addAll(URLS_TO_CACHE)))});\n";
        echo "self.addEventListener('fetch',e=>{e.respondWith(caches.match(e.request).then(r=>r||fetch(e.request)))});\n";
        echo "self.addEventListener('activate',e=>{e.waitUntil(caches.keys().then(keys=>Promise.all(keys.filter(k=>k!==CACHE_NAME).map(k=>caches.delete(k))))});\n";
        exit;
    }
}
add_action('init', 'bbr_service_worker');

function bbr_sw_register() {
    echo '<script>if("serviceWorker" in navigator){navigator.serviceWorker.register("' . esc_url(home_url('/?sw=1')) . '");}</script>' . "\n";
}
add_action('wp_footer', 'bbr_sw_register');

/* ============================================
   LOYALTY / MEMBERSHIP SYSTEM
   ============================================ */
function bbr_get_member_points($user_id) {
    return absint(get_user_meta($user_id, '_bbr_loyalty_points', true));
}

function bbr_add_member_points($user_id, $points, $reason = '') {
    $current = bbr_get_member_points($user_id);
    $new = $current + absint($points);
    update_user_meta($user_id, '_bbr_loyalty_points', $new);
    bbr_log_activity('loyalty_points', "User #$user_id: +$points points ($reason). Total: $new");
}

function bbr_get_member_level($user_id) {
    $points = bbr_get_member_points($user_id);
    if ($points >= 1000) return array('name' => __('Platinum Diver', 'babarida-dive'), 'discount' => 15, 'color' => '#E5E4E2');
    if ($points >= 500)  return array('name' => __('Gold Diver', 'babarida-dive'), 'discount' => 10, 'color' => '#FFD700');
    if ($points >= 200)  return array('name' => __('Silver Diver', 'babarida-dive'), 'discount' => 7, 'color' => '#C0C0C0');
    if ($points >= 50)   return array('name' => __('Bronze Diver', 'babarida-dive'), 'discount' => 3, 'color' => '#CD7F32');
    return array('name' => __('Member', 'babarida-dive'), 'discount' => 0, 'color' => '#0077B6');
}

/* ============================================
   DASHBOARD: FILTER BY ROLE
   ============================================ */
function bbr_dashboard_allowed_pages($user_id = 0) {
    if (!$user_id) $user_id = get_current_user_id();
    $user = get_userdata($user_id);
    if (!$user) return array();

    $role = $user->roles[0] ?? '';

    $pages = array(
        'bbr_general_manager'  => array('dashboard','bookings','reports','trips','settings','analytics'),
        'bbr_booking_staff'    => array('dashboard','bookings','checkin'),
        'bbr_dive_guide'       => array('dashboard','my-trips'),
        'bbr_hotel_partner'    => array('dashboard','hotel-manage'),
        'bbr_liveaboard_partner'=> array('dashboard','boat-manage'),
        'bbr_content_editor'   => array('dashboard','content'),
        'bbr_finance_staff'    => array('dashboard','bookings','reports','finance'),
        'administrator'        => array('dashboard','bookings','reports','trips','settings','analytics','finance','content','checkin','hotel-manage','boat-manage','my-trips','activity-log','system-health','backups'),
    );

    return $pages[$role] ?? array('dashboard');
}

/* ============================================
   WALKER: MEGA MENU
   ============================================ */
class BBR_Mega_Menu_Walker extends Walker_Nav_Menu {
    private $depth_map = array();
    private $current_top = 0;

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth === 0) {
            $this->current_top = $item->ID;
            $classes = empty($item->classes) ? array() : (array)$item->classes;
            $has_children = in_array('menu-item-has-children', $classes);
            $active = in_array('current-menu-item', $classes) ? ' current' : '';

            $output .= '<li class="bbr-nav-item">';
            $output .= '<a href="' . esc_url($item->url) . '" class="bbr-nav-link' . $active . '">' . esc_html($item->title) . '</a>';

            if ($has_children) {
                $output .= '<div class="bbr-mega-menu">';
            }
        } else {
            $icon = '';
            $icon_map = array(
                'liveaboard' => '🚢', 'dive' => '🤿', 'snorkeling' => '🐠', 'safari' => '🗺️',
                'water' => '🚤', 'day-trip' => '⚓', 'course' => '🎓', 'info' => 'ℹ️',
                'fam' => '🏕️', 'dive-center' => '🏊', 'expedition' => '🌍', 'charter' => '⛵',
                'boat' => '🛥️', 'blog' => '📝', 'faq' => '❓', 'check-in' => '✅',
            );
            foreach ($icon_map as $k => $v) {
                if (stripos($item->title, $k) !== false) { $icon = $v; break; }
            }
            if (!$icon) $icon = '•';

            $output .= '<a href="' . esc_url($item->url) . '" class="bbr-mega-link">';
            $output .= '<span class="bbr-mega-link-icon">' . $icon . '</span>';
            $output .= '<span>' . esc_html($item->title) . '</span>';
            $output .= '</a>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 0) {
            $classes = empty($item->classes) ? array() : (array)$item->classes;
            if (in_array('menu-item-has-children', $classes)) {
                $output .= '</div>';
            }
            $output .= '</li>';
        }
    }

    function start_lvl(&$output, $depth = 0, $args = null) {
        // Mega menu wrapper opened in start_el for depth 0 children
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        // Closed in end_el
    }
}

/* ============================================
   WIDGET: WEATHER
   ============================================ */
class BBR_Weather_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('bbr_weather', __('Babarida Weather', 'babarida-dive'), array('description' => __('Marine weather widget', 'babarida-dive')));
    }
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

        $weather = bbr_get_weather_data();
        if ($weather) {
            $temp = isset($weather['main']['temp']) ? round($weather['main']['temp']) : '—';
            $desc = isset($weather['weather'][0]['description']) ? ucfirst($weather['weather'][0]['description']) : '—';
            $wind = isset($weather['wind']['speed']) ? round($weather['wind']['speed'] * 3.6) : '—';
            $humidity = isset($weather['main']['humidity']) ? $weather['main']['humidity'] : '—';
            echo '<div class="bbr-weather" style="grid-template-columns:repeat(2,1fr)">';
            echo '<div class="bbr-weather-item"><div class="bbr-weather-icon">🌡️</div><div class="bbr-weather-val">' . $temp . '°C</div><div class="bbr-weather-label">Temperature</div></div>';
            echo '<div class="bbr-weather-item"><div class="bbr-weather-icon">💨</div><div class="bbr-weather-val">' . $wind . ' km/h</div><div class="bbr-weather-label">Wind</div></div>';
            echo '<div class="bbr-weather-item"><div class="bbr-weather-icon">💧</div><div class="bbr-weather-val">' . $humidity . '%</div><div class="bbr-weather-label">Humidity</div></div>';
            echo '<div class="bbr-weather-item"><div class="bbr-weather-icon">☁️</div><div class="bbr-weather-val" style="font-size:.85rem">' . esc_html($desc) . '</div><div class="bbr-weather-label">Condition</div></div>';
            echo '</div>';
        } else {
            echo '<p style="color:#6b7280;font-size:.85rem">Weather data unavailable.</p>';
        }
        echo $args['after_widget'];
    }
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Marine Weather', 'babarida-dive');
        echo '<p><label for="' . $this->get_field_id('title') . '">Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . esc_attr($title) . '"></p>';
    }
    public function update($new, $old) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new['title']);
        return $instance;
    }
}

function bbr_register_widgets() {
    register_widget('BBR_Weather_Widget');
}
add_action('widgets_init', 'bbr_register_widgets');

/* ============================================
   PRELOADER DISABLE ON ADMIN
   ============================================ */
function bbr_body_classes($classes) {
    if (is_admin_bar_showing()) {
        $classes[] = 'bbr-admin-bar';
    }
    if (is_front_page()) {
        $classes[] = 'bbr-home';
    }
    return $classes;
}
add_filter('body_class', 'bbr_body_classes');

/* ============================================
   EXCERPT LENGTH
   ============================================ */
function bbr_excerpt_length($length) {
    return is_front_page() ? 20 : 35;
}
add_filter('excerpt_length', 'bbr_excerpt_length');

/* ============================================
   REMOVE DEFAULT WP EMOJI
   ============================================ */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/* ============================================
   DEFER NON-ESSENTIAL JS
   ============================================ */
function bbr_defer_js($tag, $handle) {
    $defer_handles = array('bbr-lucide', 'bbr-main');
    if (in_array($handle, $defer_handles)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'bbr_defer_js', 10, 2);

/* ============================================
   PRECONNECT TO GOOGLE FONTS
   ============================================ */
function bbr_resource_hints($urls, $relation) {
    if ($relation === 'preconnect') {
        $urls[] = array('href' => 'https://fonts.googleapis.com', 'crossorigin' => '');
        $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous');
    }
    return $urls;
}
add_filter('wp_resource_hints', 'bbr_resource_hints', 10, 2);

/* ============================================
   ADD REL="PREFETCH" FOR KEY PAGES
   ============================================ */
function bbr_prefetch_pages() {
    $prefetch = array(
        home_url('/?page_id=' . get_option('bbr_checkin_page', '')),
        get_post_type_archive_link('trip'),
        get_post_type_archive_link('liveaboard'),
    );
    $prefetch = array_filter($prefetch);
    foreach ($prefetch as $url) {
        echo '<link rel="prefetch" href="' . esc_url($url) . '">' . "\n";
    }
}
add_action('wp_head', 'bbr_prefetch_pages', 99);

/* ============================================
   CORE WEB VITALS: DISABLE EMBEDS
   ============================================ */
function bbr_disable_embeds() {
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'bbr_disable_embeds');

/* ============================================
   LOGIN REDIRECT BY ROLE
   ============================================ */
function bbr_login_redirect($redirect, $request, $user) {
    if (is_wp_error($user) || !$user->ID) return $redirect;
    $role = $user->roles[0] ?? '';
    if (in_array($role, array('bbr_general_manager','bbr_booking_staff','bbr_finance_staff','bbr_content_editor','bbr_dive_guide','bbr_hotel_partner','bbr_liveaboard_partner','administrator'))) {
        $dash_page = get_option('bbr_dashboard_page', 0);
        if ($dash_page) return get_permalink($dash_page);
    }
    return $redirect;
}
add_filter('login_redirect', 'bbr_login_redirect', 10, 3);

/* ============================================
   DASHBOARD PAGE QUERY VAR
   ============================================ */
function bbr_dashboard_query_vars($vars) {
    $vars[] = 'bbr_tab';
    return $vars;
}
add_filter('query_vars', 'bbr_dashboard_query_vars');

/* ============================================
   AJAX: INTERNAL CHAT
   ============================================ */
function bbr_ajax_send_chat() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Unauthorized');

    $to_user  = absint($_POST['to_user'] ?? 0);
    $message  = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $from     = get_current_user_id();

    if (!$to_user || empty($message)) wp_send_json_error('Missing data');

    $chats = get_option('bbr_chat_messages', array());
    $chats[] = array(
        'from'    => $from,
        'to'      => $to_user,
        'message' => $message,
        'time'    => current_time('mysql'),
        'read'    => false,
    );

    // Keep last 1000 messages
    if (count($chats) > 1000) $chats = array_slice($chats, -1000);
    update_option('bbr_chat_messages', $chats);

    wp_send_json_success(array('time' => current_time('mysql')));
}
add_action('wp_ajax_bbr_send_chat', 'bbr_ajax_send_chat');

function bbr_ajax_get_chat() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Unauthorized');

    $with_user = absint($_GET['with'] ?? 0);
    $uid = get_current_user_id();
    $chats = get_option('bbr_chat_messages', array());

    $filtered = array();
    foreach ($chats as $c) {
        if (($c['from'] == $uid && $c['to'] == $with_user) || ($c['from'] == $with_user && $c['to'] == $uid)) {
            $filtered[] = $c;
        }
    }

    // Mark as read
    foreach ($chats as &$c) {
        if ($c['from'] == $with_user && $c['to'] == $uid) {
            $c['read'] = true;
        }
    }
    update_option('bbr_chat_messages', $chats);

    wp_send_json_success($filtered);
}
add_action('wp_ajax_bbr_get_chat', 'bbr_ajax_get_chat');

/* ============================================
   NOTIFICATION CENTER
   ============================================ */
function bbr_add_notification($user_id, $title, $message, $type = 'info') {
    $notifs = get_user_meta($user_id, '_bbr_notifications', true);
    if (!is_array($notifs)) $notifs = array();
    array_unshift($notifs, array(
        'title'   => $title,
        'message' => $message,
        'type'    => $type,
        'time'    => current_time('mysql'),
        'read'    => false,
    ));
    if (count($notifs) > 100) $notifs = array_slice($notifs, 0, 100);
    update_user_meta($user_id, '_bbr_notifications', $notifs);
}

function bbr_ajax_get_notifications() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Unauthorized');

    $notifs = get_user_meta(get_current_user_id(), '_bbr_notifications', true);
    $unread = 0;
    if (is_array($notifs)) {
        foreach ($notifs as &$n) {
            if (!$n['read']) $unread++;
        }
    }
    wp_send_json_success(array('notifications' => $notifs ?: array(), 'unread' => $unread));
}
add_action('wp_ajax_bbr_get_notifications', 'bbr_ajax_get_notifications');

function bbr_ajax_mark_notif_read() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Unauthorized');
    $notifs = get_user_meta(get_current_user_id(), '_bbr_notifications', true);
    if (is_array($notifs)) {
        foreach ($notifs as &$n) { $n['read'] = true; }
        update_user_meta(get_current_user_id(), '_bbr_notifications', $notifs);
    }
    wp_send_json_success();
}
add_action('wp_ajax_bbr_mark_notif_read', 'bbr_ajax_mark_notif_read');

/* ============================================
   WAIVER SYSTEM
   ============================================ */
function bbr_save_waiver() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error('Unauthorized');

    $booking_id = absint($_POST['booking_id'] ?? 0);
    $signature  = sanitize_text_field(wp_unslash($_POST['signature'] ?? ''));
    $accepted   = sanitize_text_field(wp_unslash($_POST['accepted'] ?? ''));

    if (!$booking_id || empty($signature) || $accepted !== 'yes') {
        wp_send_json_error('Please sign and accept the waiver.');
    }

    update_post_meta($booking_id, '_bbr_booking_waiver_signature', $signature);
    update_post_meta($booking_id, '_bbr_booking_waiver_date', current_time('mysql'));
    update_post_meta($booking_id, '_bbr_booking_waiver_accepted', 'yes');

    bbr_log_activity('waiver_signed', "Waiver signed for booking #$booking_id");
    wp_send_json_success(array('message' => __('Waiver signed successfully.', 'babarida-dive')));
}
add_action('wp_ajax_bbr_save_waiver', 'bbr_save_waiver');

/* ============================================
   PHOTO DELIVERY SYSTEM
   ============================================ */
function bbr_ajax_upload_trip_media() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!is_user_logged_in() || !current_user_can('upload_files')) wp_send_json_error('Unauthorized');

    if (empty($_FILES['files'])) wp_send_json_error('No files');

    $booking_id = absint($_POST['booking_id'] ?? 0);
    $uploaded = array();

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    foreach ($_FILES['files']['name'] as $i => $name) {
        if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) continue;
        $_FILES['upload_file'] = array(
            'name'     => sanitize_file_name($name),
            'type'     => sanitize_mime_type($_FILES['files']['type'][$i]),
            'tmp_name' => $_FILES['files']['tmp_name'][$i],
            'error'    => $_FILES['files']['error'][$i],
            'size'     => $_FILES['files']['size'][$i],
        );
        $attach_id = media_handle_upload('upload_file', 0);
        if (!is_wp_error($attach_id)) {
            $uploaded[] = $attach_id;
        }
    }

    if ($booking_id && !empty($uploaded)) {
        $existing = get_post_meta($booking_id, '_bbr_booking_media', true);
        if (!is_array($existing)) $existing = array();
        $existing = array_merge($existing, $uploaded);
        update_post_meta($booking_id, '_bbr_booking_media', $existing);
    }

    wp_send_json_success(array('uploaded' => count($uploaded)));
}
add_action('wp_ajax_bbr_upload_trip_media', 'bbr_upload_trip_media');

/* ============================================
   SYSTEM HEALTH MONITOR
   ============================================ */
function bbr_get_system_health() {
    global $wpdb;
    $health = array();

    // Database size
    $db_size = $wpdb->get_var("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "'");
    $health['db_size'] = $db_size ? $db_size . ' MB' : 'Unknown';

    // Upload directory size
    $upload_dir = wp_upload_dir();
    $health['upload_dir'] = $upload_dir['basedir'];

    // PHP version
    $health['php_version'] = PHP_VERSION;

    // WP version
    $health['wp_version'] = get_bloginfo('version');

    // Memory limit
    $health['memory_limit'] = ini_get('memory_limit');

    // Max upload
    $health['max_upload'] = ini_get('upload_max_filesize');

    // Active plugins count
    $health['active_plugins'] = count(get_option('active_plugins', array()));

    // Database tables needing optimization
    $health['db_tables'] = $wpdb->get_results("SHOW TABLE STATUS FROM `" . DB_NAME . "` WHERE Data_free > 0");

    return $health;
}

/* ============================================
   BACKUP SYSTEM
   ============================================ */
function bbr_ajax_create_backup() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

    global $wpdb;
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    $sql = "-- Babarida Dive Center Backup\n-- Date: " . current_time('mysql') . "\n\n";
    foreach ($tables as $table) {
        $table_name = $table[0];
        $create = $wpdb->get_row("SHOW CREATE TABLE `$table_name`", ARRAY_N);
        $sql .= "DROP TABLE IF EXISTS `$table_name`;\n" . $create[1] . ";\n\n";
        $rows = $wpdb->get_results("SELECT * FROM `$table_name`", ARRAY_A);
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $cols = array();
                $vals = array();
                foreach ($row as $k => $v) {
                    $cols[] = "`$k`";
                    $vals[] = $v === null ? 'NULL' : "'" . esc_sql($v) . "'";
                }
                $sql .= "INSERT INTO `$table_name` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ");\n";
            }
            $sql .= "\n";
        }
    }

    $backup_dir = WP_CONTENT_DIR . '/bbr-backups/';
    if (!file_exists($backup_dir)) {
        wp_mkdir_p($backup_dir);
        file_put_contents($backup_dir . '.htaccess', 'deny from all');
        file_put_contents($backup_dir . 'index.php', '<?php // Silence is golden');
    }

    $filename = 'bbr-backup-' . date('Y-m-d-His') . '.sql';
    file_put_contents($backup_dir . $filename, $sql);

    bbr_log_activity('backup_created', "Database backup created: $filename");
    wp_send_json_success(array('message' => "Backup created: $filename", 'file' => $filename));
}
add_action('wp_ajax_bbr_create_backup', 'bbr_create_backup');

function bbr_ajax_list_backups() {
    check_ajax_referer('bbr_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

    $backup_dir = WP_CONTENT_DIR . '/bbr-backups/';
    $files = array();
    if (is_dir($backup_dir)) {
        $items = scandir($backup_dir, SCANDIR_SORT_DESCENDING);
        foreach ($items as $f) {
            if (strpos($f, '.sql') !== false) {
                $files[] = array(
                    'name' => $f,
                    'size' => size_format(filesize($backup_dir . $f)),
                    'date' => date('Y-m-d H:i', filemtime($backup_dir . $f)),
                );
            }
        }
    }
    wp_send_json_success($files);
}
add_action('wp_ajax_bbr_list_backups', 'bbr_ajax_list_backups');

/* ============================================
   SCHEDULE: AUTO BACKUP
   ============================================ */
if (!wp_next_scheduled('bbr_daily_backup')) {
    wp_schedule_event(time(), 'daily', 'bbr_daily_backup');
}
add_action('bbr_daily_backup', function() {
    // Create daily backup silently
    global $wpdb;
    $backup_dir = WP_CONTENT_DIR . '/bbr-backups/';
    if (!file_exists($backup_dir)) wp_mkdir_p($backup_dir);

    $filename = 'bbr-auto-' . date('Y-m-d') . '.sql';
    if (file_exists($backup_dir . $filename)) return; // Already backed up today

    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    $sql = "-- Auto Backup " . current_time('mysql') . "\n";
    foreach ($tables as $table) {
        $table_name = $table[0];
        $create = $wpdb->get_row("SHOW CREATE TABLE `$table_name`", ARRAY_N);
        $sql .= "DROP TABLE IF EXISTS `$table_name`;\n" . $create[1] . ";\n\n";
    }
    file_put_contents($backup_dir . $filename, $sql);

    // Keep only last 30 auto backups
    $auto_files = glob($backup_dir . 'bbr-auto-*.sql');
    if (count($auto_files) > 30) {
        usort($auto_files, function($a, $b) { return filemtime($b) - filemtime($a); });
        for ($i = 30; $i < count($auto_files); $i++) {
            unlink($auto_files[$i]);
        }
    }
});

/* ============================================
   GOOGLE REVIEWS HELPER
   ============================================ */
function bbr_get_google_reviews() {
    $place_id = get_option('bbr_google_place_id', '');
    $api_key  = get_option('bbr_google_reviews_api_key', '');
    if (empty($place_id) || empty($api_key)) return array();

    $cache_key = 'bbr_google_reviews';
    $cached = get_transient($cache_key);
    if ($cached !== false) return $cached;

    $url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . $place_id . '&fields=reviews,rating,user_ratings_total&key=' . $api_key;
    $response = wp_remote_get($url, array('timeout' => 10));
    if (is_wp_error($response)) return array();

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['result']['reviews'])) return array();

    set_transient($cache_key, $data['result'], 86400); // Cache 24h
    return $data['result'];
}

/* ============================================
   SHORTCODES
   ============================================ */
function bbr_sc_weather($atts) {
    $atts = shortcode_atts(array('title' => ''), $atts, 'bbr_weather');
    $weather = bbr_get_weather_data();
    if (!$weather) return '<p>Weather data unavailable.</p>';

    $temp = round($weather['main']['temp']);
    $desc = ucfirst($weather['weather'][0]['description']);
    $wind = round($weather['wind']['speed'] * 3.6);
    $hum  = $weather['main']['humidity'];

    $html = '<div class="bbr-weather">';
    $html .= '<div class="bbr-weather-item"><div class="bbr-weather-icon">🌡️</div><div class="bbr-weather-val">' . $temp . '°C</div><div class="bbr-weather-label">Temp</div></div>';
    $html .= '<div class="bbr-weather-item"><div class="bbr-weather-icon">💨</div><div class="bbr-weather-val">' . $wind . 'km/h</div><div class="bbr-weather-label">Wind</div></div>';
    $html .= '<div class="bbr-weather-item"><div class="bbr-weather-icon">💧</div><div class="bbr-weather-val">' . $hum . '%</div><div class="bbr-weather-label">Humidity</div></div>';
    $html .= '<div class="bbr-weather-item"><div class="bbr-weather-icon">☁️</div><div class="bbr-weather-val" style="font-size:.85rem">' . esc_html($desc) . '</div><div class="bbr-weather-label">Condition</div></div>';
    $html .= '</div>';
    return $html;
}
add_shortcode('bbr_weather', 'bbr_sc_weather');

function bbr_sc_booking_form($atts) {
    $atts = shortcode_atts(array('trip_id' => 0), $atts, 'bbr_booking_form');
    $trip_id = absint($atts['trip_id']);
    ob_start();
    include BBR_DIR . '/template-parts/sc-booking-form.php';
    return ob_get_clean();
}
add_shortcode('bbr_booking_form', 'bbr_sc_booking_form');

function bbr_sc_search($atts) {
    ob_start();
    include BBR_DIR . '/template-parts/sc-search.php';
    return ob_get_clean();
}
add_shortcode('bbr_search', 'bbr_sc_search');

function bbr_sc_currency_switcher($atts) {
    $current = bbr_get_current_currency();
    $currencies = array('USD','IDR','EUR','SGD','AUD');
    $html = '<div class="bbr-currency-switch">';
    foreach ($currencies as $c) {
        $active = $c === $current ? ' active' : '';
        $html .= '<button class="bbr-currency-btn' . $active . '" data-currency="' . $c . '">' . $c . '</button>';
    }
    $html .= '</div>';
    return $html;
}
add_shortcode('bbr_currency', 'bbr_sc_currency_switcher');

function bbr_sc_faq($atts) {
    $atts = shortcode_atts(array('category' => ''), $atts, 'bbr_faq');
    $args = array('post_type' => 'faq', 'posts_per_page' => -1, 'post_status' => 'publish');
    if (!empty($atts['category'])) {
        $args['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'slug', 'terms' => $atts['category']));
    }
    $faqs = get_posts($args);
    ob_start();
    if (!empty($faqs)) {
        echo '<div class="bbr-faq-list">';
        foreach ($faqs as $faq) {
            echo '<div class="bbr-faq-item">';
            echo '<div class="bbr-faq-q"><span>' . esc_html(get_the_title($faq->ID)) . '</span><span class="bbr-faq-icon">+</span></div>';
            echo '<div class="bbr-faq-a"><p>' . wp_kses_post(apply_filters('the_content', $faq->post_content)) . '</p></div>';
            echo '</div>';
        }
        echo '</div>';
    }
    return ob_get_clean();
}
add_shortcode('bbr_faq', 'bbr_sc_faq');

/* ============================================
   THEME ACTIVATION
   ============================================ */
function bbr_activate() {
    // Create dashboard page
    $dash = get_page_by_path('dashboard');
    if (!$dash) {
        $dash_id = wp_insert_post(array(
            'post_title'  => 'Dashboard',
            'post_name'   => 'dashboard',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-dashboard.php',
        ));
        update_option('bbr_dashboard_page', $dash_id);
    }

    // Create check-in page
    $checkin = get_page_by_path('check-in');
    if (!$checkin) {
        $checkin_id = wp_insert_post(array(
            'post_title'  => 'Check-In',
            'post_name'   => 'check-in',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-checkin.php',
        ));
        update_option('bbr_checkin_page', $checkin_id);
    }

    // Create booking page
    $booking_page = get_page_by_path('book-now');
    if (!$booking_page) {
        $booking_id = wp_insert_post(array(
            'post_title'  => 'Book Now',
            'post_name'   => 'book-now',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-booking.php',
        ));
    }

    // Create pricing page
    $pricing = get_page_by_path('pricing');
    if (!$pricing) {
        wp_insert_post(array(
            'post_title'  => 'Monthly Price List',
            'post_name'   => 'pricing',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-pricing.php',
        ));
    }

    // Create partners page
    $partners = get_page_by_path('partners-page');
    if (!$partners) {
        wp_insert_post(array(
            'post_title'  => 'Our Partners',
            'post_name'   => 'partners-page',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-partners.php',
        ));
    }

    // Create contact page
    $contact = get_page_by_path('contact');
    if (!$contact) {
        wp_insert_post(array(
            'post_title'  => 'Contact Us',
            'post_name'   => 'contact',
            'post_status' => 'publish',
            'post_type'   => 'page',
            'page_template' => 'template-contact.php',
        ));
    }

    // Set default seasonal pricing
    if (!get_option('bbr_seasonal_pricing')) {
        update_option('bbr_seasonal_pricing', array(
            array('name' => 'High Season', 'start_month' => '6', 'end_month' => '8', 'multiplier' => '1.25'),
            array('name' => 'Peak Season', 'start_month' => '12', 'end_month' => '1', 'multiplier' => '1.40'),
            array('name' => 'Low Season', 'start_month' => '2', 'end_month' => '5', 'multiplier' => '1.00'),
        ));
    }

    // Set weekend multiplier
    if (!get_option('bbr_weekend_multiplier')) {
        update_option('bbr_weekend_multiplier', '1.10');
    }

    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'bbr_activate');

/* ============================================
   THEME DEACTIVATION
   ============================================ */
function bbr_deactivate() {
    wp_clear_scheduled_hook('bbr_daily_backup');
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'bbr_deactivate');
