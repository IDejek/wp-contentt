<?php
/**
 * Shortcode Template: Booking Form
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

 $trip_id = absint($atts['trip_id'] ?? 0);
 $all_trips = get_posts(array('post_type' => array('trip','liveaboard','water_sport','dive_course'), 'posts_per_page' => -1, 'post_status' => 'publish'));
?>
<form class="bbr-booking-form" style="max-width:600px;margin:0 auto">
    <input type="hidden" name="booking_status" value="pending">
    <input type="hidden" name="currency" value="<?php echo esc_attr(bbr_get_current_currency()); ?>">

    <div class="bbr-form-row">
        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Full Name *', 'babarida-dive'); ?></label>
            <input type="text" name="guest_name" class="bbr-form-input" required placeholder="Your name">
        </div>
        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Email *', 'babarida-dive'); ?></label>
            <input type="email" name="guest_email" class="bbr-form-input" required placeholder="your@email.com">
        </div>
    </div>

    <div class="bbr-form-group">
        <label class="bbr-form-label"><?php esc_html_e('Phone / WhatsApp *', 'babarida-dive'); ?></label>
        <input type="tel" name="guest_phone" class="bbr-form-input" required placeholder="+62...">
    </div>

    <div class="bbr-form-group">
        <label class="bbr-form-label"><?php esc_html_e('Select Trip *', 'babarida-dive'); ?></label>
        <select name="trip_name" class="bbr-form-select" required>
            <option value=""><?php esc_html_e('Choose...', 'babarida-dive'); ?></option>
            <?php foreach ($all_trips as $t) : ?>
                <option value="<?php echo esc_attr($t->post_title); ?>" <?php selected($trip_id, $t->ID); ?>><?php echo esc_html($t->post_title); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="bbr-form-row">
        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Check-in Date', 'babarida-dive'); ?></label>
            <input type="date" name="check_in_date" class="bbr-form-input">
        </div>
        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Guests', 'babarida-dive'); ?></label>
            <input type="number" name="num_guests" class="bbr-form-input" value="1" min="1">
        </div>
    </div>

    <div class="bbr-form-group">
        <label class="bbr-form-label"><?php esc_html_e('Special Requests', 'babarida-dive'); ?></label>
        <textarea name="special_requests" class="bbr-form-textarea" rows="3"></textarea>
    </div>

    <button type="submit" class="bbr-btn bbr-btn-yellow" style="width:100%;justify-content:center;padding:.9rem"><?php esc_html_e('Submit Booking', 'babarida-dive'); ?></button>
</form>
