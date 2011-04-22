<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/tag/lyte
Description: Lite and accessible YouTube audio and video embedding.
Author: Frank Goossens (futtta)
Version: 0.7.1
Author URI: http://blog.futtta.be/
*/

$plugin_dir = basename(dirname(__FILE__)).'/languages';
load_plugin_textdomain( 'wp-youtube-lyte', null, $plugin_dir );

$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));

require_once(dirname(__FILE__).'/options.php');
require_once(dirname(__FILE__).'/player_sizes.inc.php');
require_once(dirname(__FILE__).'/widget.php');

$lyteSettings[0]=$wp_lyte_plugin_url.'lyte/';

if (get_option('newTube')==="1") {
        $lyteSettings[1]="newtube-";
}

$oSize = (int) get_option('size');
if ((is_bool($oSize)) || ($pSize[$oSize]['a']===false)) { $sel = (int) $pDefault; } else { $sel= $oSize; }

$lyteSettings[2]=$pSize[$sel]['w'];
$lyteSettings[3]=$pSize[$sel]['h'];
$lyteSettings[4]=get_option('show_links');

function lyte_parse($the_content) {
	global $lyteSettings;
	if((strpos($the_content, "httpv")!==FALSE)||(strpos($the_content, "httpa")!==FALSE)) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);
	    
		preg_match_all("/http(a|v):\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 

		foreach($matches as $match) {
			if ($match[1]!=="a") {
 				$divHeight=$lyteSettings[3];
                                $audioClass="";
			} else {
				$audioClass=" audio";
				if ($lyteSettings[1]=="newtube-"){
					$divHeight=33;
				} else {
					$divHeight=25;
				}
			}

			switch ($lyteSettings[4]) {
				case "0":
					$noscript_post="<br />".__("Watch this video on YouTube","wp-youtube-lyte");
					$lytelinks_txt="";
					break;
        			case "2":
				        $noscript_post="";
					$lytelinks_txt="<div class=\"lL\">".__("Watch this video","wp-youtube-lyte")." <a href=\"http://youtu.be/".$match[4]."\">".__("on YouTube","wp-youtube-lyte")."</a> ".__("or on","wp-youtube-lyte")." <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$match[4]."\">Easy Youtube</a>.</div>";
					break;
				default:
                                        $noscript_post="";
                                        $lytelinks_txt="<div class=\"lL\">".__("Watch this video","wp-youtube-lyte")." <a href=\"http://youtu.be/".$match[4]."\">".__("on YouTube","wp-youtube-lyte")."</a>.</div>";
			}

			$lytetemplate = "<div class=\"lyte".$audioClass."\" id=\"".$match[4]."\" style=\"width:".$lyteSettings[2]."px;height:".$divHeight."px;\"><noscript><a href=\"http://youtu.be/".$match[4]."\"><img src=\"http://img.youtube.com/vi/".$match[4]."/0.jpg\" alt=\"\" width=\"".$lyteSettings[2]."\" height=\"".$divHeight."\" />".$noscript_post."</a></noscript><script type=\"text/javascript\"><!-- \n var nT='".$lyteSettings[1]."';var bU='".$lyteSettings[0]."';var d=document;if(d.addEventListener){d.addEventListener('DOMContentLoaded', insert, false)}else{window.onload=insert} function insert(){if(!d.getElementById('lytescr')){lytescr=d.createElement('script');lytescr.async=true;lytescr.id='lytescr';lytescr.src='".$lyteSettings[0]."lyte-min.js';h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(lytescr, h)}}; \n --></script></div>".$lytelinks_txt;
			$the_content = preg_replace("/<p>http(v|a):\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^\s<]*)<\/p>/", $lytetemplate, $the_content, 1);
		}
	}
    return $the_content;
}

add_filter('the_content', 'lyte_parse', 90);

/* donottrack */ 
$donottrack_js=$wp_lyte_plugin_url."external/donottrack-min.js";

function init_lyte_donottrack() {
	global $donottrack_js;
	wp_enqueue_script( 'donottrack',$donottrack_js );
	}

if (get_option('donottrack')==="1") {
	add_action('init', 'init_lyte_donottrack');
	}
?>
