<?php /**
 * Template Name: Gala Template
 **/
?>

<?php disallow_direct_load('template-gala.php');?>
<?php get_header(); the_post();?>
	<div class="page-content" id="<?php echo $post->post_name?>">
		<h1>UCF's 50th Anniversary Celebration: A Knight to Remember</h1>
		<div class="row" id="gala-header">
			<div class="span5">
				<a href="<?php echo get_permalink(get_page_by_title('UCF\'s 50th Anniversary Celebration: A Knight to Remember'))?>"><img id="gala-logo" src="<?php echo THEME_IMG_URL?>/gala-golden-anniversary-logo.gif" alt="Golden Anniversary - A Knight to Remember" title="Golden Anniversary - A Knight to Remember" /></a>
			</div>
			<div class="span7" id="gala-header-desc">
				<?php echo apply_filters('the_content', get_page_by_title('50th Celebration Template Header')->post_content)?>
			</div>
			<div class="span12" id="hr"></div>
		</div>
		<div class="row" id="gala-body">
			<div class="span3" id="gala-sidebar">
				<?php echo wp_nav_menu( array('menu' => 'Gala Menu',) );?>
			</div>
			<div class="span9" id="gala-content">
				<article>
					<?php the_content();?>
				</article>
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>

<?php get_footer();?>
