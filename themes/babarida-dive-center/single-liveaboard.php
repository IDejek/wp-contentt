<?php
/**
 * Single Liveaboard Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $length     = bbr_get_meta(get_the_ID(), 'liveaboard', 'boat_length', '');
 $cabins     = bbr_get_meta(get_the_ID(), 'liveaboard', 'cabins', '');
 $max_guests = bbr_get_meta(get_the_ID(), 'liveaboard', 'max_guests', '');
 $crew       = bbr_get_meta(get_the_ID(), 'liveaboard', 'crew', '');
 $price_usd  = bbr_get_meta(get_the_ID(), 'liveaboard', 'price_usd', '');
 $price_idr  = bbr_get_meta(get_the_ID(), 'liveaboard', 'price_idr', '');
 $routes     = bbr_get_meta(get_the_ID(), 'liveaboard', 'routes', '');
 $amenities  = bbr_get_meta(get_the_ID(), 'liveaboard', 'amenities', '');
 $specs      = bbr_get_meta(get_the_ID(), 'liveaboard', 'specifications', '');
 $schedule   = bbr_get_meta(get_the_ID(), 'liveaboard', 'schedule', '');
 $gallery_ids= bbr_get_meta(get_the_ID(), 'liveaboard', 'gallery_ids', '');
 $availability = bbr_get_meta(get_the_ID(), 'liveaboard', 'availability', '');

 $routes_arr    = $routes ? explode("\n", $routes) : array();
 $amenities_arr = $amenities ? explode("\n", $amenities) : array();
 $specs_arr     = $specs ? explode("\n", $specs) : array();
 $schedule_arr  = $schedule ? explode("\n", $schedule) : array();
 $gallery_arr   = $gallery_ids ? array_filter(array_map('absint', explode(',', $gallery_ids))) : array();
 $currency      = bbr_get_current_currency();
?>

<section class="bbr-single-hero">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/boat-hero.jpg'); ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <div class="bbr-single-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                <span>/</span>
                <a href="<?php echo esc_url(get_post_type_archive_link('liveaboard')); ?>"><?php esc_html_e('Liveaboards', 'babarida-dive'); ?></a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php if ($availability) : ?>
                <div style="margin-top:.5rem"><span class="bbr-badge bbr-badge-green"><?php echo esc_html($availability); ?> <?php esc_html_e('cabins available', 'babarida-dive'); ?></span></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="bbr-single-content">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:start">
        <div>
            <?php the_content(); ?>

            <!-- Boat Specs -->
            <?php if (!empty($specs_arr)) : ?>
                <h2><?php esc_html_e('Specifications', 'babarida-dive'); ?></h2>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-bottom:2rem">
                    <?php foreach ($specs_arr as $sp) : ?>
                        <?php $sp = trim($sp); if (empty($sp)) continue; ?>
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.88rem;color:var(--gray-700);padding:.5rem .75rem;background:var(--gray-50);border-radius:var(--radius-sm)">
                            <span style="color:var(--blue-primary)">•</span> <?php echo esc_html($sp); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Routes -->
            <?php if (!empty($routes_arr)) : ?>
                <h2><?php esc_html_e('Routes', 'babarida-dive'); ?></h2>
                <ul style="margin-bottom:2rem">
                    <?php foreach ($routes_arr as $r) : ?>
                        <?php $r = trim($r); if (empty($r)) continue; ?>
                        <li style="padding:.5rem 0;border-bottom:1px solid var(--gray-100);color:var(--gray-700);font-size:.92rem;display:flex;align-items:center;gap:.5rem">
                            <span style="color:var(--yellow-accent)">🚢</span> <?php echo esc_html($r); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Amenities -->
            <?php if (!empty($amenities_arr)) : ?>
                <h2><?php esc_html_e('Amenities', 'babarida-dive'); ?></h2>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.5rem;margin-bottom:2rem">
                    <?php foreach ($amenities_arr as $am) : ?>
                        <?php $am = trim($am); if (empty($am)) continue; ?>
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.88rem;color:var(--gray-700)">
                            <span style="color:#10B981">✓</span> <?php echo esc_html($am); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Schedule -->
            <?php if (!empty($schedule_arr)) : ?>
                <h2><?php esc_html_e('Schedule', 'babarida-dive'); ?></h2>
                <div class="bbr-pricing-wrap" style="margin-bottom:2rem">
                    <table class="bbr-pricing-table">
                        <thead><tr><th><?php esc_html_e('Schedule', 'babarida-dive'); ?></th></tr></thead>
                        <tbody>
                            <?php foreach ($schedule_arr as $sc) : ?>
                                <?php $sc = trim($sc); if (empty($sc)) continue; ?>
                                <tr><td><?php echo esc_html($sc); ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
        </div>

        <!-- Sidebar -->
        <aside style="position:sticky;top:120px">
            <div style="background:var(--white-pure);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);border:1px solid var(--gray-100);padding:1.75rem">
                <?php if ($price_usd || $price_idr) : ?>
                    <div style="text-align:center;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--gray-100)">
                        <div style="font-size:.78rem;color:var(--gray-400);text-transform:uppercase"><?php esc_html_e('From', 'babarida-dive'); ?></div>
                        <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--blue-deep)"><?php echo bbr_format_price(bbr_get_dynamic_price(get_the_ID(), '', $currency), $currency); ?>/<?php esc_html_e('night', 'babarida-dive'); ?></div>
                    </div>
                <?php endif; ?>

                <?php
                $quick_specs = array();
                if ($length) $quick_specs[] = array('label' => __('Length', 'babarida-dive'), 'val' => $length . 'm');
                if ($cabins) $quick_specs[] = array('label' => __('Cabins', 'babarida-dive'), 'val' => $cabins);
                if ($max_guests) $quick_specs[] = array('label' => __('Guests', 'babarida-dive'), 'val' => $max_guests);
                if ($crew) $quick_specs[] = array('label' => __('Crew', 'babarida-dive'), 'val' => $crew);
                ?>
                <?php foreach ($quick_specs as $qs) : ?>
                <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-50);font-size:.88rem">
                    <span style="color:var(--gray-400)"><?php echo esc_html($qs['label']); ?></span>
                    <span style="font-weight:600"><?php echo esc_html($qs['val']); ?></span>
                </div>
                <?php endforeach; ?>

                <div style="display:flex;flex-direction:column;gap:.5rem;margin-top:1.5rem">
                    <a href="https://wa.me/<?php echo esc_attr(BBR_WHATSAPP); ?>?text=<?php echo urlencode('Hi, I am interested in liveaboard: ' . get_the_title()); ?>" target="_blank" rel="noopener" class="bbr-btn bbr-btn-primary" style="justify-content:center"><?php esc_html_e('Inquire via WhatsApp', 'babarida-dive'); ?></a>
                    <a href="<?php echo esc_url(home_url('/book-now/?liveaboard=' . get_the_ID())); ?>" class="bbr-btn bbr-btn-yellow" style="justify-content:center"><?php esc_html_e('Book Now', 'babarida-dive'); ?></a>
                </div>
            </div>
        </aside>
    </div>
</section>

<?php get_footer(); ?>
