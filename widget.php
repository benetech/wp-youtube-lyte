<?php
require_once(dirname(__FILE__).'/player_sizes.inc.php');

class WYLWidget extends WP_Widget {
    function WYLWidget() {
        parent::WP_Widget(false, $name = 'WP YouTube Lyte');
    }

    function widget($args, $instance) {		
        extract( $args );
	global $wSize;
        $WYLtitle = apply_filters('widget_title', $instance['WYLtitle']);
	$WYLtext = apply_filters( 'widget_text', $instance['WYLtext'], $instance );

	$WYLsize = apply_filters( 'widget_text', $instance['WYLsize'], $instance );
	if ($WYLsize=="") $WYLsize=$wDefault;

	$WYLaudio = apply_filters( 'widget_text', $instance['WYLaudio'], $instance );
	if ($WYLaudio!=="audio") $WYLaudio="";

	$WYLurl=esc_url($instance['WYLurl']);

        if (strpos($WYLurl,'youtu.be')) {
                $WYLid=substr(parse_url($WYLurl,PHP_URL_PATH),1,11);
        } else {
                $WYLqs=substr(strstr($WYLurl,'?'),1);
                parse_str($WYLqs,$WYLarr);
                $WYLid=$WYLarr['v'];
        }

	$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	$lyteSettings[0]=$wp_lyte_plugin_url."lyte/";
	?>

        <?php echo $before_widget; ?>
              <?php if ( $WYLtitle ) echo $before_title . $WYLtitle . $after_title; ?>
	      <div class="lyte widget <?php echo $WYLaudio; ?>" id="<?php echo $WYLid; ?>" style="width:<?php echo $wSize[$WYLsize]['w']; ?>px;height:<?php if($WYLaudio==="audio") {echo "25";} else {echo $wSize[$WYLsize]['h'];} ?>px;"><noscript><a href="http://youtu.be/<?php echo $WYLid;?>"><img src="http://img.youtube.com/vi/<?php echo $WYLid; ?>/default.jpg" alt="" /></a></noscript><script type="text/javascript"><!-- 
	      var bU='<?php echo $lyteSettings[0];?>';var d=document;if(d.addEventListener){d.addEventListener('DOMContentLoaded', insert, false)}else{window.onload=insert} function insert(){if(!d.getElementById('lytescr')){lytescr=d.createElement('script');lytescr.async=true;lytescr.id='lytescr';lytescr.src='<?php echo $lyteSettings[0]."lyte-min.js";?>';h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(lytescr, h)}};
	      --></script></div>
	      <div><?php echo $WYLtext ?></div>
              <?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['WYLtitle'] = strip_tags($new_instance['WYLtitle']);
		$instance['WYLurl'] = strip_tags($new_instance['WYLurl']);
		$instance['WYLsize'] = strip_tags($new_instance['WYLsize']);
		$instance['WYLaudio'] = strip_tags($new_instance['WYLaudio']);

                if ( current_user_can('unfiltered_html') )
			$instance['WYLtext'] = $new_instance['WYLtext'];
                else
                        $instance['WYLtext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['WYLtext']) ) ); 

		return $instance;
    }

    function form($instance) {
        global $wSize, $wDefault;

        $WYLtitle = esc_attr($instance['WYLtitle']);
	$WYLurl = esc_attr($instance['WYLurl']);
	$WYLtext = format_to_edit($instance['WYLtext']);

	$WYLaudio = esc_attr($instance['WYLaudio']);
	if ($WYLaudio!=="audio") $WYLaudio="";

	$WYLsize = esc_attr($instance['WYLsize']);
	if ($WYLsize=="") $WYLsize=$wDefault;

        ?>
            <p><label for="<?php echo $this->get_field_id('WYLtitle'); ?>"><?php _e("Title:","wp-youtube-lyte") ?> <input class="widefat" id="<?php echo $this->get_field_id('WYLtitle'); ?>" name="<?php echo $this->get_field_name('WYLtitle'); ?>" type="text" value="<?php echo $WYLtitle; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('WYLsize'); ?>"><?php _e("Size:","wp-youtube-lyte") ?>
		<select class="widefat" id="<?php echo $this->get_field_id('WYLsize'); ?>" name="<?php echo $this->get_field_name('WYLsize'); ?>">
			<?php
				$x=1;
				while ($wSize[$x]) {
					if ($x==$WYLsize) {
						$selected=" selected=\"true\"";
						} else {
						$selected="";
						}
					echo "<option value=\"".$x."\"".$selected.">".$wSize[$x]['w']."X".$wSize[$x]['h']."</option>";
					$x++;
				}
			?>
		</select>
	    </label></p>
	    <p><label for="<?php echo $this->get_field_id('WYLaudio'); ?>"><?php _e("Type:","wp-youtube-lyte") ?>
                <select class="widefat" id="<?php echo $this->get_field_id('WYLaudio'); ?>" name="<?php echo $this->get_field_name('WYLaudio'); ?>">
                        <?php
				if($WYLaudio==="audio") {
					$aselected=" selected=\"true\"";
				} else {
					$vselected=" selected=\"true\"";
				}
                echo "<option value=\"audio\"".$aselected.">".__("audio","wp-youtube-lyte")."</option>";
				echo "<option value=\"video\"".$vselected.">".__("video","wp-youtube-lyte")."</option>";
                        ?>
                </select>
            </label></p>
            <p><label for="<?php echo $this->get_field_id('WYLurl'); ?>"><?php _e("Youtube-URL:","wp-youtube-lyte") ?> <input class="widefat" id="<?php echo $this->get_field_id('WYLurl'); ?>" name="<?php echo $this->get_field_name('WYLurl'); ?>" type="text" value="<?php echo $WYLurl; ?>" /></label></p>
	    <p><label for="<?php echo $this->get_field_id('WYLtext'); ?>"><?php _e("Text:","wp-youtube-lyte") ?> <textarea class="widefat" id="<?php echo $this->get_field_id('WYLtext'); ?>" name="<?php echo $this->get_field_name('WYLtext'); ?>" rows="16" cols="20"><?php echo $WYLtext; ?></textarea></label></p>
        <?php 
    }
} 

add_action('widgets_init', create_function('', 'return register_widget("WYLWidget");'));
?>
