<?php
/**
 * Template: Partners Page
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $partners = get_posts(array('post_type' => 'partner', 'posts_per_page' => -1, 'post_status' => 'publish'));
?>
<section class="bbr-archive-hero" style="padding-bottom:3rem">
    <h1 class="bbr-archive-title"><?php esc_html_e('Our Partners', 'babarida-dive'); ?></h1>
    <p class="bbr-archive-desc"><?php esc_html_e('Trusted partners who help us deliver world-class diving experiences.', 'babarida-dive'); ?></p>
</section>

<section class="bbr-section">
    <div class="bbr-container">
        <?php if (!empty($partners)) : ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem">
            <?php foreach ($partners as $partner) :
                $url = bbr_get_meta($partner->ID, 'partner', 'partner_url', '');
                $cat = bbr_get_meta($partner->ID, 'partner', 'partner_cat', '');
            ?>
            <div style="background:var(--white-pure);border-radius:var(--radius-lg);padding:2rem;text-align:center;box-shadow:var(--shadow-sm);border:1px solid var(--gray-100);transition:all .3s" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <?php if (has_post_thumbnail($partner->ID)) : ?>
                    <?php echo get_the_post_thumbnail($partner->ID, 'full', array('style' => 'height:60px;width:auto;object-fit:contain;margin:0 auto 1rem;opacity:.6', 'loading' => 'lazy')); ?>
                <?php else : ?>
                    <div style="height:60px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:var(--gray-400);margin-bottom:1rem"><?php echo esc_html(mb_substr($partner->post_title, 0, 2)); ?></div>
                <?php endif; ?>
                <h3 style="font-size:1rem;margin-bottom:.25rem"><?php echo esc_html($partner->post_title); ?></h3>
                <?php if ($cat) : ?><p style="font-size:.78rem;color:var(--gray-400);margin-bottom:.75rem"><?php echo esc_html(ucfirst($cat)); ?></p><?php endif; ?>
                <?php if ($url) : ?><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="bbr-btn bbr-btn-outline" style="padding:.35rem 1rem;font-size:.78rem"><?php esc_html_e('Visit', 'babarida-dive'); ?></a><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <p style="text-align:center;color:var(--gray-400);padding:4rem"><?php esc_html_e('No partners added yet.', 'babarida-dive'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
