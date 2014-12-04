<?php
function sb_comment_like_ajax_callback() {
    $comment_id = isset($_POST['comment_id']) ? $_POST['comment_id'] : 0;
    $session_key = isset($_POST['session_key']) ? $_POST['session_key'] : 'comment_' . $comment_id . '_likes';
    if($comment_id > 0) {
        SB_Comment::update_likes($comment_id);
        SB_PHP::set_session($session_key, 1);
        echo 1;
    }
    die();
}
add_action('wp_ajax_sb_comment_like', 'sb_comment_like_ajax_callback');
add_action('wp_ajax_nopriv_sb_comment_like', 'sb_comment_like_ajax_callback');

function sb_comment_ajax_callback() {
    $comment_body = isset($_POST['comment_body']) ? $_POST['comment_body'] : '';
    $comment_name = isset($_POST['comment_name']) ? $_POST['comment_name'] : '';
    $comment_email = isset($_POST['comment_email']) ? $_POST['comment_email'] : '';
    $comment_url = isset($_POST['comment_url']) ? $_POST['comment_url'] : '';
    $comment_data = array();
    $comment_data['comment_content'] = $comment_body;
    $comment_data['comment_author_name'] = $comment_name;
    $comment_data['comment_author_url'] = $comment_url;
    if(SB_Comment::is_spam($comment_data)) {
        echo 1;
    } else {
        echo 0;
    }
    die();
}
add_action('wp_ajax_sb_comment', 'sb_comment_ajax_callback');
add_action('wp_ajax_nopriv_sb_comment', 'sb_comment_ajax_callback');