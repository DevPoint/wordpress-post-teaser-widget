<?php
/**
 * Post Teaser Widget
 *
 * Display posts as widget items.
 *
 * @package   DPT_Post_Teaser_Widget
 * @author    Wilfried Reiter <wilfried.reiter@devpoint.at>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/extend/plugins/flexible-posts-widget
 * @copyright 2015 Wilfried Reiter
 *
 * @post-teaser-widget
 * Plugin Name:       Post Teaser Widget
 * Plugin URI:        http://wordpress.org/extend/plugins/flexible-posts-widget
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
    protected $widget_slug = 'dpt_pt_widget';
    
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
     * @var array - of strings
	 */
	protected $post_types;

    /**
     * @since    1.0.0
     *
     * @var array - of strings
	 */
	protected $post_types_names;

    /**
     * @since    1.0.0
     *
     * @var array - of strings
	 */
	protected $thumb_sizes;


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
		add_action( 'init', array( $this, 'widget_textdomain' ) );

		// The widget contrstructor
		parent::__construct(
			$this->get_widget_slug(),
			__('Post Teaser Widget', $this->get_widget_text_domain()),
			array(
				//'classname'   => $this->get_widget_slug(),
				'teaser' => __('Display posts as widget items.', $this->get_widget_text_domain()),
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
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_widget_slug() 
	{
		return $this->widget_slug;
	}

	/**
	 * Return the widget text domain.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin text domain variable.
	 */
	public function get_widget_text_domain() 
	{
		return $this->widget_text_domain;
	}
	
	/**
	 * Return the plugin version.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin version variable.
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
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget($args, $instance) 
	{
		$instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$instance['post_alias'] = apply_filters($this->get_widget_slug() . '_post_alias', $instance['post_alias'], $args, $instance);
		$instance['teaser'] = apply_filters('widget_text', $instance['teaser'], $args, $instance);
		include ($this->get_template('widget', 'none'));
    }

    /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) 
	{
		$instance = $old_instance;
		$new_instance = wp_parse_args((array)$new_instance, self::get_defaults());
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_alias'] = strip_tags($new_instance['post_alias']);
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
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) 
	{
		include ($this->get_template('widget-admin', 'none'));
	}

	/**
	 * Loads theme files in appropriate hierarchy:
	 * 1. child theme 2. parent theme 3. plugin resources.
	 * Will look in the flexible-posts-widget/ directory in a theme
	 * and the views/ directory in the plugin
	 *
	 * Based on a function in the amazing image-widget
	 * by Matt Wiebe at Modern Tribe, Inc.
	 * http://wordpress.org/extend/plugins/image-widget/
	 * 
	 * @param  string $template template file to search for
	 * @param  string $post_type null|'none'|{post_type}
	 * @return string - with template path
	 **/
	public function get_template($template, $post_type=null) 
	{
		// whether or not .php was added
		$template_slug = rtrim($template, '.php');
		$template = $template_slug . '.php';
		
		// set to the default
		$file = 'views/' . $template;

		// look for a custom version
		$widgetThemePath = 'plugins/' . $this->get_widget_text_domain() . '/';
		$widgetThemeTemplates = array($widgetThemePath . $template);
		if (!empty($post_type) && $post_type != 'none')
		{
			$widgetThemeTemplates[] = $widgetThemePath . $template_slug . '-' . $post_type . '.php';
		}
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
			'title' => ''
		);
		return $defaults;
	}

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() 
	{
		load_plugin_textdomain( $this->get_widget_text_domain(), false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() 
	{
		wp_enqueue_style(
			$this->get_widget_slug() . '-admin',
			plugins_url( 'css/admin.css', __FILE__ ),
			array(),
			$this->get_plugin_version()
		);
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() 
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
	
	/**
     * Setup a number of default variables used throughout the plugin
     *
     * Since 3.3.1
     *
     */
	public function setup_defaults() {
		
		// get the registered post types
		$this->post_types = get_post_types(array('public' => true), 'objects');
		$this->post_types_names  = get_post_types(array('public' => true), 'names');
		
		// get the registered image sizes
		$this->thumb_sizes = get_intermediate_image_sizes();
	}
}

