<?php disallow_direct_load('tag.php');?>
<?php 	get_header();
	$tag     = get_queried_object();
	$stories = get_posts(array(
		'post_type' => 'story',
		'tax_query' => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $tag->slug
			)
		)
	));
	?>

	<div class="page-content">
		<article>
			<h1>Stories tagged with <?php echo $tag->name;?></h1>
			<ul id="tag-stories">
				<?php 	$even = True;
					foreach($stories as $story) {
						$even = ($even) ? False : True;
				?>
				<li class="<?php echo $even ? 'even' : 'odd'?>">
					<a href="<?php echo get_permalink($story->ID)?>">
						<h2><?php echo $story->post_title?></h2>
						<p>
							<?php echo truncate($story->post_content, 40);?>
						</p>
					</a>
				</li>
				<?php } ?>
			</ul>
		</article>
	</div>
	<div class="push"></div>
</div><!-- .container -->

<?php get_footer();?>
