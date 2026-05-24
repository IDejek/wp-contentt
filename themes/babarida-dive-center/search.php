<?php
/**
 * Search Results Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<section class="bbr-archive-hero" style="padding-bottom:2rem">
    <h1 class="bbr-archive-title"><?php printf(esc_html__('Search: %s', 'babarida-dive'), '<span style="color:var(--yellow-accent)">' . esc_html(get_search_query()) . '</span>'); ?></h1>
</section>
<section class="bbr-section">
    <div class="bbr-container" style="max-width:800px">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article style="padding:1.5rem 0;border-bottom:1px solid var(--gray-100)" class="bbr-reveal">
                <h3 style="margin-bottom:.35rem"><a href="<?php the_permalink(); ?>" style="color:var(--gray-900)"><?php the_title(); ?></a></h3>
                <p style="color:var(--gray-500);font-size:.88rem;margin:0"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
            </article>
        <?php endwhile; ?>
            <div style="display:flex;justify-content:center;margin-top:2rem"><?php the_posts_pagination(array('mid_size' => 2)); ?></div>
        <?php else : ?>
            <p style="text-align:center;color:var(--gray-400);padding:3rem"><?php esc_html_e('No results found. Try a different search.', 'babarida-dive'); ?></p>
            <div style="text-align:center"><?php get_search_form(); ?></div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
