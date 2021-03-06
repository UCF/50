<?php @header("HTTP/1.1 404 Not found", true, 404);?>
<?php disallow_direct_load('404.php');?>

<?php get_header(); ?>
	<div class="row page-content" id="page-not-found">
		<div class="span9">
			<article>
				<h1>Page Not Found</h1>
				<?php
					$content = '';
					$page = get_page_by_title('404');
					if($page){
						$content = $page->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]>', $content);
					}
				?>
				<?php if($content):?>
				<?php echo $content?>
				<?php else:?>
				<p>The page you requested doesn't exist.  Sorry about that.</p>
				<?php endif;?>
			</article>
		</div>

		<div id="sidebar" class="span3">
			<?php echo get_sidebar();?>
		</div>
	</div>
	<div class="push"></div>
<?php get_footer();?>
