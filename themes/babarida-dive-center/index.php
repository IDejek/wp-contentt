<?php
/**
 * Fallback Index Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
get_header();
?>
<section class="bbr-archive-hero">
    <h1 class="bbr-archive-title"><?php esc_html_e('Welcome', 'babarida-dive'); ?></h1>
    <p class="bbr-archive-desc"><?php esc_html_e('Explore our diving paradise in North Sulawesi.', 'babarida-dive'); ?></p>
</section>
<section class="bbr-section">
    <div class="bbr-container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article <?php post_class('bbr-reveal'); ?> style="margin-bottom:2rem">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php the_excerpt(); ?></p>
            </article>
        <?php endwhile; ?>
            <div class="bbr-reveal" style="display:flex;justify-content:center;gap:.5rem">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'class'     => '',
                ));
                ?>
            </div>
        <?php else : ?>
            <p style="text-align:center;color:#6b7280;padding:3rem"><?php esc_html_e('No content found.', 'babarida-dive'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
