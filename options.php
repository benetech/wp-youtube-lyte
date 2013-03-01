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
	register_setting( 'lyte-settings-group', 'lyte_show_links' );
	register_setting( 'lyte-settings-group', 'lyte_size' );
	register_setting( 'lyte-settings-group', 'lyte_hidef' );
	register_setting( 'lyte-settings-group', 'lyte_position' );
	register_setting( 'lyte-settings-group', 'lyte_notification' );
	register_setting( 'lyte-settings-group', 'lyte_microdata' );
}

function lyte_admin_scripts() {
	wp_enqueue_script('jqzrssfeed', plugins_url('/external/jquery.zrssfeed.min.js', __FILE__), array('jquery'),null,true);
	wp_enqueue_script('jqcookie', plugins_url('/external/jquery.cookie.min.js', __FILE__), array('jquery'),null,true);
}

function lyte_admin_styles() {
        wp_enqueue_style('zrssfeed', plugins_url('/external/jquery.zrssfeed.css', __FILE__));
}

function lyte_admin_notice(){
    echo '<div class="updated"><p>Hello WP YouTube Lyte user!<br />Just to let you know that <strong>the bonus feature, DoNotTrack, was removed</strong> from WP YouTube Lyte. If you would like to keep blocking third party tracking on your blog, you might want to <strong>install <a href="http://wordpress.org/extend/plugins/wp-donottrack/" title="WP DoNotTrack">WP DoNotTrack</a></strong>, which is a more powerful and flexible solution.</p><p>Have a great day!<br /><a href="http://blog.futtta.be/">frank</a>.</div>';
    }

if (get_option('lyte_notification','0')!=="2") {
	add_action('admin_notices', 'lyte_admin_notice');
	update_option('lyte_notification','2');
	}

function lyte_settings_page() {
	global $pSize, $pSizeOrder;
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
	<input type="hidden" name="lyte_notification" value="<?php echo get_option('lyte_notification','0'); ?>" />

        <tr valign="top">
            <th scope="row">Player size:</th>
            <td>
                <fieldset><legend class="screen-reader-text"><span><?php _e("Player size","wp-youtube-lyte") ?></span></legend>
		<?php
			if (is_bool(get_option('lyte_size'))) { $sel = (int) $pDefault; } else { $sel= (int) get_option('lyte_size'); }
			foreach (array("169","43") as $f) {
				foreach ($pSizeOrder[$f] as $i) {
					$pS=$pSize[$i];
					if ($pS[a]===true) {
						?>
						<label title="<?php echo $pS[w]."X".$pS[h]; ?>"><input type="radio" name="lyte_size" class="l_size" value="<?php echo $i."\"";if($i===$sel) echo " checked";echo " /> ".$pS[w]."X".$pS[h]." (".$pS[t];?>)</label><br />
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
					<label title="Show YouTube-link"><input type="radio" name="lyte_show_links" value="1" <?php if (get_option('lyte_show_links')==="1") echo "checked" ?> /><?php _e(" Add YouTube-link.","wp-youtube-lyte") ?></label><br />
					<label title="Show YouTube and Ease YouTube link"><input type="radio" name="lyte_show_links" value="2" <?php if (get_option('lyte_show_links')==="2") echo "checked" ?> /><?php _e(" Add both a YouTube and an <a href=\"http://icant.co.uk/easy-youtube/docs/index.html\" target=\"_blank\">Easy YouTube</a>-link.","wp-youtube-lyte") ?></label><br />
					<label title="Don't include links."><input type="radio" name="lyte_show_links" value="0" <?php if ((get_option('lyte_show_links')!=="1") && (get_option('lyte_show_links')!=="2")) echo "checked" ?> /><?php _e(" Don't add any links.","wp-youtube-lyte") ?></label>
				</fieldset>
			</td>
         </tr>
         <tr valign="top">
                <th scope="row"><?php _e("Player position:","wp-youtube-lyte") ?></th>
                <td>
                        <fieldset>
                                <legend class="screen-reader-text"><span>Left, center or right?</span></legend>
                                <label title="Left"><input type="radio" name="lyte_position" value="0" <?php if (get_option('lyte_position','0')==="0") echo "checked" ?> /><?php _e("Left","wp-youtube-lyte") ?></label><br />
				<label title="Center"><input type="radio" name="lyte_position" value="1" <?php if (get_option('lyte_position','0')==="1") echo "checked" ?> /><?php _e("Center","wp-youtube-lyte") ?></label>
                        </fieldset>
                </td>
         </tr>
         <tr valign="top">
                <th scope="row"><?php _e("Try to force HD (experimental)?","wp-youtube-lyte") ?></th>
                <td>
                        <fieldset>
                                <legend class="screen-reader-text"><span>HD or not?</span></legend>
                                <label title="Enable HD?"><input type="radio" name="lyte_hidef" value="1" <?php if (get_option('lyte_hidef','0')==="1") echo "checked" ?> /><?php _e("Enable HD","wp-youtube-lyte") ?></label><br />
                                <label title="Don't enable HD playback"><input type="radio" name="lyte_hidef" value="0" <?php if (get_option('lyte_hidef','0')!=="1") echo "checked" ?> /><?php _e("No HD (default)","wp-youtube-lyte") ?></label>
                        </fieldset>
                </td>
	</tr>
         <tr valign="top">
                <th scope="row"><?php _e("Add microdata?","wp-youtube-lyte") ?></th>
                <td>
                        <fieldset>
                                <legend class="screen-reader-text"><span>Add video microdata to the HTML?</span></legend>
                                <label title="Sure, add microdata!"><input type="radio" name="lyte_microdata" value="1" <?php if (get_option('lyte_microdata','1')==="1") echo "checked" ?> /><?php _e("Yes (default)","wp-youtube-lyte") ?></label><br />
                                <label title="No microdata in my HTML please."><input type="radio" name="lyte_microdata" value="0" <?php if (get_option('lyte_microdata','1')!=="1") echo "checked" ?> /><?php _e("No microdata, thanks.","wp-youtube-lyte") ?></label>
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
			<?php if ( is_ssl() ) echo "ssl: true,"; ?>
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
