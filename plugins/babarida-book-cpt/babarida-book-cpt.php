<?php
/**
 * Plugin Name: Babarida Book CPT
 * Plugin URI: https://babaridadive.com
 * Description: Extends the Babarida Dive Center theme with advanced booking management, CRM, pricing engine, payment integration, and admin enhancements.
 * Version: 1.0.0
 * Author: Iqbal Tombinawa
 * Author Email: tombinawaiqbal@gmail.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: babarida-dive
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

defined('ABSPATH') || exit;

/* ============================================
   PLUGIN CONSTANTS
   ============================================ */
define('BBR_BOOK_VERSION', '1.0.0');
define('BBR_BOOK_DIR', plugin_dir_path(__FILE__));
define('BBR_BOOK_URI', plugin_dir_url(__FILE__));

/* ============================================
   ENSURE THEME IS ACTIVE
   ============================================ */
function bbr_book_check_theme() {
    $theme = wp_get_theme();
    if ($theme->get_template() !== 'babarida-dive-center') {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Babarida Book CPT:</strong> This plugin requires the Babarida Dive Center theme to be active.</p></div>';
        });
    }
}
add_action('plugins_loaded', 'bbr_book_check_theme');

/* ============================================
   ADMIN: BOOKING LIST COLUMNS
   ============================================ */
function bbr_book_columns($columns) {
    $new = array();
    $new['cb'] = $columns['cb'];
    $new['booking_id'] = __('Booking ID', 'babarida-dive');
    $new['guest'] = __('Guest', 'babarida-dive');
    $new['trip'] = __('Trip', 'babarida-dive');
    $new['date_in'] = __('Check-in', 'babarida-dive');
    $new['guests'] = __('Guests', 'babarida-dive');
    $new['total'] = __('Total', 'babarida-dive');
    $new['status'] = __('Status', 'babarida-dive');
    $new['date'] = __('Created', 'babarida-dive');
    return $new;
}
add_filter('manage_booking_posts_columns', 'bbr_book_columns');

function bbr_book_column_content($column, $post_id) {
    switch ($column) {
        case 'booking_id':
            $qr = get_post_meta($post_id, '_bbr_booking_qr_code', true);
            echo $qr ? '<strong>' . esc_html($qr) . '</strong>' : '#' . $post_id;
            break;
        case 'guest':
            $name = get_post_meta($post_id, '_bbr_booking_guest_name', true);
            $email = get_post_meta($post_id, '_bbr_booking_guest_email', true);
            echo esc_html($name);
            if ($email) echo '<br><small style="color:#9ca3af">' . esc_html($email) . '</small>';
            break;
        case 'trip':
            echo esc_html(get_post_meta($post_id, '_bbr_booking_trip_name', true) ?: '—');
            break;
        case 'date_in':
            echo esc_html(get_post_meta($post_id, '_bbr_booking_check_in_date', true) ?: '—');
            break;
        case 'guests':
            echo esc_html(get_post_meta($post_id, '_bbr_booking_num_guests', true) ?: '—');
            break;
        case 'total':
            $price = get_post_meta($post_id, '_bbr_booking_total_price', true);
            $curr = get_post_meta($post_id, '_bbr_booking_currency', true) ?: 'USD';
            echo $price ? esc_html(bbr_format_price($price, $curr)) : '—';
            break;
        case 'status':
            $st = get_post_meta($post_id, '_bbr_booking_booking_status', true) ?: 'pending';
            echo '<span class="bbr-status ' . esc_attr($st) . '">' . esc_html(ucfirst(str_replace('-', ' ', $st))) . '</span>';
            break;
    }
}
add_action('manage_booking_posts_custom_column', 'bbr_book_column_content', 10, 2);

/* ============================================
   ADMIN: BOOKING ROW ACTIONS
   ============================================ */
