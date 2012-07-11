<?php disallow_direct_load('page.php');?>
<?php get_header(); the_post();?>
	<div class="page-content" id="<?=$post->post_name?>">
		<article>
			<h1><?php the_title();?></h1>
			<?php the_content();?>
		</article>
	</div>
</div>

<?php get_footer();?>