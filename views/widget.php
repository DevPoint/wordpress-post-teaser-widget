<?php
/**
 * Post Teaser Widget: Default widget template
 * 
 * @since 1.0.0
 */

// Block direct requests
if (!defined('ABSPATH')) die('-1');
?>

<?php echo $args['before_widget']; ?>

<?php $post_query = $this->query_post($instance['post_type'], $instance['post_slug']); ?>
<?php if ($post_query->have_posts()) : ?>
<?php while ($post_query->have_posts()) : $post_query->the_post(); ?>

<?php if ($this->has_title($instance)) : ?>
<?php echo $args['before_title']; ?>
<a href="<?php $this->the_permalink($instance);?>" title="<?php $this->the_title_attribute($instance);?>"><?php $this->the_title($instance); ?></a>
<?php echo $args['after_title']; ?>
<?php endif; ?>
<?php if (has_post_thumbnail()) : ?>
<figure class="pt-widget-image">
<a href="<?php $this->the_permalink($instance);?>" title="<?php $this->the_title_attribute($instance);?>"><?php the_post_thumbnail('medium'); ?></a>
</figure>
<?php endif; ?>
<?php if ($this->has_teaser($instance)) : ?>
<div class="pt-widget-teaser"><?php $this->the_teaser($instance); ?></div>
<?php endif; ?>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php echo $args['after_widget']; ?>