function bbr_book_row_actions($actions, $post) {
    if ($post->post_type !== 'booking') return $actions;

    $status = get_post_meta($post->post_ID, '_bbr_booking_booking_status', true) ?: 'pending';
    $qr = get_post_meta($post->post_ID, '_bbr_booking_qr_code', true);

    // Remove default quick edit for bookings
    unset($actions['inline hide-if-no-js']);

    // Add status change actions
    if ($status !== 'confirmed') {
        $actions['confirm'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=bbr_set_status&post=' . $post->ID . '&status=confirmed'), 'bbr_set_status_' . $post->ID)) . '">' . __('Confirm', 'babarida-dive') . '</a>';
    }
    if ($status !== 'completed') {
        $actions['complete'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=bbr_set_status&post=' . $post->ID . '&status=completed'), 'bbr_set_status_' . $post->ID)) . '">' . __('Complete', 'babarida-dive') . '</a>';
    }
    if ($status !== 'cancelled') {
        $actions['cancel'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=bbr_set_status&post=' . $post->ID . '&status=cancelled'), 'bbr_set_status_' . $post->ID)) . '" style="color:#DC2626">' . __('Cancel', 'babarida-dive') . '</a>';
    }

    return $actions;
}
add_filter('post_row_actions', 'bbr_book_row_actions', 10, 2);

/* ============================================
   ADMIN: HANDLE STATUS CHANGE VIA ROW ACTION
   ============================================ */
function bbr_handle_set_status() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'bbr_set_status') return;
    if (!isset($_GET['post'], $_GET['status'])) return;

    $post_id = absint($_GET['post']);
    $status  = sanitize_text_field(wp_unslash($_GET['status']));

    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'bbr_set_status_' . $post_id)) {
        wp_die(__('Security check failed.', 'babarida-dive'));
    }

    $allowed = array('pending','confirmed','paid','checked-in','completed','cancelled');
    if (!in_array($status, $allowed)) wp_die(__('Invalid status.', 'babarida-dive'));

    update_post_meta($post_id, '_bbr_booking_booking_status', $status);

    // Send notification
    $guest_email = get_post_meta($post_id, '_bbr_booking_guest_email', true);
    $guest_name  = get_post_meta($post_id, '_bbr_booking_guest_name', true);
    $qr          = get_post_meta($post_id, '_bbr_booking_qr_code', true);

    if ($guest_email && $guest_name) {
        $status_labels = array(
            'confirmed' => __('confirmed', 'babarida-dive'),
            'completed' => __('completed', 'babarida-dive'),
            'cancelled' => __('cancelled', 'babarida-dive'),
        );
        $label = $status_labels[$status] ?? $status;
        $subject = sprintf(__('Booking %s: %s', 'babarida-dive'), $label, $qr);
        $body = sprintf(
            __("Dear %s,\n\nYour booking (%s) has been %s.\n\nThank you,\nBabarida Dive Center", 'babarida-dive'),
            $guest_name,
            $qr,
            $label
        );
        wp_mail($guest_email, $subject, $body);
    }

    wp_safe_redirect(admin_url('edit.php?post_type=booking&message=status_updated'));
    exit;
}
add_action('admin_post_bbr_set_status', 'bbr_handle_set_status');

/* ============================================
   ADMIN: STATUS UPDATED NOTICE
   ============================================ */
function bbr_book_admin_notices() {
    if (isset($_GET['message']) && $_GET['message'] === 'status_updated') {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Booking status updated successfully.', 'babarida-dive') . '</p></div>';
    }
}
add_action('admin_notices', 'bbr_book_admin_notices');

/* ============================================
   ADMIN: BOOKING ADMIN MENU
   ============================================ */
function bbr_book_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=booking',
        __('Booking Stats', 'babarida-dive'),
        __('Stats', 'babarida-dive'),
        'edit_posts',
        'booking-stats',
        'bbr_book_stats_page'
    );
}
add_action('admin_menu', 'bbr_book_admin_menu');

