(function($){
    (function(){
        $('#respond').on('submit', function(event){
            var commentBody = $(this).find('textarea'),
                commentName = $(this).find('#author'),
                commentEmail = $(this).find('#email'),
                mathCaptcha = $(this).find('#mc-input'),
                data = {
                    'action': 'sb_comment',
                    'comment_body': commentBody.val(),
                    'comment_name': commentName.val(),
                    'comment_email': commentEmail.val()
                };

            if((commentBody.length && '' == commentBody.val()) || (commentName.length && '' == commentName.val()) || (commentEmail.length && '' == commentEmail.val()) || (mathCaptcha.length && ('' == mathCaptcha.val() || false == $.isNumeric(mathCaptcha.val())))) {
                if(event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }
            }
        });
    })();
    (function(){
        $('.comment-tools .comment-like').on('click', function(e){
            var that = $(this),
                data = null,
                tool_box_container = that.closest('.comment-tools'),
                like = parseInt(that.find('.count').text()),
                comment_id = tool_box_container.attr('data-comment'),
                session_key = that.attr('data-session-liked-key');
            if(!that.hasClass('disable')) {
                data = {
                    'action': 'sb_comment_like',
                    'comment_id': comment_id,
                    'session_key': session_key
                };
                $.post(sb_core_ajax.url, data, function(resp){
                    resp = parseInt(resp);
                    if(1 == resp) {
                        that.addClass('disable');
                        like++;
                        that.find('.count').text(like);
                        $.session.set(session_key, 1);
                    }
                });
            }
        });
        $('.comment-tools .comment-report').on('click', function(e){
            var that = $(this);
        });
        $('.comment-tools .comment-share').on('click', function(e){
            var that = $(this),
                share_item_container = that.find('.list-share');
            share_item_container.toggleClass('active');
            share_item_container.find('i').fadeIn();
        });
        $('.comment-tools .list-share > i').on('click', function(e){
            var that = $(this),
                share_item_container = that.closest('span');
            share_item_container.find('i').fadeOut();
            window.open(that.attr('data-url'), 'ShareWindow', 'height=450, width=550, toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
        });
    })();
})(jQuery);