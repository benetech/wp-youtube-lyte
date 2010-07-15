<?php
add_action('admin_menu', 'lyte_create_menu');

function lyte_create_menu() {
	add_options_page( 'WP YouTube Lyte settings', 'WP YouTube Lyte', 'manage_options', __FILE__, 'lyte_settings_page');
	add_action( 'admin_init', 'register_lyte_settings' );
}

function register_lyte_settings() {
	register_setting( 'lyte-settings-group', 'newTube' );
	register_setting( 'lyte-settings-group', 'show_links' );
	register_setting( 'lyte-settings-group', 'size' );
}

function lyte_settings_page() {
?>
<div class="wrap">
<h2>WP YouTube Lyte Settings</h2>

<p>WP-YouTube-Lyte inserts "Lite YouTube Embeds" in your blog. These look and feel like normal embedded YouTube, but don't use Flash unless clicked on (the HTML5-option even allows for entirely Flash-less YouTube embeds), thereby <a href="http://blog.futtta.be/2010/04/23/high-performance-youtube-embeds/" target="_blank">reducing download size & rendering time substantially</a>. You can find more info on the <a href="http://wordpress.org/extend/plugins/wp-youtube-lyte/" target="_blank">wordpress.org WP-YouTube-Lyte page</a>.</p>
<p>You can embed the LYTE-player by adding one or more YouTube-links to your post, replacing the "http://" with "httpv://" (just add a 'v' for 'video'). WP-YouTube-Lyte will replace those links with the correct (flash-less) code.</p>
<p>You can modify WP-YouTube-Lyte's behaviour by changing the following settings:</p>
<form method="post" action="options.php">
    <?php settings_fields( 'lyte-settings-group' ); ?>
    <table class="form-table">
		<tr valign="top">
			<th scope="row">Play video using Flash or HTML5-video?</th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Use Flash or HTML5 video?</span></legend>
					<label title="normal YouTube embeds with Flash video"><input type="radio" name="newTube" value="0" <?php if (get_option('newTube')!=="1") echo "checked" ?> /> Normal YouTube embeds with Flash video. You can set player size below.</label><br />
					<label title="embed HTML5 video (highly experimental)"><input type="radio" name="newTube" value="1" <?php if (get_option('newTube')==="1") echo "checked" ?> /> Embed HTML5 video (<a href="http://wordpress.org/extend/plugins/wp-youtube-lyte/faq/" target="_blank">very experimental, see FAQ</a>). Fixed player size: 650X390px.</label><br />
			</fieldset>
			</td>
        </tr>
        <tr valign="top">
            <th scope="row">Player size (has no effect on fixed-size HTML5-version)?</th>
            <td>
                 <fieldset><legend class="screen-reader-text"><span>Player size (does not work for HTML5-version)</span></legend>
		<?php require 'player_sizes.inc';
			$i=0;
			if (is_bool(get_option('size'))) { $sel = (int) $pDefault; } else { $sel= (int) get_option('size'); }
		 	while ($pS=$pSize[$i]) { 
				if ($pS[a]===true) {
				?>
				<label title="<?php echo $pS[w]."X".$pS[h]; ?>"><input type="radio" name="size" value="<?php echo $i."\"";if($i===$sel) echo " checked";echo " /> ".$pS[w]."X".$pS[h]." (".$pS[t];?>)</label><br />
				<?php
				}
				$i++;
			}
		?>
                </fieldset>
             </td>
         </tr>
        <tr valign="top">
			<th scope="row">Show links below the embedded videos?</th>
			<td>
				<fieldset><legend class="screen-reader-text"><span>Show links?</span></legend>
					<label title="Show YouTube-link"><input type="radio" name="show_links" value="1" <?php if (get_option('show_links')==="1") echo "checked" ?> /> Add YouTube-link.</label><br />
					<label title="Show YouTube and Ease YouTube link"><input type="radio" name="show_links" value="2" <?php if (get_option('show_links')==="2") echo "checked" ?> /> Add both a YouTube and an <a href="http://icant.co.uk/easy-youtube/docs/index.html" target="_blank">Easy YouTube</a>-link.</label><br />
					<label title="Don't include links."><input type="radio" name="show_links" value="0" <?php if ((get_option('show_links')!=="1") && (get_option('show_links')!=="2")) echo "checked" ?> /> Don't add any links.</label>
			</fieldset>
			</td>
         </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } ?>
