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

if(!sb_comment_check_core()) {
    return;
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

function sb_comment_style_and_script() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    if(is_singular() || is_page()) {
        wp_enqueue_style('sb-comment-style', SB_COMMENT_URL . '/css/sb-comment-style.css');
        wp_enqueue_script('sb-comment', SB_COMMENT_URL . '/js/sb-comment-script.js', array('jquery', 'sb-core'), false, true);
    }
}
add_action('wp_enqueue_scripts', 'sb_comment_style_and_script');

function sb_comment_navigation($type) {
    if(get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
        <nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation">
            <h4 class="screen-reader-text"><?php _e('Phân trang bình luận', 'sb-comment'); ?></h4>
            <div class="nav-previous"><?php previous_comments_link(__('&larr; Bình luận cũ hơn'), 'sb-comment'); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Bình luận mới hơn &rarr;', 'sb-comment')); ?></div>
        </nav>
    <?php endif;
}

function sb_comment_template() {
    if(!SB_Comment::is_spam_session() && (comments_open() || get_comments_number())) {
        comments_template();
    } else {
        echo '<div class="no-comment"></div>';
    }
}

function sb_comment_form_args() {
    global $user_identity;
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ($req ? ' aria-required="true"' : '' );
    $args = array(
        'fields' => apply_filters( 'comment_form_default_fields', array(
                'author' => '<p class="comment-form-author name">' . '<label for="author">' . __( 'Tên', 'sb-comment' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" placeholder="'.__('Họ và tên', 'sb-comment').' *" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' class="sb-author-info"></p>',
                'email' => '<p class="comment-form-email email">' . '<label for="email">' . __( 'Địa chỉ email', 'sb-comment' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" placeholder="'.__('Địa chỉ email', 'sb-comment').' *" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' class="sb-author-info"></p>',
                'url' => '<p class="comment-form-url website">' . '<label for="url">' . __( 'Trang web', 'sb-comment' ) . '</label>' . '<input id="url" name="url" placeholder="'.__('Địa chỉ trang web', 'sb-comment').'" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="sb-author-info"></p>'
            )
        ),
        'comment_field'			=> '<p class="comment-form-comment">' . '<label for="comment">' . __( 'Nội dung', 'sb-comment' ) . '</label>' . '<textarea id="comment" name="comment" placeholder="" aria-required="true" class="sb-comment-msg"></textarea></p>',
        'comment_notes_before'	=> '<p class="comment-notes before">' . __( 'Địa chỉ email của bạn sẽ được giữ bí mật.', 'sb-comment' ) . __( $req ? ' '.sprintf(__('Những mục được đánh dấu %s là bắt buộc.', 'sb-comment'), '(*)') : '' ) . '</p>',
        'comment_notes_after'	=> '<p class="form-allowed-tags comment-notes after">' . sprintf( __( sprintf(__('Bạn có thể sử dụng những thẻ %1$s được liệt kê như sau: %2$s', 'sb-comment'), '<abbr title="'.__('Ngôn ngữ đánh dấu siêu văn bản bằng thẻ', 'sb-comment').'">HTML</abbr>', ' <code>' . allowed_tags() . '</code>' ), 'sb-comment')) . '</p>',
        'must_log_in'			=> '<p class="must-log-in">' . sprintf(__( 'Bạn phải %s trước khi tiến hành gửi bình luận.', 'sb-comment' ), sprintf('<a href="%1$s">%2$s</a>', wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ), __('đăng nhập', 'sb-comment')) ) . '</p>',
        'logged_in_as'			=> '<p class="logged-in-as">' . sprintf(__('Bạn đang đăng nhập với tên tài khoản %s', 'sb-comment'), sprintf(' <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">%5$s?</a>', admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url(apply_filters('the_permalink', get_permalink( ) ) ), __('Thoát', 'sb-comment'), __('Thoát', 'sb-comment'))). '</p>',
        'title_reply'			=> '<a id="leaveyourcomment"></a><span class="comment-title">'.__('Gửi bình luận', 'sb-comment').'</span>',
        'label_submit'			=> __('Gửi bình luận', 'sb-comment'),
        'title_reply_to'		=>  __( 'Trả lời %s', 'sb-comment' ),
        'cancel_reply_link'		=> __('Hủy trả lời', 'sb-comment')
    );
    return $args;
}

function sb_comment_allowed() {
    return (bool) comments_open(get_the_ID());
}

function sb_user_can_post_comment() {
    return apply_filters('sb_user_can_post_comment', true);
}

function sb_comments() {
    include SB_COMMENT_INC_PATH . '/sb-comment-template.php';
}

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

function sb_comment_spam($commentdata) {
    return SB_Comment::is_spam($commentdata);
}

function sb_preprocess_comment($commentdata) {
    if(SB_Comment::enable_spam_check() && sb_comment_spam($commentdata)) {
        $commentdata['comment_content'] = '';
        SB_Comment::set_spam_session(1);
        return $commentdata;
    }
    $comment_author_url = isset($commentdata['comment_author_url']) ? $commentdata['comment_author_url'] : '';
    if(!empty($comment_author_url) && !SB_PHP::is_url($comment_author_url) ) {
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

function sb_comment_empty_spam_cron_function(){
    if(SB_Comment::enable_auto_empty_spam()) {
        SB_Comment::delete_spam();
    }
}

function sb_comment_empty_spam_schedule(){
    if(!wp_next_scheduled('sb_comment_empty_spam_cron_job')) {
        wp_schedule_event(time(), 'hourly', 'sb_comment_empty_spam_cron_job');
    }
}
add_action('init', 'sb_comment_empty_spam_schedule');
add_action('sb_comment_empty_spam_cron_job', 'sb_comment_empty_spam_cron_function');

require SB_COMMENT_INC_PATH . '/sb-plugin-load.php';