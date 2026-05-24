<?php
/**
 * Template: Booking Page
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $preselected_trip = isset($_GET['trip']) ? absint($_GET['trip']) : 0;
 $preselected_boat = isset($_GET['liveaboard']) ? absint($_GET['liveaboard']) : 0;
 $preselected_name = '';

if ($preselected_trip) {
    $preselected_name = get_the_title($preselected_trip);
} elseif ($preselected_boat) {
    $preselected_name = get_the_title($preselected_boat);
}

 $all_trips = get_posts(array('post_type' => array('trip','liveaboard','water_sport','dive_course'), 'posts_per_page' => -1, 'post_status' => 'publish'));
?>
<section class="bbr-archive-hero" style="padding-bottom:3rem">
    <h1 class="bbr-archive-title"><?php esc_html_e('Book Your Adventure', 'babarida-dive'); ?></h1>
    <p class="bbr-archive-desc"><?php esc_html_e('Fill in the form below and we will confirm your booking within 24 hours.', 'babarida-dive'); ?></p>
</section>

<section class="bbr-section">
    <div class="bbr-container" style="max-width:800px">
        <div style="background:var(--white-pure);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);border:1px solid var(--gray-100);padding:2.5rem">
            <form class="bbr-booking-form">
                <input type="hidden" name="booking_status" value="pending">
                <input type="hidden" name="currency" value="<?php echo esc_attr(bbr_get_current_currency()); ?>">

                <div class="bbr-form-row">
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Full Name *', 'babarida-dive'); ?></label>
                        <input type="text" name="guest_name" class="bbr-form-input" required value="<?php echo esc_attr($preselected_name ? '' : ''); ?>" placeholder="Your full name">
                    </div>
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Email *', 'babarida-dive'); ?></label>
                        <input type="email" name="guest_email" class="bbr-form-input" required placeholder="your@email.com">
                    </div>
                </div>

                <div class="bbr-form-row">
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Phone / WhatsApp *', 'babarida-dive'); ?></label>
                        <input type="tel" name="guest_phone" class="bbr-form-input" required placeholder="+62...">
                    </div>
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Nationality', 'babarida-dive'); ?></label>
                        <input type="text" name="guest_nationality" class="bbr-form-input" placeholder="Your nationality">
                    </div>
                </div>

                <div class="bbr-form-group">
                    <label class="bbr-form-label"><?php esc_html_e('Select Trip / Package *', 'babarida-dive'); ?></label>
                    <select name="trip_name" class="bbr-form-select" required>
                        <option value=""><?php esc_html_e('Choose a trip...', 'babarida-dive'); ?></option>
                        <?php foreach ($all_trips as $t) : ?>
                            <option value="<?php echo esc_attr($t->post_title); ?>" <?php selected($t->post_title, $preselected_name); ?>><?php echo esc_html($t->post_title); ?> (<?php echo esc_html(ucfirst(str_replace('_', ' ', get_post_type($t->ID)))); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="bbr-form-row">
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Check-in Date *', 'babarida-dive'); ?></label>
                        <input type="date" name="check_in_date" class="bbr-form-input" required>
                    </div>
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Check-out Date', 'babarida-dive'); ?></label>
                        <input type="date" name="check_out_date" class="bbr-form-input">
                    </div>
                </div>

                <div class="bbr-form-row">
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Number of Guests', 'babarida-dive'); ?></label>
                        <input type="number" name="num_guests" class="bbr-form-input" value="1" min="1" max="30">
                    </div>
                    <div class="bbr-form-group">
                        <label class="bbr-form-label"><?php esc_html_e('Preferred Payment', 'babarida-dive'); ?></label>
                        <select name="payment_method" class="bbr-form-select">
                            <option value="bank_transfer"><?php esc_html_e('Bank Transfer', 'babarida-dive'); ?></option>
                            <option value="midtrans"><?php esc_html_e('Midtrans', 'babarida-dive'); ?></option>
                            <option value="xendit"><?php esc_html_e('Xendit', 'babarida-dive'); ?></option>
                            <option value="stripe"><?php esc_html_e('Stripe / Credit Card', 'babarida-dive'); ?></option>
                            <option value="paypal"><?php esc_html_e('PayPal', 'babarida-dive'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="bbr-form-group">
                    <label class="bbr-form-label"><?php esc_html_e('Hotel Pickup Location', 'babarida-dive'); ?></label>
                    <input type="text" name="hotel_pickup" class="bbr-form-input" placeholder="Your hotel in Manado (optional)">
                </div>

                <div class="bbr-form-group">
                    <label class="bbr-form-label"><?php esc_html_e('Special Requests', 'babarida-dive'); ?></label>
                    <textarea name="special_requests" class="bbr-form-textarea" rows="3" placeholder="Equipment sizes, dietary needs, certifications, etc."></textarea>
                </div>

                <button type="submit" class="bbr-btn bbr-btn-yellow" style="width:100%;justify-content:center;padding:1rem;font-size:1rem">
                    <?php esc_html_e('Submit Booking Request', 'babarida-dive'); ?>
                </button>

                <p style="text-align:center;font-size:.78rem;color:var(--gray-400);margin-top:1rem"><?php esc_html_e('No payment required now. We will send you a confirmation with payment details.', 'babarida-dive'); ?></p>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>
