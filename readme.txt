=== SB Comment ===
Contributors: skylarkcob
Donate link: http://hocwp.net/donate/
Tags: sb, sb team, sb plugin, comment, wordpress comment, sb comment
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.0.2
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
* [SB Paginate](https://wordpress.org/plugins/sb-paginate/)
* [SB Login Page](https://wordpress.org/plugins/sb-login-page/)
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

Please visit [homepage](http://hocwp.net) for more details.

== Upgrade Notice ==

Please update SB Core before you upgrade SB Comment to new version.

== Changelog ==

= 1.0.2 =
Update new check core functions.

= 1.0.1 =
Update sanitize data input functions.

= 1.0.0 =
First release of SB Comment.