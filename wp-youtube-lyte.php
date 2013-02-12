<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/wp-youtube-lyte/
Description: Lite and accessible YouTube audio and video embedding.
Author: Frank Goossens (futtta)
Version: 1.2.0
Author URI: http://blog.futtta.be/
Text Domain: wp-youtube-lyte
Domain Path: /languages
*/

$debug=true;

if (!$debug) {
	$wyl_version="1.2.0";
	$wyl_file="lyte-min.js";
} else {
	$wyl_version=rand()/1000;
	$wyl_file="lyte.js";
} 

$plugin_dir = basename(dirname(__FILE__)).'/languages';
load_plugin_textdomain( 'wp-youtube-lyte', null, $plugin_dir );

$wp_lyte_plugin_url = trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/';

require_once(dirname(__FILE__).'/player_sizes.inc.php');
require_once(dirname(__FILE__).'/widget.php');

$oSize = (int) get_option('size');
if ((is_bool($oSize)) || ($pSize[$oSize]['a']===false)) { $sel = (int) $pDefault; } else { $sel=$oSize; }

$pSizeFormat=$pSize[$sel]['f'];
$j=0;
foreach ($pSizeOrder[$pSizeFormat] as $sizeId) {
	$sArray[$j]['w']=(int) $pSize[$sizeId]['w'];
	$sArray[$j]['h']=(int) $pSize[$sizeId]['h'];
	if ($sizeId===$sel) $selSize=$j;
	$j++;
}

$lyteSettings['sizeArray']=$sArray;
$lyteSettings['selSize']=$selSize;
$lyteSettings['path']=$wp_lyte_plugin_url.'lyte/';
$lyteSettings['links']=get_option('show_links');
$lyteSettings['file']=$wyl_file."?wyl_version=".$wyl_version;
$lyteSettings['ratioClass']= ( $pSizeFormat==="43" ) ? " fourthree" : "";
$lyteSettings['pos']= ( get_option('position','0')==="1" ) ? $pos="margin:5px auto;" : $pos="margin:5px;";

function lyte_parse($the_content,$doExcerpt=false) {
	global $lyteSettings;

	$urlArr=parse_url($lyteSettings['path']);
	$origin=$urlArr[scheme]."://".$urlArr[host]."/";

	if((strpos($the_content, "httpv")!==FALSE)||(strpos($the_content, "httpa")!==FALSE)) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);

		if (get_option('hidef')==="1") {
       			$hidefClass=" hidef";
		} else {
			$hidefClass="";
		}

		if ( is_ssl() ) {
        		$scheme="https";
 		} else {
			$scheme="http";
		}

		$postID = get_the_ID();

		$lytes_regexp="/(?:<p>)?http(v|a):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|.+?v\=|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=(PL[a-zA-Z0-9\-\_]*)))([^\s<]*)(<?:\/p>)?/";

		preg_match_all($lytes_regexp, $the_content, $matches, PREG_SET_ORDER); 

		foreach($matches as $match) {
			preg_match("/stepSize\=([\+\-0-9]{2})/",$match[12],$sMatch);
			preg_match("/showinfo\=([0-1]{1})/",$match[12],$showinfo);
			preg_match("/start\=([0-9]*)/",$match[12],$start);
			preg_match("/enablejsapi\=([0-1]{1})/",$match[12],$jsapi);

			$qsa="";
			if ($showinfo[0]) {
				$qsa="&amp;".$showinfo[0];
				$titleClass=" hidden";
			} else {
				$titleClass="";
			}
			if ($start[0]) $qsa.="&amp;".$start[0];
			if ($jsapi[0]) $qsa.="&amp;".$jsapi[0]."&amp;origin=".$origin;

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
				$audioClass=" audio";
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
						$noscript="<noscript><a href=\"".$scheme."://youtube.com/playlist?list=PL".$vid."\">".$noscript_post."</a> ".$NSbanner."</noscript>";
						$lytelinks_txt="";
                                                break;
                                        default:
						$noscript="<noscript>".$NSbanner."</noscript>";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this playlist","wp-youtube-lyte")." <a href=\"".$scheme."://www.youtube.com/playlist?list=PL".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a></div>";
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
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a> ".__("or on","wp-youtube-lyte")." <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$vid."\">Easy Youtube</a>.</div>";
						break;
					default:
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;".$lyteSettings['pos']."\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a>.</div>";
					}

				$noscript="<noscript><a href=\"".$scheme."://youtu.be/".$vid."\"><img src=\"".$scheme."://i.ytimg.com/vi/".$vid."/0.jpg\" alt=\"\" width=\"".$lyteSettings[2]."\" height=\"".$NSimgHeight."\" />".$noscript_post."</a> ".$NSbanner."</noscript>";
			}

			if ($doExcerpt) {$noscript="";}

			// get, set and cache info from youtube
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
					$yt_api_target = "playlists/".$vid."?v=2&alt=json&fields=id,title,entry";
				} else {
					$yt_api_target = "videos/".$vid."?fields=id,title&alt=json";
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

                        // If there was a result from youtube or from cache, use it
                        if ( $yt_resp ) {
				$yt_resp_array=json_decode($yt_resp,true);
				if ($plClass===" playlist") {
					$yt_title="Playlist: ".$yt_resp_array['feed']['title']['$t'];
					$thumbUrl=$yt_resp_array['feed']['entry'][0]['media$group']['media$thumbnail'][2]['url'];
				} else {
					$yt_title=$yt_resp_array['entry']['title']['$t'];
					$thumbUrl=$scheme."://i.ytimg.com/vi/".$vid."/0.jpg";
				}
			}
		
			if ($audio===true) {
				$wrapper="<div class=\"lyte-wrapper-audio\" style=\"width:".$lyteSettings[2]."px;max-width:100%;overflow:hidden;height:38px;".$lyteSettings['pos']."\">";
			} else {
				$wrapper="<div class=\"lyte-wrapper".$lyteSettings['ratioClass']."\" style=\"width:".$lyteSettings[2]."px;max-width: 100%;".$lyteSettings['pos']."\">";
			}

			$lytetemplate = $wrapper."<div class=\"lyMe".$audioClass.$hidefClass.$plClass.$qsaClass."\" id=\"WYL_".$vid."\" itemprop=\"video\" itemscope itemtype=\"http://schema.org/VideoObject\"><meta itemprop=\"duration\" content=\"T1M33S\" /><meta itemprop=\"thumbnailUrl\" content=".$thumbUrl." /><meta itemprop=\"embedURL\" content=\"http://www.example.com/videoplayer.swf?video=123\" /><meta itemprop=\"uploadDate\" content=\"2011-07-05T08:00:00+08:00\" /><div id=\"lyte_".$vid."\" data-src=\"".$thumbUrl."\" class=\"pL\"><div class=\"tC".$titleClass."\"><div class=\"tT\" itemprop=\"name\">".$yt_title."</div></div><div class=\"play\"></div><div class=\"ctrl\"><div class=\"Lctrl\"></div><div class=\"Rctrl\"></div></div></div>".$noscript."<span itemprop=\"description\">Video description</span></div></div>".$lytelinks_txt;
			$the_content = preg_replace($lytes_regexp, $lytetemplate, $the_content, 1);
                }

		// update lyte_cache_index
		if (is_array($toCache_index)) {
			// would we want to gzcompress & base64_encode this?
			$lyte_cache=json_decode(get_option('lyte_cache_index'),true);
                        $lyte_cache[$postID]=$toCache_index;
                        update_option('lyte_cache_index',json_encode($lyte_cache));
		}
		lyte_initer();
	}
        return $the_content;
}

