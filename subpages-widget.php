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
		$widget_ops = array('classname' => 'shailan_SubpagesWidget', 'description' => __( 'Subpages list', 'subpages-extended' ) );
		$this->WP_Widget('shailan-subpages-widget', __('Subpages Extended', 'subpages-extended'), $widget_ops);
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

function subpages_widget_adminMenu(){

	if(is_admin()){ 
		wp_enqueue_script( 'jquery' );
		//wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( 'tweetable', WP_PLUGIN_URL . '/subpages-extended/js/jquery.tweetable.js', 'jquery' );
		wp_enqueue_style( 'tweetable', WP_PLUGIN_URL . '/subpages-extended/css/tweetable.css' );
	};


	if ( @$_GET['page'] == 'subpages-extended' ) {
		if ( @$_REQUEST['action'] && 'save' == $_REQUEST['action'] ) {
			//update_option( 'shailan_adsense_id', $_REQUEST['shailan_adsense_id'] );
		}
	}

	if (function_exists('add_options_page')) {
			$page = add_options_page(__('Subpages Options', 'subpages-extended') , __('Adsense Widget', 'subpages-extended'), 'edit_themes', 'subpages-extended', 'subpages_widget_options_page');
	}
}
// add admin menu
add_action('admin_menu', 'subpages_widget_adminMenu');

function subpages_widget_options_page(){

	$title = "Subpages Extended Options";
	?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<div class="nav"><small><a href="http://shailan.com/wordpress/plugins/subpages-widget">Plugin page</a> | <a href="http://shailan.com/wordpress/plugins/subpages-widget/help">Usage</a> | <a href="http://shailan.com/wordpress/plugins/subpages-widget/shortcode">Shortcode</a> | <a href="http://shailan.com/donate">Donate</a> | <a href="http://shailan.com/wordpress">Get more widgets..</a></small></div>

<?php if ( isset($_GET['message']) && isset($messages[$_GET['message']]) ) { ?>
<div id="message" class="updated"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php } ?>
<?php if ( isset($_GET['error']) && isset($errors[$_GET['error']]) ) { ?>
<div id="message" class="error"><p><?php echo $errors[$_GET['error']]; ?></p></div>
<?php } ?>

<form id="frmShailanDm" name="frmShailanDm" method="post" action="">

<table class="form-table"> 
<tr valign="top"> 
<th scope="row"><label for="shailan_adsense_id"><?php _e('Adsense ID:'); ?></label></th> 
<td><input name="shailan_adsense_id" id="shailan_adsense_id" type="text" value="<?php if ( get_option( 'shailan_adsense_id' ) != "") { echo stripslashes(get_option( 'shailan_adsense_id' )); } else { echo ''; } ?>" size="44" />
<span class="description">Your unique Adsense ID.</span></td> 
</tr> 
</table>

<input type="hidden" name="action" value="save" />

<p class="submit"> 
<input type="submit" name="Submit" class="button-primary" value="Save Changes" /> 
</p> 
 
</form>

<table><tr><td width="400" valign="top">
<div id="shailancom" style="background:#ededed; border-top:10px solid #ff9966; padding:15px;">
<h3>Latest headlines from Shailan.com</h3>
		<?php	
			
			$rss_options = array(
				'link' => 'http://shailan.com',
				'url' => 'http://feeds.feedburner.com/shailan',
				'title' => 'Shailan.com',
				'items' => 5,
				'show_summary' => 0,
				'show_author' => 0,
				'show_date' => 0,
				'before' => 'text'
			);

			wp_widget_rss_output( $rss_options ); ?>
</div>
</td><td width="400" valign="top">
<div id="twit" style="background:#ededed; border-top:10px solid #68d8fd; padding:15px;">
<h3>My latest tweets</h3>
<div id="tweets">
</div>
</div>
</td></tr>
</table>

<script type="text/javascript"> 
jQuery(document).ready(function($) {
	$('#tweets').tweetable({username: 'mattsay', time: false, limit: 2, replies: false});
});
</script> 

<p>
<small><a href="http://shailan.com/wordpress/plugins/subpages-widget">Subpages Extended</a> by <a href="http://shailan.com">shailan</a>.</small>
</p>

</div>

<?php
}

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