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
	<label for="<?php echo $this->get_field_id('post_alias'); ?>"><?php _e('Post Alias:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('post_alias'); ?>" name="<?php echo $this->get_field_name('post_alias'); ?>" type="text" value="<?php echo $instance['post_alias']; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('teaser'); ?>"><?php _e('Teaser:', $this->get_widget_text_domain()); ?></label> 
	<textarea class="widefat" id="<?php echo $this->get_field_id('teaser'); ?>" name="<?php echo $this->get_field_name('teaser'); ?>" cols="20" rows="4" ><?php echo format_to_edit($instance['teaser']); ?></textarea>
</p>
