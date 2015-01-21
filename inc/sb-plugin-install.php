<?php
function sb_comment_check_core() {
    $activated_plugins = get_option('active_plugins');
    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
    return $sb_core_installed;
}

function sb_comment_activation() {
    if(!current_user_can('activate_plugins')) {
        return;
    }
    do_action('sb_comment_activation');
}
register_activation_hook(SB_COMMENT_FILE, 'sb_comment_activation');

function sb_comment_check_admin_notices() {
    if(!sb_comment_check_core()) {
        unset($_GET['activate']);
        printf('<div class="error"><p><strong>' . __('Lỗi', 'sb-comment') . ':</strong> ' . __('The plugin with name %1$s has been deactivated because of missing %2$s plugin.', 'sb-comment') . '.</p></div>', '<strong>SB Comment</strong>', sprintf('<a target="_blank" href="%s" style="text-decoration: none">SB Core</a>', 'https://wordpress.org/plugins/sb-core/'));
        deactivate_plugins(SB_COMMENT_BASENAME);
    }
}
if(!empty($GLOBALS['pagenow']) && 'plugins.php' === $GLOBALS['pagenow']) {
    add_action('admin_notices', 'sb_comment_check_admin_notices', 0);
}

function sb_comment_settings_link($links) {
    if(sb_comment_check_core()) {
        $settings_link = sprintf('<a href="admin.php?page=sb_comment">%s</a>', __('Cài đặt', 'sb-comment'));
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links_' . SB_COMMENT_BASENAME, 'sb_comment_settings_link');

function sb_comment_textdomain() {
    load_plugin_textdomain('sb-comment', false, SB_COMMENT_DIRNAME . '/languages/');
}
add_action('plugins_loaded', 'sb_comment_textdomain');