<?php
/*
Plugin Name: SB Comment
Plugin URI: http://hocwp.net/
Description: SB Comment is a plugin that allows to check spam comment on your WordPress site, improve the default comment template on your blog.
Author: SB Team
Version: 1.0.2
Author URI: http://hocwp.net/
Text Domain: sb-comment
Domain Path: /languages/
*/

define('SB_COMMENT_FILE', __FILE__);

define('SB_COMMENT_PATH', untrailingslashit(plugin_dir_path(SB_COMMENT_FILE)));

define('SB_COMMENT_URL', plugins_url('', SB_COMMENT_FILE));

define('SB_COMMENT_INC_PATH', SB_COMMENT_PATH . '/inc');

define('SB_COMMENT_BASENAME', plugin_basename(SB_COMMENT_FILE));

define('SB_COMMENT_DIRNAME', dirname(SB_COMMENT_BASENAME));

require SB_COMMENT_INC_PATH . '/sb-plugin-functions.php';
