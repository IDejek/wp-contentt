<?php
/**
 * Archive Template (fallback for all CPTs)
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();

 $post_type  = get_post_type();
 $type_obj   = get_post_type_object($post_type);
 $type_name  = $type_obj ? $type_obj->labels->name : __('Archive', 'babarida-dive');
 $type_desc  = $type_obj ? $type_obj->description : '';
?>

<section class="bbr-archive-hero">
    <h1 class="bbr-archive-title"><?php echo esc_html($type_name); ?></h1>
    <?php if ($type_desc) : ?><p class="bbr-archive-desc"><?php echo esc_html($type_desc); ?></p><?php endif; ?>
</section>

<section class="bbr-section">
    <div class="bbr-container">
        <?php echo do_shortcode('[bbr_search]'); ?>
        <div style="margin-top:2.5rem">
            <?php if (have_posts()) : ?>
                <div class="bbr-liveaboard-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="bbr-boat-card bbr-reveal">
                            <div class="bbr-boat-card-img">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php echo get_the_post_thumbnail(get_the_ID(), 'bbr-card', array('loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(BBR_URI . '/assets/images/archive-default.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="bbr-boat-card-body">
                                <span class="bbr-badge bbr-badge-blue" style="margin-bottom:.35rem"><?php echo esc_html($type_name); ?></span>
                                <h3 class="bbr-boat-card-name"><a href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h3>
                                <p style="font-size:.82rem;color:var(--gray-500);margin:.5rem 0"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                <a href="<?php the_permalink(); ?>" class="bbr-btn bbr-btn-primary" style="padding:.45rem 1rem;font-size:.78rem"><?php esc_html_e('View Details', 'babarida-dive'); ?></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div style="display:flex;justify-content:center;margin-top:2.5rem">
                    <?php the_posts_pagination(array('mid_size' => 2, 'prev_text' => '&larr;', 'next_text' => '&rarr;')); ?>
                </div>
            <?php else : ?>
                <p style="text-align:center;color:var(--gray-400);padding:3rem"><?php esc_html_e('No items found.', 'babarida-dive'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