function bbr_book_stats_page() {
    global $wpdb;
    ?>
    <div class="wrap" style="max-width:1200px">
        <h1><?php esc_html_e('Booking Statistics', 'babarida-dive'); ?></h1>

        <?php
        $statuses = array('pending','confirmed','paid','checked-in','completed','cancelled');
        $counts = array();
        $total = 0;
        foreach ($statuses as $st) {
            $q = new WP_Query(array('post_type'=>'booking','meta_key'=>'_bbr_booking_booking_status','meta_value'=>$st,'posts_per_page'=>-1,'fields'=>'ids'));
            $counts[$st] = $q->found_posts;
            $total += $q->found_posts;
        }

        // Revenue
        $revenue = 0;
        $paid_q = new WP_Query(array('post_type'=>'booking','meta_query'=>array(array('key'=>'_bbr_booking_booking_status','value'=>array('paid','completed'),'compare'=>'IN')),'posts_per_page'=>-1,'fields'=>'ids'));
        foreach ($paid_q->posts as $pid) {
            $revenue += floatval(get_post_meta($pid, '_bbr_booking_total_price', true));
        }
        ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin:1.5rem 0">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.25rem;text-align:center">
                <div style="font-size:2rem;font-weight:700;color:#111827"><?php echo esc_html($total); ?></div>
                <div style="font-size:.8rem;color:#6b7280;margin-top:.25rem"><?php esc_html_e('Total Bookings', 'babarida-dive'); ?></div>
            </div>
            <?php foreach ($counts as $st => $cnt) : ?>
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.25rem;text-align:center">
                <div style="font-size:2rem;font-weight:700;color:#111827"><?php echo esc_html($cnt); ?></div>
                <div style="font-size:.8rem;color:#6b7280;margin-top:.25rem;text-transform:capitalize"><?php echo esc_html(str_replace('-', ' ', $st)); ?></div>
            </div>
            <?php endforeach; ?>
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.25rem;text-align:center">
                <div style="font-size:2rem;font-weight:700;color:#059669">$<?php echo number_format($revenue, 0); ?></div>
                <div style="font-size:.8rem;color:#6b7280;margin-top:.25rem"><?php esc_html_e('Total Revenue', 'babarida-dive'); ?></div>
            </div>
        </div>

        <h2><?php esc_html_e('Recent Bookings', 'babarida-dive'); ?></h2>
        <?php
        $recent = new WP_Query(array('post_type'=>'booking','posts_per_page'=>20,'orderby'=>'date','order'=>'DESC'));
        if ($recent->have_posts()) :
        ?>
        <table class="wp-list-table widefat fixed striped" style="margin-top:1rem">
            <thead>
                <tr>
                    <th>ID</th><th>Guest</th><th>Trip</th><th>Date</th><th>Guests</th><th>Total</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent->posts as $p) :
                    $st = get_post_meta($p->ID, '_bbr_booking_booking_status', true) ?: 'pending';
                    $qr = get_post_meta($p->ID, '_bbr_booking_qr_code', true);
                ?>
                <tr>
                    <td><strong><?php echo esc_html($qr ?: '#' . $p->ID); ?></strong></td>
                    <td><?php echo esc_html(get_post_meta($p->ID, '_bbr_booking_guest_name', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($p->ID, '_bbr_booking_trip_name', true) ?: '—'); ?></td>
                    <td><?php echo esc_html(get_post_meta($p->ID, '_bbr_booking_check_in_date', true) ?: '—'); ?></td>
                    <td><?php echo esc_html(get_post_meta($p->ID, '_bbr_booking_num_guests', true) ?: '1'); ?></td>
                    <td><?php $pr = get_post_meta($p->ID, '_bbr_booking_total_price', true); echo $pr ? bbr_format_price($pr) : '—'; ?></td>
                    <td><span class="bbr-status <?php echo esc_attr($st); ?>"><?php echo esc_html(ucfirst(str_replace('-', ' ', $st))); ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else : ?>
            <p style="color:#6b7280;padding:2rem 0"><?php esc_html_e('No bookings yet.', 'babarida-dive'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/* ============================================
   ADMIN: ADD DESTINATION QUICK LINKS
   ============================================ */
function bbr_book_add_admin_links() {
    $screen = get_current_screen();
    if (!$screen) return;

    if ($screen->post_type === 'trip' || $screen->post_type === 'liveaboard' || $screen->post_type === 'water_sport' || $screen->post_type === 'dive_course') {
        $post_type_obj = get_post_type_object($screen->post_type);
        ?>
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.75rem 1rem;margin:10px 0;font-size:.85rem;color:#1e40af">
            <strong><?php esc_html_e('Tip:', 'babarida-dive'); ?></strong>
            <?php printf(
                esc_html__('Set the "Destination" meta field to "Bunaken", "Siladen", "Bangka", or "Lembeh" to link this %s to the correct destination page.', 'babarida-dive'),
                esc_html(strtolower($post_type_obj->labels->singular_name))
            ); ?>
        </div>
        <?php
    }

    if ($screen->post_type === 'booking') {
        ?>
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem 1rem;margin:10px 0;font-size:.85rem;color:#92400e">
            <strong><?php esc_html_e('Note:', 'babarida-dive'); ?></strong>
            <?php esc_html_e('Use the row actions (Confirm / Complete / Cancel) to quickly change booking status. All status changes send automatic email notifications to guests.', 'babarida-dive'); ?>
        </div>
        <?php
    }
}
add_action('edit_form_after_title', 'bbr_book_add_admin_links');

/* ============================================
   ADMIN: AJAX GET TRIP TITLE
   ============================================ */
function bbr_ajax_get_trip_title() {
    check_ajax_referer('bbr_admin_nonce', 'nonce');
    $post_id = absint($_POST['post_id'] ?? 0);
    if (!$post_id) wp_send_json_error('Invalid');

    $title = get_the_title($post_id);
    if ($title) {
        wp_send_json_success($title);
    } else {
        wp_send_json_error('Not found');
    }
}
add_action('wp_ajax_bbr_get_trip_title', 'bbr_ajax_get_trip_title');

/* ============================================
   ADMIN: RESTRICT POST DELETION FOR BOOKINGS
   ============================================ */
function bbr_book_prevent_delete($check, $post) {
    if (!$post || $post->post_type !== 'booking') return $check;

    $status = get_post_meta($post->ID, '_bbr_booking_booking_status', true);
    if (in_array($status, array('confirmed', 'paid', 'checked-in'))) {
        return new WP_Error(
            'cannot_delete_active_booking',
            __('Cannot delete an active booking. Cancel it first, then delete.', 'babarida-dive')
        );
    }
    return $check;
}
add_filter('pre_delete_post', 'bbr_book_prevent_delete', 10, 2);

/* ============================================
   ADMIN: EXPORT BOOKINGS TO CSV
   ============================================ */
function bbr_book_export_csv() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'bbr_export_csv') return;
    if (!current_user_can('edit_posts')) wp_die(__('Unauthorized', 'babarida-dive'));
    check_admin_referer('bbr_export_csv', '_wpnonce');

    $bookings = get_posts(array('post_type' => 'booking', 'posts_per_page' => -1, 'post_status' => 'publish'));

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings-' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Booking ID', 'Guest Name', 'Email', 'Phone', 'Trip', 'Check-in', 'Check-out', 'Guests', 'Currency', 'Total', 'Payment Method', 'Payment Status', 'Booking Status', 'Created'));

    foreach ($bookings as $b) {
        fputcsv($output, array(
            get_post_meta($b->ID, '_bbr_booking_qr_code', true),
            get_post_meta($b->ID, '_bbr_booking_guest_name', true),
            get_post_meta($b->ID, '_bbr_booking_guest_email', true),
            get_post_meta($b->ID, '_bbr_booking_guest_phone', true),
            get_post_meta($b->ID, '_bbr_booking_trip_name', true),
            get_post_meta($b->ID, '_bbr_booking_check_in_date', true),
            get_post_meta($b->ID, '_bbr_booking_check_out_date', true),
            get_post_meta($b->ID, '_bbr_booking_num_guests', true),
            get_post_meta($b->ID, '_bbr_booking_currency', true),
            get_post_meta($b->ID, '_bbr_booking_total_price', true),
            get_post_meta($b->ID, '_bbr_booking_payment_method', true),
            get_post_meta($b->ID, '_bbr_booking_payment_status', true),
            get_post_meta($b->ID, '_bbr_booking_booking_status', true),
            $b->post_date,
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'bbr_book_export_csv');

/* Add export link to booking admin page */
function bbr_book_export_link() {
    $screen = get_current_screen();
    if ($screen && $screen->id === 'edit-booking') {
        $url = wp_nonce_url(admin_url('admin.php?action=bbr_export_csv'), 'bbr_export_csv');
        echo '<div style="margin:5px 0 15px"><a href="' . esc_url($url) . '" class="button button-secondary" style="margin-left:8px">📥 ' . esc_html__('Export All to CSV', 'babarida-dive') . '</a></div>';
    }
}
add_action('admin_notices', 'bbr_book_export_link');

/* ============================================
   CRON: AUTO-CANCEL UNPAID BOOKINGS
   ============================================ */
if (!wp_next_scheduled('bbr_auto_cancel_old_pending')) {
    wp_schedule_event(time(), 'twicedaily', 'bbr_auto_cancel_old_pending');
}

function bbr_auto_cancel_old_pending_func() {
    $cutoff = date('Y-m-d H:i:s', strtotime('-3 days'));
    $old = new WP_Query(array(
        'post_type'      => 'booking',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'date_query'     => array(array('column' => 'post_date', 'before' => $cutoff)),
        'meta_query'     => array(array('key' => '_bbr_booking_booking_status', 'value' => 'pending')),
        'fields'         => 'ids',
    ));

    foreach ($old->posts as $bid) {
        update_post_meta($bid, '_bbr_booking_booking_status', 'cancelled');
        bbr_log_activity('auto_cancel', "Booking #$bid auto-cancelled (pending > 3 days)");

        $guest_email = get_post_meta($bid, '_bbr_booking_guest_email', true);
        $guest_name  = get_post_meta($bid, '_bbr_booking_guest_name', true);
        $qr          = get_post_meta($bid, '_bbr_booking_qr_code', true);

        if ($guest_email) {
            wp_mail($guest_email,
                __('Booking Expired — Babarida Dive Center', 'babarida-dive'),
                sprintf(__("Dear %s,\n\nYour booking (%s) has been automatically cancelled because it was not confirmed within 3 days.\n\nTo rebook, please visit our website or contact us via WhatsApp.\n\nBabarida Dive Center", 'babarida-dive'), $guest_name, $qr)
            );
        }
    }
}
add_action('bbr_auto_cancel_old_pending', 'bbr_auto_cancel_old_pending_func');

/* ============================================
   CRON: SEND REMINDERS FOR UPCOMING TRIPS
   ============================================ */
if (!wp_next_scheduled('bbr_send_trip_reminders')) {
    wp_schedule_event(time(), 'daily', 'bbr_send_trip_reminders');
}

function bbr_send_trip_reminders_func() {
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $upcoming = new WP_Query(array(
        'post_type'      => 'booking',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => '_bbr_booking_check_in_date', 'value' => $tomorrow),
            array('key' => '_bbr_booking_booking_status', 'value' => array('confirmed','paid'), 'compare' => 'IN'),
        ),
        'fields' => 'ids',
    ));

    foreach ($upcoming->posts as $bid) {
        $guest_email = get_post_meta($bid, '_bbr_booking_guest_email', true);
        $guest_name  = get_post_meta($bid, '_bbr_booking_guest_name', true);
        $trip        = get_post_meta($bid, '_bbr_booking_trip_name', true);
        $qr          = get_post_meta($bid, '_bbr_booking_qr_code', true);

        if ($guest_email) {
            wp_mail($guest_email,
                __('Trip Reminder — Tomorrow!', 'babarida-dive'),
                sprintf(__("Dear %s,\n\nThis is a reminder that your trip (%s) is scheduled for TOMORROW.\n\nBooking ID: %s\n\nPlease arrive at the pickup location on time. If you have any questions, contact us via WhatsApp.\n\nBabarida Dive Center", 'babarida-dive'), $guest_name, $trip, $qr)
            );
            bbr_log_activity('trip_reminder', "Reminder sent for booking #$bid");
        }
    }
}
add_action('bbr_send_trip_reminders', 'bbr_send_trip_reminders_func');

