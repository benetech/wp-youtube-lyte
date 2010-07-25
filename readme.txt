=== Plugin Name ===
Contributors: futtta
Tags: youtube, video, lyte, lite youtube embeds, html5 video, html5
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 0.5.0

"Lite YouTube Embeds" look like normal YouTube embeds but don't use Flash, thus reducing download size & page rendering time.

== Description ==

WP-YouTube-Lyte inserts "Lite YouTube Embeds" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on, thereby [reducing download size & rendering time substantially](http://blog.futtta.be/2010/04/23/high-performance-youtube-embeds/). Just add a YouTube-link with "httpv" instead of "http" and WP-YouTube-Lyte will replace that link with the correct (flash-less) code.

WP-YouTube-Lyte implements [LYTE](http://blog.futtta.be/2010/04/23/high-performance-youtube-embeds/ "High Performance YouTube embeds"), which is a small javascript-library that creates a "dummy" YouTube-player which includes the clip thumbnail and title. When clicked on, the dummy player is seamlessly replaced by the Flash video player.

Experimental support for embedding html5 YouTube video is available (implementing [YouTube's new embed code](http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html)), meaning WP-YouTube-Lyte allows for an entirely flash-less YouTube experience on your blog, displaying YouTube's HTML5 video in h264 or the new WebM-coded.

WP-Youtube-lyte can be used together with [Smart Youtube](http://wordpress.org/extend/plugins/smart-youtube/ "Great plugin"). In that case WP-Youtube-lyte will take care of the default embeds (httpv), while Smart Youtube continues to parse other types (httpvh, httpvhd, httpvp, ...).

== Installation ==

Just install form your Wordpress "Plugins|Add New" screen and all will be well. Manual installation is very straightforward as well:

1. Upload the zip-file and unzip it in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place a link to a YouTube clip like this; httpv://www.youtube.com/watch?v=_SQkWbRublY

== Frequently Asked Questions ==
= What does "experimental html5 video support" mean? =
HTML5 video will not be visible for everyone (see requirements), some visitors will see the fallback Flash video instead.

= What are requirements to see embedded YouTube HTML5 video? =

* It only works in browsers that support the h264 (Safari, Chrome, IE9) or WebM (currently development versions of Chrome, Opera and Firefox) video codecs
* You have to be enrolled in the [YouTube html5 beta](http://www.youtube.com/html5)

= Any bugs/ issues should I know about? =
* The new YouTube HTML5-embed-code is a work in progress, positioning of video isn't always perfect when fallback Flash-version is used.
* The YouTube-thumbnail doesn't fit in the smaller-sized player, which will typically be visible when playing 16:9 videos (as YouTube adds black border on top and bottom)
* The controls (play button & bottom control) are not optimized for the different sizes, they just scale along (for now).

= What features might be added at a later stage? =
* Having the video title link to the YouTube-page
* Better quality controls for different sizes

= But I would like yet other features to be added! =
Just ask, I'll see what I can do.

== Changelog ==
= 0.5.0 =
* implemented the new [HTML5 YouTube embed code](http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html) and removed my [newTube.js-hack](http://blog.futtta.be/2010/06/16/embedding-html5-youtube-video-with-wp-youtube-lyte/) for html5-embedding
* player size now applies to Flash- and the new HTML5-embeds

= 0.4.1 =
* add fullscreen-button to player
* disable size in options if html5 is selected
* move player_sizes.inc to player_sizes.inc.php

= 0.4.0 =
* add options to change player size (does not apply to html5-version)
* noscript optimizations: show image (typically useful in rss-feeds), no text if config is to show links beneath lyte-player

= 0.3.5 =
* changed function-name in options.php to avoid errors like "Fatal error: Cannot redeclare register_mysettings()"

= 0.3.4 =
* tested succesfully on the brand new wordpress 3.0 release
* css changes to avoid themes messing up lyte-player layout
* minor text tweaks

= 0.3.3 =
* the "sorry for the linebreak-release"; a linebreak at the very end of options.php caused some configurations [to produce "headers already sent" errors on all wp-admin pages](http://codex.wordpress.org/FAQ_Troubleshooting#How_do_I_solve_the_Headers_already_sent_warning_problem.3F).
* some further readme.txt optimizations

= 0.3.2 =
* fixed misc. readme.txt markdown issues (again)

= 0.3.0 =
* added very experimental support for embedded html5 video (see [faq](http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/))

= 0.2.2 =
* improved the html of the form in options.php for better accessibility

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
