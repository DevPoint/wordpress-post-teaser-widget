<?php
/**
 * Post Teaser Widget: Widget Admin Form 
 */

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );
?>

<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('post_slug'); ?>"><?php _e('Permalink:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('post_slug'); ?>" name="<?php echo $this->get_field_name('post_slug'); ?>" type="text" value="<?php echo $instance['post_slug']; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('teaser'); ?>"><?php _e('Teaser:', $this->get_widget_text_domain()); ?></label> 
	<textarea class="widefat" id="<?php echo $this->get_field_id('teaser'); ?>" name="<?php echo $this->get_field_name('teaser'); ?>" cols="20" rows="4" ><?php echo format_to_edit($instance['teaser']); ?></textarea>
</p>
