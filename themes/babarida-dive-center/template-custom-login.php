<?php
/**
 * Template: Custom Login Page
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

// Handle login
 $errors = array();
if (isset($_POST['bbr_login_submit'])) {
    $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
    if (!wp_verify_nonce($nonce, 'bbr_login_action')) {
        $errors[] = __('Security verification failed.', 'babarida-dive');
    } else {
        $creds = array(
            'user_login'    => sanitize_text_field(wp_unslash($_POST['log'] ?? '')),
            'user_password' => $_POST['pwd'] ?? '',
            'remember'      => isset($_POST['rememberme']),
        );

        if (empty($creds['user_login']) || empty($creds['user_password'])) {
            $errors[] = __('Please enter username and password.', 'babarida-dive');
        } else {
            $user = wp_signon($creds, is_ssl());

            if (is_wp_error($user)) {
                $errors[] = $user->get_error_message();
            } else {
                $redirect = isset($_GET['redirect_to']) ? esc_url_raw($_GET['redirect_to']) : '';
                if (empty($redirect)) {
                    $dash_page = get_option('bbr_dashboard_page', 0);
                    $redirect = $dash_page ? get_permalink($dash_page) : home_url('/');
                }
                wp_safe_redirect($redirect);
                exit;
            }
        }
    }
}

// Check if already logged in
if (is_user_logged_in()) {
    $dash_page = get_option('bbr_dashboard_page', 0);
    $redirect = $dash_page ? get_permalink($dash_page) : home_url('/');
    wp_safe_redirect($redirect);
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php esc_html_e('Login', 'babarida-dive'); ?> — <?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body class="bbr-login-page">
    <div class="bbr-login-card">
        <div class="bbr-login-logo">
            <div class="bbr-logo-text" style="font-size:1.5rem">Babarida<span style="display:block;font-size:.65rem;font-family:var(--font-body);font-weight:400;letter-spacing:.15em;text-transform:uppercase;opacity:.6;margin-top:2px">Dive Center</span></div>
        </div>

        <?php if (!empty($errors)) : ?>
            <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius-md);padding:.75rem 1rem;margin-bottom:1.5rem">
                <?php foreach ($errors as $err) : ?>
                    <p style="color:#DC2626;font-size:.82rem;margin:0"><?php echo esc_html($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="bbr-login-form">
            <?php wp_nonce_field('bbr_login_action', '_wpnonce'); ?>

            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Username or Email', 'babarida-dive'); ?></label>
                <input type="text" name="log" class="bbr-form-input" required autofocus autocomplete="username" placeholder="admin">
            </div>

            <div class="bbr-form-group">
                <label class="bbr-form-label"><?php esc_html_e('Password', 'babarida-dive'); ?></label>
                <input type="password" name="pwd" class="bbr-form-input" required autocomplete="current-password" placeholder="••••••••">
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;font-size:.82rem;color:rgba(255,255,255,.6)">
                    <input type="checkbox" name="rememberme" value="forever" style="width:16px;height:16px;accent-color:var(--yellow-accent)">
                    <?php esc_html_e('Remember me', 'babarida-dive'); ?>
                </label>
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="font-size:.82rem;color:var(--yellow-accent)"><?php esc_html_e('Lost password?', 'babarida-dive'); ?></a>
            </div>

            <input type="hidden" name="bbr_login_submit" value="1">
            <button type="submit" class="bbr-login-btn"><?php esc_html_e('Sign In', 'babarida-dive'); ?></button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:.82rem;color:rgba(255,255,255,.4)">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.6)">&larr; <?php esc_html_e('Back to website', 'babarida-dive'); ?></a>
        </p>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
