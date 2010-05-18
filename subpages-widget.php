<?php 
/*
Plugin Name: Subpages Extended
Plugin URI: http://shailan.com/wordpress/plugins/subpages-widget
Description: A multi widget to list subpages of a page. It also comes with a [subpages] shortcode. You can read <a href="http://shailan.com/wordpress/plugins/subpages-widget#usage">how to use subpages</a> . You can find more widgets, plugins and themes at <a href="http://shailan.com">shailan.com</a>.
Version: 1.0.1
Author: Matt Say
Author URI: http://shailan.com
*/

define('SHAILAN_SP_VERSION','1.0.1');
define('SHAILAN_SP_TITLE', 'Subpages List');

/**
 * Shailan Subpages Widget Class
 */
class shailan_SubpagesWidget extends WP_Widget {
    /** constructor */
    function shailan_SubpagesWidget() {
		$widget_ops = array('classname' => 'shailan_SubpagesWidget', 'description' => __( 'Subpages list' ) );
		$this->WP_Widget('shailan-subpages-widget', __('Subpages Extended'), $widget_ops);
		$this->alt_option_name = 'widget_shailan_subpages';
		
		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array(&$this, 'styles') );		
    }
	
    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
		global $post;
	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$exclude = $instance['exclude'];
		$depth = $instance['depth'];
		
		if(is_page()){				

			//$parent = $childof;
			//if($parent==''){ $parent = $post->ID; }
			
			$parent = $post->ID;
			$children=wp_list_pages( 'echo=0&child_of=' . $parent . '&title_li=' );
		
			if ($children) {		
			?>
				  <?php echo $before_widget; ?>
					<?php if ( $title )
							echo $before_title . $title . $after_title;
					?>

				<div id="shailan-subpages-<?php echo $this->number; ?>">
					<ul class="subpages">
						<?php wp_list_pages('sort_column=menu_order&depth='.$depth.'&title_li=&child_of='.$post->ID.'&exclude='.$exclude); ?>
					</ul>
				</div> 			
				
				  <?php echo $after_widget; ?>
			<?php
			} else {
				echo "\n\t<!-- SUBPAGES : This page doesn't have any subpages. -->";
			};
		}
		
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$exclude = $instance['exclude'];
		$depth = $instance['depth'];
		
        ?>		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (won\'t be shown):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
		<p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" /></label><br /> 
		<small>Page IDs, separated by commas.</small></p>
		
		<p><label for="<?php echo $this->get_field_id('depth'); ?>"><?php _e('Depth:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('depth'); ?>" name="<?php echo $this->get_field_name('depth'); ?>" type="text" value="<?php echo $depth; ?>" /></label><br /> 
		<small>Depth of menu.</small></p>
			
<div class="widget-control-actions alignright">
<p><small><a href="http://shailan.com/wordpress/plugins/subpages-widget">Visit plugin site</a></small></p>
</div>
			
        <?php 
	}
	
	function styles($instance){
		// additional styles will be printed here.
	}

} // class shailan_SubpagesWidget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_SubpagesWidget");'));

// [bartag foo="foo-value"]
function shailan_subpages_shortcode($atts) {
	global $post;

	extract(shortcode_atts(array(
		'depth' => '3',
		'exclude' => '',
		'childof' => '',
		'exceptme' => false
	), $atts));

	$parent = $childof;
	if($parent==''){ $parent = $post->ID; }
	
	if($exceptme) { $exclude .= ','.$post->ID; }

	$children=wp_list_pages( 'echo=0&child_of=' . $parent . '&title_li=' );
	
	if ($children) {
		$subpages = '<div id="shailan-subpages-<?php echo $this->number; ?>"><ul class="subpages">';
		$subpages .= wp_list_pages('echo=0&sort_column=menu_order&depth='.$depth.'&title_li=&child_of='.$parent.'&exclude='.$exclude);
		$subpages .= '</ul></div>';
	} else {
		$subpages = '"' . get_the_title($parent) . '" doesn\'t have any sub pages.';
	}
		
	return $subpages;
}
add_shortcode('subpages', 'shailan_subpages_shortcode');