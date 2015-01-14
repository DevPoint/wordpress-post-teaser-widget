<?php
/**
 * Post Teaser Widget: Widget Admin Form Javascript
 */

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );
?>

<script type="text/javascript">
<?php $widget_id_string = $this->get_field_id(''); ?>
jQuery(document).ready(function($) {
	var test = "<?php echo $widget_id_string;?>";
}
</script>
