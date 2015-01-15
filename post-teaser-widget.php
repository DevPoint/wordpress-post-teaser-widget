<?php
/**
 * Post Teaser Widget
 *
 * Display posts as widget items.
 *
 * @package   DPT_Post_Teaser_Widget
 * @author    Wilfried Reiter <wilfried.reiter@devpoint.at>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/extend/plugins/post-teaser-widget
 * @copyright 2015 Wilfried Reiter
 *
 * @post-teaser-widget
 * Plugin Name:       Post Teaser Widget
 * Plugin URI:        http://wordpress.org/extend/plugins/post-teaser-widget
 * Description:       An advanced posts display widget with many options: get posts by post type and taxonomy & term or by post ID; sorting & ordering; feature images; custom templates and more.
 * Version:           1.0.ß
 * Author:            willriderat
 * Author URI:        http://devpoint.at
 * Text Domain:       post-teaser-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/devpoint/post-teaser-widget
 */

/**
 * Copyright 2015  Wilfried Reiter (email: wilfried.reiter@devpoint.at)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Block direct requests
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load the widget on widgets_init
function dpt_load_post_teaser_widget() {
	register_widget('DPT_Post_Teaser_Widget');
}
add_action('widgets_init', 'dpt_load_post_teaser_widget');


/**
 * Post Teaser Widget Class
 */
class DPT_Post_Teaser_Widget extends WP_Widget {

    /**
     * Plugin version number
     *
     * The variable name is used as a unique identifier for the widget
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_version = '1.0.0';

    /**
     * Unique identifier for your widget.
     *
     * The variable name is used as a unique identifier for the widget
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'dpt-pt-widget';
    
    /**
     * Unique identifier for your widget.
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_text_domain = 'post-teaser-widget';
    
    /**
     * @since    1.0.0
     *
     * @var array - of post type objects
	 */
	protected $post_types;

