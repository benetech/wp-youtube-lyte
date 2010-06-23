<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/tag/lyte
Description: WordPress Lite YouTube Embeds (with optional HTML5 video) in posts.
Author: Frank (futtta) Goossens
Version: 0.3.5
Author URI: http://blog.futtta.be/
*/

require(dirname(__FILE__).'/options.php');

$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 

$lyteSettings[0]=$wp_lyte_plugin_url."lyte/";

if (get_option('newTube')==="1") {
	$lyteSettings[1]="width:650;height:390;";
	$lyteSettings[2]="lyte-newtube-min.js";
} else {
	$lyteSettings[1]="width:480;height:385;";
	$lyteSettings[2]="lyte-min.js";
}

$lyteSettings[3]=get_option('show_links');

function lyte_parse($the_content) {
	global $lyteSettings;
	if(strpos($the_content, "httpv")!==FALSE  ) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);
	    
		preg_match_all("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 
		foreach($matches as $match) {
			$lytetemplate="<div class=\"lyte\" id=\"".$match[3]."\" style=\"".$lyteSettings[1]."\"><noscript><a href=\"http://youtu.be/".$match[3]."\">Watch on YouTube</a> or on <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$match[3]."\">Easy Youtube</a></noscript><script>var bU='".$lyteSettings[0]."';(function(){d=document;if(!document.getElementById('lytescr')){lyte=d.createElement('script');lyte.async=true;lyte.id='lytescr';lyte.src='".$lyteSettings[0].$lyteSettings[2]."';d.getElementsByTagName('head')[0].appendChild(lyte)}})();</script></div>";
			switch ($lyteSettings[3]) {
        			case "1":
                			$lytetemplate .= "<div id=\"lytelinks\" style=\"margin:0px 0px 10px 0px;\">Watch this video <a href=\"http://youtu.be/".$match[3]."\">on YouTube</a>.</div>";
                			break;
        			case "2":
                			$lytetemplate .= "<div id=\"lytelinks\" style=\"margin:0px 0px 10px 0px;\">Watch this video <a href=\"http://youtu.be/".$match[3]."\">on YouTube</a> or on <a href=\"http://icant.co.uk/easy-youtube/?http://www.youtube.com/watch?v=".$match[3]."\">Easy Youtube</a>.</div>";
        		}
			$the_content = preg_replace("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^\s<]*)/", $lytetemplate, $the_content, 1);
		}
	}
    return $the_content;
}

add_filter('the_content', 'lyte_parse', 90);
?>
