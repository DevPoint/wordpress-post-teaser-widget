<?php
/**
 * Post Teaser Widget: Default widget template
 * 
 * @since 1.0.0
 */

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

echo $args['before_widget'];

if ( !empty($instance['title']) ) :
echo $args['before_title'] . $instance['title'] . $args['after_title'];
endif;
?>

<figure class="pt-widget-image">
</figure>

<div class="pt-widget-teaser">
<?php echo $instance['teaser']; ?>
</div>

<?php echo $args['after_widget']; ?>
