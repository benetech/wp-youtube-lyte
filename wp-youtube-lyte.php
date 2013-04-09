<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/wp-youtube-lyte/
Description: Lite and accessible YouTube audio and video embedding.
Author: Frank Goossens (futtta)
Version: 1.2.2
Author URI: http://blog.futtta.be/
Text Domain: wp-youtube-lyte
Domain Path: /languages
*/

$debug=false;
$lyte_version="1.2.2";
$lyte_db_version=get_option('lyte_version','none');

/** have we updated? */
if ($lyte_db_version !== $lyte_version) {
	switch($lyte_db_version) {
		case "none":
			lyte_options_update();
	}
	update_option('lyte_version',$lyte_version);
	$lyte_db_version=$lyte_version;
}

/** are we in debug-mode */
if (!$debug) {
	$wyl_version=$lyte_version;
	$wyl_file="lyte-min.js";
} else {
	$wyl_version=rand()/1000;
	$wyl_file="lyte.js";
	lyte_rm_cache();
}

/** get paths, language and includes */
$plugin_dir = basename(dirname(__FILE__)).'/languages';
load_plugin_textdomain( 'wp-youtube-lyte', null, $plugin_dir );
$wp_lyte_plugin_url = trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/';
require_once(dirname(__FILE__).'/player_sizes.inc.php');
require_once(dirname(__FILE__).'/widget.php');

/** get default embed size and build array to change size later if requested */
$oSize = (int) get_option('lyte_size');
if ((is_bool($oSize)) || ($pSize[$oSize]['a']===false)) { $sel = (int) $pDefault; } else { $sel=$oSize; }

$pSizeFormat=$pSize[$sel]['f'];
$j=0;
foreach ($pSizeOrder[$pSizeFormat] as $sizeId) {
	$sArray[$j]['w']=(int) $pSize[$sizeId]['w'];
	$sArray[$j]['h']=(int) $pSize[$sizeId]['h'];
	if ($sizeId===$sel) $selSize=$j;
	$j++;
}

/** get other options and push in array*/
$lyteSettings['sizeArray']=$sArray;
$lyteSettings['selSize']=$selSize;
$lyteSettings['path']=$wp_lyte_plugin_url.'lyte/';
$lyteSettings['links']=get_option('lyte_show_links');
$lyteSettings['file']=$wyl_file."?wyl_version=".$wyl_version;
$lyteSettings['ratioClass']= ( $pSizeFormat==="43" ) ? " fourthree" : "";
$lyteSettings['pos']= ( get_option('lyte_position','0')==="1" ) ? "margin:5px auto;" : "margin:5px;";
$lyteSettings['microdata']=get_option('lyte_microdata','1');
$lyteSettings['hidef']=get_option('lyte_hidef',0);
$lyteSettings['scheme'] = ( is_ssl() ) ? "https" : "http";

