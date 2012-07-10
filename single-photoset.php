<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
	
	<div class="row page-content" id="photoset-page">
		<div class="span12">
			<article>
				<h1><?php the_title();?></h1>
				<?php the_content();?>
				<ul id="images">
					<? $images = get_posts(array(
									'post_type'   => 'attachment',
									'numberposts' => -1,
									'post_status' => NULL,
									'post_parent' => $post->ID,
									'orderby'     => 'menu_order',
									'order'       => 'ASC'));
						$count = 1;
						foreach($images as $image) {
							$details = wp_get_attachment_image_src($image->ID, 'full');
							if($details !== False) {
								echo sprintf('<li><img src="%s" /><p><span class="num">%s</span>%s</p></li>', $details[0], $count, $image->post_content);
								$count++;
							}
						}
					?>
				</ul>
			</article>
		</div>
	</div>

<?php get_footer();?>