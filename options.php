<?php
add_action('admin_menu', 'lyte_create_menu');

function lyte_create_menu() {
	add_options_page( 'WP YouTube Lyte settings', 'WP YouTube Lyte', 'manage_options', __FILE__, 'lyte_settings_page');
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	register_setting( 'lyte-settings-group', 'show_links' );
}

function lyte_settings_page() {
?>
<div class="wrap">
<h2>WP YouTube Lyte Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'lyte-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Show links below the embedded videos?</th>
	<td>
	<fieldset><legend class="screen-reader-text"><span>Show links?</span></legend>
	<label title="Show YouTube-link"><input type="radio" name="show_links" value="1" <?php if (get_option('show_links')==="1") echo "checked" ?> /> Add YouTube-link.</label><br />
	<label title="Show YouTube and Ease YouTube link"><input type="radio" name="show_links" value="2" <?php if (get_option('show_links')==="2") echo "checked" ?> /> Add both a YouTube and a <a href="http://icant.co.uk/easy-youtube/docs/index.html" target="_blank">Easy YouTube</a>-link.</label><br />
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
