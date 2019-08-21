<?php get_header(); the_post(); ?>
<?php $query = isset( $_GET['s'] ) ? $_GET['s'] : '';
?>
	<div class="row page-content" id="search-results">
		<div class="span9">
			<article>
				<h1>Search Results</h1>
				<?php if(have_posts()):?>
					<ul class="result-list">
					<?php while(have_posts()): the_post();?>
						<li class="item">
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
							<a href="<?php the_permalink();?>"><?php the_permalink();?></a>
							<div class="snippet">
								<?php the_excerpt();?>
							</div>
						</li>
					<?php endwhile;?>
					</ul>
				<?php else:?>
					<p>No results found for "<?php echo htmlentities( $query )?>".</p>
				<?php endif;?>
			</article>
		</div>

		<div id="sidebar" class="span3">
			<?php echo get_sidebar();?>
		</div>
	</div>
	<div class="push"></div>
</div>
<?php get_footer();?>
