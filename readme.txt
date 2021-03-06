=== SB Comment ===
Contributors: skylarkcob
Donate link: http://hocwp.net/donate/
Tags: sb, sb team, sb plugin, comment, wordpress comment, sb comment
Requires at least: 3.9
Tested up to: 4.1.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SB Comment is a plugin that allows to check spam comment on your WordPress site, improve the default comment template on your blog.

== Description ==

SB Comment is a plugin that allows to check spam comment on your WordPress site, improve the default comment template on your blog. With this plugin, visitors can receive notify email when their comment is approved.

**Required Plugin**

* [SB Core](https://wordpress.org/plugins/sb-core/)

**Features**

* Check for spam comment.
* Send email notifications to user when their comment is approved.

**Translations**

* English
* Vietnamese

**Recommended WordPress Plugins**

* [SB Banner Widget](https://wordpress.org/plugins/sb-banner-widget/)
* [SB Clean](https://wordpress.org/plugins/sb-clean/)
* [SB Login Page](https://wordpress.org/plugins/sb-login-page/)
* [SB Paginate](https://wordpress.org/plugins/sb-paginate/)
* [SB Post Widget](https://wordpress.org/plugins/sb-post-widget/)
* [SB Tab Widget](https://wordpress.org/plugins/sb-tab-widget/)
* [SB TBFA](https://wordpress.org/plugins/sb-tbfa/)

== Installation ==

Install this plugin from your WordPress site Dashboard or follow these steps below:

1. Download plugin from WordPress Plugins directory and extract it.
1. Upload the `sb-comment` folder to the `/wp-content/plugins/` directory.
1. Activate the SB Comment plugin through the 'Plugins' menu in WordPress.
1. Configure the plugin by going to the `SB Options` menu that appears in your admin menu.

Put the sb_comments function into comments.php file of your theme.

<?php if(function_exists('sb_comments')) sb_comments(); ?>

Put the sb_comment_template function into where you want comment template display (Usually after The Loop).

<?php if(function_exists('sb_comment_template')) sb_comment_template(); ?>

== Frequently Asked Questions ==

Please visit [homepage](http://hocwp.net) for more details.

== Screenshots ==

1. The default comment form.
2. The default comment list.

== Upgrade Notice ==

Please update SB Core before you upgrade SB Comment to new version.

== Changelog ==

= 1.1.2 =
Stop using SB Plugins if current theme doesn't support.

= 1.1.1 =
* Update style for comment list.
* Re-struct SB Comment.

= 1.1.0 =
* Tested up to WordPress 4.1 version.
* Add style for small screen.
* Add screenshots.

= 1.0.9 =
Add function for user set default avatar.

= 1.0.8 =
* Add option to disable comment author url.
* Add option to control comment tools.

= 1.0.7 =
Update style for comment date.

= 1.0.6 =
* Update functions for class SB_Comment
* Update comment ajax.
* Update function to check comment before post.
* Change Vietnamese to default language.

= 1.0.5 =
* Update style for default comment template.
* Add function for user can like and report a comment.

= 1.0.4 =
Update check core functions.

= 1.0.3 =
Update check core function.

= 1.0.2 =
Update new check core functions.

= 1.0.1 =
Update sanitize data input functions.

= 1.0.0 =
First release of SB Comment.