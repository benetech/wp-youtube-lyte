<?php
$plugin_dir = basename(dirname(__FILE__)).'/languages';
load_plugin_textdomain( 'wp-youtube-lyte', false, $plugin_dir );

add_action('admin_menu', 'lyte_create_menu');

function lyte_create_menu() {
        $hook=add_options_page( 'WP YouTube Lyte settings', 'WP YouTube Lyte', 'manage_options', 'lyte_settings_page', 'lyte_settings_page');
        add_action( 'admin_init', 'register_lyte_settings' );
        add_action( 'admin_print_scripts-'.$hook, 'lyte_admin_scripts' );
        add_action( 'admin_print_styles-'.$hook, 'lyte_admin_styles' );
}

function register_lyte_settings() {
	register_setting( 'lyte-settings-group', 'show_links' );
	register_setting( 'lyte-settings-group', 'size' );
	register_setting( 'lyte-settings-group', 'hidef' );
	register_setting( 'lyte-settings-group', 'position' );
	register_setting( 'lyte-settings-group', 'donottrack' );
	register_setting( 'lyte-settings-group', 'notification' );
}

function lyte_admin_scripts() {
	wp_enqueue_script('jqzrssfeed', plugins_url('/external/jquery.zrssfeed.min.js', __FILE__), array(jquery),null,true);
	wp_enqueue_script('jqcookie', plugins_url('/external/jquery.cookie.min.js', __FILE__), array(jquery),null,true);
}

function lyte_admin_styles() {
        wp_enqueue_style('zrssfeed', plugins_url('/external/jquery.zrssfeed.css', __FILE__));
}

function lyte_admin_notice(){
    echo '<div class="updated"><p><strong>Hi there WP YouTube Lyte user!</strong></p><p>Just to let you know that, if you use Lyte Widgets, <a href="http://apiblog.youtube.com/2012/03/minimum-embeds-200px-x-200px.html">from the end of April 2012 onwards YouTube requires the <strong>the minimum size for an embedded player to be 200X200px</strong></a>. If YouTube enforces this, the <strong>smaller widget sizes will not work any more</strong> and for that reason should be considered deprecated from now on. A new widget with size 200X200 is now available to allow you to continue to use WP YouTube Lyte widgets.</p><p>Have a swell day!<br /><a href="http://blog.futtta.be/">frank</a>.</div>';
    }

if (get_option('notification','0')!=="1") {
	add_action('admin_notices', 'lyte_admin_notice');
	update_option('notification','1');
	}