/* main function to parse the content, searching and replacing httpv-links */
function lyte_parse($the_content,$doExcerpt=false) {
	global $lyteSettings;

	$urlArr=parse_url($lyteSettings['path']);
	$origin=$urlArr['scheme']."://".$urlArr['host']."/";

	if((strpos($the_content, "httpv")!==FALSE)||(strpos($the_content, "httpa")!==FALSE)) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);
		$lyte_feed=is_feed();
		
		$hidefClass = ($lyteSettings['hidef']==="1") ? " hidef" : "";

		$postID = get_the_ID();
		$toCache_index=array();

		$lytes_regexp="/(?:<p>)?http(v|a):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|.+?v\=|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=(PL[a-zA-Z0-9\-\_]*)))([^\s<]*)(<?:\/p>)?/";

		preg_match_all($lytes_regexp, $the_content, $matches, PREG_SET_ORDER); 

		foreach($matches as $match) {
			preg_match("/stepSize\=([\+\-0-9]{2})/",$match[12],$sMatch);
			preg_match("/showinfo\=([0-1]{1})/",$match[12],$showinfo);
			preg_match("/start\=([0-9]*)/",$match[12],$start);
			preg_match("/enablejsapi\=([0-1]{1})/",$match[12],$jsapi);

			$qsa="";
			if (!empty($showinfo[0])) {
				$qsa="&amp;".$showinfo[0];
				$titleClass=" hidden";
			} else {
				$titleClass="";
			}
			if (!empty($start[0])) $qsa.="&amp;".$start[0];
			if (!empty($jsapi[0])) $qsa.="&amp;".$jsapi[0]."&amp;origin=".$origin;

			if (!empty($qsa)) {
				$esc_arr=array("&" => "\&", "?" => "\?", "=" => "\=");
				$qsaClass=" qsa_".strtr($qsa,$esc_arr);
			} else {
				$qsaClass="";
			}

			if (!empty($sMatch)) {
				$newSize=(int) $sMatch[1];
				$newPos=(int) $lyteSettings['selSize']+$newSize;
				if ($newPos<0) {
					$newPos=0;
				} else if ($newPos > count($lyteSettings['sizeArray'])-1) {
					$newPos=count($lyteSettings['sizeArray'])-1;
				}
				$lyteSettings[2]=$lyteSettings['sizeArray'][$newPos]['w'];
				$lyteSettings[3]=$lyteSettings['sizeArray'][$newPos]['h'];
			} else {
				$lyteSettings[2]=$lyteSettings['sizeArray'][$lyteSettings['selSize']]['w'];
				$lyteSettings[3]=$lyteSettings['sizeArray'][$lyteSettings['selSize']]['h'];
			}

			if ($match[1]!=="a") {
 				$divHeight=$lyteSettings[3];
                                $audioClass="";
				$audio=false;
			} else {
				$audio=true;
				$audioClass=" lyte-audio";
				$divHeight=38;
			}

			$NSimgHeight=$divHeight-20;
			$NSbanner="Embedded with WP YouTube Lyte.";

	                if ($match[11]!="") {
        	                $plClass=" playlist";
                	        $vid=$match[11];
				switch ($lyteSettings['links']) {
                                	case "0":
                                        	$noscript_post="<br />".__("Watch this playlist on YouTube","wp-youtube-lyte");
						$noscript="<noscript><a href=\"".$lyteSettings['scheme']."://youtube.com/playlist?list=PL".$vid."\">".$noscript_post."</a> ".$NSbanner."</noscript>";
						$lytelinks_txt="";
                                                break;
                                        default:
						$noscript="<noscript>".$NSbanner."</noscript>";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this playlist","wp-youtube-lyte")." <a href=\"".$lyteSettings['scheme']."://www.youtube.com/playlist?list=PL".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a></div>";
				}
			} else if ($match[9]!="") {
				$plClass="";
	                        $vid=$match[9];
				switch ($lyteSettings['links']) {
					case "0":
						$noscript_post="<br />".__("Watch this video on YouTube","wp-youtube-lyte");
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\"></div>";
						break;
					case "2":
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$lyteSettings['scheme']."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a> ".__("or on","wp-youtube-lyte")." <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$vid."\">Easy Youtube</a>.</div>";
						break;
					default:
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$lyteSettings['scheme']."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a>.</div>";
					}

				$noscript="<noscript><a href=\"".$lyteSettings['scheme']."://youtu.be/".$vid."\"><img src=\"".$lyteSettings['scheme']."://i.ytimg.com/vi/".$vid."/0.jpg\" alt=\"\" width=\"".$lyteSettings[2]."\" height=\"".$NSimgHeight."\" />".$noscript_post."</a> ".$NSbanner."</noscript>";
			}

			/** logic to get video info from cache or get it from YouTube and set it */
	                if ( $postID ) {
	                        // Check for a cached result (stored in the post meta)
       		                $cachekey = '_lyte_' . $vid;
                                $yt_resp = get_post_meta( $postID, $cachekey, true );
				if (!empty($yt_resp)) {
					$yt_resp = gzuncompress(base64_decode($yt_resp));
					}
			} else {
				$yt_resp = "";
			}

                        if ( empty( $yt_resp ) ) {
                       		// get info from youtube 
				$yt_api_base = "http://gdata.youtube.com/feeds/api/";
				
				if ($plClass===" playlist") {
					$yt_api_target = "playlists/".$vid."?v=2&alt=json&fields=id,title,author,updated,media:group(media:thumbnail)";
				} else {
					$yt_api_target = "videos/".$vid."?v=2&alt=json&fields=id,title,published,content,media:group(media:description,yt:duration,yt:aspectRatio),author(name)";
				}

				$yt_api_url = $yt_api_base.$yt_api_target;
				$yt_resp = wp_remote_get($yt_api_url);

				// check if we got through
				if (is_wp_error($yt_resp)) {
					$yt_resp="";
				} else {
					$yt_resp = wp_remote_retrieve_body($yt_resp);

					if ( $postID ) {
                       				// we can cache the result

						// first add timestamp
						$yt_resp_array=json_decode($yt_resp,true);
						if(is_array($yt_resp_array)) {
							$yt_resp_array['lyte_date_added']=time();
							$yt_resp_precache=json_encode($yt_resp_array);

							// then gzip + base64 (to limit amount of data + solve problems with wordpress removing slashes)
							$yt_resp_precache=base64_encode(gzcompress($yt_resp_precache));

							// and do the actual caching
							$toCache = ( $yt_resp_precache ) ? $yt_resp_precache : '{{unknown}}';
                       					update_post_meta( $postID, $cachekey, $toCache );

							// and finally add new cache-entry to toCache_index which will be added to lyte_cache_index pref
							$toCache_index[]=$cachekey;
						}
					}
				}
			}

                        // If there was a result from youtube or from cache, use it
                        if ( $yt_resp ) {
				$yt_resp_array=json_decode($yt_resp,true);
				if (is_array($yt_resp_array)) {
				  if ($plClass===" playlist") {
					$yt_title="Playlist: ".esc_attr(sanitize_text_field($yt_resp_array['feed']['title']['$t']));
					$thumbUrl=esc_url($yt_resp_array['feed']['media$group']['media$thumbnail'][2]['url']);
					$dateField=sanitize_text_field($yt_resp_array['feed']['updated']['$t']);
					$duration="";
					$description=$yt_title;
				  } else {
					$yt_title=esc_attr(sanitize_text_field($yt_resp_array['entry']['title']['$t']));
					$thumbUrl=esc_url($lyteSettings['scheme']."://i.ytimg.com/vi/".$vid."/0.jpg");
					$dateField=sanitize_text_field($yt_resp_array['entry']['published']['$t']);
					$duration="T".sanitize_text_field($yt_resp_array['entry']['media$group']['yt$duration']['seconds'])."S";
					$description=esc_attr(sanitize_text_field($yt_resp_array['entry']['media$group']['media$description']['$t']));
				  }
				}
			}
		
			if ($audio===true) {
				$wrapper="<div class=\"lyte-wrapper-audio\" style=\"width:".$lyteSettings[2]."px;max-width:100%;overflow:hidden;height:38px;".$lyteSettings['pos']."\">";
			} else {
				$wrapper="<div class=\"lyte-wrapper".$lyteSettings['ratioClass']."\" style=\"width:".$lyteSettings[2]."px;max-width: 100%;".$lyteSettings['pos']."\">";
			}

			if ($doExcerpt) {
				$lytetemplate="";
			} elseif ($lyte_feed) {
				$postURL = get_permalink( $postID ); 
				$textLink = ($lyteSettings['links']===0)? "" : "<br />".strip_tags($lytelinks_txt, '<a>')."<br />";
				$lytetemplate = "<a href=\"".$postURL."\"><img src=\"".$lyteSettings['scheme']."://i.ytimg.com/vi/".$vid."/0.jpg\" alt=\"YouTube Video\"></a>".$textLink;
			} elseif (($audio !== true) && ( $plClass !== " playlist") && ($lyteSettings['microdata'] === "1")) {
				$lytetemplate = $wrapper."<div class=\"lyMe".$audioClass.$hidefClass.$plClass.$qsaClass."\" id=\"WYL_".$vid."\" itemprop=\"video\" itemscope itemtype=\"http://schema.org/VideoObject\"><meta itemprop=\"duration\" content=\"".$duration."\" /><meta itemprop=\"thumbnailUrl\" content=\"".$thumbUrl."\" /><meta itemprop=\"embedURL\" content=\"http://www.youtube.com/embed/".$vid."\" /><meta itemprop=\"uploadDate\" content=\"".$dateField."\" /><div id=\"lyte_".$vid."\" data-src=\"".$thumbUrl."\" class=\"pL\"><div class=\"tC".$titleClass."\"><div class=\"tT\" itemprop=\"name\">".$yt_title."</div></div><div class=\"play\"></div><div class=\"ctrl\"><div class=\"Lctrl\"></div><div class=\"Rctrl\"></div></div></div>".$noscript."<meta itemprop=\"description\" content=\"".$description."\"></div></div>".$lytelinks_txt;
			} else {
				$lytetemplate = $wrapper."<div class=\"lyMe".$audioClass.$hidefClass.$plClass.$qsaClass."\" id=\"WYL_".$vid."\"><div id=\"lyte_".$vid."\" data-src=\"".$thumbUrl."\" class=\"pL\"><div class=\"tC".$titleClass."\"><div class=\"tT\">".$yt_title."</div></div><div class=\"play\"></div><div class=\"ctrl\"><div class=\"Lctrl\"></div><div class=\"Rctrl\"></div></div></div>".$noscript."</div></div>".$lytelinks_txt;
			}
			$the_content = preg_replace($lytes_regexp, $lytetemplate, $the_content, 1);
        }

		// update lyte_cache_index
		if ((is_array($toCache_index))&&(!empty($toCache_index))) {
			$lyte_cache=json_decode(get_option('lyte_cache_index'),true);
            		$lyte_cache[$postID]=$toCache_index;
            		update_option('lyte_cache_index',json_encode($lyte_cache));
		}

		if (!$lyte_feed) {
			lyte_initer();
		}
	}
