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
?>

<?php $post_query = $this->query_post($instance['post_type'], $instance['post_slug']); ?>
<?php if ($post_query->have_posts()) : ?>
<?php while ($post_query->have_posts()) : $post_query->the_post(); ?>

<?php if ( !empty($instance['title']) ) : ?>
<?php echo $args['before_title']; ?>
<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $instance['title']; ?></a>;
<?php echo $args['after_title']; ?>
<?php endif; ?>

<?php if (has_post_thumbnail()) : ?>
<figure class="pt-widget-image">
<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('medium'); ?></a>
</figure>
<?php endif; ?>
<div class="pt-widget-teaser">
<?php echo $instance['teaser']; ?>
</div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php echo $args['after_widget']; ?>
