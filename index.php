<?php get_header();?>
<div class="page-content" id="front-page">
	<? extract(get_front_page_post()); ?>
	<div id="feature-wrap">
		<div>
			<img src="<?=$featured_image[0]?>" />
		</div>
	</div>
	<div id="post-content">
		<h2 class="sans"><?=$post->post_title?></h2>
		<div id="post-body">
			<?=apply_filters('the_content', $post->post_content)?>
		</div>
	</div>
</div>
<?php get_footer();?>