return $the_content;
}

/* only add js/css once and only if needed */
function lyte_initer() {
	global $lynited;
	if (!$lynited) {
		$lynited=true;
		add_action('wp_footer', 'lyte_init');
	}
}

/* actual initialization */
function lyte_init() {
	global $lyteSettings;
	echo "<script type=\"text/javascript\">var bU='".$lyteSettings['path']."';style = document.createElement('style');style.type = 'text/css';rules = document.createTextNode(\".lyte-wrapper-audio div, .lyte-wrapper div {margin:0px !important; overflow:hidden;} .lyte,.lyMe{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;background-color:#777;} .fourthree .lyMe, .fourthree .lyte {padding-bottom:75%;} .lidget{margin-bottom:5px;} .lidget .lyte, .widget .lyMe {padding-bottom:0!important;height:100%!important;} .lyte-wrapper-audio .lyte{height:38px!important;overflow:hidden;padding:0!important} .lyte iframe,.lyte .pL{position:absolute;top:0;left:0;width:100%;height:100%;background:no-repeat scroll center #000;background-size:cover;cursor:pointer} .tC{background-color:rgba(0,0,0,0.5);left:0;position:absolute;top:0;width:100%} .tT{color:#FFF;font-family:sans-serif;font-size:12px;height:auto;text-align:left;padding:5px 10px} .tT:hover{text-decoration:underline} .play{background:no-repeat scroll 0 0 transparent;width:90px;height:62px;position:absolute;left:43%;left:calc(50% - 45px);left:-webkit-calc(50% - 45px);top:38%;top:calc(50% - 31px);top:-webkit-calc(50% - 31px);} .widget .play {top:30%;top:calc(45% - 31px);top:-webkit-calc(45% - 31px);transform:scale(0.6);-webkit-transform:scale(0.6);-ms-transform:scale(0.6);} .lyte:hover .play{background-position:0 -65px} .lyte-audio .pL{max-height:38px!important} .lyte-audio iframe{height:438px!important} .ctrl{background:repeat scroll 0 -215px transparent;width:100%;height:40px;bottom:0;left:0;position:absolute} .Lctrl{background:no-repeat scroll 0 -132px transparent;width:158px;height:40px;bottom:0;left:0;position:absolute} .Rctrl{background:no-repeat scroll -42px -174px transparent;width:117px;height:40px;bottom:0;right:0;position:absolute} .lyte-audio .play,.lyte-audio .tC{display:none} .hidden{display:none}\" );if(style.styleSheet) { style.styleSheet.cssText = rules.nodeValue;} else {style.appendChild(rules);}document.getElementsByTagName('head')[0].appendChild(style);</script>";
	echo "<script type=\"text/javascript\" async=true src=\"".$lyteSettings['path'].$lyteSettings['file']."\"></script>";
}

