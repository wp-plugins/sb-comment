<?php
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
    if(SB_Comment::disable_website_url()) {
        unset($args['fields']['url']);
    }
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

function sb_comment_spam($commentdata) {
    return SB_Comment::is_spam($commentdata);
}

function sb_comment_empty_spam_cron_function(){
    if(SB_Comment::enable_auto_empty_spam()) {
        SB_Comment::delete_spam();
    }
}

function sb_comment_testing() {
    return apply_filters('sb_comment_testing', false);
}