/* ============================================
   ADMIN: SEASONAL PRICING MANAGER
   ============================================ */
function bbr_seasonal_pricing_page() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['bbr_save_seasonal']) && wp_verify_nonce($_POST['_wpnonce'] ?? '', 'bbr_seasonal_nonce')) {
        $seasons = array();
        if (isset($_POST['seasons']) && is_array($_POST['seasons'])) {
            foreach ($_POST['seasons'] as $s) {
                $name = sanitize_text_field($s['name'] ?? '');
                $sm   = absint($s['start_month'] ?? 0);
                $em   = absint($s['end_month'] ?? 0);
                $mult = floatval($s['multiplier'] ?? 1);
                if ($name && $sm && $em && $mult > 0) {
                    $seasons[] = array(
                        'name'       => $name,
                        'start_month'=> $sm,
                        'end_month'  => $em,
                        'multiplier' => $mult,
                    );
                }
            }
        }
        update_option('bbr_seasonal_pricing', $seasons);

        if (isset($_POST['weekend_multiplier'])) {
            update_option('bbr_weekend_multiplier', floatval($_POST['weekend_multiplier']));
        }

        echo '<div class="notice notice-success"><p>' . esc_html__('Seasonal pricing saved.', 'babarida-dive') . '</p></div>';
    }

    $seasons = get_option('bbr_seasonal_pricing', array());
    $weekend = get_option('bbr_weekend_multiplier', '1.10');
    $months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
    ?>
    <div class="wrap" style="max-width:800px">
        <h1><?php esc_html_e('Seasonal Pricing Manager', 'babarida-dive'); ?></h1>
        <p style="color:#6b7280;margin-bottom:1.5rem"><?php esc_html_e('Set seasonal price multipliers. Base prices are multiplied by these values during the specified months.', 'babarida-dive'); ?></p>

        <form method="post">
            <?php wp_nonce_field('bbr_seasonal_nonce', '_wpnonce'); ?>
            <input type="hidden" name="bbr_save_seasonal" value="1">

            <table class="widefat striped" style="margin-bottom:1.5rem">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Season Name', 'babarida-dive'); ?></th>
                        <th><?php esc_html_e('Start Month', 'babarida-dive'); ?></th>
                        <th><?php esc_html_e('End Month', 'babarida-dive'); ?></th>
                        <th><?php esc_html_e('Multiplier', 'babarida-dive'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="bbr-season-rows">
                    <?php if (!empty($seasons)) : foreach ($seasons as $i => $s) : ?>
                    <tr>
                        <td><input type="text" name="seasons[<?php echo $i; ?>][name]" value="<?php echo esc_attr($s['name']); ?>" style="width:100%"></td>
                        <td>
                            <select name="seasons[<?php echo $i; ?>][start_month]">
                                <?php foreach ($months as $m => $ml) : ?>
                                <option value="<?php echo $m; ?>" <?php selected($s['start_month'], $m); ?>><?php echo esc_html($ml); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="seasons[<?php echo $i; ?>][end_month]">
                                <?php foreach ($months as $m => $ml) : ?>
                                <option value="<?php echo $m; ?>" <?php selected($s['end_month'], $m); ?>><?php echo esc_html($ml); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="seasons[<?php echo $i; ?>][multiplier]" value="<?php echo esc_attr($s['multiplier']); ?>" step="0.05" min="0.5" max="3" style="width:80px"></td>
                        <td><button type="button" class="button bbr-remove-season" style="color:#DC2626">✕</button></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>

            <button type="button" id="bbr-add-season" class="button" style="margin-bottom:1.5rem">+ <?php esc_html_e('Add Season', 'babarida-dive'); ?></button>

            <table class="widefat striped">
                <tr>
                    <th style="width:200px"><?php esc_html_e('Weekend Multiplier', 'babarida-dive'); ?></th>
                    <td><input type="number" name="weekend_multiplier" value="<?php echo esc_attr($weekend); ?>" step="0.05" min="1" max="2" style="width:80px">
                    <p class="description"><?php esc_html_e('Applied on Saturdays and Sundays. 1.00 = no extra charge.', 'babarida-dive'); ?></p></td>
                </tr>
            </table>

            <p style="margin-top:1.5rem">
                <?php submit_button(__('Save Pricing', 'babarida-dive'), 'primary', '', false); ?>
            </p>
        </form>
    </div>

    <script>
    jQuery(function($){
        var idx = <?php echo !empty($seasons) ? max(array_keys($seasons)) + 1 : 0; ?>;
        $('#bbr-add-season').on('click', function(){
            var months = <?php echo json_encode($months); ?>;
            var opts = '';
            for (var m in months) {
                opts += '<option value="'+m+'">'+months[m]+'</option>';
            }
            var row = '<tr><td><input type="text" name="seasons['+idx+'][name]" style="width:100%"></td><td><select name="seasons['+idx+'][start_month]">'+opts+'</select></td><td><select name="seasons['+idx+'][end_month]">'+opts+'</select></td><td><input type="number" name="seasons['+idx+'][multiplier]" value="1.00" step="0.05" min="0.5" max="3" style="width:80px"></td><td><button type="button" class="button bbr-remove-season" style="color:#DC2626">✕</button></td></tr>';
            $('#bbr-season-rows').append(row);
            idx++;
        });
        $(document).on('click', '.bbr-remove-season', function(){
            $(this).closest('tr').remove();
        });
    });
    </script>
    <?php
}