/** override default wp_trim_excerpt to have lyte_parse remove the httpv-links */
function lyte_trim_excerpt($text) {
	global $post;
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = lyte_parse($text, true);
                $text = strip_shortcodes( $text );
                $text = apply_filters('the_content', $text);
                $text = str_replace(']]>', ']]&gt;', $text);
                $excerpt_length = apply_filters('excerpt_length', 55);
                $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
		if (function_exists('wp_trim_words')) {
               		$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
		} else {
			$length = $excerpt_length*6;
			$text = substr( strip_tags(trim(preg_replace('/\s+/', ' ', $text))), 0, $length );
			$text .= $excerpt_more;
		}
        }
        return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}

/** Lyte shortcode */
function shortcode_lyte($atts) {
        extract(shortcode_atts(array(
                "id"    => '',
                "audio" => '',
		"playlist" => '',
        ), $atts));
        
	if ($audio) {$proto="httpa";} else {$proto="httpv";}
	if ($playlist) {$action="playlist?list=PL";} else {$action="watch?v=";}

        return lyte_parse($proto.'://www.youtube.com/'.$action.$id);
    }

/** update functions */
/** upgrade function for 1.1.x to 1.2.x */
function lyte_options_update() {
	if (get_option('size')!==false) {
        	foreach (array('size','show_links','position','hidef','notification') as $oldOptionName) {
                	$oldOptionValue=get_option($oldOptionName);
                	$newOptionName="lyte_".$oldOptionName;
                	update_option($newOptionName,$oldOptionValue);
                	delete_option($oldOptionName);
                }
        }
}

/** function to clean YT responses from cache */
function lyte_rm_cache() {
	$lyte_posts=json_decode(get_option('lyte_cache_index'),true);
	if (is_array($lyte_posts)){
		foreach ($lyte_posts as $postID => $lyte_post) {
			foreach ($lyte_post as $cachekey) {
				delete_post_meta($postID, $cachekey);
			}
		}
		delete_option('lyte_cache_index');
	}
}

/** hooking it all up to wordpress */
if ( is_admin() ) {
        require_once(dirname(__FILE__).'/options.php');
} else {
	add_filter('the_content', 'lyte_parse', 4);
	add_shortcode("lyte", "shortcode_lyte");
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	add_filter('get_the_excerpt', 'lyte_trim_excerpt');
}
?>
