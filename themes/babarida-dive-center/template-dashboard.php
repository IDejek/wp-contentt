<?php
/**
 * Template: Dashboard
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

 $user     = wp_get_current_user();
 $allowed  = bbr_dashboard_allowed_pages($user->ID);
 $all_tabs = array(
    'dashboard'      => array('icon' => '📊', 'label' => __('Dashboard', 'babarida-dive')),
    'bookings'       => array('icon' => '📋', 'label' => __('Bookings', 'babarida-dive')),
    'checkin'        => array('icon' => '✅', 'label' => __('Check-In', 'babarida-dive')),
    'reports'        => array('icon' => '📈', 'label' => __('Reports', 'babarida-dive')),
    'analytics'      => array('icon' => '👁️', 'label' => __('Analytics', 'babarida-dive')),
    'finance'        => array('icon' => '💰', 'label' => __('Finance', 'babarida-dive')),
    'content'        => array('icon' => '📝', 'label' => __('Content', 'babarida-dive')),
    'activity-log'   => array('icon' => '📜', 'label' => __('Activity Log', 'babarida-dive')),
    'system-health'  => array('icon' => '🩺', 'label' => __('System Health', 'babarida-dive')),
    'backups'        => array('icon' => '💾', 'label' => __('Backups', 'babarida-dive')),
    'settings'       => array('icon' => '⚙️', 'label' => __('Settings', 'babarida-dive')),
);

 $level = bbr_get_member_level($user->ID);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html(get_bloginfo('name') . ' — Dashboard'); ?></title>
    <?php wp_head(); ?>
</head>
<body class="bbr-dash">
    <button class="bbr-dash-sidebar-toggle" onclick="document.querySelector('.bbr-dash-sidebar').classList.toggle('open')">☰</button>

    <!-- Sidebar -->
    <aside class="bbr-dash-sidebar" id="bbr-dash-sidebar">
        <div class="bbr-dash-sidebar-brand">
            <div class="bbr-logo-text" style="font-size:1rem;color:#fff">Babarida<span style="display:block;font-size:.6rem;font-family:var(--font-body);font-weight:400;letter-spacing:.1em;text-transform:uppercase;opacity:.6;margin-top:2px">Dashboard</span></div>
        </div>

        <div style="padding:0 1rem;margin-bottom:1rem">
            <div style="display:flex;align-items:center;gap:.75rem;padding:.75rem;background:rgba(255,255,255,.08);border-radius:var(--radius-md)">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#0077B6,#00B4D8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.85rem;flex-shrink:0"><?php echo esc_html(mb_substr($user->display_name, 0, 1)); ?></div>
                <div>
                    <div style="color:#fff;font-size:.82rem;font-weight:600;line-height:1.2"><?php echo esc_html($user->display_name); ?></div>
                    <div style="color:rgba(255,255,255,.5);font-size:.68rem"><?php echo esc_html($level['name']); ?> &middot; <?php echo esc_html(ucfirst(str_replace('_', ' ', $user->roles[0] ?? 'user'))); ?></div>
                </div>
            </div>
        </div>

        <nav class="bbr-dash-nav">
            <?php foreach ($all_tabs as $tab_slug => $tab_info) : ?>
                <?php if (!in_array($tab_slug, $allowed)) continue; ?>
                <a href="#" class="bbr-dash-nav-link" data-tab="<?php echo esc_attr($tab_slug); ?>" onclick="event.preventDefault();bbrLoadDashboard('<?php echo esc_js($tab_slug); ?>')">
                    <span class="icon"><?php echo $tab_info['icon']; ?></span>
                    <span><?php echo esc_html($tab_info['label']); ?></span>
                </a>
            <?php endforeach; ?>

            <div class="bbr-dash-nav-label" style="margin-top:1rem"><?php esc_html_e('Quick Links', 'babarida-dive'); ?></div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="bbr-dash-nav-link" target="_blank">
                <span class="icon">🌐</span>
                <span><?php esc_html_e('View Website', 'babarida-dive'); ?></span>
            </a>
            <a href="<?php echo esc_url(admin_url()); ?>" class="bbr-dash-nav-link" target="_blank">
                <span class="icon">⚙️</span>
                <span><?php esc_html_e('WP Admin', 'babarida-dive'); ?></span>
            </a>
            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="bbr-dash-nav-link">
                <span class="icon">🚪</span>
                <span><?php esc_html_e('Logout', 'babarida-dive'); ?></span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="bbr-dash-main">
        <div class="bbr-dash-header">
            <h1 class="bbr-dash-title" id="bbr-dash-page-title"><?php esc_html_e('Dashboard', 'babarida-dive'); ?></h1>
            <div style="display:flex;align-items:center;gap:1rem">
                <div class="bbr-dash-notif" onclick="this.querySelector('.bbr-dash-notif-dropdown').classList.toggle('open')" style="position:relative;cursor:pointer;width:36px;height:36px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center">
                    🔔
                    <span class="bbr-dash-notif-badge" id="bbr-notif-count">0</span>
                    <div class="bbr-dash-notif-dropdown" id="bbr-notif-dropdown">
                        <div style="padding:1rem;border-bottom:1px solid var(--gray-100);font-weight:600;font-size:.85rem"><?php esc_html_e('Notifications', 'babarida-dive'); ?></div>
                        <div id="bbr-notif-list" style="max-height:300px;overflow-y:auto"><p style="padding:1rem;color:var(--gray-400);font-size:.82rem;text-align:center"><?php esc_html_e('No notifications', 'babarida-dive'); ?></p></div>
                    </div>
                </div>
                <div style="font-size:.82rem;color:var(--gray-500)"><?php echo esc_html($user->display_name); ?></div>
            </div>
        </div>
        <div id="bbr-dash-content">
            <p style="text-align:center;padding:3rem;color:var(--gray-400)">Loading dashboard...</p>
        </div>
    </main>

    <?php wp_footer(); ?>

    <script>
    // Load notifications
    (function(){
        fetch(bbrData.ajaxUrl + '?action=bbr_get_notifications&nonce=' + bbrData.nonce)
        .then(r => r.json())
        .then(res => {
            if (res.success && res.data.notifications) {
                var notifs = res.data.notifications;
                var unread = res.data.unread || 0;
                var countEl = document.getElementById('bbr-notif-count');
                var listEl = document.getElementById('bbr-notif-list');
                if (countEl) countEl.textContent = unread;
                if (listEl && notifs.length) {
                    listEl.innerHTML = notifs.slice(0, 10).map(function(n) {
                        return '<div class="bbr-dash-notif-item' + (n.read ? '' : ' unread') + '"><div class="bbr-dash-notif-item-title">' + n.title + '</div><div class="bbr-dash-notif-item-time">' + n.time + '</div></div>';
                    }).join('');
                }
            }
        });
    })();
    </script>
</body>
</html>
