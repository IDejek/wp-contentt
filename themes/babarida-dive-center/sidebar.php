<?php
/**
 * Sidebar Template
 * @package Babarida_Dive_Center
 */
defined('ABSPATH') || exit;
if (!is_active_sidebar('sidebar-blog')) return;
?>
<aside style="padding:1.5rem">
    <?php dynamic_sidebar('sidebar-blog'); ?>
</aside>
