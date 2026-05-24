<?php
/**
 * Template: Monthly Price List
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<section class="bbr-archive-hero" style="padding-bottom:3rem">
    <h1 class="bbr-archive-title"><?php esc_html_e('Monthly Price List', 'babarida-dive'); ?></h1>
    <p class="bbr-archive-desc"><?php esc_html_e('Dynamic pricing for the next 24 months. Prices may vary by season.', 'babarida-dive'); ?></p>
</section>

<section class="bbr-section">
    <div class="bbr-container" style="max-width:1100px">
        <div style="text-align:center;margin-bottom:2rem" class="bbr-reveal">
            <?php echo do_shortcode('[bbr_currency]'); ?>
        </div>

        <?php
        // Generate 24-month pricing table
        $currency = bbr_get_current_currency();
        $trips = get_posts(array('post_type' => array('trip','liveaboard','water_sport','dive_course'), 'posts_per_page' => -1, 'post_status' => 'publish'));
        ?>
        <div class="bbr-pricing-wrap bbr-reveal">
            <table class="bbr-pricing-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Trip / Package', 'babarida-dive'); ?></th>
                        <?php for ($m = 0; $m < 24; $m++) : ?>
                            <th><?php echo date('M Y', strtotime('first day of +' . $m . ' month')); ?></th>
                        <?php endfor; ?>
                        <th><?php esc_html_e('Action', 'babarida-dive'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($trips)) : foreach ($trips as $trip) :
                        $base_price = bbr_get_meta($trip->ID, 'trip', 'price_usd', bbr_get_meta($trip->ID, 'liveaboard', 'price_usd', bbr_get_meta($trip->ID, 'watersport', 'ws_price_usd', bbr_get_meta($trip->ID, 'course', 'course_price_usd', ''))));
                        if (empty($base_price)) continue;
                    ?>
                    <tr>
                        <td style="font-weight:600;white-space:nowrap"><?php echo esc_html($trip->post_title); ?></td>
                        <?php for ($m = 0; $m < 24; $m++) :
                            $date_str = date('Y-m-15', strtotime('first day of +' . $m . ' month'));
                            $price = bbr_get_dynamic_price($trip->ID, $date_str, $currency);
                            $base = floatval(bbr_get_meta($trip->ID, 'trip', 'price_usd', bbr_get_meta($trip->ID, 'liveaboard', 'price_usd', 0)));
                            $is_high = $price > $base * 1.1;
                            $is_peak = $price > $base * 1.3;
                        ?>
                        <td class="price-cell <?php echo $is_peak ? 'season-peak' : ($is_high ? 'season-high' : ''); ?>">
                            <?php echo bbr_format_price($price, $currency); ?>
                        </td>
                        <?php endfor; ?>
                        <td><a href="<?php echo esc_url(home_url('/book-now/?trip=' . $trip->ID)); ?>" class="bbr-btn bbr-btn-primary" style="padding:.3rem .7rem;font-size:.7rem"><?php esc_html_e('Book', 'babarida-dive'); ?></a></td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr><td colspan="26" style="text-align:center;padding:2rem;color:var(--gray-400)"><?php esc_html_e('No trips with pricing found. Add trips with prices in the admin panel.', 'babarida-dive'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="display:flex;gap:1rem;justify-content:center;margin-top:2rem;flex-wrap:wrap" class="bbr-reveal">
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem"><span style="width:14px;height:14px;border-radius:3px;background:rgba(255,214,10,.08);border:1px solid rgba(255,214,10,.3)"></span> <?php esc_html_e('High Season', 'babarida-dive'); ?></div>
            <div style="display:flex;align-items:center;gap:.4rem;font-size:.82rem"><span style="width:14px;height:14px;border-radius:3px;background:rgba(255,100,100,.06);border:1px solid rgba(255,100,100,.2)"></span> <?php esc_html_e('Peak Season', 'babarida-dive'); ?></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
