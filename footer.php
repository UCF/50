		<div id="footer">	
			<div class="container">
				<div class="row">
					<div class="footer-widget-1 span2">
						<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column One')):?>
							<h2 id="footer-logo"><a href="<?php echo site_url(); ?>">UCF's 50th Anniversary</a></h2>
						<?php endif;?>
					</div>
					<div class="footer-widget-2 span6">
						<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?>
						&nbsp;
						<?php endif;?>
					</div>
					<div class="footer-widget-3 span4">
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
	</body>
</html>