function bbr_seasonal_menu() {
    add_submenu_page(
        'edit.php?post_type=trip',
        __('Seasonal Pricing', 'babarida-dive'),
        __('Seasonal Pricing', 'babarida-dive'),
        'manage_options',
        'seasonal-pricing',
        'bbr_seasonal_pricing_page'
    );
}
add_action('admin_menu', 'bbr_seasonal_menu');

/* ============================================
   ADMIN: LOYALTY POINTS MANAGER
   ============================================ */
function bbr_loyalty_page() {
    if (!current_user_can('edit_posts')) return;

    if (isset($_POST['bbr_add_points']) && wp_verify_nonce($_POST['_wpnonce'] ?? '', 'bbr_loyalty_nonce')) {
        $user_id = absint($_POST['user_id'] ?? 0);
        $points  = absint($_POST['points'] ?? 0);
        $reason  = sanitize_text_field($_POST['reason'] ?? '');

        if ($user_id && $points) {
            bbr_add_member_points($user_id, $points, $reason);
            echo '<div class="notice notice-success"><p>' . esc_html__('Points added.', 'babarida-dive') . '</p></div>';
        }
    }

    // Get users with points
    $users = get_users(array('orderby' => 'registered', 'order' => 'DESC', 'number' => 50));
    ?>
    <div class="wrap" style="max-width:1000px">
        <h1><?php esc_html_e('Loyalty Points Manager', 'babarida-dive'); ?></h1>

        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem;margin:1.5rem 0">
            <h3 style="margin-bottom:1rem"><?php esc_html_e('Add Points', 'babarida-dive'); ?></h3>
            <form method="post" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap">
                <?php wp_nonce_field('bbr_loyalty_nonce', '_wpnonce'); ?>
                <input type="hidden" name="bbr_add_points" value="1">
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:4px"><?php esc_html_e('User ID', 'babarida-dive'); ?></label><input type="number" name="user_id" style="width:100px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px" required></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:4px"><?php esc_html_e('Points', 'babarida-dive'); ?></label><input type="number" name="points" style="width:100px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px" min="1" required></div>
                <div><label style="display:block;font-size:.8rem;font-weight:600;margin-bottom:4px"><?php esc_html_e('Reason', 'babarida-dive'); ?></label><input type="text" name="reason" style="width:250px;padding:6px 10px;border:1px solid #d1d5db;border-radius:6px" required></div>
                <?php submit_button(__('Add', 'babarida-dive'), 'primary', '', false); ?>
            </form>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Points</th><th>Level</th><th>Discount</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u) :
                    $pts = bbr_get_member_points($u->ID);
                    if ($pts <= 0) continue;
                    $lvl = bbr_get_member_level($u->ID);
                ?>
                <tr>
                    <td><?php echo $u->ID; ?></td>
                    <td><strong><?php echo esc_html($u->display_name); ?></strong></td>
                    <td><?php echo esc_html($u->user_email); ?></td>
                    <td><strong><?php echo esc_html($pts); ?></strong></td>
                    <td><span style="color:<?php echo esc_attr($lvl['color']); ?>;font-weight:600"><?php echo esc_html($lvl['name']); ?></span></td>
                    <td><?php echo esc_html($lvl['discount']); ?>%</td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty(array_filter(array_map(function($u) { return bbr_get_member_points($u->ID); }, $users)))) : ?>
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:#6b7280"><?php esc_html_e('No members with points yet.', 'babarida-dive'); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function bbr_loyalty_menu() {
    add_submenu_page(
        'users.php',
        __('Loyalty Points', 'babarida-dive'),
        __('Loyalty Points', 'babarida-dive'),
        'edit_posts',
        'loyalty-points',
        'bbr_loyalty_page'
    );
}
add_action('admin_menu', 'bbr_loyalty_menu');

