<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/wp-youtube-lyte/
Description: Lite and accessible YouTube audio and video embedding.
Author: Frank Goossens (futtta)
Version: 1.1.5
Author URI: http://blog.futtta.be/
Text Domain: wp-youtube-lyte
Domain Path: /languages
*/

$wyl_version="1.1.5";
#$wyl_version=rand()/1000;

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
$lyteSettings['version']=$wyl_version;
$lyteSettings['position']=get_option('position','0');

function lyte_parse($the_content) {
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

		if ($_SERVER['HTTPS']) {
        		$scheme="https";
 		} else {
			$scheme="http";
		}

		preg_match_all("/http(a|v):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|.+?v\=|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=PL([a-zA-Z0-9\-\_]{16})))([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 


		foreach($matches as $match) {
			preg_match("/stepSize\=([\+\-0-9]{2})/",$match[12],$sMatch);
			preg_match("/showinfo\=([0-1]{1})/",$match[12],$showinfo);
			preg_match("/start\=([0-9]*)/",$match[12],$start);
			preg_match("/enablejsapi\=([0-1]{1})/",$match[12],$jsapi);

			$qsa="";
			if ($showinfo[0]) $qsa="&amp;".$showinfo[0];
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
			} else {
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
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;\">".__("Watch this playlist","wp-youtube-lyte")." <a href=\"".$scheme."://www.youtube.com/playlist?list=PL".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a></div>";
				}
			} else if ($match[9]!="") {
				$plClass="";
	                        $vid=$match[9];
				switch ($lyteSettings['links']) {
					case "0":
						$noscript_post="<br />".__("Watch this video on YouTube","wp-youtube-lyte");
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;\"></div>";
						break;
					case "2":
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a> ".__("or on","wp-youtube-lyte")." <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$vid."\">Easy Youtube</a>.</div>";
						break;
					default:
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\" style=\"width:".$lyteSettings[2]."px;\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a>.</div>";
					}

				$noscript="<noscript><a href=\"".$scheme."://youtu.be/".$vid."\"><img src=\"".$scheme."://img.youtube.com/vi/".$vid."/0.jpg\" alt=\"\" width=\"".$lyteSettings[2]."\" height=\"".$NSimgHeight."\" />".$noscript_post."</a> ".$NSbanner."</noscript>";
			}

			$lytetemplate = "<div class=\"lyMe".$audioClass.$hidefClass.$plClass.$qsaClass."\" id=\"WYL_".$vid."\" style=\"width:".$lyteSettings[2]."px;height:".$divHeight."px;overflow:hidden;\">".$noscript."</div>".$lytelinks_txt;
			$the_content = preg_replace("/(<p>)?http(v|a):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|.+?v\=|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=PL([a-zA-Z0-9\-\_]{16})))([^\s<]*)(<\/p>)?/", $lytetemplate, $the_content, 1);
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
	if ($lyteSettings['position']==="1") {
		$pos="auto";
		}
	echo "<script type=\"text/javascript\">var bU='".$lyteSettings['path']."';style = document.createElement('style');style.type = 'text/css';rules = document.createTextNode('.lyte img {border:0px !important;padding:0px;spacing:0px;margin:0px;display:inline;background-color:transparent;max-width:100%} .lL {margin:5px ".$pos.";} .lP {background-color:#fff;margin:5px ".$pos.";} .pL {cursor:pointer;text-align:center;overflow:hidden;position:relative;margin:0px !important;} .tC {left:0;top:0;position:absolute;width:100%;background-color:rgba(0,0,0,0.6);} .tT {padding:5px 10px;font-size:16px;color:#ffffff;font-family:sans-serif;text-align:left;} .ctrl {position:absolute;left:0px;bottom:0px;}');if(style.styleSheet) { style.styleSheet.cssText = rules.nodeValue;} else {style.appendChild(rules);}document.getElementsByTagName('head')[0].appendChild(style);</script>";
	echo "<script type=\"text/javascript\" async=true src=\"".$lyteSettings['path']."lyte-min.js?wylver=".$lyteSettings['version']."\"></script>";
}

/** YouTube shortcode */
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

if ( is_admin() ) {
        require_once(dirname(__FILE__).'/options.php');
} else {
	add_filter('the_content', 'lyte_parse', 4);
	add_filter('the_excerpt', 'lyte_parse', 4);
	add_shortcode("lyte", "shortcode_lyte");
}

/* donottrack */
$donottrack_js=$wp_lyte_plugin_url."external/donottrack-min.js";

if ($_SERVER['HTTPS']) {
	$donottrack_js = str_replace( "http:","https:",$donottrack_js );
	}

function lyte_donottrack_init() {
        global $donottrack_js;
        wp_enqueue_script( 'donottrack',$donottrack_js );
        }

function lyte_donottrack_config() {
        echo "<script type=\"text/javascript\">var dnt_config={ifdnt:\"\",mode:\"blacklist\",black:[\"media6degrees.com\",\"quantserve.com\",\"lockerz.com\"],white:[]};</script>\n";
}

function lyte_donottrack_footer() {
	echo "<script type=\"text/javascript\">aop_around(document.body, 'appendChild'); aop_around(document.body, 'insertBefore');</script>";
}

if (get_option('donottrack')==="1") {
	add_action('wp_print_scripts', 'lyte_donottrack_config');
        add_action('init', 'lyte_donottrack_init');
	add_action('wp_footer', 'lyte_donottrack_footer');
        }
?>
