=== WP YouTube Lyte ===
Contributors: futtta
Tags: youtube, video, lyte, lite youtube embeds, html5 video, html5, widget, youtube audio, audio, playlist, youtube playlist, hd, performance, accessibility, sidebar, lazy load
Requires at least: 2.9
Tested up to: 3.3
Stable tag: 1.0.0

"Lite YouTube Embeds" look like normal YouTube embeds but don't use Flash, thus reducing download size & page rendering time.

== Description ==

WP YouTube Lyte allows you to "lazy load" your video's, by inserting "Lite YouTube Embeds". These look and feel like normal embedded YouTube, but only call the actual "fat" Flash or HTML5-player when clicked on, thereby [reducing download size & rendering time substantially](http://blog.futtta.be/2010/08/30/the-state-of-wp-youtube-lyte/) when embedding YouTube occasionally and improving page performance dramatically when you've got multiple YouTube video's on one and the same page.

Just add a YouTube-link for a video or [an entire playlist](http://blog.futtta.be/2011/10/11/wp-youtube-lyte-support-for-playlists-almost-included/) with "httpv" (or "httpa" to [embed YouTube's audio](http://blog.futtta.be/2011/04/19/audio-only-youtube-embedding-with-wp-youtube-lyte-0-7/) only) instead of "http" or add a Lyte widget to your sidebar and WP YouTube Lyte replaces that link with the correct performance-optimized code. When a visitor clicks the play-button, WP YouTube Lyte seamlessly initiates [YouTube's new embedded player](http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html). Some examples:

* httpv://www.youtube.com/watch?v=_SQkWbRublY (normal video embed)
* httpv://youtu.be/_SQkWbRublY (video embed with youtube-shortlink)
* httpa://www.youtube.com/watch?v=_SQkWbRublY (audio only embed)
* httpv://www.youtube.com/playlist?list=PLA486E741B25F8E00 (playlist embed)
* httpv://www.youtube.com/watch?v=_SQkWbRublY#stepSize=-1 (video player, one size smaller than what's configured as default)

WP YouTube Lyte has been written with optimal performance as primary goal, but has been tested for maximum browser-compatibility (iPad included) while keeping an eye on accessibility. The plugin is fully multi-language, with support for Catalan, Dutch, English, French, German, Hebrew, Spanish and Slovene.

Feedback is welcome; see [info in the faq](http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/) for bug reports/ feature requests and feel free to [rate and/or report on compatibility on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/).

== Installation ==

Just install form your Wordpress "Plugins|Add New" screen and all will be well. Manual installation is very straightforward as well:

1. Upload the zip-file and unzip it in the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place a link to a YouTube clip like this; httpv://www.youtube.com/watch?v=_SQkWbRublY

== Frequently Asked Questions ==

= What does "html5 video support" mean? =
When playing, HTML5 video will not be visible for everyone (see requirements). Indeed some visitors will see the fallback Flash video instead.

= What are the requirements to see embedded YouTube HTML5 video? =
* It only works in browsers that support the h264 (Safari, Chrome, IE9) or WebM (currently Chrome, Opera and Firefox) video codecs
* Your visitor has to be enrolled in the [YouTube html5 beta](http://www.youtube.com/html5)

= Does WP YouTube Lyte protect my visitor's privacy? =
As opposed to some of the [most important](http://blog.futtta.be/2010/12/15/wordpress-com-stats-trojan-horse-for-quantcast-tracking/) [plugins](http://blog.futtta.be/2010/01/22/add-to-any-removed-from-here/) there is no 3rd party tracking code in WP YouTube Lyte, but YouTube off course does see visitor requests coming in (see also the youtube-nocookie.com remark in Bugs/Issues below).

= How can I center the player? =
Centering the player is pretty easy; open up wp-content/plugins/wp-youtube-lyte/lyte/lyte.css and change
`.lP {background-color:#fff;}`
into
`.lP {background-color:#fff;margin:0 auto;}`

= Can I use WP YouTube Lyte for a custom field? =
As tested and confirmed by [rumultik.ru's Dimitri](http://rumultik.ru) (thanks for that man!), this indeed does work. Just pass the httpv url of such a field to lyte_parse like this: 
`if(function_exists('lyte_parse')) { echo lyte_parse($video); }`
and you're good to go!

= Any bugs/ issues should I know about? =
* The playlist-player currently does not work on iPad or iPhone, this is a known limitation of Youtube's playlist player and [is on the todo-list to get fixed](http://groups.google.com/group/youtube-api-gdata/browse_frm/thread/adbec924f43688e5#)
* The new YouTube embed-code doesn't look great when using WP YouTube Lyte widgets in your sidebar. This is because YouTube's embedded player doesn't scale well for small sizes, this is something that YouTube should (and hopefully will) fix at a later stage.
* Having the same YouTube-video on one page can cause WP YouTube Lyte to malfunction (as the YouTube id is used as the div's id in the DOM, and DOM id's are supposed to be unique)
* As youtube-nocookie.com does not serve the HTML5-player, WP YouTube Lyte uses the youtube.com domain (which provides less privacy), but as soon as youtube-nocookie.com serves HTML5-video, this will become the default domain for WP YouTube Lyte again.

= I found a bug/ I would like a feature to be added! =
* Just tell me, I like the feedback! Use the [Contact-page on my blog](http://blog.futtta.be/contact/), [leave a comment in a post about wp-youtube-lyte](http://blog.futtta.be/tag/wp-youtube-lyte/) or [create a new topic on the wordpress.org forum](http://wordpress.org/tags/wp-youtube-lyte?forum_id=10#postform).

= How you can help =
* Tell me about bugs you think you've found and if you can't find any, [confirm it works with your version of WP on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/)
* Ask me for a feature you would like to see added
* [Rate my plugin on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/), even if you think it stinks ;-)

== Screenshots ==

1. This is the administration-page of WP YouTube Lyte.

== Changelog ==

= 1.0.0 =
* new: also works on (manual) excerpts; just add a httpv link to the "excerpt" field on the post/page admin (based on feedback from [Ruben@tuttingegneri](http://www.tuttingegneri.com))
* new: if youtube-url contains "start" or "showinfo" parameters, these are used when playing the actual video. This means that you can now jump to a specific time in the YouTube video or stop the title/ author from being displayed (based on feedback from a.o. Miguel and Josh D)
* update: javascript now initiates either after full page load or after 1 second (whatever comes first), thus avoiding video not showing due to other requests taking too long
* update: bonus feature stops lockerz.com tracking by addtoany (you'll still want to [hide the "earn pointz" tab though](http://share.lockerz.com/buttons/customize/hide_lockerz_earn_ptz_tab))
* bugfix: prevent the playing video to be in front of e.g. a dropdown-menu or lightbox (thanks to Matt Whittingham)
* bugfix: solve overlap between player and text when option was set not to show links (reported by Josh D)

= 0.9.4 =
* security: WP YouTube Lyte now works entirely in https if your blog is running in https
* performance (js/ page rendering): initiate the javascript a little later (at "load" instead of "DOMContentLoaded") to speed up page load (might need further optimizations)
* performance (php): have the plugin [only include/ execute php when needed](http://w-shadow.com/blog/2009/02/22/make-your-plugin-faster-with-conditional-tags/)
* updated donottrack.js to match the version used in my [WP DoNotTrack-plugin](http://wordpress.org/extend/plugins/wp-donottrack/). if want to tweak the way donottrack.js functions, you migth want to [check that plugin out](http://wordpress.org/extend/plugins/wp-donottrack/) (and disable the option in WP YouTube Lyte)
* bugfix: small tweak in css to force transparency of play-button

= 0.9.3 =
* Bugfix: donottrack.js incorrectly handled document.write, causing javascript that depends on it to malfunction (reported by [S.K.](http://aimwa.in), thanks for helping out!)
* Bugfix: moved inline javascript into a function expression to protect values (d=document) from other javascript that might use global variables (thanks to Eric McNiece of [emc2innovation.com](http://emc2innovation.com) for reporting & investigating)
* Bugfix: made changes to widgets to allow a video to appear both in a blog post and in the widget bar and to allow httpv-links in there (although httpv is not needed in widgets) based on feedback from [Nick Tann](http://nicktann.co.uk/)
* Bugfix: changed priority of add_filter to ensure wp-youtube-lyte can work alongside of the new Smart Youtube Pro v4 (although this might become a problem again if/when a new version of Smart Youtube arrives)
* Languages: added a full French translation (thanks Serge of [blogaf.org](http://www.blogaf.org))

= 0.9.2 =
* solved bug with W3 Total Cache where the URL for lyte-min.js got broken (thanks to Serge of [blogaf.org](http://www.blogaf.org) for reporting and helping figure this out)
* some [work on the bonus feature](http://blog.futtta.be/2011/11/16/applying-javascript-aop-magic-to-stop-3rd-party-tracking-in-wordpress/)

= 0.9.1 =
* even better xhtml-compliancy
* fixed readme.txt problems

= 0.9.0 =
* you can now change player size from the default one (as proposed by [Edward Owen](http://www.edwardlowen.com/)); httpv://www.youtube.com/watch?v=_SQkWbRublY#stepSize=-2 or httpv://youtu.be/_SQkWbRublY#stepSize=+1 will change player size to one of the other available sizes in your choosen format (4:3 or 16:9)
* added a smaller 16:9 size and re-arranged player sizes on the options-screen
* Bugfix: changed lyte-div ID to force it to be xhtml-compliant (ID's can't start with a digit, hat tip: Ruben of [ytuquelees.net](http://ytuquelees.net)
* Bugfix: added version in js-call to avoid caching issues (lyte-min.js?ver=0.8.1) as experienced by some users and reported by [Ryan of givemeshred.com](http://www.givemeshred.com)
* Upgrade to the "bonus feature" to [fix things](http://blog.futtta.be/2011/11/07/wp-privacy-quantcast-sneaks-back-in/) (consider this beta)
* Languages: added Hebrew (by [Sagive SEO](http://www.sagive.co.il/)) and Catalan (by Ruben of [ytuquelees.net](http://ytuquelees.net)) translations and added completed Spanish version (thanks to [Paulino Brener from Social Media Travelers](http://socialmediatravelers.com/ "Paulino Brener from Social Media Travelers, Your guide to your Social Media journey. We help businesses start or improve their Social Media presence."))
* tested succesfully on WordPress 3.3 (beta 2)

= 0.8.0 =
* added support for playlists
* added support for HD
* dropped support for the legacy YouTube embed-code
* updated UI elements to match new, dark YouTube player style
* updated player sizes to match YouTube's
* added new translations: Spanish (front-end strings, thanks to [Paulino Brener @Social Media Travelers](http://socialmediatravelers.com/)) and German (complete, by ["der Tuxman"](http://tuxproject.de/blog))

= 0.7.3 =
* sdded support for youtu.be links
* added sl_SI translation (thanks [Mitja Miheli&#268; @arnes.si](http://www.arnes.si/))
* load donottrack js in https if needed (thanks [Chris @campino2k.de](http://campino2k.de/))
* tested & confirmed to work perfectly with wordpress 3.2.1

= 0.7.2 =
* fixed a bug introduced in 0.7.1 which caused httpv-links that were not on newline, not to be turned into a lyte-player
* added audio as option for widgets as well (consider this beta, not thoroughly tested yet)

= 0.7.1 =
* re-minized lyte-min.js (there's lyte.js for your reading pleasure though)
* thumbnail image in noscript-tags now inherits size of div (to keep it from messing up the layout when JS is not available, e.g. in a feedburner-feed)
* the html5 version of the audio-player now is a bit higher (was 27px, now 33px) to allow scrolling through the clip
* the html-output of the plugin now validates against xhtml 1.0 transitional (thanks for the heads-up Carolin)
* text in frontend (i.e. what your visitors see) is translated into Dutch & French, [corrections and other translations are welcome](http://blog.futtta.be/contact/)

= 0.7.0 =
* new feature (as seen [on Pitchfork](http://pitchfork.com/ "great site for music lovers")): [audio-only YouTube embeds](http://blog.futtta.be/2011/04/19/audio-only-youtube-embedding-with-wp-youtube-lyte-0-7/) (use "httpa://" instead of "httpv://")
* merged lyte-min.js and lyte-newtube-min.js into one file
* added wmode=transparant when video is played in flash-mode

= 0.6.5 =
* updated images for html5-version to new look&feel
* disabled "watch later" by adding variable "probably_logged_in=false" to youtube embed
* changed lyte/lyte.css (move margin from .lt to .lyte) to allow changes to positioning of player
* changed name of js-variable in options.php to solve small bug in rss display
* added an (experimental) bonus feature

= 0.6.4 =
* happy New Year & thanks for the 10.000 downloads so far!
* solved an [issue with pre-5.2.1 versions of PHP which caused errors in widget.php](http://wordpress.org/support/topic/plugin-wp-youtube-lyte-parse_url-error-in-widget-version)
* tested on iPad, the HTML5-version works
* tested succesfully on WordPress 3.0.4 and 3.1 (release candidate)

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

== Upgrade Notice ==

= 0.9.3 =
Bugfix release, especially important if you've activated DoNotTrack or if you use widgets.