function lyte_initer() {
	if (!$lynited) {
		$lynited=true;
		add_action('wp_footer', 'lyte_init');
	}
}

function lyte_init() {
	global $lyteSettings;
	echo "<script type=\"text/javascript\">var bU='".$lyteSettings['path']."';style = document.createElement('style');style.type = 'text/css';rules = document.createTextNode(\".lyte,.lyMe{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;background-color:#777;} .fourthree .lyMe, .fourthree .lyte {padding-bottom:75%;} .widget .lyte, .widget .lyMe {padding-bottom:100%} .lyte-wrapper-audio .lyte{height:38px!important;overflow:hidden;padding:0!important} .lyte iframe,.lyte .pL{position:absolute;top:0;left:0;width:100%;height:100%;background:no-repeat scroll center #888;background-size:cover;cursor:pointer} .tC{background-color:rgba(0,0,0,0.5);left:0;position:absolute;top:0;width:100%} .tT{color:#FFF;font-family:sans-serif;font-size:16px;height:auto;text-align:left;padding:5px 10px} .tT:hover{text-decoration:underline} .play{background:no-repeat scroll 0 0 transparent;width:90px;height:62px;position:absolute;left:43%;left:calc(50% - 45px);left:-webkit-calc(50% - 45px);top:38%;top:calc(50% - 31px);top:-webkit-calc(50% - 31px);} .widget .play {top:30%;top:calc(45% - 31px);top:-webkit-calc(45% - 31px);transform:scale(0.6);-webkit-transform:scale(0.6);-ms-transform:scale(0.6);} .lyte:hover .play{background-position:0 -65px} .audio .pL{max-height:38px!important} .audio iframe{height:438px!important} .ctrl{background:repeat scroll 0 -215px transparent;width:100%;height:40px;bottom:0;left:0;position:absolute} .Lctrl{background:no-repeat scroll 0 -132px transparent;width:158px;height:40px;bottom:0;left:0;position:absolute} .Rctrl{background:no-repeat scroll -42px -174px transparent;width:117px;height:40px;bottom:0;right:0;position:absolute} .audio .play,.audio .tC{display:none} .hidden{display:none}\" );if(style.styleSheet) { style.styleSheet.cssText = rules.nodeValue;} else {style.appendChild(rules);}document.getElementsByTagName('head')[0].appendChild(style);</script>";
	echo "<script type=\"text/javascript\" async=true src=\"".$lyteSettings['path'].$lyteSettings['file']."\"></script>";
}

function lyte_parse_excerpt($excerpt){
	$excerpt=lyte_parse($excerpt,$doExcerpt=true);
	return $excerpt;
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

function yt_func($atts, $content="") {
     return lyte_parse("httpv://www.youtube.com/watch?v=".$content);
     }
add_shortcode('youtube', 'yt_func');

if ( is_admin() ) {
        require_once(dirname(__FILE__).'/options.php');
} else {
	add_filter('the_content', 'lyte_parse', 4);
	add_filter('the_excerpt', 'lyte_parse_excerpt', 4);
	add_shortcode("lyte", "shortcode_lyte");
}
?>
