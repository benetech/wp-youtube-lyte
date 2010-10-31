<?php
class WYLWidget extends WP_Widget {
    function WYLWidget() {
        parent::WP_Widget(false, $name = 'WP-YouTube-Lyte');	
    }

    function widget($args, $instance) {		
        extract( $args );
        $WYLtitle = apply_filters('widget_title', $instance['WYLtitle']);
	$WYLtext = apply_filters( 'widget_text', $instance['WYLtext'], $instance );
	parse_str(parse_url(esc_url($instance['WYLurl']),PHP_URL_QUERY),$WYLarr);
	$WYLid=$WYLarr['v'];

	$wp_lyte_plugin_url = defined('WP_PLUGIN_URL') ? trailingslashit(WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	$lyteSettings[0]=$wp_lyte_plugin_url."lyte/";

	if (get_option('newTube')==="1") {
	        $lyteSettings[1]="lyte-newtube-min.js";
	} else {
		$lyteSettings[1]="lyte-min.js";
	}
	?>
        <?php echo $before_widget; ?>
              <?php if ( $WYLtitle ) echo $before_title . $WYLtitle . $after_title; ?>
	      <div class="lyte widget" id="<?php echo $WYLid; ?>" style="width:150px;height:125px;"><noscript><a href="http://youtu.be/<?php echo $WYLid;?>"><img src="http://img.youtube.com/vi/<?php echo $WYLid; ?>/default.jpg"></a></noscript><script type="text/javascript">/*<![CDATA[*/var bU='<?php echo $lyteSettings[0];?>';var d=document;if(d.addEventListener){d.addEventListener('DOMContentLoaded', insert, false)}else{window.onload=insert} function insert(){if(!d.getElementById('lytescr')){lytescr=d.createElement('script');lytescr.async=true;lytescr.id='lytescr';lytescr.src='<?php echo $lyteSettings[0].$lyteSettings[1];?>';h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(lytescr, h)}};/*]]>*/</script></div>
	      <div><?php echo $WYLtext ?></div>
              <?php echo $after_widget; ?>
        <?php
    }

    function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['WYLtitle'] = strip_tags($new_instance['WYLtitle']);
		$instance['WYLurl'] = strip_tags($new_instance['WYLurl']);
                if ( current_user_can('unfiltered_html') )
			$instance['WYLtext'] = $new_instance['WYLtext'];
                else
                        $instance['WYLtext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['WYLtext']) ) ); 

		return $instance;
    }

    function form($instance) {				
        $WYLtitle = esc_attr($instance['WYLtitle']);
	$WYLurl = esc_attr($instance['WYLurl']);
	$WYLtext = format_to_edit($instance['WYLtext']);
        ?>
            <p><label for="<?php echo $this->get_field_id('WYLtitle'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('WYLtitle'); ?>" name="<?php echo $this->get_field_name('WYLtitle'); ?>" type="text" value="<?php echo $WYLtitle; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('WYLurl'); ?>"><?php _e('Youtube-URL:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('WYLurl'); ?>" name="<?php echo $this->get_field_name('WYLurl'); ?>" type="text" value="<?php echo $WYLurl; ?>" /></label></p>
	    <p><label for="<?php echo $this->get_field_id('WYLtext'); ?>"><?php _e('Text:'); ?> <textarea class="widefat" id="<?php echo $this->get_field_id('WYLtext'); ?>" name="<?php echo $this->get_field_name('WYLtext'); ?>" rows="16" cols="20"><?php echo $WYLtext; ?></textarea></label></p>
        <?php 
    }
} 

add_action('widgets_init', create_function('', 'return register_widget("WYLWidget");'));
?>
