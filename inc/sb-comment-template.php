<?php
if(post_password_required()) {
    return;
}
if(!function_exists('sb_comment_callback')) {
    function sb_comment_callback($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);
        $comment_permalink = get_comment_link($comment);
        if('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>
        <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
        <?php if('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
            <?php printf('<cite class="fn inline-block"><strong>%1$s</strong> - <span class="time-ago">' . SB_Comment::get_human_time_diff($comment->comment_ID) . '</span></cite> <span class="says">%2$s</span>', get_comment_author_link(), __('nói:', 'sb-comment')); ?>
            <?php edit_comment_link('('.__('Sửa', 'sb-comment').')', '', '' ); ?>
        </div>
        <?php
        $show_avatar = get_option('show_avatars');
        $style = '';
        if(!(bool)$show_avatar) {
            $style = 'margin-left:0;';
        }
        ?>
        <div class="comment-content" style="<?php echo $style; ?>">
            <?php comment_text(); ?>
        </div>
        <?php if($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Bình luận của bạn đang được chờ để xét duyệt.', 'sb-comment'); ?></em>
        <?php endif; ?>
        <div class="comment-tools" data-comment="<?php echo $comment->comment_ID; ?>" data-url="<?php echo $comment_permalink; ?>">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
            <?php
            $class = 'comment-like';
            $session_comment_liked_key = 'comment_' . $comment->comment_ID . '_likes';
            $liked = intval(SB_PHP::get_session($session_comment_liked_key));
            if($liked == 1) {
                $class = SB_PHP::add_string_with_space_before($class, 'disable');
            }
            ?>
            <a class="<?php echo $class; ?>" href="javascript:;" data-session-liked-key="<?php echo $session_comment_liked_key; ?>"><span class="text"><?php _e('Thích', 'sb-comment'); ?></span><i class="fa fa-thumbs-o-up icon-right"></i> <span class="sep-dot">.</span> <span class="count"><?php echo SB_Comment::get_likes($comment->comment_ID); ?></span></a>
            <a class="comment-report" href="javascript:;"><?php _e('Báo cáo vi phạm', 'sb-comment'); ?></a>
            <a class="comment-share" href="javascript:;">
                <span class="text">
                    <?php _e('Chia sẻ', 'sb-comment'); ?>
                </span>
                <i class="fa fa-angle-down icon-right"></i>
                <span class="list-share">
                    <?php $url = SB_Core::get_social_share_url(array('social_name' => 'facebook', 'permalink' => $comment_permalink)); ?>
                    <i class="fa fa-facebook facebook" data-url="<?php echo $url; ?>"></i>
                    <i class="fa fa-google-plus google" data-url="<?php echo SB_Core::get_social_share_url(array('social_name' => 'googleplus', 'permalink' => $comment_permalink)); ?>"></i>
                    <i class="fa fa-twitter twitter" data-url="<?php echo SB_Core::get_social_share_url(array('social_name' => 'twitter', 'permalink' => $comment_permalink)); ?>"></i>
                </span>
            </a>
        </div>
        <?php if('div' != $args['style'] ) : ?>
            </div>
        <?php endif;
    }
}
?>
<div id="comments" class="comments-area sb-comment-area sb-comment-template">
    <?php if(comments_open() || get_comments_number()) : ?>
        <div class="comments-title">
            <span class="comment-count">
                <?php
                $int_count = get_comments_number();
                printf(_n('1 bình luận', '%1$s bình luận', $int_count, 'sb-comment' ), number_format_i18n($int_count));
                ?>
            </span>
            <span class="yours"><a href="<?php the_permalink(); ?>#leaveyourcomment"><?php _e('Thêm bình luận', 'sb-comment'); ?></a></span>
        </div>
        <?php sb_comment_navigation('above'); ?>
        <ol class="comment-list">
            <?php
            $args = array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 100,
                'reply_text' => '<i class="fa fa-reply icon-left"></i><span class="text">' . __('Trả lời', 'sb-comment') . '</span>',
                'callback' => 'sb_comment_callback',
                'max_depth' => 3
            );
            wp_list_comments($args);
            ?>
        </ol>
        <?php sb_comment_navigation('below'); ?>
    <?php endif; ?>
    <?php if(!sb_comment_allowed()) : ?>
        <p class="no-comments"><?php _e('Bình luận đã được đóng.', 'sb-comment'); ?></p>
    <?php else : ?>
        <?php
        $user_can_post_comment = sb_user_can_post_comment();
        if($user_can_post_comment) {
            comment_form(sb_comment_form_args());
        }
        ?>
    <?php endif; ?>
</div>