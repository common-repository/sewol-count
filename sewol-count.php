<?php
/*
Plugin Name: Sewol Count
Plugin URI: http://parkyong.com
Description: count day after Sewol Ferry Disaster
Version: 1.1.4
Author: Park Yong
Author URI: http://parkyong.com
License: GPLv2 or later
Text Domain: sewol-count
Domain Path: /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

add_action( 'widgets_init', 'sewol_register_widgets' );
add_action('plugins_loaded', 'sewol_load_textdomain');
add_action( 'load-widgets.php', 'color_picker_load' );

function color_picker_load() {    
    wp_enqueue_style( 'wp-color-picker' );        
    wp_enqueue_script( 'wp-color-picker' );    
}

function sewol_load_textdomain() {
	load_plugin_textdomain( 'sewol-count', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

function sewol_register_widgets() {
	register_widget( 'sewol_widget' );
}

class sewol_widget extends WP_Widget {

	function sewol_widget () {
		$widget_ops = array( 'classname' => 'sewol_widget',
			'description' => __( 'Count day after Sewol Ferry Disaster', 'sewol-count' ));
		$this->WP_Widget( 'sewol_widget', __('Sewol Count Widget', 'sewol-count'), $widget_ops );
	}

	function form ( $instance ) {
		$defaults = array( 'title' => __('Sewol', 'sewol-count'),
			'fontColor' => 'yellow',
			'backGroundColor' => 'white');

		$instance = wp_parse_args( (array)$instance, $defaults );
		$title = strip_tags( $instance['title'] );
		$fontColor = strip_tags( $instance['fontColor']);
		$backGroundColor = strip_tags( $instance['backGroundColor']);
		?>
		<p>
			<script type='text/javascript'>
				( function( $ ) {
					$(document).on( 'ready widget-added widget-updated', function(event, widget) {
						var params = {
							change: function(e, ui) {
								$( e.target ).val( ui.color.toString() );
								$( e.target ).trigger('change'); // enable widget "Save" button
							},
						}
						$('.font-color-picker').not('[id*="__i__"]').wpColorPicker(params);
						$('.background-color-picker').not('[id*="__i__"]').wpColorPicker(params);
					});
				})( jQuery );
			</script>
			<?php _e('Title', 'pp-plugin' ) ?>:
			<input class="widefat" name="<?php echo $this->get_field_name('title'); ?>"
			type="text" value="<?php echo esc_attr($title); ?>" />
			<br />
			<?php _e('Font Color', 'pp-plugin' ) ?>:
			<input class="widefat font-color-picker" name="<?php echo $this->get_field_name('fontColor'); ?>"
			type="text" value="<?php echo esc_attr($fontColor); ?>" />
			<br />
			<?php _e('Background Color', 'pp-plugin' ) ?>:
			<input type="text" class="widefat background-color-picker" name="<?php echo $this->get_field_name('backGroundColor'); ?>" value="<?php echo esc_attr($backGroundColor); ?>" />
		</p>
	<?php
	}

	function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(esc_attr($new_instance['title']));
		$instance['fontColor'] = strip_tags(esc_attr($new_instance['fontColor']));
		$instance['backGroundColor'] = strip_tags(esc_attr($new_instance['backGroundColor']));
		return $instance;
	}

	function widget ( $args, $instance ) {

		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$fontColor = apply_filters('widget_fontColor', $instance['fontColor']);
		$backGroundColor = apply_filters('widget_backGroundColor', $instance['backGroundColor']);

		if ( !empty( $title ) ){
			echo $before_title . $title . $after_title;
		}

		$now = time();
		$dday = mktime(0,0,0,4,16,2014);
		$xday = ceil(($now-$dday)/(60*60*24));
		echo "<aside id='sewol' class='widget' style='background-color:$backGroundColor'>";
		echo "<p id='sewol' style='color:$fontColor;'>" . $xday . "</p>";
		echo "<a href='http://socialdisasterscommission.go.kr/page/index2.jsp' style='color:$fontColor' target='_blank'>세월호 더 알아보기</a>";
		echo "</aside>";
	}
}?>