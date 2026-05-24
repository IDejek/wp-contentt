<?php
/**
 * Header Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#0077B6">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Preloader -->
<div class="bbr-preloader">
    <div class="bbr-preloader-logo"><?php echo esc_html(get_bloginfo('name')); ?></div>
    <div class="bbr-preloader-wave">
        <svg viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
            <path class="wave1" d="M0,30 C50,10 50,50 100,30 C150,10 150,50 200,30 L200,60 L0,60 Z" fill="rgba(255,255,255,0.15)"/>
            <path class="wave2" d="M0,35 C50,15 50,55 100,35 C150,15 150,55 200,35 L200,60 L0,60 Z" fill="rgba(255,255,255,0.1)"/>
            <path class="wave3" d="M0,40 C50,20 50,60 100,40 C150,20 150,60 200,40 L200,60 L0,60 Z" fill="rgba(255,255,255,0.05)"/>
        </svg>
    </div>
</div>

<!-- Top Bar -->
<div class="bbr-topbar" id="bbr-topbar">
    <div class="bbr-topbar-left">
        <a href="<?php echo esc_url(home_url('/check-in/')); ?>" class="bbr-topbar-checkin">✈ CHECK-IN</a>
    </div>

    <div class="bbr-topbar-center">
        <!-- Desktop clocks rendered by JS -->
    </div>

    <!-- Mobile clocks (hidden on desktop) -->
    <div class="bbr-mobile-clock" style="display:none;gap:.75rem;align-items:center">
        <!-- Rendered by JS -->
    </div>

    <div class="bbr-topbar-right">
        <a href="https://wa.me/<?php echo esc_attr(get_theme_mod('bbr_whatsapp', BBR_WHATSAPP)); ?>" target="_blank" rel="noopener" class="bbr-topbar-icon" aria-label="WhatsApp">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.61.609l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.318-.704-6.024-1.902l-.42-.298-2.646.887.887-2.646-.298-.42A9.953 9.953 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        </a>
        <a href="mailto:<?php echo esc_attr(get_theme_mod('bbr_email', BBR_EMAIL)); ?>" class="bbr-topbar-icon" aria-label="Email">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        </a>
        <div class="bbr-lang-switch">
            <span class="active-lang"><?php echo function_exists('pll_current_language') ? strtoupper(pll_current_language()) : 'EN'; ?></span>
            <span class="sep">/</span>
            <span><?php echo function_exists('pll_current_language') && pll_current_language() === 'id' ? 'EN' : 'ID'; ?></span>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bbr-header" id="bbr-header">
    <div class="bbr-header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="bbr-logo-group">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <div class="bbr-logo-text" style="font-size:1.4rem">
                    Babarida
                    <span>Dive Center</span>
                </div>
            <?php endif; ?>
        </a>

        <nav class="bbr-nav" aria-label="Primary navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'bbr-nav-list',
                'walker'         => new BBR_Mega_Menu_Walker(),
                'fallback_cb'    => false,
                'depth'          => 2,
            ));
            ?>
        </nav>

        <button class="bbr-mobile-toggle" id="bbr-mobile-toggle" aria-label="Toggle menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<!-- Mobile Navigation -->
<nav class="bbr-mobile-nav" id="bbr-mobile-nav" aria-label="Mobile navigation">
    <?php
    // Build mobile menu from all registered locations
    $mobile_locations = array('primary','bunaken','siladen','bangka','lembeh','liveaboard','info');
    $mobile_items = array();
    foreach ($mobile_locations as $loc) {
        $locations = get_nav_menu_locations();
        if (isset($locations[$loc])) {
            $menu = wp_get_nav_menu_object($locations[$loc]);
            if ($menu) {
                $items = wp_get_nav_menu_items($menu->term_id);
                if ($items) {
                    $loc_label = $loc === 'primary' ? '' : strtoupper(str_replace('_', ' ', $loc));
                    foreach ($items as $item) {
                        $mobile_items[] = array(
                            'label'   => $item->title,
                            'url'     => $item->url,
                            'loc'     => $loc_label,
                            'parent'  => $item->menu_item_parent,
                            'id'      => $item->ID,
                        );
                    }
                }
            }
        }
    }

    // Group by location
    $grouped = array();
    foreach ($mobile_items as $mi) {
        $grouped[$mi['loc']][] = $mi;
    }

    if (!empty($grouped)) :
    ?>
    <ul class="bbr-mobile-nav-list">
        <?php foreach ($grouped as $loc => $items) : ?>
            <?php if ($loc) : ?>
                <li style="padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#0077B6;margin-top:.5rem"><?php echo esc_html($loc); ?></li>
            <?php endif; ?>
            <?php foreach ($items as $item) : ?>
                <?php if ($item['parent'] == 0) : ?>
                    <li><a href="<?php echo esc_url($item['url']); ?>" class="bbr-mobile-nav-link"><?php echo esc_html($item['label']); ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php if (is_user_logged_in()) : ?>
            <li style="padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:#0077B6;margin-top:.5rem">ACCOUNT</li>
            <li><a href="<?php echo esc_url(get_permalink(get_option('bbr_dashboard_page', 0))); ?>" class="bbr-mobile-nav-link">📊 Dashboard</a></li>
            <li><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="bbr-mobile-nav-link">🚪 Logout</a></li>
        <?php else : ?>
            <li><a href="<?php echo esc_url(wp_login_url()); ?>" class="bbr-mobile-nav-link">🔑 Login</a></li>
        <?php endif; ?>
    </ul>
    <?php else : ?>
        <ul class="bbr-mobile-nav-list">
            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="bbr-mobile-nav-link">Home</a></li>
            <li><a href="<?php echo esc_url(home_url('/?post_type=destination')); ?>" class="bbr-mobile-nav-link">Destinations</a></li>
            <li><a href="<?php echo esc_url(home_url('/?post_type=liveaboard')); ?>" class="bbr-mobile-nav-link">Liveaboards</a></li>
            <li><a href="<?php echo esc_url(home_url('/?post_type=trip')); ?>" class="bbr-mobile-nav-link">Trips</a></li>
            <li><a href="<?php echo esc_url(home_url('/pricing/')); ?>" class="bbr-mobile-nav-link">Pricing</a></li>
            <li><a href="<?php echo esc_url(home_url('/contact/')); ?>" class="bbr-mobile-nav-link">Contact</a></li>
        </ul>
    <?php endif; ?>
</nav>
