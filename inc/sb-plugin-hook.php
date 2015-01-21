<?php
function sb_comment_style_and_script() {
    if(is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    if(is_singular() || is_page()) {
        if(sb_comment_testing()) {
            wp_enqueue_style('sb-comment-style', SB_COMMENT_URL . '/css/sb-comment-style.css');
            wp_enqueue_script('sb-comment', SB_COMMENT_URL . '/js/sb-comment-script.js', array('jquery', 'sb-core'), false, true);
        } else {
            wp_enqueue_style('sb-comment-style', SB_COMMENT_URL . '/css/sb-comment-style.min.css');
            wp_enqueue_script('sb-comment', SB_COMMENT_URL . '/js/sb-comment-script.min.js', array('jquery', 'sb-core'), false, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'sb_comment_style_and_script');

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

function sb_insert_comment($comment_id, $comment_object) {
    if(SB_Comment::enable_spam_check() && empty($comment_object->comment_content)) {
        SB_Comment::delete($comment_id);
        wp_die(__('Bình luận spam đã được phát hiện!', 'sb-comment'));
    }
}
add_action('wp_insert_comment', 'sb_insert_comment', 10, 2);

function sb_transition_comment_status($new_status, $old_status, $comment) {
    if($new_status != $old_status) {
        if('approved' == $new_status && SB_Comment::enable_notify_comment_approved()) {
            SB_Mail::notify_user_for_comment_approved($comment);
        } elseif('spam' == $new_status && SB_Comment::enable_auto_empty_spam()) {
            SB_Comment::delete($comment->comment_ID);
        }
    }
}
add_action('transition_comment_status', 'sb_transition_comment_status', 10, 3);

function sb_preprocess_comment($commentdata) {
    if(SB_Comment::enable_spam_check() && sb_comment_spam($commentdata)) {
        $commentdata['comment_content'] = '';
        SB_Comment::set_spam_session(1);
        return $commentdata;
    }
    $comment_author_url = isset($commentdata['comment_author_url']) ? $commentdata['comment_author_url'] : '';
    if(!empty($comment_author_url) && (!SB_PHP::is_url($comment_author_url) || SB_Comment::disable_website_url())) {
        unset( $commentdata['comment_author_url'] );
    } else {
        $commentdata['comment_author_url'] = SB_PHP::get_domain_name_with_http($comment_author_url);
    }
    if( $commentdata['comment_content'] == SB_PHP::strtoupper( $commentdata['comment_content'] )) {
        $commentdata['comment_content'] = SB_PHP::strtolower( $commentdata['comment_content'] );
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'sb_preprocess_comment', 1);

function sb_comment_nonce_field() {
    wp_nonce_field('sb_comment_form');
}
add_action('comment_form', 'sb_comment_nonce_field');

function sb_comment_stop() {
    if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'sb_comment_form')) {
        wp_die(__('Bình luận của bạn không hợp lệ!', 'sb-core'));
    }
}
add_action('pre_comment_on_post', 'sb_comment_stop');

function sb_comment_empty_spam_schedule(){
    if(!wp_next_scheduled('sb_comment_empty_spam_cron_job')) {
        wp_schedule_event(time(), 'hourly', 'sb_comment_empty_spam_cron_job');
    }
}
add_action('init', 'sb_comment_empty_spam_schedule');
add_action('sb_comment_empty_spam_cron_job', 'sb_comment_empty_spam_cron_function');