<?php get_header();?>
<div class="page-content" id="front_page">
	<? 
		extract(get_front_page_post());
	?>
	<div id="feature_wrap">
		<div>
			<img src="<?=$featured_image[0]?>" />
		</div>
	</div>
</div>
<?php get_footer();?>