<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	
	<div class="row page-content" id="<?php echo $post->post_name?>">
		<div class="span8">
			<article>
				<h1><?php the_title();?></h1>
				<?php the_content();?>
			</article>
		</div>
		<div id="sidebar" class="span4">
			<?php echo get_sidebar();?>
		</div>
	</div>
	<div class="push"></div>
</div><!-- .container -->

<?php get_footer();?>