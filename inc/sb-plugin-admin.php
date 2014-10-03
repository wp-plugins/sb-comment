<?php
defined('ABSPATH') OR exit;

function sb_comment_menu() {
    SB_Admin_Custom::add_submenu_page("SB Comment", "sb_comment", array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action("sb_admin_menu", "sb_comment_menu");

function sb_comment_tab($tabs) {
    $tabs["sb_comment"] = array('title' => "SB Comment", 'section_id' => "sb_comment_section", "type" => "plugin");
    return $tabs;
}
add_filter("sb_admin_tabs", "sb_comment_tab");

function sb_comment_setting_field() {
    SB_Admin_Custom::add_section("sb_comment_section", __("SB Comment options page", "sb-comment"), "sb_comment");
    SB_Admin_Custom::add_setting_field("sb_comment_spam_check", __("Spam check", "sb-comment"), "sb_comment_section", "sb_comment_spam_check_callback", "sb_comment");
}
add_action("sb_admin_init", "sb_comment_setting_field");

function sb_comment_spam_check_callback() {
    $options = get_option("sb_options");
    $value = isset($options["comment"]["spam_check"]) ? $options["comment"]["spam_check"] : 1;
    $id = "";
    $name = "sb_options[comment][spam_check]";
    $description = "";
    SB_Field::switch_button($id, $name, $value, $description);
}

function sb_comment_sanitize($input) {
    $data = $input;
    $data["comment"]["spam_check"] = SB_Core::sanitize(isset($input["comment"]["spam_check"]) ? $input["comment"]["spam_check"] : 1, "bool");
    return $data;
}
add_filter("sb_options_sanitize", "sb_comment_sanitize");