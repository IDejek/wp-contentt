<?php
/**
 * Default Page Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<?php if (is_page_template('template-dashboard.php') || is_page_template('template-checkin.php') || is_page_template('template-booking.php') || is_page_template('template-pricing.php') || is_page_template('template-partners.php') || is_page_template('template-contact.php')) : ?>
    <?php // These templates handle their own layout ?>
<?php else : ?>
<section class="bbr-single-hero" style="height:40vh;min-height:320px">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/page-default.jpg'); ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <div class="bbr-single-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</section>
<section class="bbr-single-content">
    <?php the_content(); ?>
    <?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '</div>')); ?>
</section>
<?php endif; ?>
<?php get_footer(); ?>
