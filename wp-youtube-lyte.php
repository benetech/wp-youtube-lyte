<?php
/*
Plugin Name: WP YouTube Lyte
Plugin URI: http://blog.futtta.be/2010/05/18/lite-youtube-embeds-in-wordpress/
Description: WordPress Lite YouTube Embeds (look ma, even faster!) in posts (comments and RSS feeds to come).
Author: Frank (futtta) Goossens
Version: 0.1.1
Author URI: http://blog.futtta.be/
*/

$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 
$lyteUrl=$wp_lyte_plugin_url."lyte/";

function lyte_parse($the_content, $side=0) {
	global $lyteUrl;
	if(strpos($the_content, "httpv")!==FALSE  ) {
		$char_codes = array('&#215;','&#8211;');
		$replacements = array("x", "--");
		$the_content=str_replace($char_codes, $replacements, $the_content);
	    
		preg_match_all("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $the_content, $matches, PREG_SET_ORDER); 
		foreach($matches as $match) { 	
			$the_content = preg_replace("/httpv:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^\s<]*)/", "<div class=\"lyte\" id=\"".$match[3]."\" style=\"width:480;height:385;\"><noscript><a href=\"http://youtu.be/".$match[3]."\">Watch on YouTube</a></noscript><script>var bU='".$lyteUrl."';(function(){d=document;if(!document.getElementById('lytescr')){lyte=d.createElement('script');lyte.async=true;lyte.id='lytescr';lyte.src='".$lyteUrl."lyte-min.js';d.getElementsByTagName('head')[0].appendChild(lyte)}})();</script></div>", $the_content, 1);	
		}
		
	}
    return $the_content;
}

add_filter('the_content', 'lyte_parse', 90);
?>
