<?php
/**
 * Default Single Post Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

// Route to specific CPT templates
 $post_type = get_post_type();
 $cpt_templates = array(
    'trip'        => 'single-trip.php',
    'liveaboard'  => 'single-liveaboard.php',
    'destination' => 'single-destination.php',
    'hotel'       => 'single-hotel.php',
    'water_sport' => 'single-trip.php',
    'dive_course' => 'single-trip.php',
);

if (isset($cpt_templates[$post_type]) && file_exists(BBR_DIR . '/' . $cpt_templates[$post_type])) {
    include BBR_DIR . '/' . $cpt_templates[$post_type];
    return;
}

// Default single template (for standard posts, testimonials, FAQ, etc.)
get_header();
?>
<section class="bbr-single-hero" style="height:40vh;min-height:300px">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('bbr-hero', array('loading' => 'eager')); ?>
    <?php else : ?>
        <img src="<?php echo esc_url(BBR_URI . '/assets/images/post-default.jpg'); ?>" alt="" loading="eager">
    <?php endif; ?>
    <div class="bbr-single-hero-overlay">
        <div class="bbr-single-hero-content">
            <?php if (get_post_type() === 'post') : ?>
                <div class="bbr-single-breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'babarida-dive'); ?></a>
                    <span>/</span>
                    <a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'babarida-dive'); ?></a>
                    <span>/</span>
                    <span><?php the_title(); ?></span>
                </div>
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
            <?php if (get_post_type() === 'post') : ?>
                <div style="margin-top:.5rem;font-size:.85rem;color:rgba(255,255,255,.7)">
                    <?php echo get_the_date(); ?> &middot; <?php the_category(', '); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="bbr-single-content">
    <?php the_content(); ?>
    <?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '</div>')); ?>

    <?php if (get_post_type() === 'post') : ?>
        <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--gray-200)">
            <?php
            the_post_navigation(array(
                'prev_text' => '&larr; %title',
                'next_text' => '%title &rarr;',
            ));
            ?>
        </div>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div style="margin-top:3rem">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php get_footer(); ?>
