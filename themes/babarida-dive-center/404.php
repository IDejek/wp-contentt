<?php
/**
 * 404 Error Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<section style="min-height:70vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:6rem 2rem">
    <div>
        <div style="font-family:var(--font-display);font-size:clamp(5rem,15vw,10rem);font-weight:700;color:var(--blue-primary);line-height:1;opacity:.3">404</div>
        <h1 style="margin:1rem 0;font-size:clamp(1.5rem,3vw,2.5rem)"><?php esc_html_e('Page Not Found', 'babarida-dive'); ?></h1>
        <p style="color:var(--gray-500);max-width:500px;margin:0 auto 2rem"><?php esc_html_e('The page you are looking for doesn\'t exist or has been moved. Let\'s get you back on track.', 'babarida-dive'); ?></p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="bbr-btn bbr-btn-primary"><?php esc_html_e('Go Home', 'babarida-dive'); ?></a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="bbr-btn bbr-btn-outline"><?php esc_html_e('Contact Us', 'babarida-dive'); ?></a>
        </div>
    </div>
</section>
<?php get_footer(); ?>
