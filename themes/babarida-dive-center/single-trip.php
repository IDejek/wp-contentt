<?php
/**
 * Single Trip / Water Sport / Dive Course Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $prefix     = 'trip';
 $post_type  = get_post_type();
if ($post_type === 'water_sport') $prefix = 'watersport';
if ($post_type === 'dive_course') $prefix = 'course';

 $price_usd  = bbr_get_meta(get_the_ID(), $prefix, 'price_usd', bbr_get_meta(get_the_ID(), $prefix, 'course_price_usd', bbr_get_meta(get_the_ID(), $prefix, 'ws_price_usd', '')));
 $price_idr  = bbr_get_meta(get_the_ID(), $prefix, 'price_idr', bbr_get_meta(get_the_ID(), $prefix, 'course_price_idr', bbr_get_meta(get_the_ID(), $prefix, 'ws_price_idr', '')));
 $duration   = bbr_get_meta(get_the_ID(), $prefix, 'duration', bbr_get_meta(get_the_ID(), $prefix, 'course_duration', bbr_get_meta(get_the_ID(), $prefix, 'ws_duration', '')));
 $dest       = bbr_get_meta(get_the_ID(), $prefix, 'destination', bbr_get_meta(get_the_ID(), $prefix, 'ws_destination', ''));
 $includes   = bbr_get_meta(get_the_ID(), $prefix, 'includes', bbr_get_meta(get_the_ID(), $prefix, 'course_includes', bbr_get_meta(get_the_ID(), $prefix, 'ws_includes', ''));
 $excludes   = bbr_get_meta(get_the_ID(), $prefix, 'excludes', '');
 $itinerary  = bbr_get_meta(get_the_ID(), $prefix, 'itinerary', '');
 $gallery_ids= bbr_get_meta(get_the_ID(), $prefix, 'gallery_ids', '');
 $max_guests = bbr_get_meta(get_the_ID(), $prefix, 'max_guests', '');
 $min_cert   = bbr_get_meta(get_the_ID(), $prefix, 'min_cert', '');
 $currency   = bbr_get_current_currency();

 $includes_arr = $includes ? explode("\n", $includes) : array();
 $excludes_arr = $excludes ? explode("\n", $excludes) : array();
 $itinerary_arr= $itinerary ? explode("\n", $itinerary) : array();
 $gallery_arr  = $gallery_ids ? array_filter(array_map('absint', explode(',', $gallery_ids))) : array();

 $type_labels = array(
    'trip' => __('Dive Trip', 'babarida-dive'),
    'water_sport' => __('Water Sport', 'babarida-dive'),
    'dive_course' => __('Dive Course', 'babarida-dive'),
);
 $type_label = $type_labels[$post_type] ?: __('Trip', 'babarida-dive');
?>

<section class="bbr-single-hero">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/trip-default.jpg'); ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <div class="bbr-single-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                <span>/</span>
                <a href="<?php echo esc_url(get_post_type_archive_link($post_type)); ?>"><?php echo esc_html($type_label . 's'); ?></a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
            <div style="display:flex;gap:1rem;margin-top:.75rem;flex-wrap:wrap">
                <?php if ($dest) : ?><span class="bbr-badge bbr-badge-blue"><?php echo esc_html($dest); ?></span><?php endif; ?>
                <?php if ($duration) : ?><span class="bbr-badge bbr-badge-yellow"><?php echo esc_html($duration); ?></span><?php endif; ?>
                <span class="bbr-badge bbr-badge-green"><?php echo esc_html($type_label); ?></span>
            </div>
        </div>
    </div>
</section>

<section class="bbr-single-content">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:start">
        <div>
            <?php the_content(); ?>

            <?php if (!empty($itinerary_arr)) : ?>
                <h2><?php esc_html_e('Itinerary', 'babarida-dive'); ?></h2>
                <ol style="padding-left:1.5rem;margin-bottom:2rem">
                    <?php foreach ($itinerary_arr as $day) : ?>
                        <?php $day = trim($day); if (empty($day)) continue; ?>
                        <li style="margin-bottom:.5rem;color:var(--gray-700);line-height:1.7"><?php echo esc_html($day); ?></li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

            <?php if (!empty($gallery_arr)) : ?>
                <h2><?php esc_html_e('Gallery', 'babarida-dive'); ?></h2>
                <div class="bbr-gallery-grid">
                    <?php foreach ($gallery_arr as $gid) : ?>
                        <?php if (wp_get_attachment_url($gid)) : ?>
                            <div class="bbr-gallery-item">
                                <?php echo wp_get_attachment_image($gid, 'bbr-gallery', false, array('loading' => 'lazy')); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside style="position:sticky;top:120px">
            <div style="background:var(--white-pure);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);border:1px solid var(--gray-100);padding:1.75rem;overflow:hidden">
                <?php if ($price_usd || $price_idr) : ?>
                    <div style="text-align:center;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--gray-100)">
                        <div style="font-size:.78rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em"><?php esc_html_e('Starting from', 'babarida-dive'); ?></div>
                        <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--blue-deep);margin:.25rem 0">
                            <?php echo bbr_format_price(bbr_get_dynamic_price(get_the_ID(), '', $currency), $currency); ?>
                        </div>
                        <div class="bbr-currency-switch" style="display:inline-flex;margin-top:.5rem">
                            <?php foreach (array('USD','IDR','EUR','SGD','AUD') as $c) : ?>
                                <button class="bbr-currency-btn <?php echo $c === $currency ? 'active' : ''; ?>" data-currency="<?php echo esc_attr($c); ?>" style="font-size:.65rem;padding:.2rem .5rem"><?php echo $c; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($duration) : ?>
                <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-50);font-size:.88rem">
                    <span style="color:var(--gray-400)"><?php esc_html_e('Duration', 'babarida-dive'); ?></span>
                    <span style="font-weight:600"><?php echo esc_html($duration); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($max_guests) : ?>
                <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-50);font-size:.88rem">
                    <span style="color:var(--gray-400)"><?php esc_html_e('Max Guests', 'babarida-dive'); ?></span>
                    <span style="font-weight:600"><?php echo esc_html($max_guests); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($min_cert) : ?>
                <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-50);font-size:.88rem">
                    <span style="color:var(--gray-400)"><?php esc_html_e('Min. Cert.', 'babarida-dive'); ?></span>
                    <span style="font-weight:600"><?php echo esc_html($min_cert); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($includes_arr)) : ?>
                <div style="margin-top:1rem">
                    <h4 style="font-size:.82rem;color:var(--gray-500);margin-bottom:.5rem;font-family:var(--font-body)"><?php esc_html_e('Includes', 'babarida-dive'); ?></h4>
                    <?php foreach ($includes_arr as $inc) : ?>
                        <?php $inc = trim($inc); if (empty($inc)) continue; ?>
                        <div style="display:flex;align-items:center;gap:.4rem;font-size:.85rem;margin-bottom:.3rem;color:var(--gray-700)">
                            <span style="color:#10B981;font-size:.7rem">✓</span> <?php echo esc_html($inc); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($excludes_arr)) : ?>
                <div style="margin-top:1rem">
                    <h4 style="font-size:.82rem;color:var(--gray-500);margin-bottom:.5rem;font-family:var(--font-body)"><?php esc_html_e('Excludes', 'babarida-dive'); ?></h4>
                    <?php foreach ($excludes_arr as $exc) : ?>
                        <?php $exc = trim($exc); if (empty($exc)) continue; ?>
                        <div style="display:flex;align-items:center;gap:.4rem;font-size:.85rem;margin-bottom:.3rem;color:var(--gray-700)">
                            <span style="color:#EF4444;font-size:.7rem">✗</span> <?php echo esc_html($exc); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div style="display:flex;flex-direction:column;gap:.5rem;margin-top:1.5rem">
                    <a href="https://wa.me/<?php echo esc_attr(BBR_WHATSAPP); ?>?text=<?php echo urlencode('Hi, I am interested in: ' . get_the_title()); ?>" target="_blank" rel="noopener" class="bbr-btn bbr-btn-primary" style="justify-content:center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        <?php esc_html_e('Book via WhatsApp', 'babarida-dive'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/book-now/?trip=' . get_the_ID())); ?>" class="bbr-btn bbr-btn-yellow" style="justify-content:center"><?php esc_html_e('Book Online', 'babarida-dive'); ?></a>
                </div>
            </div>
        </aside>
    </div>
</section>

<?php get_footer(); ?>
