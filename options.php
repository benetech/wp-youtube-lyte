<?php
		$plugin_dir = basename(dirname(__FILE__)).'/languages';
		load_plugin_textdomain( 'wp-youtube-lyte', false, $plugin_dir );

        $wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));

add_action('admin_menu', 'lyte_create_menu');

function lyte_create_menu() {
        $hook=add_options_page( 'WP YouTube Lyte settings', 'WP YouTube Lyte', 'manage_options', 'lyte_settings_page', 'lyte_settings_page');
        add_action( 'admin_init', 'register_lyte_settings' );
        add_action( 'admin_print_scripts-'.$hook, 'lyte_admin_scripts' );
        add_action( 'admin_print_styles-'.$hook, 'lyte_admin_styles' );
}

function register_lyte_settings() {
	register_setting( 'lyte-settings-group', 'newTube' );
	register_setting( 'lyte-settings-group', 'show_links' );
	register_setting( 'lyte-settings-group', 'size' );
	register_setting( 'lyte-settings-group', 'donottrack' );
}

function lyte_admin_scripts() {
	global $wp_lyte_plugin_url;
	wp_enqueue_script('jqzrssfeed',$wp_lyte_plugin_url.'external/jquery.zrssfeed.min.js',array(jquery),null,true);
	wp_enqueue_script('jqcookie',$wp_lyte_plugin_url.'external/jquery.cookie.min.js',array(jquery),null,true);
}

function lyte_admin_styles() {
        global $wp_lyte_plugin_url;
        wp_enqueue_style('zrssfeed',$wp_lyte_plugin_url.'external/jquery.zrssfeed.css');
}

