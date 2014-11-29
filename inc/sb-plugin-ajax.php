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