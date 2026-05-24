<?php
/**
 * Shortcode Template: Search Bar
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
?>
<form id="bbr-search-form" class="bbr-search-bar bbr-reveal">
    <div class="bbr-search-field">
        <label class="bbr-form-label"><?php esc_html_e('Destination', 'babarida-dive'); ?></label>
        <select name="destination" class="bbr-form-select">
            <option value=""><?php esc_html_e('All', 'babarida-dive'); ?></option>
            <option value="Bunaken"><?php esc_html_e('Bunaken', 'babarida-dive'); ?></option>
            <option value="Siladen"><?php esc_html_e('Siladen', 'babarida-dive'); ?></option>
            <option value="Bangka"><?php esc_html_e('Bangka', 'babarida-dive'); ?></option>
            <option value="Lembeh"><?php esc_html_e('Lembeh', 'babarida-dive'); ?></option>
        </select>
    </div>
    <div class="bbr-search-field">
        <label class="bbr-form-label"><?php esc_html_e('Date', 'babarida-dive'); ?></label>
        <input type="date" name="date" class="bbr-form-input">
    </div>
    <div class="bbr-search-field">
        <label class="bbr-form-label"><?php esc_html_e('Activity', 'babarida-dive'); ?></label>
        <select name="type" class="bbr-form-select">
            <option value=""><?php esc_html_e('All', 'babarida-dive'); ?></option>
            <option value="diving"><?php esc_html_e('Diving', 'babarida-dive'); ?></option>
            <option value="liveaboard"><?php esc_html_e('Liveaboard', 'babarida-dive'); ?></option>
            <option value="course"><?php esc_html_e('Course', 'babarida-dive'); ?></option>
            <option value="water-sport"><?php esc_html_e('Water Sport', 'babarida-dive'); ?></option>
        </select>
    </div>
    <div class="bbr-search-field" style="display:flex;align-items:flex-end">
        <button type="submit" class="bbr-btn bbr-btn-primary" style="width:100%;justify-content:center">
            <?php esc_html_e('Search', 'babarida-dive'); ?>
        </button>
    </div>
</form>
<div id="bbr-search-results" style="margin-top:1.5rem"></div>
