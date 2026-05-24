<?php
/**
 * Single Hotel Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $location = bbr_get_meta(get_the_ID(), 'hotel', 'hotel_location', 'Manado');
 $stars    = bbr_get_meta(get_the_ID(), 'hotel', 'hotel_stars', '');
 $price    = bbr_get_meta(get_the_ID(), 'hotel', 'price_from_usd', '');
 $rooms    = bbr_get_meta(get_the_ID(), 'hotel', 'room_types', '');
 $facil    = bbr_get_meta(get_the_ID(), 'hotel', 'facilities', '');
 $phone    = bbr_get_meta(get_the_ID(), 'hotel', 'hotel_phone', '');
 $email    = bbr_get_meta(get_the_ID(), 'hotel', 'hotel_email', '');
 $website  = bbr_get_meta(get_the_ID(), 'hotel', 'hotel_website', '');
 $gallery  = bbr_get_meta(get_the_ID(), 'hotel', 'gallery_ids', '');

 $rooms_arr  = $rooms ? explode("\n", $rooms) : array();
 $facil_arr  = $facil ? explode("\n", $facil) : array();
 $gallery_arr= $gallery ? array_filter(array_map('absint', explode(',', $gallery))) : array();
?>

<section class="bbr-single-hero">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/hotel-hero.jpg'); ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <div class="bbr-single-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                <span>/</span>
                <a href="<?php echo esc_url(get_post_type_archive_link('hotel')); ?>"><?php esc_html_e('Hotels', 'babarida-dive'); ?></a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </div>
            <h1><?php the_title(); ?> <?php if ($stars) echo '<span style="color:#FFD60A">' . str_repeat('★', (int)$stars) . '</span>'; ?></h1>
            <p style="color:rgba(255,255,255,.7);margin-top:.25rem">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html($location); ?>
            </p>
        </div>
    </div>
</section>

<section class="bbr-single-content">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:start">
        <div>
            <?php the_content(); ?>

            <?php if (!empty($rooms_arr)) : ?>
                <h2><?php esc_html_e('Room Types', 'babarida-dive'); ?></h2>
                <div style="display:grid;gap:.75rem;margin-bottom:2rem">
                    <?php foreach ($rooms_arr as $rm) : ?>
                        <?php $rm = trim($rm); if (empty($rm)) continue; ?>
                        <div style="padding:1rem 1.25rem;background:var(--gray-50);border-radius:var(--radius-md);border:1px solid var(--gray-100);font-size:.9rem;color:var(--gray-700)"><?php echo esc_html($rm); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($facil_arr)) : ?>
                <h2><?php esc_html_e('Facilities', 'babarida-dive'); ?></h2>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.5rem;margin-bottom:2rem">
                    <?php foreach ($facil_arr as $f) : ?>
                        <?php $f = trim($f); if (empty($f)) continue; ?>
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.88rem;color:var(--gray-700)"><span style="color:#10B981">✓</span> <?php echo esc_html($f); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

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

        <aside style="position:sticky;top:120px">
            <div style="background:var(--white-pure);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);border:1px solid var(--gray-100);padding:1.75rem">
                <?php if ($price) : ?>
                    <div style="text-align:center;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--gray-100)">
                        <div style="font-size:.78rem;color:var(--gray-400);text-transform:uppercase"><?php esc_html_e('From', 'babarida-dive'); ?></div>
                        <div style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:var(--blue-deep)"><?php echo bbr_format_price($price); ?>/<?php esc_html_e('night', 'babarida-dive'); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($phone) : ?><p style="font-size:.88rem;margin-bottom:.5rem"><strong><?php esc_html_e('Phone:', 'babarida-dive'); ?></strong> <?php echo esc_html($phone); ?></p><?php endif; ?>
                <?php if ($email) : ?><p style="font-size:.88rem;margin-bottom:.5rem"><strong><?php esc_html_e('Email:', 'babarida-dive'); ?></strong> <a href="mailto:<?php echo esc_attr($email); ?>" style="color:var(--blue-primary)"><?php echo esc_html($email); ?></a></p><?php endif; ?>
                <?php if ($website) : ?><p style="font-size:.88rem;margin-bottom:1.5rem"><strong><?php esc_html_e('Website:', 'babarida-dive'); ?></strong> <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" style="color:var(--blue-primary)"><?php esc_html_e('Visit', 'babarida-dive'); ?></a></p><?php endif; ?>
                <a href="https://wa.me/<?php echo esc_attr(BBR_WHATSAPP); ?>?text=<?php echo urlencode('Hi, I am interested in hotel: ' . get_the_title()); ?>" target="_blank" rel="noopener" class="bbr-btn bbr-btn-primary" style="width:100%;justify-content:center"><?php esc_html_e('Inquire via WhatsApp', 'babarida-dive'); ?></a>
            </div>
        </aside>
    </div>
</section>

<?php get_footer(); ?>
