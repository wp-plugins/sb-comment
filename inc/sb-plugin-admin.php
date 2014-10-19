<?php
function sb_comment_menu() {
    SB_Admin_Custom::add_submenu_page('SB Comment', 'sb_comment', array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action('sb_admin_menu', 'sb_comment_menu');

function sb_comment_tab($tabs) {
    $tabs['sb_comment'] = array('title' => 'SB Comment', 'section_id' => 'sb_comment_section', 'type' => 'plugin');
    return $tabs;
}
add_filter('sb_admin_tabs', 'sb_comment_tab');

function sb_comment_setting_field() {
    SB_Admin_Custom::add_section('sb_comment_section', __('SB Comment options page', 'sb-comment'), 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_spam_check', __('Spam check', 'sb-comment'), 'sb_comment_section', 'sb_comment_spam_check_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_notify_user', __('Notify user', 'sb-comment'), 'sb_comment_section', 'sb_comment_notify_user_callback', 'sb_comment');
    SB_Admin_Custom::add_setting_field('sb_comment_auto_empty_spam', __('Auto empty spam', 'sb-comment'), 'sb_comment_section', 'sb_comment_auto_empty_spam_callback', 'sb_comment');
}
add_action('sb_admin_init', 'sb_comment_setting_field');

function sb_comment_auto_empty_spam_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['auto_empty_spam']) ? $options['comment']['auto_empty_spam'] : 1;
    $id = 'sb_comment_auto_empty_spam';
    $name = 'sb_options[comment][auto_empty_spam]';
    $description = __('User can turn on or turn off the functions to allow empty spam automatically.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_notify_user_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['notify_user']) ? $options['comment']['notify_user'] : 1;
    $id = '';
    $name = 'sb_options[comment][notify_user]';
    $description = __('User can turn on or turn off the functions to allow sending email notifications when comment is approved.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_spam_check_callback() {
    $options = SB_Option::get();
    $value = isset($options['comment']['spam_check']) ? $options['comment']['spam_check'] : 1;
    $id = 'sb_comment_spam_check';
    $name = 'sb_options[comment][spam_check]';
    $description = __('User can turn on or turn off the functions for checking spam comment.', 'sb-comment');
    $args = array(
        'id' => $id,
        'name' => $name,
        'value' => $value,
        'description' => $description
    );
    SB_Field::switch_button($args);
}

function sb_comment_sanitize($input) {
    $data = $input;
    $data['comment']['spam_check'] = SB_Core::sanitize(isset($input['comment']['spam_check']) ? $input['comment']['spam_check'] : 1, 'bool');
    return $data;
}
add_filter('sb_options_sanitize', 'sb_comment_sanitize');