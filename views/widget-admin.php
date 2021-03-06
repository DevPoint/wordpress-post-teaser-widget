<?php
/**
 * Post Teaser Widget: Widget Admin Form 
 * 
 * @since 1.0.0
 */

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );
?>

<p>
	<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $instance['title'];?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('post_type');?>"><?php _e('Post Type:', $this->get_widget_text_domain()); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id('post_type');?>" name="<?php echo $this->get_field_name('post_type');?>">
	<?php foreach ($this->post_types as $post_type) : ?>
	<?php $selected_str = ($post_type->name == $instance['post_type']) ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $post_type->name;?>"<?php echo $selected_str;?> ><?php echo $post_type->label;?></option>
	<?php endforeach; ?>
	</select>
</p>
<p>
	<label for="<?php echo $this->get_field_id('post_slug');?>"><?php _e('Permalink:', $this->get_widget_text_domain()); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id('post_slug'); ?>" name="<?php echo $this->get_field_name('post_slug');?>" type="text" value="<?php echo $instance['post_slug'];?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('teaser');?>"><?php _e('Teaser:', $this->get_widget_text_domain()); ?></label> 
	<textarea class="widefat" id="<?php echo $this->get_field_id('teaser');?>" name="<?php echo $this->get_field_name('teaser');?>" cols="20" rows="4" ><?php echo format_to_edit($instance['teaser']); ?></textarea>
</p>
<p>
	<label for="<?php echo $this->get_field_id('thumbnail_pos');?>"><?php _e('Image Position:', $this->get_widget_text_domain()); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id('thumbnail_pos');?>" name="<?php echo $this->get_field_name('thumbnail_pos');?>">
	<?php foreach ($this->get_thumbnail_pos_list() as $thumbnail_pos) : ?>
	<?php $selected_str = ($this->is_thumbnail_pos($instance, $thumbnail_pos['name'])) ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $thumbnail_pos['name'];?>"<?php echo $selected_str;?> ><?php echo $thumbnail_pos['label'];?></option>
	<?php endforeach; ?>
	</select>
</p>
<?php $custom_template_list = $this->get_custom_template_list(); ?>
<?php if (!empty($custom_template_list)) : ?>
<p>
	<label for="<?php echo $this->get_field_id('template');?>"><?php _e('Template:', $this->get_widget_text_domain()); ?></label> 
	<select class="widefat" id="<?php echo $this->get_field_id('template');?>" name="<?php echo $this->get_field_name('template');?>">
	<?php $selected_str = ($this->is_template($instance, 'default')) ? ' selected="selected"' : ''; ?>
	<option value="default"<?php echo $selected_str;?> ><?php _e('default', $this->get_widget_text_domain()); ?></option>
	<?php foreach ($custom_template_list as $template) : ?>
	<?php $selected_str = ($this->is_template($instance, $template['name'])) ? ' selected="selected"' : ''; ?>
	<option value="<?php echo $template['name'];?>"<?php echo $selected_str;?> ><?php echo $template['label'];?></option>
	<?php endforeach; ?>
	</select>
</p>
<?php else : ?>
<input id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template');?>" type="hidden" value="default" />
<?php endif; ?>
