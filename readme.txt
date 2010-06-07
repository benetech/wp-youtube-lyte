=== Plugin Name ===
Contributors: futtta
Tags: youtube, video, lyte, lite youtube embeds
Requires at least: 2.9
Tested up to: 2.9.2
Stable tag: 0.2.1

"Lite YouTube Embeds" look like normal YouTube embeds but don't use Flash unless clicked, thus reducing download size & page rendering time.

== Description ==

WP-Youtube-lyte inserts "Lite YouTube Embeds" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on, thereby reducing download size & time substantially. Just add a YouTube-link with "httpv" instead of "http" and WP-YouTube-lyte will replace that link with the correct (flash-less) code.

WP-Youtube-lyte implements [LYTE](http://blog.futtta.be/2010/04/23/high-performance-youtube-embeds/ "High Performance YouTube embeds"), which is a small javascript-library that creates a "dummy" YouTube-player which includes the clip thumbnail and title. When clicked on, the dummy player is replaced by the Flash player.

WP-Youtube-lyte can be used together with [Smart Youtube](http://wordpress.org/extend/plugins/smart-youtube/ "Great plugin"). In that case WP-Youtube-lyte will take care of the default embeds (httpv), while Smart Youtube continues to parse other types (httpvh, httpvhd, httpvp, ...).

== Installation ==

Installation is very straightforward:

1. Upload the zip-file and unzip it in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place a link to a YouTube clip like this; httpv://www.youtube.com/watch?v=_SQkWbRublY

== Frequently Asked Questions ==
= What features might be added at a later stage? =
* Extra options in the admin-page, e.g. the default size of your video (now: 480X385)
* Having the video title link to the YouTube-page

= But I would like yet other features to be added! =
Just ask, I'll see what I can do.

== Changelog ==
= 0.2.1 =
* 0.2.0 was broken (options.php M.I.A.), 0.2.1 fixes this

= 0.2.0 =
* Added a simple admin-page to allow administrators to choose if links to YouTube and Easy YouTube are added or not
* Added some bottom-margin to the lytelinks div

= 0.1.4 =
* forgot to update version in the php-file for 0.1.3, causing the update not being fully propageted

= 0.1.3 =
* small bugfix release (opacity of the play-button in Chrome/Safari)

= 0.1.2 =
Accessibility enhancements (hat tip: Ricky Buchanan):

* added alt attributes to images
* moved youtube link from noscript to div
* added link to easy youtube

= 0.1.1 =
* Changed meta-info in readme and php-file

= 0.1 =
* Initial version
