<?php
if ( post_password_required() ) {
    return;
}
?>
    <div id="comments" class="comments-area sb-comment-area">
        <?php if ( have_comments() ) : ?>
            <div class="comments-title">
                <?php
                $int_count = get_comments_number();
                printf( _n( '1 comment', '%1$s comments', $int_count, 'sb-comment' ), number_format_i18n( $int_count ) );
                ?>
                <span class="yours"><a href="<?php the_permalink(); ?>#leaveyourcomment"><?php _e('Add your comment', 'sb-comment'); ?></a></span>
            </div>

            <?php sb_comment_navigation( 'above' ); ?>

            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'style'			=> 'ol',
                    'short_ping'	=> true,
                    'avatar_size'	=> 100,
                    'reply_text'	=> __('Reply', 'sb-comment'),
                    'callback'		=> 'sb_comment_callback'
                ) );
                ?>
            </ol>

            <?php sb_comment_navigation( 'below' ); ?>

        <?php endif; ?>

        <?php if ( !sb_comment_allowed() ) : ?>
            <p class="no-comments"><?php _e( 'Comments are closed.', 'sb-comment' ); ?></p>
        <?php else : ?>
            <?php
            $user_can_post_comment = sb_user_can_post_comment();
            if($user_can_post_comment) {
                comment_form( sb_comment_form_args() );
            }
            ?>
        <?php endif; ?>

    </div>
<?php
function sb_comment_callback( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        <?php printf( '<cite class="fn inline-block"><strong>%1$s</strong></cite> <span class="says">%2$s</span>', get_comment_author_link(), __('says:', 'sb-comment') ); ?>
    </div>
    <div class="comment-meta comment-metadata">
        <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
            <?php printf( '%1$s %2$s %3$s', get_comment_date(), __('at', 'sb-comment'),  get_comment_time() ); ?></a><?php edit_comment_link( '('.__('Edit', 'sb-comment').')', '', '' ); ?>
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
    <?php if ( $comment->comment_approved == '0' ) : ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'sb-comment' ); ?></em>
    <?php endif; ?>
    <div class="reply">
        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
    </div>
    <?php if ( 'div' != $args['style'] ) : ?>
        </div>
    <?php endif;
}
?>