<?php
function sb_core_default_avatar($avatar_defaults) {
    $myavatar = SB_CORE_URL . '/images/sb-default-avatar-32.png';
    $avatar_defaults[$myavatar] = 'SB default avatar';
    return $avatar_defaults;
}
if(SB_Comment::use_default_avatar()) add_filter('avatar_defaults', 'sb_core_default_avatar');

function sb_comment_get_avatar($avatar, $id_or_email, $size, $default, $alt) {
    if(SB_PHP::is_string_contain($avatar, 'avatar-default') && SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
        $class = 'avatar avatar-default photo avatar-' . $size;
        $image_source = SB_Comment::get_default_avatar_url();
        if(empty($image_source)) {
            $image_source = SB_COMMENT_URL . '/images/sb-default-avatar-' . $size . '.png';
            $image_file = SB_COMMENT_URL . '/images/sb-default-avatar-' . $size . '.png';
            if(!file_exists($image_file)) {
                $image_source = SB_COMMENT_URL . '/images/sb-default-avatar-100.png';
            }
        }
        $avatar = '<img class="' . $class . '" src="' . $image_source . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '">';
    }
    return $avatar;
}
if(SB_Comment::use_default_avatar()) add_filter('get_avatar', 'sb_comment_get_avatar', 10, 5);

function sb_comment_get_avatar_options_discussion($avatar, $id_or_email, $size, $default, $alt) {
    if(SB_PHP::is_string_contain($avatar, 'sb-default-avatar')) {
        $class = 'avatar photo avatar-' . $size;
        $avatar_url = SB_Comment::get_default_avatar_url();
        if(empty($avatar_url)) {
            $avatar_url = SB_COMMENT_URL . '/images/sb-default-avatar-32.png';
        }
        $avatar = '<img class="' . $class . '" src="' . $avatar_url . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '">';
    }
    return $avatar;
}
if(SB_Comment::use_default_avatar() && $GLOBALS['pagenow'] == 'options-discussion.php') add_filter('get_avatar', 'sb_comment_get_avatar_options_discussion', 10, 5);