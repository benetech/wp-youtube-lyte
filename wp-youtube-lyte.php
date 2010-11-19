<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/tag/lyte
Description: WordPress Lite YouTube Embeds (with optional HTML5 video) in posts.
Author: Frank Goossens (futtta)
Version: 0.6.2
Author URI: http://blog.futtta.be/
*/

require_once(dirname(__FILE__).'/options.php');
require_once(dirname(__FILE__).'/player_sizes.inc.php');
require_once(dirname(__FILE__).'/widget.php');

$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 

$lyteSettings[0]=$wp_lyte_plugin_url."lyte/";

if (get_option('newTube')==="1") {
        $lyteSettings[1]="lyte-newtube-min.js";
} else {
        $lyteSettings[1]="lyte-min.js";
}

$oSize = (int) get_option('size');
if ((is_bool($oSize)) || ($pSize[$oSize]['a']===false)) { $sel = (int) $pDefault; } else { $sel= $oSize; }
$lyteSettings[2]=$pSize[$sel]['w'];
$lyteSettings[3]=$pSize[$sel]['h'];

$lyteSettings[4]=get_option('show_links');

function lyte_parse($the_content) {
	global $lyteSettings;
	if(strpos($the_content, "httpv")!==FALSE  ) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);
	    
		preg_match_all("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 
		foreach($matches as $match) {
			switch ($lyteSettings[4]) {
				case "0":
					$noscript_post="<br />Watch on YouTube";
					$lytelinks_txt="";
					break;
        			case "2":
				        $noscript_post="";
					$lytelinks_txt="<div class=\"lL\">Watch this video <a href=\"http://youtu.be/".$match[3]."\">on YouTube</a> or on <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$match[3]."\">Easy Youtube</a>.</div>";
					break;
				default:
                                        $noscript_post="";
                                        $lytelinks_txt="<div class=\"lL\">Watch this video <a href=\"http://youtu.be/".$match[3]."\">on YouTube</a>.</div>";
			}
			$lytetemplate = "<div class=\"lyte\" id=\"".$match[3]."\" style=\"width:".$lyteSettings[2]."px;height:".$lyteSettings[3]."px;\"><noscript><a href=\"http://youtu.be/".$match[3]."\"><img src=\"http://img.youtube.com/vi/".$match[3]."/0.jpg\">".$noscript_post."</a></noscript><script type=\"text/javascript\"><!-- \n var bU='".$lyteSettings[0]."';var d=document;if(d.addEventListener){d.addEventListener('DOMContentLoaded', insert, false)}else{window.onload=insert} function insert(){if(!d.getElementById('lytescr')){lytescr=d.createElement('script');lytescr.async=true;lytescr.id='lytescr';lytescr.src='".$lyteSettings[0].$lyteSettings[1]."';h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(lytescr, h)}}; \n --></script></div>".$lytelinks_txt;
			$the_content = preg_replace("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^\s<]*)/", $lytetemplate, $the_content, 1);
		}
	}
    return $the_content;
}

add_filter('the_content', 'lyte_parse', 90);
?>
