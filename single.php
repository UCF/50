<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	
	<div class="row page-content" id="<?=$post->post_name?>">
		<div class="span8">
			<article>
				<h1><?php the_title();?></h1>
				<?php the_content();?>
			</article>
		</div>
		<div id="sidebar" class="span4">
			<?=get_sidebar();?>
		</div>
		
		<?php get_template_part('includes/below-the-fold'); ?>
	</div>

<?php get_footer();?>