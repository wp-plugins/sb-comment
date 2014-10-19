<?php
function sb_comment_check_core() {
    $activated_plugins = get_option('active_plugins');
    $sb_core_installed = in_array('sb-core/sb-core.php', $activated_plugins);
    if(!$sb_core_installed) {
        $sb_plugins = array(SB_COMMENT_BASENAME);
        $activated_plugins = get_option('active_plugins');
        $activated_plugins = array_diff($activated_plugins, $sb_plugins);
        update_option('active_plugins', $activated_plugins);
    }
    return $sb_core_installed;
}

function sb_comment_activation() {
    if(!sb_comment_check_core()) {
        wp_die(sprintf(__('You must install and activate plugin %1$s first! Click here to %2$s.', 'sb-comment'), '<a href="https://wordpress.org/plugins/sb-core/">SB Core</a>', sprintf('<a href="%1$s">%2$s</a>', admin_url('plugins.php'), __('go back', 'sb-comment'))));
    }
    do_action('sb_comment_activation');
}
register_activation_hook(SB_COMMENT_FILE, 'sb_comment_activation');

if(!sb_comment_check_core()) {
    return;
}

function sb_comment_settings_link($links) {
    if(sb_comment_check_core()) {
        $settings_link = sprintf('<a href="admin.php?page=sb_comment">%s</a>', __('Settings', 'sb-comment'));
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
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    if(is_singular() || is_page()) {
        wp_enqueue_style('sb-comment-style', SB_COMMENT_URL . '/css/sb-comment-style.css');
        wp_enqueue_script('sb-comment', SB_COMMENT_URL . '/js/sb-comment-script.js', array('jquery', 'sb-core'), false, true);
    }
}
add_action("wp_enqueue_scripts", "sb_comment_style_and_script");

function sb_comment_navigation( $type ) {
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

        <nav id="comment-nav-<?php echo $type; ?>" class="navigation comment-navigation">
            <h4 class="screen-reader-text"><?php _e( 'Comment navigation', 'sb-comment' ); ?></h4>
            <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older comment'), 'sb-comment' ); ?></div>
            <div class="nav-next"><?php next_comments_link( __( 'Newer comment &rarr;', 'sb-comment' ) ); ?></div>
        </nav>

    <?php endif;
}

function sb_comment_template() {
    if(!SB_Comment::is_spam_session() && (comments_open() || get_comments_number())) {
        comments_template();
    }
}

function sb_comment_form_args() {
    global $user_identity;
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $args = array(
        'fields'				=> apply_filters( 'comment_form_default_fields', array(
                'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'sb-comment' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" placeholder="'.__('Your name', 'sb-comment').' *" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' class="sb-author-info"></p>',
                'email' => '<p class="comment-form-email">' . '<label for="email">' . __( 'Email', 'sb-comment' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" placeholder="'.__('Your email', 'sb-comment').' *" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' class="sb-author-info"></p>',
                'url' => '<p class="comment-form-url">' . '<label for="url">' . __( 'Website', 'sb-comment' ) . '</label>' . '<input id="url" name="url" placeholder="'.__('Your website', 'sb-comment').'" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" class="sb-author-info"></p>'
            )
        ),
        'comment_field'			=> '<p class="comment-form-comment">' . '<label for="comment">' . __( 'Comment', 'sb-comment' ) . '</label>' . '<textarea id="comment" name="comment" placeholder="" aria-required="true" class="sb-comment-msg"></textarea></p>',
        'comment_notes_before'	=> '<p class="comment-notes before">' . __( 'Your email address will not be published.', 'sb-comment' ) . __( $req ? ' '.sprintf(__('Required fields are marked %s', 'sb-comment'), '(*)') : '' ) . '</p>',
        'comment_notes_after'	=> '<p class="form-allowed-tags comment-notes after">' . sprintf( __( sprintf(__('You may use these %1$s tags and attributes: %2$s', 'sb-comment'), '<abbr title="'.__('HyperText Markup Language', 'sb-comment').'">HTML</abbr>', ' <code>' . allowed_tags() . '</code>' ), 'sb-comment')) . '</p>',
        'must_log_in'			=> '<p class="must-log-in">' . sprintf(__( 'You must %s before leave a comment.', 'sb-comment' ), sprintf('<a href="%1$s">%2$s</a>', wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ), __('login', 'sb-comment')) ) . '</p>',
        'logged_in_as'			=> '<p class="logged-in-as">' . sprintf(__('You are logged in as %s', 'sb-comment'), sprintf(' <a href="%1$s">%2$s</a>. <a href="%3$s" title="%4$s">%5$s?</a>', admin_url( 'profile.php' ), esc_attr( $user_identity ), wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ), __('Logout', 'sb-comment'), __('Logout', 'sb-comment'))). '</p>',
        'title_reply'			=> '<a id="leaveyourcomment"></a><span class="comment-title">'.__('Leave a reply', 'sb-comment').'</span>',
        'label_submit'			=> __('Post comment', 'sb-comment'),
        'title_reply_to'		=>  __( 'Reply to %s', 'sb-comment' ),
        'cancel_reply_link'		=> __('Cancel reply', 'sb-comment')
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
        wp_die(__('Spam detected!', 'sb-comment'));
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

function sb_comment_nonce_field() {
    wp_nonce_field('sb_comment_form');
}
add_action('comment_form', 'sb_comment_nonce_field');

function sb_comment_stop() {
    if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'sb_comment_form')) {
        wp_die(__('Your comment is not valid!', 'sb-core'));
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