/* ============================================
   ADMIN: ACTIVITY LOG VIEWER
   ============================================ */
function bbr_activity_log_page() {
    if (!current_user_can('manage_options')) return;

    // Clear log
    if (isset($_GET['action']) && $_GET['action'] === 'clear_log' && wp_verify_nonce($_GET['_wpnonce'] ?? '', 'bbr_clear_log')) {
        update_option('bbr_activity_logs', array());
        echo '<div class="notice notice-success"><p>' . esc_html__('Activity log cleared.', 'babarida-dive') . '</p></div>';
    }

    $logs = get_option('bbr_activity_logs', array());
    ?>
    <div class="wrap" style="max-width:1100px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
            <h1><?php esc_html_e('Activity Log', 'babarida-dive'); ?></h1>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=activity-log&action=clear_log'), 'bbr_clear_log')); ?>" class="button" style="color:#DC2626" onclick="return confirm('Clear all logs?')"><?php esc_html_e('Clear Log', 'babarida-dive'); ?></a>
        </div>

        <?php if (!empty($logs)) : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr><th>Time</th><th>User</th><th>Action</th><th>Details</th><th>IP</th></tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($logs, 0, 200) as $log) : ?>
                <tr>
                    <td style="white-space:nowrap"><?php echo esc_html($log['time']); ?></td>
                    <td><?php echo esc_html($log['user_name'] ?: 'System'); ?></td>
                    <td><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:.8rem"><?php echo esc_html($log['action']); ?></code></td>
                    <td style="max-width:400px;word-break:break-word"><?php echo esc_html($log['details']); ?></td>
                    <td style="white-space:nowrap"><?php echo esc_html($log['ip']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="color:#6b7280;margin-top:1rem;font-size:.82rem"><?php printf(esc_html__('Showing last %d of %d entries.', 'babarida-dive'), min(200, count($logs)), count($logs)); ?></p>
        <?php else : ?>
        <p style="color:#6b7280;padding:2rem"><?php esc_html_e('No activity logged yet.', 'babarida-dive'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function bbr_activity_log_menu() {
    add_submenu_page(
        'tools.php',
        __('Activity Log', 'babarida-dive'),
        __('Activity Log', 'babarida-dive'),
        'manage_options',
        'activity-log',
        'bbr_activity_log_page'
    );
}
add_action('admin_menu', 'bbr_activity_log_menu');

/* ============================================
   PLUGIN ACTIVATION
   ============================================ */
function bbr_book_activate() {
    // Flush rewrite rules in case CPTs need updating
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'bbr_book_activate');

/* ============================================
   PLUGIN DEACTIVATION
   ============================================ */
function bbr_book_deactivate() {
    wp_clear_scheduled_hook('bbr_auto_cancel_old_pending');
    wp_clear_scheduled_hook('bbr_send_trip_reminders');
}
register_deactivation_hook(__FILE__, 'bbr_book_deactivate');