    /**
     * @since    1.0.0
     *
     * @var array - of strings
	 */
	protected $thumbnail_sizes;

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() 
	{
		// load plugin text domain
		add_action('init', array($this, 'widget_textdomain'));

		// The widget constructor
		parent::__construct(
			$this->get_widget_slug(),
			__('Post Teaser', $this->get_widget_text_domain()),
			array(
				'class' => $this->get_widget_text_domain(),
				'description' => __('Teaser of a post using its featured image.', $this->get_widget_text_domain()),
			)
		);
		
		// Setup the default variables after wp is loaded
		add_action('wp_loaded', array($this, 'setup_defaults'));

		// Register admin styles and scripts
		add_action('admin_enqueue_scripts', array($this, 'register_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
	}
	
	/**
	 * Return the widget slug.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin slug variable.
	 */
	public function get_widget_slug() 
	{
		return $this->widget_slug;
	}

	/**
	 * Return the widget text domain.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin text domain variable.
	 */
	public function get_widget_text_domain() 
	{
		return $this->widget_text_domain;
	}
	
	/**
	 * Return the plugin version.
	 *
	 * @since  1.0.0
	 *
	 * @return string - Plugin version variable.
	 */
	public function get_plugin_version() 
	{
		return $this->plugin_version;
	}


	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/
	
	/**
	 * Outputs the content of the widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param  array $args - The array of form elements
	 * @param  array $instance - The current instance of the widget
	 * @return void
	 */
	public function widget($args, $instance) 
	{
		$instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$instance['post_type'] = apply_filters($this->get_widget_slug() . '_post_type', $instance['post_type'], $args, $instance);
		$instance['post_slug'] = apply_filters($this->get_widget_slug() . '_post_slug', $instance['post_slug'], $args, $instance);
		$instance['teaser'] = apply_filters('widget_text', $instance['teaser'], $args, $instance);
		$instance['thumbnail_pos'] = apply_filters($this->get_widget_slug() . '_thumbnail_pos', $instance['thumbnail_pos'], $args, $instance);
		include ($this->get_template('widget', $instance['post_type'], $instance['post_slug'], $instance['template']));
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param  array $new_instance - Values just sent to be saved.
	 * @param  array $old_instance - Previously saved values from database.
	 * @return array - Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$new_instance = wp_parse_args((array)$new_instance, self::get_defaults());
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_type'] = strip_tags($new_instance['post_type']);
		$instance['post_slug'] = strip_tags($new_instance['post_slug']);
		$instance['thumbnail_pos'] = strip_tags($new_instance['thumbnail_pos']);
		$instance['template'] = strip_tags($new_instance['template']);
		if (current_user_can('unfiltered_html')) 
		{
			$instance['teaser'] = $new_instance['teaser'];
		} 
		else 
		{
			$instance['teaser'] = wp_filter_post_kses($new_instance['teaser']);
		}
        return $instance;
    }

    /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance - Previously saved values from database.
	 */
	public function form($instance) 
	{
		include ($this->get_template('widget-admin', null, null, 'default'));
		//include ('views/widget-admin.js.php');
	}

	/**
	 * Loads theme files in appropriate hierarchy:
	 * 1. child theme 2. parent theme 3. plugin resources.
	 * Will look in the plugins/post-teaser-widget directory in a theme
	 * and the views directory in the plugin
	 *
	 * Based on a function in the amazing image-widget
	 * by Matt Wiebe at Modern Tribe, Inc.
	 * http://wordpress.org/extend/plugins/image-widget/
	 * 
	 * @param  string $template - template file to search for
	 * @param  string $post_type
	 * @param  string $post_slug
	 * @return string - with template path
	 **/
	protected function get_template($template, $post_type, $post_slug, $custom_template) 
	{
		// whether or not .php was added
		$template_slug = rtrim($template, '.php');
		$template = $template_slug . '.php';
		
		// set to the default
		$file = 'views/' . $template;

		// look for a custom version
		$widgetThemeTemplates = array();
		$widgetThemePath = 'plugins/' . $this->get_widget_text_domain() . '/';
		if (!empty($custom_template) && $custom_template != 'default')
		{
			$custom_template_slug = rtrim($custom_template, '.php');
			$widgetThemeTemplates[] = $widgetThemePath . $custom_template_slug . '.php';
		}
		if (!empty($post_type) && $post_type == 'page' && !empty($post_slug))
		{
			$page_name = str_replace(array('/', '\\'), '-', $post_slug);
			$widgetThemeTemplates[] = $widgetThemePath . $template_slug . '-' . $page_name . '.php';
		}
		if (!empty($post_type) && $post_type != 'post')
		{
			$widgetThemeTemplates[] = $widgetThemePath . $template_slug . '-' . $post_type . '.php';
		}
		$widgetThemeTemplates[] = $widgetThemePath . $template;
		if ($theme_file = locate_template($widgetThemeTemplates))
		{
			$file = $theme_file;
		}
		
		return apply_filters($this->get_widget_slug() . '_template_' . $template_slug, $file);
	}

	/**
	 * @return array - with default values
	 */
	private static function get_defaults() 
	{
		$defaults = array(
			'title' => '',
			'teaser' => '',
			'post_type' => 'post',
			'post_slug' => '',
			'thumbnail_pos' => 'middle',
			'template' => 'default'
		);
		return $defaults;
	}

	/*--------------------------------------------------*/
	/* Template functions
	/*--------------------------------------------------*/

	/**
	 * Query posts by slug
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return WP_Query
	 */
	public function query_post(&$instance)
	{
		$slugKey = null;
		$slug = $instance['post_slug'];
		$args = array('post_type' => $instance['post_type']);
		if ($instance['post_type'] == 'page')
		{
			$slugKey = is_numeric($slug) ? 'page_id' : 'pagename';
		}
		else
		{
			$slugKey = is_numeric($slug) ? 'p' : 'name';
		}
		if (!empty($slugKey))
		{
			$args[$slugKey] = $slug;
		}
		return new WP_Query($args);
	}

	/**
	 * Check for widget title
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_title(&$instance)
	{
		return !empty($instance['title']);
	}

	/**
	 * Print widget title
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_title(&$instance)
	{
		echo $instance['title'];
	}

	/**
	 * Print widget title attribute
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_title_attribute(&$instance)
	{
		the_title_attribute();
	}

	/**
	 * Check for widget teaser
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return bool
	 */
	public function has_teaser(&$instance)
	{
		return empty($instance['template']) && !empty($instance['teaser']);
	}

	/**
	 * Print widget teaser
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_teaser(&$instance)
	{
		echo $instance['teaser'];
	}

	/**
	 * Print widget permalink
	 *
     * @since  1.0.0
     *
     * @param  array $instance 
	 * @return void
	 */
	public function the_permalink(&$instance)
	{
		the_permalink();
	}

	/**
	 * Compare with widget thumbnail position
	 *
     * @since  1.0.0
     *
     * @param  array  $instance 
     * @param  string $position
	 * @return bool
	 */
	public function is_thumbnail_pos(&$instance, $position)
	{
		return ($position == (!empty($instance['thumbnail_pos']) ? $instance['thumbnail_pos'] : 'middle'));
	}

	/**
	 * Retrieve available widget thumbnail position
	 *
     * @since  1.0.0
     *
     * @return array - with thumb_pos[name,label]
	 */
	public function get_thumbnail_pos_list()
	{
		$thumbnail_positions = array(
			array('name' => 'top', 'label' => 'top'),
			array('name' => 'middle', 'label' => 'middle'),
			array('name' => 'hidden', 'label' => '[hidden]'));
		$thumbnail_positions = apply_filters($this->get_widget_slug() . '_thumbnail_pos_list', $thumbnail_positions);
		return $thumbnail_positions;
	}

	/**
	 * Compare with widget thumbnail template
	 *
     * @since  1.0.0
     *
     * @param  array  $instance 
     * @param  string $template
	 * @return bool
	 */
	public function is_template(&$instance, $template)
	{
		return ($template == (!empty($instance['template']) ? $instance['template'] : 'default'));
	}

	/**
	 * Retrieve list with custum widget templates
	 *
     * @since  1.0.0
     *
     * @return array - with custom_template[name,label]
	 */
	public function get_custom_template_list()
	{
		$custom_templates = array();
		$custom_templates = apply_filters($this->get_widget_slug() . '_template_list', $custom_templates);
		return $custom_templates;
	}

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 *
     * @since  1.0.0
     *
     * @return void
 	 */
 	public function widget_textdomain() 
	{
		load_plugin_textdomain( $this->get_widget_text_domain(), false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Registers and enqueues admin-specific styles.
	 *
     * @since  1.0.0
     *
     * @return void
 	 */
	public function register_admin_styles($hook) 
	{
		if ('widgets.php' == $hook) 
		{
			wp_enqueue_style(
				$this->get_widget_slug() . '-admin',
				plugins_url('css/admin.css', __FILE__),
				array(),
				$this->get_plugin_version()
			);
    	}
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 *
     * @since  1.0.0
     *
     * @param  string $hook
     * @return void
 	 */
	public function register_admin_scripts($hook) 
	{
		if ('widgets.php' == $hook) 
		{
			$source = 'js/admin.min.js';
			if (SCRIPT_DEBUG) 
			{
				$source = 'js/admin.js';
			}
			wp_enqueue_script(
				$this->get_widget_slug() . '-admin',
				plugins_url( $source, __FILE__ ),
				array(),
				$this->get_plugin_version(),
				true
			);
		}
	}

	/**
     * Setup a number of default variables used throughout the plugin
     *
     * @since  1.0.0
     *
     * @return void
     */
	public function setup_defaults() 
	{
		// get the registered post types
		$this->post_types = get_post_types(array('public' => true), 'objects');
		
		// get the registered image sizes
		$this->thumbnail_sizes = get_intermediate_image_sizes();
	}
}

