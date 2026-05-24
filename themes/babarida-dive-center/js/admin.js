/**
 * Babarida Dive Center — Admin JavaScript
 */
(function($) {
    'use strict';

    // Auto-generate booking title from guest name
    $(document).on('input', '#bbr_booking_guest_name', function() {
        var name = $(this).val() || 'New';
        $('#title').val('Booking: ' + name + ' — ' + new Date().toLocaleDateString());
    });

    // Copy trip name to booking field
    $(document).on('change', '#bbr_booking_trip_id', function() {
        var id = $(this).val();
        if (id) {
            $.post(ajaxurl, { action: 'bbr_get_trip_title', post_id: id, nonce: bbrAdmin.nonce }, function(res) {
                if (res.success) {
                    $('#_bbr_booking_trip_name').val(res.data);
                }
            });
        }
    });

    // Color code booking status in post list
    $(function() {
        $('body.post-type-booking .column-title').each(function() {
            var text = $(this).text();
            if (text.indexOf('Pending') > -1) $(this).closest('tr').css('background', 'rgba(255,214,10,0.06)');
            if (text.indexOf('Cancelled') > -1) $(this).closest('tr').css('background', 'rgba(239,68,68,0.04)');
            if (text.indexOf('Completed') > -1) $(this).closest('tr').css('background', 'rgba(16,185,129,0.04)');
        });
    });

    // Quick status update via admin bar
    $(document).on('click', '.bbr-quick-status', function(e) {
        e.preventDefault();
        var postId = $(this).data('id');
        var status = $(this).data('status');
        if (!postId || !status) return;

        $.post(ajaxurl, {
            action: 'bbr_change_status',
            nonce: bbrAdmin.nonce,
            booking_id: postId,
            status: status
        }, function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Failed to update status.');
            }
        });
    });

})(jQuery);
