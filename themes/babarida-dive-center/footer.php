<?php
/**
 * Footer Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

 $socials = array(
    'instagram'  => get_theme_mod('bbr_social_instagram', ''),
    'facebook'   => get_theme_mod('bbr_social_facebook', ''),
    'youtube'    => get_theme_mod('bbr_social_youtube', ''),
    'tiktok'     => get_theme_mod('bbr_social_tiktok', ''),
    'tripadvisor'=> get_theme_mod('bbr_social_tripadvisor', ''),
);
 $social_icons = array(
    'instagram'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg
    'facebook'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
    'youtube'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.43z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>',
    'tiktok'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.88-2.88 2.89 2.89 0 0 1 2.88-2.88c.28 0 .56.04.82.11V9.02a6.35 6.35 0 0 0-.82-.05A6.34 6.34 0 0 0 3.15 15.3a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.75a8.18 8.18 0 0 0 4.76 1.52V6.84a4.84 4.84 0 0 1-1-.15z"/></svg>',
    'tripadvisor'=> '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>',
);

 $footer_menus = array(
    'destinations' => array(
        'label' => __('Destinations', 'babarida-dive'),
        'items' => array(
            array('label' => 'Bunaken', 'url' => home_url('/destinations/bunaken/')),
            array('label' => 'Siladen', 'url' => home_url('/destinations/siladen/')),
            array('label' => 'Bangka', 'url' => home_url('/destinations/bangka/')),
            array('label' => 'Lembeh', 'url' => home_url('/destinations/lembeh/')),
        ),
    ),
    'services' => array(
        'label' => __('Services', 'babarida-dive'),
        'items' => array(
            array('label' => __('Liveaboards', 'babarida-dive'), 'url' => get_post_type_archive_link('liveaboard')),
            array('label' => __('Dive Courses', 'babarida-dive'), 'url' => get_post_type_archive_link('dive_course')),
            array('label' => __('Water Sports', 'babarida-dive'), 'url' => get_post_type_archive_link('water_sport')),
            array('label' => __('Snorkeling', 'babarida-dive'), 'url' => get_post_type_archive_link('trip')),
            array('label' => __('Pricing', 'babarida-dive'), 'url' => home_url('/pricing/')),
        ),
    ),
    'company' => array(
        'label' => __('Company', 'babarida-dive'),
        'items' => array(
            array('label' => __('About Us', 'babarida-dive'), 'url' => home_url('/about/')),
            array('label' => __('Blog', 'babarida-dive'), 'url' => home_url('/blog/')),
            array('label' => __('FAQ', 'babarida-dive'), 'url' => home_url('/faq/')),
            array('label' => __('Contact', 'babarida-dive'), 'url' => home_url('/contact/')),
            array('label' => __('Partners', 'babarida-dive'), 'url' => home_url('/partners-page/')),
        ),
    ),
);
?>

<!-- Floating Contact Buttons -->
<div class="bbr-floating-btns">
    <a href="https://wa.me/<?php echo esc_attr(get_theme_mod('bbr_whatsapp', BBR_WHATSAPP)); ?>" target="_blank" rel="noopener" class="bbr-float-btn whatsapp" aria-label="WhatsApp">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.61.609l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.318-.704-6.024-1.902l-.42-.298-2.646.887.887-2.646-.298-.42A9.953 9.953 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        <span class="bbr-float-tooltip">WhatsApp</span>
    </a>
    <a href="mailto:<?php echo esc_attr(get_theme_mod('bbr_email', BBR_EMAIL)); ?>" class="bbr-float-btn email" aria-label="Email">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        <span class="bbr-float-tooltip">Email</span>
    </a>
    <a href="<?php echo esc_url(home_url('/book-now/')); ?>" class="bbr-float-btn booking" aria-label="Book Now">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span class="bbr-float-tooltip">Book Now</span>
    </a>
    <button class="bbr-float-btn top" aria-label="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
</div>

<!-- AI Chat Widget -->
<div class="bbr-ai-chat">
    <div class="bbr-ai-chat-window">
        <div class="bbr-ai-chat-header">
            <div>
                <strong style="font-size:.9rem">Babarida Assistant</strong>
                <div style="font-size:.7rem;opacity:.7">Ask about diving, trips, courses</div>
            </div>
            <button onclick="this.closest('.bbr-ai-chat-window').classList.remove('open')" style="background:none;border:none;color:#fff;cursor:pointer;font-size:1.2rem">&times;</button>
        </div>
        <div class="bbr-ai-chat-messages"></div>
        <div class="bbr-ai-chat-input">
            <input type="text" placeholder="Ask me anything..." aria-label="Chat message">
            <button aria-label="Send message">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
    </div>
    <button class="bbr-ai-chat-btn" aria-label="Open chat assistant">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </button>
</div>

<!-- Footer -->
<footer class="bbr-footer">
    <div class="bbr-container-wide">
        <div class="bbr-footer-grid">
            <!-- Brand Column -->
            <div class="bbr-footer-brand">
                <div class="bbr-logo-text" style="font-size:1.4rem;margin-bottom:1rem">Babarida<span style="display:block;font-size:.7rem;font-family:var(--font-body);font-weight:400;letter-spacing:.15em;text-transform:uppercase;opacity:.6;margin-top:3px">Dive Center</span></div>
                <p class="bbr-footer-desc"><?php echo esc_html(get_bloginfo('description')); ?></p>
                <div class="bbr-footer-social">
                    <?php foreach ($socials as $key => $url) : ?>
                        <?php if (!empty($url)) : ?>
                            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr(ucfirst($key)); ?>">
                                <?php echo $social_icons[$key]; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dynamic Footer Columns -->
            <?php foreach ($footer_menus as $section) : ?>
                <div class="bbr-footer-col">
                    <h4><?php echo esc_html($section['label']); ?></h4>
                    <ul>
                        <?php foreach ($section['items'] as $item) : ?>
                            <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="bbr-footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'babarida-dive'); ?></p>
            <p>
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'babarida-dive'); ?></a>
                <span style="margin:0 .5rem;opacity:.3">|</span>
                <a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms of Service', 'babarida-dive'); ?></a>
                <span style="margin:0 .5rem;opacity:.3">|</span>
                <?php esc_html_e('Developed by', 'babarida-dive'); ?> <a href="mailto:tombinawaiqbal@gmail.com">Iqbal Tombinawa</a>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
