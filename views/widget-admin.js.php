<?php
/**
 * Post Teaser Widget: Widget Admin Form Javascript
 */

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die( '-1' );
?>

<script type="text/javascript">
(function($) {
	$("#<?php echo $this->get_field_id('teaser_invisible');?>").change(function() {
		$teaserParent = $("#<?php echo $this->get_field_id('teaser');?>").parent();
		if ($(this).is(':checked')) 
		{
			$teaserParent.slideUp();
        }
        else 
        {
			$teaserParent.slideDown();
        }	
	});
})(jQuery);	
</script>
