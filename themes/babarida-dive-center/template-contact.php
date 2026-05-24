<?php
/**
 * Template: Contact Page
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $whatsapp = get_theme_mod('bbr_whatsapp', BBR_WHATSAPP);
 $email    = get_theme_mod('bbr_email', BBR_EMAIL);
 $phone    = get_theme_mod('bbr_phone', '');
?>
<section class="bbr-archive-hero" style="padding-bottom:3rem">
    <h1 class="bbr-archive-title"><?php esc_html_e('Contact Us', 'babarida-dive'); ?></h1>
    <p class="bbr-archive-desc"><?php esc_html_e('Ready to dive? Reach out and we will help you plan the perfect trip.', 'babarida-dive'); ?></p>
</section>

<section class="bbr-section">
    <div class="bbr-container" style="max-width:1100px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem">

            <!-- Contact Info -->
            <div class="bbr-reveal">
                <h2 style="font-size:1.75rem;margin-bottom:1.5rem"><?php esc_html_e('Get in Touch', 'babarida-dive'); ?></h2>

                <div style="display:flex;flex-direction:column;gap:1.5rem;margin-bottom:2rem">
                    <div style="display:flex;align-items:flex-start;gap:1rem">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(0,119,182,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--blue-primary);font-size:1.2rem">💬</div>
                        <div>
                            <h4 style="font-size:.9rem;margin-bottom:.2rem;font-family:var(--font-body)"><?php esc_html_e('WhatsApp', 'babarida-dive'); ?></h4>
                            <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" rel="noopener" style="color:var(--blue-primary);font-size:.92rem">+<?php echo esc_html($whatsapp); ?></a>
                        </div>
                    </div>

                    <div style="display:flex;align-items:flex-start;gap:1rem">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(0,119,182,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--blue-primary);font-size:1.2rem">📧</div>
                        <div>
                            <h4 style="font-size:.9rem;margin-bottom:.2rem;font-family:var(--font-body)"><?php esc_html_e('Email', 'babarida-dive'); ?></h4>
                            <a href="mailto:<?php echo esc_attr($email); ?>" style="color:var(--blue-primary);font-size:.92rem"><?php echo esc_html($email); ?></a>
                        </div>
                    </div>

                    <?php if ($phone) : ?>
                    <div style="display:flex;align-items:flex-start;gap:1rem">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(0,119,182,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--blue-primary);font-size:1.2rem">📱</div>
                        <div>
                            <h4 style="font-size:.9rem;margin-bottom:.2rem;font-family:var(--font-body)"><?php esc_html_e('Phone', 'babarida-dive'); ?></h4>
                            <a href="tel:<?php echo esc_attr($phone); ?>" style="color:var(--blue-primary);font-size:.92rem"><?php echo esc_html($phone); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div style="display:flex;align-items:flex-start;gap:1rem">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(0,119,182,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--blue-primary);font-size:1.2rem">📍</div>
                        <div>
                            <h4 style="font-size:.9rem;margin-bottom:.2rem;font-family:var(--font-body)"><?php esc_html_e('Location', 'babarida-dive'); ?></h4>
                            <p style="font-size:.92rem;color:var(--gray-600)"><?php esc_html_e('Bunaken Island, Manado, North Sulawesi, Indonesia', 'babarida-dive'); ?></p>
                        </div>
                    </div>

                    <div style="display:flex;align-items:flex-start;gap:1rem">
                        <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(0,119,182,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--blue-primary);font-size:1.2rem">🕐</div>
                        <div>
                            <h4 style="font-size:.9rem;margin-bottom:.2rem;font-family:var(--font-body)"><?php esc_html_e('Operating Hours', 'babarida-dive'); ?></h4>
                            <p style="font-size:.92rem;color:var(--gray-600)"><?php esc_html_e('Daily: 07:00 — 18:00 (WITA)', 'babarida-dive'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div style="border-radius:var(--radius-xl);overflow:hidden;border:1px solid var(--gray-200);height:250px">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.476760978688!2d124.746!3d1.623!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d0a92e0e7f2e7a3%3A0x5030bfbca83b1b0!2sBunaken%20Island!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Babarida Dive Center Location"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bbr-reveal bbr-reveal-delay-2">
                <div style="background:var(--white-pure);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);border:1px solid var(--gray-100);padding:2.5rem">
                    <h2 style="font-size:1.5rem;margin-bottom:1.5rem"><?php esc_html_e('Send a Message', 'babarida-dive'); ?></h2>
                    <form id="bbr-contact-form">
                        <div class="bbr-form-group">
                            <label class="bbr-form-label"><?php esc_html_e('Your Name *', 'babarida-dive'); ?></label>
                            <input type="text" id="contact-name" class="bbr-form-input" required placeholder="Full name">
                        </div>
                        <div class="bbr-form-group">
                            <label class="bbr-form-label"><?php esc_html_e('Email Address *', 'babarida-dive'); ?></label>
                            <input type="email" id="contact-email" class="bbr-form-input" required placeholder="your@email.com">
                        </div>
                        <div class="bbr-form-group">
                            <label class="bbr-form-label"><?php esc_html_e('Subject', 'babarida-dive'); ?></label>
                            <input type="text" id="contact-subject" class="bbr-form-input" placeholder="How can we help?">
                        </div>
                        <div class="bbr-form-group">
                            <label class="bbr-form-label"><?php esc_html_e('Message *', 'babarida-dive'); ?></label>
                            <textarea id="contact-message" class="bbr-form-textarea" rows="5" required placeholder="Tell us about your trip plans..."></textarea>
                        </div>
                        <button type="submit" class="bbr-btn bbr-btn-primary" style="width:100%;justify-content:center;padding:.9rem">
                            <?php esc_html_e('Send Message', 'babarida-dive'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
