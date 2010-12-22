=== Plugin Name ===
Contributors: futtta
Tags: youtube, video, lyte, lite youtube embeds, html5 video, html5, widget
Requires at least: 2.9
Tested up to: 3.0.3
Stable tag: 0.6.3

"Lite YouTube Embeds" look like normal YouTube embeds but don't use Flash, thus reducing download size & page rendering time.

== Description ==

WP-YouTube-Lyte inserts "Lite YouTube Embeds" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on, thereby [reducing download size & rendering time substantially](http://blog.futtta.be/2010/08/30/the-state-of-wp-youtube-lyte/). Just add a YouTube-link with "httpv" instead of "http" and WP-YouTube-Lyte will replace that link with the correct (flash-less) code.

WP-YouTube-Lyte implements [LYTE](http://blog.futtta.be/2010/04/23/high-performance-youtube-embeds/ "High Performance YouTube embeds"), which is a small javascript-library that creates a "dummy" YouTube-player which includes the clip thumbnail and title. When clicked on, the dummy player is seamlessly replaced by the actual video player.

Experimental support for embedding html5 YouTube video is available (implementing [YouTube's new embed code](http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html)), meaning WP-YouTube-Lyte allows for an entirely flash-less YouTube experience on your blog, displaying YouTube's HTML5 video in h264 or the new WebM-codec.

WP-Youtube-lyte can be used together with [Smart Youtube](http://wordpress.org/extend/plugins/smart-youtube/ "Great plugin"). In that case WP-Youtube-lyte will take care of the default embeds (httpv), while Smart Youtube continues to parse other types (httpvh, httpvhd, httpvp, ...).

Feedback is welcome; see [info in the faq](http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/) for bug reports/ feature requests and feel free to [rate and/or report on compatibility on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/).

== Installation ==

Just install form your Wordpress "Plugins|Add New" screen and all will be well. Manual installation is very straightforward as well:

1. Upload the zip-file and unzip it in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place a link to a YouTube clip like this; httpv://www.youtube.com/watch?v=_SQkWbRublY

== Frequently Asked Questions ==
= What does "experimental html5 video support" mean? =
HTML5 video will not be visible for everyone (see requirements), some visitors will see the fallback Flash video instead.

= What are the requirements to see embedded YouTube HTML5 video? =
* It only works in browsers that support the h264 (Safari, Chrome, IE9) or WebM (currently development versions of Chrome, Opera and Firefox) video codecs
* You have to be enrolled in the [YouTube html5 beta](http://www.youtube.com/html5)

= Does WP YouTube Lyte protect my visitor's privacy? =
As opposed to some of the [most important](http://blog.futtta.be/2010/12/15/wordpress-com-stats-trojan-horse-for-quantcast-tracking/) [plugins](http://blog.futtta.be/2010/01/22/add-to-any-removed-from-here/) there is no 3rd party tracking code in WP YouTube Lyte.

= Any bugs/ issues should I know about? =
* The new YouTube HTML5-embed-code is a work in progress, positioning of video isn't always perfect when fallback Flash-version is used.
* If you're using the HTML5-version and you have a WP-YouTube-Lyte widget in your sidebar, you'll notice how the controls at the bottom overlap. This is because YouTube's new embed code doesn't scale down to small sizes too great. The normal (Flash-based) player omits most controls in this case, I would expect the HTML5-version to do this as well in a not to distant future.
* Having the same YouTube-video on one page can cause WP YouTube Lyte to malfunction (as the YouTube id is used as the div's id in the DOM, and DOM id's are supposed to be unique)

= I found a bug/ I would like a feature to be added! =
* Just tell me, I like the feedback! Use the [Contact-page on my blog](http://blog.futtta.be/contact/), [leave a comment in a post about wp-youtube-lyte](http://blog.futtta.be/tag/wp-youtube-lyte/) or [create a new topic on the wordpress.org forum](http://wordpress.org/tags/wp-youtube-lyte?forum_id=10#postform).

= How you can help =
* Tell me about bugs you think you've found and if you can't find any, [confirm it works with your version of WP on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/)
* Ask me for a feature you would like to see added
* [Rate my plugin on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/), even if you think it stinks ;-)

== Changelog ==
= 0.6.3 =
* only load jquery plugins on this plugin's options page
* change thumbnail positiong slightly (5 pixels up)
* tested on WordPress 3.0.3

= 0.6.2 =
* bugfix: the javascript in widgets.php caused a wp youtube lyte widget not to be shown in the sidebar if no wp youtube lyte was present in the main content
* load jquery plugins in admin screen using wp_enqueue_script rather then adding them "manually"
* store the selected feed on the admin-page in a cookie to show the same feed next time

= 0.6.1 =
* widget size can now be set (3 sizes available, to be specified for each widget individually)
* admin-page now contains links to most recent info (blogposts) on WP YouTube Lyte (and optionally WordPress and Web Technology in general) using [the excellent jQuery-plugin zrssfeed](http://www.zazar.net/developers/zrssfeed/)
* bugfix: removed CDATA-wrapper from javascript as WordPress turned ]]> into ]]&amp;gt; which broke the html (which in turn broke syndication in e.g. planets)

= 0.6.0 =
* There now is a WP-YouTube-Lyte widget which you can add to your sidebar (see under "Appearance"->"Widgets"), as requested by the fabulous [fruityoaty](http://fruityoaty.com/)
* The thumbnail is now stretched to use as much of the player as possible (thanks to css3's background-size:contain directive, which works in [all bleeding edge browsers](http://www.quirksmode.org/css/background.html#t012))
* Updated the "play"-button to fit the new YouTube style

= 0.5.3 =
* we now wait for the DOM to be fully loaded (except for MS IE, where we have to wait for window.load) before kicking in, which means wp-youtube-lyte now functions correctly in Opera
* fixed a bug where lyte's javascript would overwrite the main div's class-name (causing css-issues in some themes)
* there's [new test-data on my blog](http://blog.futtta.be/2010/08/30/the-state-of-wp-youtube-lyte/) that shows how fast wp-youtube-lyte really is.

= 0.5.2 =
* fixed a bug where WordPress' the_excerpt function showed wp-youtube-lyte javascript as text in excerpts
* fixed problem where google tried to index e.g. options.php (which produced ugly php errors)
* fixed some css-related bugs, do contact me (see FAQ) if LYTE-player isn't rendered correctly in your wordpress-theme!
* moved more css out of javascript to the static css-file

= 0.5.1 =
* added new versions of images, fitting the player width (no more ugly rescaling)
* moved a lot of css from javascript to a css-file which gets loaded on-the-fly

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
