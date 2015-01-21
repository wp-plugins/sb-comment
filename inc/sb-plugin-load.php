<?php
require SB_COMMENT_INC_PATH . '/sb-plugin-install.php';

if(!sb_comment_check_core()) {
    return;
}

require SB_COMMENT_INC_PATH . '/sb-plugin-functions.php';

require SB_COMMENT_INC_PATH . '/class-sb-comment.php';

require SB_COMMENT_INC_PATH . '/class-sb-spam.php';

require SB_COMMENT_INC_PATH . '/sb-plugin-admin.php';

require SB_COMMENT_INC_PATH . '/sb-plugin-ajax.php';

require SB_COMMENT_INC_PATH . '/sb-plugin-hook.php';