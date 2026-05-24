<?php
/**
 * Single Destination Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $subtitle     = bbr_get_meta(get_the_ID(), 'destination', 'dest_subtitle', '');
 $distance     = bbr_get_meta(get_the_ID(), 'destination', 'dest_distance', '');
 $travel_time  = bbr_get_meta(get_the_ID(), 'destination', 'dest_travel_time', '');
 $best_season  = bbr_get_meta(get_the_ID(), 'destination', 'dest_best_season', '');
 $water_temp   = bbr_get_meta(get_the_ID(), 'destination', 'dest_water_temp', '');
 $visibility   = bbr_get_meta(get_the_ID(), 'destination', 'dest_visibility', '');
 $depth        = bbr_get_meta(get_the_ID(), 'destination', 'dest_depth', '');
 $current_str  = bbr_get_meta(get_the_ID(), 'destination', 'dest_current', '');
 $marine_life  = bbr_get_meta(get_the_ID(), 'destination', 'dest_marine_life', '');
 $dive_sites   = bbr_get_meta(get_the_ID(), 'destination', 'dest_dive_sites', '');
 $gallery_ids  = bbr_get_meta(get_the_ID(), 'destination', 'gallery_ids', '');

 $marine_arr   = $marine_life ? explode("\n", $marine_life) : array();
 $sites_arr    = $dive_sites ? explode("\n", $dive_sites) : array();
 $gallery_arr  = $gallery_ids ? array_filter(array_map('absint', explode(',', $gallery_ids))) : array();

// Get trips for this destination
 $dest_trips = get_posts(array(
    'post_type'      => 'trip',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'meta_key'       => '_bbr_trip_destination',
    'meta_value'     => get_the_title(),
));

// Get liveaboards
 $dest_boats = get_posts(array(
    'post_type'      => 'liveaboard',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
));
?>

<section class="bbr-single-hero">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/dest-' . sanitize_file_name(get_post_field('post_name', get_the_ID())) . '.jpg'); ?>" alt="" loading="eager" onerror="this.src='<?php echo esc_url(BBR_URI . '/assets/images/dest-default.jpg'); ?>'">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <div class="bbr-single-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                <span>/</span>
                <a href="<?php echo esc_url(get_post_type_archive_link('destination')); ?>"><?php esc_html_e('Destinations', 'babarida-dive'); ?></a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php if ($subtitle) : ?><p style="font-size:1.1rem;color:rgba(255,255,255,.8);margin-top:.5rem"><?php echo esc_html($subtitle); ?></p><?php endif; ?>
        </div>
    </div>
</section>

<section class="bbr-single-content">
    <?php the_content(); ?>

    <!-- Destination Quick Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin:2rem 0">
        <?php
        $stats = array();
        if ($distance)    $stats[] = array('icon' => '📏', 'label' => __('Distance', 'babarida-dive'), 'val' => $distance);
        if ($travel_time) $stats[] = array('icon' => '🚤', 'label' => __('Travel Time', 'babarida-dive'), 'val' => $travel_time);
        if ($best_season) $stats[] = array('icon' => '📅', 'label' => __('Best Season', 'babarida-dive'), 'val' => $best_season);
        if ($water_temp)  $stats[] = array('icon' => '🌡️', 'label' => __('Water Temp', 'babarida-dive'), 'val' => $water_temp);
        if ($visibility)  $stats[] = array('icon' => '👁️', 'label' => __('Visibility', 'babarida-dive'), 'val' => $visibility);
        if ($depth)       $stats[] = array('icon' => '🔽', 'label' => __('Depth', 'babarida-dive'), 'val' => $depth);
        if ($current_str) $stats[] = array('icon' => '🌊', 'label' => __('Current', 'babarida-dive'), 'val' => $current_str);
        ?>
        <?php foreach ($stats as $st) : ?>
        <div style="text-align:center;padding:1.25rem;background:var(--gray-50);border-radius:var(--radius-md);border:1px solid var(--gray-100)">
            <div style="font-size:1.5rem;margin-bottom:.35rem"><?php echo $st['icon']; ?></div>
            <div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem"><?php echo esc_html($st['label']); ?></div>
            <div style="font-weight:700;color:var(--gray-900);font-size:.95rem"><?php echo esc_html($st['val']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Marine Life -->
    <?php if (!empty($marine_arr)) : ?>
        <h2><?php esc_html_e('Marine Life', 'babarida-dive'); ?></h2>
        <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:2rem">
            <?php foreach ($marine_arr as $ml) : ?>
                <?php $ml = trim($ml); if (empty($ml)) continue; ?>
                <span style="padding:.4rem 1rem;background:rgba(0,119,182,.08);color:var(--blue-primary);border-radius:var(--radius-full);font-size:.82rem;font-weight:500"><?php echo esc_html($ml); ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Dive Sites -->
    <?php if (!empty($sites_arr)) : ?>
        <h2><?php esc_html_e('Dive Sites', 'babarida-dive'); ?></h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem;margin-bottom:2rem">
            <?php foreach ($sites_arr as $site) : ?>
                <?php $site = trim($site); if (empty($site)) continue; ?>
                <div style="padding:1rem 1.25rem;background:var(--white-pure);border:1px solid var(--gray-200);border-radius:var(--radius-md);display:flex;align-items:center;gap:.75rem">
                    <span style="width:8px;height:8px;border-radius:50%;background:var(--blue-primary);flex-shrink:0"></span>
                    <span style="font-weight:500;color:var(--gray-800)"><?php echo esc_html($site); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Gallery -->
    <?php if (!empty($gallery_arr)) : ?>
        <h2><?php esc_html_e('Gallery', 'babarida-dive'); ?></h2>
        <div class="bbr-gallery-grid">
            <?php foreach ($gallery_arr as $gid) : ?>
                <?php if (wp_get_attachment_url($gid)) : ?>
                    <div class="bbr-gallery-item"><?php echo wp_get_attachment_image($gid, 'bbr-gallery', false, array('loading' => 'lazy')); ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Related Trips -->
    <?php if (!empty($dest_trips)) : ?>
        <h2 style="margin-top:3rem"><?php esc_html_e('Trips in', 'babarida-dive'); ?> <?php the_title(); ?></h2>
        <div class="bbr-liveaboard-grid">
            <?php foreach ($dest_trips as $trip) :
                $tp = bbr_get_meta($trip->ID, 'trip', 'price_usd', '');
            ?>
            <div class="bbr-boat-card">
                <div class="bbr-boat-card-img">
                    <?php if (has_post_thumbnail($trip->ID)) : ?>
                        <?php echo get_the_post_thumbnail($trip->ID, 'bbr-card', array('loading' => 'lazy')); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(BBR_URI . '/assets/images/trip-default.jpg'); ?>" alt="<?php echo esc_attr($trip->post_title); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="bbr-boat-card-body">
                    <h3 class="bbr-boat-card-name"><?php echo esc_html($trip->post_title); ?></h3>
                    <p style="font-size:.82rem;color:var(--gray-500);margin:.5rem 0"><?php echo wp_trim_words(get_the_excerpt($trip->ID), 15); ?></p>
                    <div class="bbr-boat-card-footer">
                        <?php if ($tp) : ?><div class="bbr-boat-price"><strong><?php echo bbr_format_price($tp); ?></strong></div><?php endif; ?>
                        <a href="<?php echo esc_url(get_permalink($trip->ID)); ?>" class="bbr-btn bbr-btn-primary" style="padding:.4rem 1rem;font-size:.75rem"><?php esc_html_e('View', 'babarida-dive'); ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