function lyte_settings_page() {
?>
<div class="wrap">
<h2><?php _e("WP YouTube Lyte Settings","wp-youtube-lyte") ?></h2>
<div style="float:left;width:70%;">
<p><?php _e("WP YouTube Lyte inserts \"Lite YouTube Embeds\" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on, thereby <a href=\"http://blog.futtta.be/2012/04/03/speed-matters-re-evaluating-wp-youtube-lytes-performance/\" target=\"_blank\">reducing download size & rendering time substantially</a>. When a video is played, WP-YouTube-Lyte can either activate <a href=\"http://apiblog.youtube.com/2010/07/new-way-to-embed-youtube-videos.html\" target=\"_blank\">YouTube's embedded html5-player</a> or the older Flash-version, depending on the settings below.","wp-youtube-lyte") ?></p>
<p><?php _e("You can place video and audio in your posts and pages by adding one or more http<strong>v</strong> or http<strong>a</strong> YouTube-links to your post. These will automatically be replaced by WP YouTube Lyte with the correct (flash-less) code. To add a video for example, you type a URL like <em>http<strong>v</strong>://www.youtube.com/watch?v=QQPSMRQnNlU</em> or <em>http<strong>v</strong>://www.youtube.com/playlist?list=PLA486E741B25F8E00</em> for a playlist. If you want an audio-only player, you enter <em>http<strong>a</strong>://www.youtube.com/watch?v=BIQIGR-kWtc</em>. There's more info on the <a href=\"http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/\" target=\"_blank\">wordpress.org WP YouTube Lyte FAQ page</a>.","wp-youtube-lyte") ?></p>
<p><?php _e("You can modify WP-YouTube-Lyte's behaviour by changing the following settings:","wp-youtube-lyte") ?></p>
<form method="post" action="options.php">
    <?php settings_fields( 'lyte-settings-group' ); ?>
    <table class="form-table">
	<input type="hidden" name="notification" value="<?php echo get_option('notification','0'); ?>" />

        <tr valign="top">
            <th scope="row">Player size:</th>
            <td>
                <fieldset><legend class="screen-reader-text"><span><?php _e("Player size","wp-youtube-lyte") ?></span></legend>
		<?php require 'player_sizes.inc.php';
			if (is_bool(get_option('size'))) { $sel = (int) $pDefault; } else { $sel= (int) get_option('size'); }
			foreach (array("169","43") as $f) {
				foreach ($pSizeOrder[$f] as $i) {
					$pS=$pSize[$i];
					if ($pS[a]===true) {
						?>
						<label title="<?php echo $pS[w]."X".$pS[h]; ?>"><input type="radio" name="size" class="l_size" value="<?php echo $i."\"";if($i===$sel) echo " checked";echo " /> ".$pS[w]."X".$pS[h]." (".$pS[t];?>)</label><br />
						<?php
					}
				}
				?><br /><?php
			}
		?>
                </fieldset>
             </td>
         </tr>
        <tr valign="top">
			<th scope="row"><?php _e("Add links below the embedded videos?","wp-youtube-lyte") ?></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php _e("Show links?","wp-youtube-lyte") ?></span></legend>
					<label title="Show YouTube-link"><input type="radio" name="show_links" value="1" <?php if (get_option('show_links')==="1") echo "checked" ?> /><?php _e(" Add YouTube-link.","wp-youtube-lyte") ?></label><br />
					<label title="Show YouTube and Ease YouTube link"><input type="radio" name="show_links" value="2" <?php if (get_option('show_links')==="2") echo "checked" ?> /><?php _e(" Add both a YouTube and an <a href=\"http://icant.co.uk/easy-youtube/docs/index.html\" target=\"_blank\">Easy YouTube</a>-link.","wp-youtube-lyte") ?></label><br />
					<label title="Don't include links."><input type="radio" name="show_links" value="0" <?php if ((get_option('show_links')!=="1") && (get_option('show_links')!=="2")) echo "checked" ?> /><?php _e(" Don't add any links.","wp-youtube-lyte") ?></label>
				</fieldset>
			</td>
         </tr>
         <tr valign="top">
                <th scope="row"><?php _e("Player position:","wp-youtube-lyte") ?></th>
                <td>
                        <fieldset>
                                <legend class="screen-reader-text"><span>Left, center or right?</span></legend>
                                <label title="Left"><input type="radio" name="position" value="0" <?php if (get_option('position','0')==="0") echo "checked" ?> /><?php _e("Left","wp-youtube-lyte") ?></label><br />
				<label title="Center"><input type="radio" name="position" value="1" <?php if (get_option('position','0')==="1") echo "checked" ?> /><?php _e("Center","wp-youtube-lyte") ?></label>
                        </fieldset>
                </td>
         </tr>
         <tr valign="top">
                <th scope="row"><?php _e("Play video in HD if possible?","wp-youtube-lyte") ?></th>
                <td>
                        <fieldset>
                                <legend class="screen-reader-text"><span>HD or not?</span></legend>
                                <label title="Enable HD?"><input type="radio" name="hidef" value="1" <?php if (get_option('hidef')==="1") echo "checked" ?> /><?php _e("Enable HD","wp-youtube-lyte") ?></label><br />
                                <label title="Don't enable HD playback"><input type="radio" name="hidef" value="0" <?php if (get_option('hidef')!=="1") echo "checked" ?> /><?php _e("No HD, we're smallband!","wp-youtube-lyte") ?></label>
                        </fieldset>
                </td>
	</tr>
	 <tr valign="top">
	 	<th scope="row"><?php _e("Bonus feature: ","wp-youtube-lyte") ?><a href="http://wordpress.org/extend/plugins/wp-donottrack/" target="_blank">DoNotTrack</a></th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span>Activate DoNotTrack</span></legend>
				<label title="Enable DoNotTrack"><input type="radio" name="donottrack" value="1" <?php if (get_option('donottrack')==="1") echo "checked" ?> /><?php _e("Disable 3rd party tracking.","wp-youtube-lyte") ?></label><br />
				<label title="Leave DoNotTrack disabled (default)"><input type="radio" name="donottrack" value="0" <?php if (get_option('donottrack')!=="1") echo "checked" ?> /><?php _e("I don't mind 3rd party tracking (default)","wp-youtube-lyte") ?></label>
			</fieldset>
			<span class="description"><?php _e( "This stops tracking by Quantcast as initiated by some <a href=\"http://profiles.wordpress.org/users/automattic/profile/public/\" target=\"_blank\">Automattic plugins</a>. You can also <a href=\"http://wordpress.org/extend/plugins/wp-donottrack/\" target=\"_blank\">try out WP DoNotTrack</a>, a new plugin that provides these features and more (custom black- or whitelist, conditional DoNotTrack, ...)", "wp-donottrack" )  ?></span>
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
			<?php if ($_SERVER['HTTPS']) echo "ssl: true,"; ?>
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
