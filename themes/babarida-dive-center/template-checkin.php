<?php
/**
 * Template: Check-In Form
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<section class="bbr-checkin-hero">
    <h1 style="font-size:clamp(2rem,4vw,3rem)"><?php esc_html_e('Guest Check-In', 'babarida-dive'); ?></h1>
    <p style="color:rgba(255,255,255,.8);margin-top:.5rem"><?php esc_html_e('Complete your registration before your dive adventure begins.', 'babarida-dive'); ?></p>
</section>

<div class="bbr-checkin-form-wrap">
    <form class="bbr-booking-form" id="bbr-checkin-form">
        <input type="hidden" name="booking_status" value="pending">

        <h3 style="margin-bottom:1.5rem;font-family:var(--font-display);font-size:1.4rem;color:var(--gray-900)"><?php esc_html_e('Personal Information', 'babarida-dive'); ?></h3>

        <div class="bbr-form-row">
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Full Name *', 'babarida-dive'); ?></label>
                <input type="text" name="guest_name" class="bbr-form-input" required placeholder="John Doe">
            </div>
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Email *', 'babarida-dive'); ?></label>
                <input type="email" name="guest_email" class="bbr-form-input" required placeholder="john@example.com">
            </div>
        </div>

        <div class="bbr-form-row">
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Phone / WhatsApp *', 'babarida-dive'); ?></label>
                <input type="tel" name="guest_phone" class="bbr-form-input" required placeholder="+62 812 3456 7890">
            </div>
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Nationality *', 'babarida-dive'); ?></label>
                <input type="text" name="guest_nationality" class="bbr-form-input" required placeholder="Indonesian / German / etc.">
            </div>
        </div>

        <div class="bbr-form-row">
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Passport Number', 'babarida-dive'); ?></label>
                <input type="text" name="guest_passport" class="bbr-form-input" placeholder="A1234567">
            </div>
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Number of Guests', 'babarida-dive'); ?></label>
                <input type="number" name="num_guests" class="bbr-form-input" value="1" min="1" max="20">
            </div>
        </div>

        <hr style="border:none;border-top:1px solid var(--gray-200);margin:2rem 0">

        <h3 style="margin-bottom:1.5rem;font-family:var(--font-display);font-size:1.4rem;color:var(--gray-900)"><?php esc_html_e('Trip Details', 'babarida-dive'); ?></h3>

        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Trip / Package', 'babarida-dive'); ?></label>
            <select name="trip_name" class="bbr-form-select">
                <option value=""><?php esc_html_e('Select a trip...', 'babarida-dive'); ?></option>
                <?php
                $all_trips = get_posts(array('post_type' => array('trip','liveaboard'), 'posts_per_page' => -1, 'post_status' => 'publish'));
                foreach ($all_trips as $t) : ?>
                    <option value="<?php echo esc_attr($t->post_title); ?>"><?php echo esc_html($t->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bbr-form-row">
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Check-in Date', 'babarida-dive'); ?></label>
                <input type="date" name="check_in_date" class="bbr-form-input">
            </div>
            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Check-out Date', 'babarida-dive'); ?></label>
                <input type="date" name="check_out_date" class="bbr-form-input">
            </div>
        </div>

        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Hotel Pickup Location', 'babarida-dive'); ?></label>
            <input type="text" name="hotel_pickup" class="bbr-form-input" placeholder="Hotel name or address in Manado">
        </div>

        <div class="bbr-form-group">
            <label class="bbr-form-label"><?php esc_html_e('Special Requests', 'babarida-dive'); ?></label>
            <textarea name="special_requests" class="bbr-form-textarea" rows="3" placeholder="Dietary requirements, equipment sizes, etc."></textarea>
        </div>

        <hr style="border:none;border-top:1px solid var(--gray-200);margin:2rem 0">

        <h3 style="margin-bottom:1.5rem;font-family:var(--font-display);font-size:1.4rem;color:var(--gray-900)"><?php esc_html_e('Digital Waiver', 'babarida-dive'); ?></h3>

        <div class="bbr-waiver-content">
            <h4 style="margin-bottom:.75rem"><?php esc_html_e('LIABILITY RELEASE AND ASSUMPTION OF RISK', 'babarida-dive'); ?></h4>
            <p>I, the undersigned, acknowledge that diving, snorkeling, and water sports activities involve inherent risks including but not limited to: equipment failure, environmental hazards, marine life encounters, drowning, decompression sickness, and other diving-related injuries or death.</p>
            <p style="margin-top:.75rem">I hereby release Babarida Dive Center, its owners, employees, guides, and affiliated partners from any and all liability for personal injury, property damage, or death that may occur during my participation in these activities.</p>
            <p style="margin-top:.75rem">I confirm that I am in good physical and mental health, and I have disclosed any medical conditions that may affect my safety while diving.</p>
            <p style="margin-top:.75rem">I understand that I must follow all safety instructions provided by the dive guides and staff at all times.</p>
        </div>

        <div class="bbr-waiver-sign" id="waiver-pad">
            <canvas></canvas>
        </div>
        <p style="font-size:.78rem;color:var(--gray-400);margin-bottom:1rem"><?php esc_html_e('Please sign above using your mouse or finger', 'babarida-dive'); ?></p>

        <div class="bbr-form-group">
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.88rem">
                <input type="checkbox" name="accepted" value="yes" required style="width:18px;height:18px;accent-color:var(--blue-primary)">
                <?php esc_html_e('I have read, understand, and agree to the liability waiver above. *', 'babarida-dive'); ?>
            </label>
        </div>

        <button type="submit" class="bbr-btn bbr-btn-yellow" style="width:100%;justify-content:center;padding:1rem;font-size:1rem;margin-top:1rem">
            <?php esc_html_e('Submit Check-In', 'babarida-dive'); ?>
        </button>
    </form>
</div>

<?php get_footer(); ?>
