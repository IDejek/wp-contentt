<?php
/**
 * Comments Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

if (post_password_required()) {
    return;
}
?>
<div id="comments" class="bbr-comments" style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--gray-200)">

    <?php if (have_comments()) : ?>
        <h3 style="font-family:var(--font-body);font-size:1.1rem;margin-bottom:1.5rem">
            <?php
            printf(
                esc_html(_nx('%1$s Comment', '%1$s Comments', get_comments_number(), 'comments title', 'babarida-dive')),
                number_format_i18n(get_comments_number())
            );
            ?>
        </h3>

        <ol style="list-style:none;display:flex;flex-direction:column;gap:1.25rem">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'bbr_comment_callback',
            ));
            ?>
        </ol>

        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p style="color:var(--gray-400);font-size:.88rem;text-align:center;padding:1.5rem"><?php esc_html_e('Comments are closed.', 'babarida-dive'); ?></p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'class_form'         => 'bbr-comment-form',
        'class_submit'       => 'bbr-btn bbr-btn-primary',
        'comment_notes_before'=> '<p class="comment-notes" style="font-size:.82rem;color:var(--gray-400);margin-bottom:1rem">Your email address will not be published.</p>',
        'fields'             => array(
            'author' => '<div class="bbr-form-group"><label class="bbr-form-label">' . __('Name', 'babarida-dive') . ' *</label><input type="text" name="author" class="bbr-form-input" required /></div>',
            'email'  => '<div class="bbr-form-group"><label class="bbr-form-label">' . __('Email', 'babarida-dive') . ' *</label><input type="email" name="email" class="bbr-form-input" required /></div>',
            'url'    => '<div class="bbr-form-group"><label class="bbr-form-label">' . __('Website', 'babarida-dive') . '</label><input type="url" name="url" class="bbr-form-input" /></div>',
        ),
        'comment_field'      => '<div class="bbr-form-group"><label class="bbr-form-label">' . __('Comment', 'babarida-dive') . ' *</label><textarea name="comment" class="bbr-form-textarea" rows="4" required></textarea></div>',
    ));
    ?>
</div>

<?php
function bbr_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> style="padding:1.25rem;background:var(--gray-50);border-radius:var(--radius-md);border:1px solid var(--gray-100)">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem">
            <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('style' => 'border-radius:50%;border:2px solid var(--blue-primary)')); ?>
            <div>
                <strong style="font-size:.9rem;color:var(--gray-900)"><?php echo get_comment_author_link(); ?></strong>
                <span style="font-size:.75rem;color:var(--gray-400);margin-left:.5rem"><?php echo get_comment_date(); ?></span>
            </div>
        </div>
        <?php if ('0' == $comment->comment_approved) : ?>
            <p style="font-size:.82rem;color:var(--yellow-warm);margin-bottom:.5rem"><?php esc_html_e('Your comment is awaiting moderation.', 'babarida-dive'); ?></p>
        <?php endif; ?>
        <div style="font-size:.9rem;color:var(--gray-700);line-height:1.7"><?php comment_text(); ?></div>
        <div style="margin-top:.5rem;font-size:.78rem"><?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?></div>
    </<?php echo $tag; ?>>
    <?php
}
