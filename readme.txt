=== WP YouTube Lyte ===
Contributors: futtta
Tags: youtube, video, lyte, lite youtube embeds, html5 video, html5, widget, youtube audio, audio, playlist, youtube playlist, hd, performance, accessibility, sidebar, lazy load, responsive, microdata, videoobject
Requires at least: 2.9
Tested up to: 3.6
Stable tag: 1.2.2

High performance YouTube video, playlist and audio-only embeds which don't slow down your blog and offer optimal accessibility.

== Description ==

WP YouTube Lyte allows you to "lazy load" your video's, by inserting responsive "Lite YouTube Embeds". These look and feel like normal embedded YouTube, but only call the actual "fat" Flash or HTML5-player when clicked on, thereby [reducing download size & rendering time substantially](http://blog.futtta.be/2012/04/03/speed-matters-re-evaluating-wp-youtube-lytes-performance/) when embedding YouTube occasionally and improving page performance dramatically when you've got multiple YouTube video's on one and the same page.

Just add a YouTube-link for a video or [an entire playlist](http://blog.futtta.be/2011/10/11/wp-youtube-lyte-support-for-playlists-almost-included/) with "httpv" (or "httpa" to [embed YouTube's audio](http://blog.futtta.be/2011/04/19/audio-only-youtube-embedding-with-wp-youtube-lyte-0-7/) only) instead of "http" or add a Lyte widget to your sidebar and WP YouTube Lyte replaces that link with the correct performance-optimized code. When a visitor clicks the play-button, WP YouTube Lyte seamlessly initiates [YouTube's new embedded player](http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html). Some examples:

* httpv://www.youtube.com/watch?v=_SQkWbRublY (normal video embed)
* httpv://youtu.be/_SQkWbRublY (video embed with youtube-shortlink)
* httpa://www.youtube.com/watch?v=_SQkWbRublY (audio only embed)
* httpv://www.youtube.com/playlist?list=PLA486E741B25F8E00 (playlist embed)
* httpv://www.youtube.com/watch?v=_SQkWbRublY#stepSize=-1 (video player, one size smaller than what's configured as default)
* httpv://www.youtube.com/watch?v=_SQkWbRublY?start=20&showinfo=0 (video player, start playing at 20 seconds and don't show title)

Or using shortcodes:
`[lyte id="_SQkWbRublY" /]`
`[lyte id="_SQkWbRublY" audio="true" /]`
`[lyte id="A486E741B25F8E00" playlist="true" /]`

WP YouTube Lyte has been written with optimal performance as primary goal, but has been tested for maximum browser-compatibility (iPad included) while keeping an eye on accessibility. Starting with version 1.2.0 lyte embeds are fully responsive and can automatically embed [videoObject microdata](http://support.google.com/webmasters/bin/answer.py?hl=en&answer=2413309) as well. The plugin is fully multi-language, with support for Catalan, Dutch, English, French, German, Hebrew, Romanian, Spanish and Slovene.

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
* Your visitor has to be enrolled in the [YouTube html5 beta](http://www.youtube.com/html5) except if no Flash in present, in which case YouTube will attempt to fall-back to HTML5 as well

= Does WP YouTube Lyte protect my visitor's privacy? =
As opposed to some of the [most important](http://blog.futtta.be/2010/12/15/wordpress-com-stats-trojan-horse-for-quantcast-tracking/) [plugins](http://blog.futtta.be/2010/01/22/add-to-any-removed-from-here/) there is no 3rd party tracking code in WP YouTube Lyte, but YouTube off course does see visitor requests coming in (see also the youtube-nocookie.com remark in Bugs/Issues below).

If you want to stop YouTube from setting cookies, add the "&showinfo=0" parameter to your httpv-url. This will prevent the call to the Youtube API, which is used to fetch the title of the video, and stop YouTube-cookies from being set when the LYTE-player is loaded. This however does not work for playlists (the API-call is needed to be able to present something meaningful). You should also take into account that any user actually playing the video, will always receive YouTube-cookies ([as is the case with youtube-nocokie embeds as well](http://support.google.com/youtube/bin/answer.py?hl=en&answer=171780&expand=PrivacyEnhancedMode#privacy)).

= Can I use WP YouTube Lyte for a custom field? =
As tested and confirmed by [rumultik.ru's Dimitri](http://rumultik.ru) (thanks for that man!), this indeed does work. Just pass the httpv url of such a field to lyte_parse like this: 
`if(function_exists('lyte_parse')) { echo lyte_parse($video); }`
and you're good to go!

= Does WP YouTube Lyte work with Infinite Scroll? =
Starting from version 1.1.0 it does; in [Infinite Scroll](http://wordpress.org/extend/plugins/infinite-scroll/)'s configuration you just have to add "ly.te()" in the  "Javascript to be called after the next posts are fetched"-box.

= Can I still integrate with YouTube's JS API? =
This was added as a beta feature in version 1.1.0; add ?enablejsapi=1 to the httpv URL. WP YouTube Lyte will pick this up, and add ?enablejsapi=1&origin=<hostname-of-blog> to the URL of the iFrame with the iFrame id being set to iF_<youtube-video-id>. As soon as your visitors plays the video, you should be able to hook up with the video.

= How does WP YouTube Lyte support microdata? =
* There is a [specific microdata scheme for "videoObject"](http://support.google.com/webmasters/bin/answer.py?hl=en&answer=2413309) which WP YouTube Lyte can add to your page, which Google can use to display the video thumbnail next to the search-result
* This is optional and can be disabled in the options page
* The videoobject microdata is NOT added for audio-only embeds, playlists or widgets
* Google will not always display the thumbnail, this presumably depends of the relevance of the video to the rest of the page.

= Responsive LYTE embeds =
* The video width in posts and pages will adapt to the width of the container (the div) in which your blogposts/ pages are shown. This means that if your theme is responsive, WP YouTube Lyte will follow.
* Widgets are not responsive.
* if the content div width gets to around 200 pixels, the LYTE UI will become garbled (YouTube requires the minimum embed width to be 200px as well).

= Can I use WP YouTube Lyte on normal YouTube links? =
Sure, just add the following code-snippet in your theme's functions.php:

`
/** force wp youtube lyte on http://www.youtube.com url's as well */
add_filter('the_content', 'force_lyte_parse', 1);
function force_lyte_parse($content) {
     $content=str_replace('http://www.youtube.com/watch?v=','httpv://www.youtube.com/watch?v=',$content);
     return $content;
}
`

= Any bugs/ issues should I know about? =
* Although the widget is available in (very) small sizes, these do not display that great and might, in the near future, be disabled by YouTube as their Terms of Service state that the smallest available embedded player is 200X200 pixels. Use the deprecated smaller sizes at your own risk.
* Having the same YouTube-video on one page can cause WP YouTube Lyte to malfunction (as the YouTube id is used as the div's id in the DOM, and DOM id's are supposed to be unique)
* As youtube-nocookie.com does not serve the HTML5-player, WP YouTube Lyte uses the youtube.com domain (which provides less privacy), but as soon as youtube-nocookie.com serves HTML5-video, this will become the default domain for WP YouTube Lyte again.
* When using the Firefox plugin Karma Blocker, the [video isn't visible when clicking "play", with a warning message being shown instead](http://blog.futtta.be/?p=7584). This is expected behavior and should be solved by tweaking Karma Blocker's configuration.
* The translations have not been updated entirely for version 1.2.0, this will be included in 1.2.1. Help with translations is always welcome!

= I found a bug/ I would like a feature to be added! =
Just tell me, I like the feedback! Use the [Contact-page on my blog](http://blog.futtta.be/contact/), [leave a comment in a post about wp-youtube-lyte](http://blog.futtta.be/tag/wp-youtube-lyte/) or [create a new topic on the wordpress.org forum](http://wordpress.org/tags/wp-youtube-lyte?forum_id=10#postform).

= How you can help =
* Tell me about bugs you think you've found and if you can't find any, [confirm it works with your version of WP on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/)
* Ask me for a feature you would like to see added
* [Rate my plugin on wordpress.org](http://wordpress.org/extend/plugins/wp-youtube-lyte/), even if you think it stinks ;-)

== Changelog ==

= 1.2.2 =
* bugfix: apply sanitize_text_field to microdata description- and title-fields to escape e.g. quotes
* bugfix: added CSS resets to better avoid CSS-conflicts with themes (as reported by longtime user [FruityOaty](http://fruityoaty.com/))
* bugfix: fallback for missing wp_trim_words function in wordpress < 3.3 (as reported by [Armude](http://www.armudepictures.es/))
* bugfix: check if the data from cache/ youtube is valid before trying to extract info from it [as reported by Collin](http://blog.futtta.be/2013/03/01/the-best-wp-youtube-lyte-to-date/#li-comment-39222)
* improvement: better support for RSS/ ATOM feeds [as requested by drreen](http://wordpress.org/support/topic/textlinks-around-embedded-video-in-rss-feed)
* added item in FAQ on how to force normal YouTube links to be parsed by WP YouTube Lyte as well
* tested with WordPress 3.6 beta 1

= 1.2.1 =
* bugfix: if e.g. modernizr added "audio" as class to the html element, wp youtube lyte got confused. reported by [Peco of dubtechnoblog.com](http://www.dubtechnoblog.com/) and [Delphuk](http://delphuk.ru/)
* tested succesfully with WordPress 3.6 (development-version)

= 1.2.0 =
* LYTE embeds are now fully responsive
* automatic inclusion of [scheme.org microdata (VideoObject)](http://support.google.com/webmasters/bin/answer.py?hl=en&answer=2413309)
* even better performance (less requests; was 5, now 3)
* updated to current YouTube look & feel
* less JavaScript, more CSS
* bugfix: all lyte-output is now removed from excerpts

= 1.1.9 =
* privacy enhancement: load thumbnails from the cookie-less i.ytimg.com instead of from the cookie-riddled youtube.com (but there are some caveats, [see FAQ](http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/))
* bugfix: the fullscreen-button did not always appear when playing a video, added the "allowfullscreen" attribute to the iframe (as in the most recent youtube embed code)
* still [waiting for the big leap forwards (responsive LYTE and custom sizes) I'm afraid](http://blog.futtta.be/2012/10/16/wp-youtube-lyte-waiting-for-the-big-leap-forwards/)

= 1.1.8 =
* bugfix: [playlists API changed: playlists can now have ID's of more than 16 chars and always should be prepended with PL](http://apiblog.youtube.com/2012/08/playlists-now-with-more-pl.html), which broke playlist rendering. Hat tip for the guys over at [dubtechnoblog.com](http://www.dubtechnoblog.com) for reporting!
* bugfix: further work on excerpts to make sure http(v|a) links in manual excerpts get replaced by a lyte player.

= 1.1.7 =
* bugfix: 1.1.6 broke excerpts, as reported by Franz of [noobtech.at](http://www.noobtech.at)

= 1.1.6 =
* bugfix: remove noscript-stuff from the_excerpt [as requested by wordpressvoxbox13](http://wordpress.org/support/topic/plugin-wp-youtube-lyte-wp-youtube-lyte-adds-text-to-my-posts?replies=8#post-2977006)
* bugfix: switch HTTPS detection to is_ssl() WordPress function to avoid breakage on MS IIS
* removed the "DoNotTrack" bonus feature due to added complexity, [at least one bug](http://wordpress.org/support/topic/wp-youtube-lyte-conflicts-with-wysija-newsletter) and the fact that [WP DoNotTrack](http://wordpress.org/extend/plugins/wp-donottrack/) does a better job at this

= 1.1.5 =
* bugfix: added missing lyte/controls-400.png, which was needed for the new widget-size in 1.1.4
* bugfix: force margin for widget to 0 for themes that think they know better (as reported by long-time user [FruityOaty](http://fruityoaty.com/))
* bugfix: workaround for iOS quirk that caused the video to only start after 2nd click (as reported by Robert of [audio-times.com](http://audio-times.com) and [Collin Maessen](http://www.realsceptic.com/))
* bugfix: add sizes to images as [suggested by elmll on the wordpress.org forum](http://wordpress.org/support/topic/plugin-wp-youtube-lyte-specify-img-dimensions)
* beta: shortcode (see [Description](http://wordpress.org/extend/plugins/wp-youtube-lyte/) for examples) as [suggested by ben4d85 on the wordpress.org forum](http://wordpress.org/support/topic/plugin-wp-youtube-lyte-shortcode-custom-class)

= 1.1.4 =
* bugfix: audio-only youtube was broken due to changes at YouTube (thanks for reporting [Adrian](http://www.yeahlabs.ca/)!)
* bugfix: playlist embedding was somewhat broken (again due to changes at YouTube)
* bugfix: httpv- or httpa- links were only found if the v-parameter was at the beginning of the querystring (as discovered by [Mye](http://virtualassistanttipsthatmatters.com/))
* added 2 new widget sizes (250X200px and 400X333px)

= 1.1.3 =
* new: Player position can now be set to "left" (default) or "center".
* YouTube bugfix: playlist player should now [also work on iphone/ipad](http://apiblog.youtube.com/2012/03/new-player-options-for-lists-of-videos.html)
* YouTube TOS change: official [minimal embed size is now 200X200](http://apiblog.youtube.com/2012/03/minimum-embeds-200px-x-200px.html), created new widget size for that and marked others as deprecated
* bugfix: recent version of [AddThis caused problems with getElementsByClassName](http://support.addthis.com/customer/portal/questions/216309-getelementsbyclassname-error-appearing), which impacted WP YouTube Lyte as reported by [nocotytato](http://nocotytato.org.pl/).
* bugfix for mixed HTTP/HTTPS resources while in HTTPS admin (as reported for [WP DoNotTrack](http://wordpress.org/extend/plugins/wp-donottrack/) by Paul Martinus)
* tested and confirmed to work with WordPress 3.4 (beta 2).

= 1.1.2 =
* bugfix: during development of 1.1.0 the javascript to lazy load the lyte player got lost somehow, readded (now in lyte(-min).js)
* performance: replaced the external stylesheet (lyte.css) with inline javascript that adds an internal stylesheet to the head of the document (thanks for pushing me [Collin](http://www.realsceptic.com/)!

= 1.1.1 =
* bugfix: lyte css wasn't applied as class was already removed (regression caused by "Infinite Scroll" support), lyMe now is the name of the unprocessed div and becomes lyte after processing
* performance: [minimizing reflows caused by amongst others how styles are applied](http://blog.futtta.be/2012/03/20/going-against-the-reflow/) (kudo's to ["der Tuxman"](http://tuxproject.de/blog) again, buy that man a beer!)

= 1.1.0 =
* updated LYTE-player UI: larger display of preview image, changed look of title placeholder, updated controls image
* new: added support for "Infinite Scroll" as proposed by ["der Tuxman"](http://tuxproject.de/blog) and [Olivier](http://www.wwebsolution.com/hemisphere)
* new: added beta-support for YouTube's JS API as requested by Yun
* performance: remove double DOM-lookups in javascript, hat tip Yun
* annoyance-avoidance: check for "Karma Blocker" addon and warn, based on feedback from [Leona](http://www.tinuum.net/)
* translation: added Romanian, thanks to Alexander and [Web Hosting Geeks](http://webhostinggeeks.com/)
* bugfix: 2nd video with start or showinfo parameters inherited the ones from the 1st one as well as reported by Josh D
* bugfix: marked lyte img border css as !important
* bugfix: moved inline javascript (for each lyte-div) to the footer of the page to solve conflict with some jQuery plugins in MSIE as reported by Yun
* bugfix: set autohide to false for audio-only embeds

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
* The thumbnail is now stretched to use as much of the player as possible (thanks to css3's background-size:contain directive, which works in [all bleeding edge browsers](https://developer.mozilla.org/en/CSS/background-size#Browser_compatibility))
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
