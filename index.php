<?php get_header();?>
	<div class="page-content" id="front-page">
		<?php extract(get_front_page_post()); ?>
		<div id="feature-wrap">
			<div>
				<img src="<?=$featured_image[0]?>" />
			</div>
		</div>
		<div id="tooltip-desc"><?php $tooltip = get_post_meta($post->ID, 'frontpage_tooltip', true); print empty($tooltip) ? 'notooltip' : $tooltip; ?></div>
	</div>
</div><!--.container -->
<div id="footer">
	<div class="container">
		<div class="row">
			<div class="footer-widget-1 span6" id="post-content">
				<h2><?=$post->post_title?></h2>
				<div id="post-body">
					<?=apply_filters('the_content', $post->post_content)?>
				</div>
			</div>
			<div class="footer-widget-2 span3">
				<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?>
					&nbsp;
				<?php endif;?>
			</div>
			<div class="footer-widget-3 span3">
				<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Three')):?>
					<?php $options = get_option(THEME_OPTIONS_NAME);?>
					<?php if($options['site_contact'] or $options['organization_name']):?>
						<div class="maintained">
							Site maintained by the <br />
							<?php if($options['site_contact'] and $options['organization_name']):?>
							<a href="mailto:<?=$options['site_contact']?>"><?=$options['organization_name']?></a>
							<?php elseif($options['site_contact']):?>
							<a href="mailto:<?=$options['site_contact']?>"><?=$options['site_contact']?></a>
							<?php elseif($options['organization_name']):?>
							<?=$options['organization_name']?>
							<?php endif;?>
						</div>
						<?php endif;?>
					<div class="copyright">&copy; University of Central Florida</div>
				<?php endif;?>
			</div>
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- #footer -->
</body>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?="\n".footer_()."\n"?>
</html>