function lyte_settings_page() {
?>
<div class="wrap">
<h2><?php _e("WP YouTube Lyte Settings","wp-youtube-lyte") ?></h2>
<div style="float:left;width:70%;">
<p><?php _e("WP YouTube Lyte inserts \"Lite YouTube Embeds\" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on, thereby <a href=\"http://blog.futtta.be/2010/08/30/the-state-of-wp-youtube-lyte/\" target=\"_blank\">reducing download size & rendering time substantially</a>. When a video is played, WP-YouTube-Lyte can either activate <a href=\"http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html\" target=\"_blank\">YouTube's embedded html5-player</a> or the older Flash-version, depending on the settings below.","wp-youtube-lyte") ?></p>
<p><?php _e("You can place video and audio in your posts and pages by adding one or more http<strong>v</strong> or http<strong>a</strong> YouTube-links to your post. These will automatically be replaced by WP YouTube Lyte with the correct (flash-less) code. To add a video for example, you type a URL like <em>http<strong>v</strong>://www.youtube.com/watch?v=QQPSMRQnNlU</em>. If you want an audio-only player, you enter <em>http<strong>a</strong>://www.youtube.com/watch?v=BIQIGR-kWtc</em>. There's more info on the <a href=\"http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/\" target=\"_blank\">wordpress.org WP YouTube Lyte FAQ page</a>.","wp-youtube-lyte") ?></p>
<p><?php _e("You can modify WP-YouTube-Lyte's behaviour by changing the following settings:","wp-youtube-lyte") ?></p>
<form method="post" action="options.php">
    <?php settings_fields( 'lyte-settings-group' ); ?>
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e("Play video using Flash or HTML5-video?","wp-youtube-lyte") ?></th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Use Flash or HTML5 video?</span></legend>
					<label title="embed HTML5 video"><input type="radio" name="newTube" value="1" <?php if (get_option('newTube')==="1") echo "checked" ?> /> <?php _e("Embed HTML5 video (uses <a href=\"http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html\" target=\"_blank\">the new YouTube embed-code</a>)","wp-youtube-lyte") ?></label><br />
                    <label title="normal YouTube embeds with Flash video"><input type="radio" name="newTube" value="0" <?php if (get_option('newTube')!=="1") echo "checked" ?> /> <?php _e("Old-style YouTube embeds with Flash video.","wp-youtube-lyte") ?></label><br />
			</fieldset>
			</td>
        </tr>
        <tr valign="top">
            <th scope="row">Player size:</th>
            <td>
                <fieldset><legend class="screen-reader-text"><span><?php _e("Player size","wp-youtube-lyte") ?></span></legend>
		<?php require 'player_sizes.inc.php';
			$i=0;
			if (is_bool(get_option('size'))) { $sel = (int) $pDefault; } else { $sel= (int) get_option('size'); }
		 	while ($pS=$pSize[$i]) { 
				if ($pS[a]===true) {
				?>
				<label title="<?php echo $pS[w]."X".$pS[h]; ?>"><input type="radio" name="size" class="l_size" value="<?php echo $i."\"";if($i===$sel) echo " checked";echo " /> ".$pS[w]."X".$pS[h]." (".$pS[t];?>)</label><br />
				<?php
				}
				$i++;
			}
		?>
                </fieldset>
             </td>
         </tr>
        <tr valign="top">
			<th scope="row"><?php _e("Add links below the embedded videos?","wp-youtube-lyte") ?></th>
			<td>
				<fieldset><legend class="screen-reader-text"><span><?php _e("Show links?","wp-youtube-lyte") ?></span></legend>
					<label title="Show YouTube-link"><input type="radio" name="show_links" value="1" <?php if (get_option('show_links')==="1") echo "checked" ?> /><?php _e(" Add YouTube-link.","wp-youtube-lyte") ?></label><br />
					<label title="Show YouTube and Ease YouTube link"><input type="radio" name="show_links" value="2" <?php if (get_option('show_links')==="2") echo "checked" ?> /><?php _e(" Add both a YouTube and an <a href=\"http://icant.co.uk/easy-youtube/docs/index.html\" target=\"_blank\">Easy YouTube</a>-link.","wp-youtube-lyte") ?></label><br />
					<label title="Don't include links."><input type="radio" name="show_links" value="0" <?php if ((get_option('show_links')!=="1") && (get_option('show_links')!=="2")) echo "checked" ?> /><?php _e(" Don't add any links.","wp-youtube-lyte") ?></label>
			</fieldset>
			</td>
         </tr>
	 <tr valign="top">
	 	<th scope="row"><?php _e("Bonus feature: ","wp-youtube-lyte") ?><a href="http://blog.futtta.be/tag/donottrack" target="_blank">DoNotTrack</a></th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span>Activate DoNotTrack</span></legend>
				<label title="Enable DoNotTrack"><input type="radio" name="donottrack" value="1" <?php if (get_option('donottrack')==="1") echo "checked" ?> /><?php _e("Disable 3rd party tracking.","wp-youtube-lyte") ?></label><br />
				<label title="Leave DoNotTrack disabled (default)"><input type="radio" name="donottrack" value="0" <?php if (get_option('donottrack')!=="1") echo "checked" ?> /><?php _e("I don't mind 3rd party tracking (default)","wp-youtube-lyte") ?></label>
			</fieldset>
		</td>
	 </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<div style="float:right;width:30%" id="lyte_admin_feed">
        <div style="margin-left:10px;margin-top:-5px;">
                <h3>
                        <?php _e("futtta about","wp-youtube-lyte") ?>
                        <select id="feed_dropdown" >
                                <option value="1"><?php _e("WP YouTube Lyte","wp-youtube-lyte") ?></option>
                                <option value="2"><?php _e("WordPress","wp-youtube-lyte") ?></option>
                                <option value="3"><?php _e("Web Technology","wp-youtube-lyte") ?></option>
                        </select>
                </h3>
                <div id="futtta_feed"></div>
        </div>
</div>

<script type="text/javascript">
	var feed = new Array;
	feed[1]="http://feeds.feedburner.com/futtta_wp-youtube-lyte";
	feed[2]="http://feeds.feedburner.com/futtta_wordpress";
	feed[3]="http://feeds.feedburner.com/futtta_webtech";
	cookiename="wp-youtube-lyte_feed";

        jQuery(document).ready(function() {
		jQuery("#feed_dropdown").change(function() { show_feed(jQuery("#feed_dropdown").val()) });

		feedid=jQuery.cookie(cookiename);
		if(typeof(feedid) !== "string") feedid=1;

		show_feed(feedid);
		})

	function show_feed(id) {
  		jQuery('#futtta_feed').rssfeed(feed[id], {
    			limit: 4,
			date: true,
			header: false
  		});
		jQuery("#feed_dropdown").val(id);
		jQuery.cookie(cookiename,id,{ expires: 365 });
	}
</script>

</div>
<?php } ?>
