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
})(jQuery);