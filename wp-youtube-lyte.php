<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/wp-youtube-lyte/
Description: Lite and accessible YouTube audio and video embedding.
Author: Frank Goossens (futtta)
Version: 1.0.0
Author URI: http://blog.futtta.be/
Text Domain: wp-youtube-lyte
Domain Path: /languages
*/

$wyl_version="1.0.0";

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

function lyte_parse($the_content) {
	global $lyteSettings;

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

		preg_match_all("/http(a|v):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=PL([a-zA-Z0-9\-\_]{16})))([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 

		foreach($matches as $match) {
			preg_match("/stepSize\=([\+\-0-9]{2})/",$match[12],$sMatch);
			preg_match("/showinfo\=([0-1]{1})/",$match[12],$showinfo);
			preg_match("/start\=([0-9]*)/",$match[12],$start);

			if ($showinfo[0]) $qsa="&amp;".$showinfo[0];
			if ($start[0]) $qsa.="&amp;".$start[0];

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
				$divHeight=34;
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
						$lytelinks_txt="<div class=\"lL\">".__("Watch this playlist","wp-youtube-lyte")." <a href=\"".$scheme."://www.youtube.com/playlist?list=PL".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a></div>";
				}
			} else if ($match[9]!="") {
				$plClass="";
	                        $vid=$match[9];
				switch ($lyteSettings['links']) {
					case "0":
						$noscript_post="<br />".__("Watch this video on YouTube","wp-youtube-lyte");
						$lytelinks_txt="<div class=\"lL\"></div>";
						break;
					case "2":
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a> ".__("or on","wp-youtube-lyte")." <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$vid."\">Easy Youtube</a>.</div>";
						break;
					default:
						$noscript_post="";
						$lytelinks_txt="<div class=\"lL\">".__("Watch this video","wp-youtube-lyte")." <a href=\"".$scheme."://youtu.be/".$vid."\">".__("on YouTube","wp-youtube-lyte")."</a>.</div>";
					}

				$noscript="<noscript><a href=\"".$scheme."://youtu.be/".$vid."\"><img src=\"".$scheme."://img.youtube.com/vi/".$vid."/0.jpg\" alt=\"\" width=\"".$lyteSettings[2]."\" height=\"".$NSimgHeight."\" />".$noscript_post."</a> ".$NSbanner."</noscript>";
			}

			if (!empty($qsa)) {
				$qsa_init="w.lst=w.lst||{};w.lst[\"".$vid."\"]=\"".$qsa."\"";
				} else {
				$qsa_init="";
				}

			$lytetemplate = "<div class=\"lyte".$audioClass.$hidefClass.$plClass."\" id=\"WYL_".$vid."\" style=\"width:".$lyteSettings[2]."px;height:".$divHeight."px;\">".$noscript."<script type=\"text/javascript\"><!-- \n (function(){var d=document;var w=window;if(w.addEventListener){w.addEventListener('load', insert, false)}else{w.onload=insert};setTimeout(insert, 1000);function insert(){if(!d.getElementById('lytescr')){lytescr=d.createElement('script');lytescr.async=true;lytescr.id='lytescr';lytescr.src='".$lyteSettings['path']."lyte-min.js?wylver=".$lyteSettings['version']."';h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(lytescr, h)}};".$qsa_init."}()) \n --></script></div>".$lytelinks_txt;
			$the_content = preg_replace("/(<p>)?http(v|a):\/\/([a-zA-Z0-9\-\_]+\.|)(youtube|youtu)(\.com|\.be)\/(((watch(\?v\=|\/v\/)|)([a-zA-Z0-9\-\_]{11}))|(playlist\?list\=PL([a-zA-Z0-9\-\_]{16})))([^\s<]*)(<\/p>)?/", $lytetemplate, $the_content, 1);
                }
        }
        return $the_content;
}

if ( is_admin() ) {
        require_once(dirname(__FILE__).'/options.php');
} else {
	add_filter('the_content', 'lyte_parse', 4);
	add_filter('the_excerpt', 'lyte_parse', 4);
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
