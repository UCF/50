<!DOCTYPE html>
<html lang="en-US"<?php echo  (is_front_page() ? ' class="front-page"':'')?>>
	<head>
		<?php echo "\n".header_()."\n"?>
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" />
		<?php if(GA_ACCOUNT or CB_UID):?>

		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>

			var GA_ACCOUNT  = '<?php echo GA_ACCOUNT?>';
			var _gaq        = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>

			var CB_UID      = '<?php echo CB_UID?>';
			var CB_DOMAIN   = '<?php echo CB_DOMAIN?>';
			<?php endif?>

		</script>
		<?php endif;?>

		<?php 		if ( isset( $post ) && $post instanceof WP_Post ):
			$post_type = get_post_type( $post->ID );
			if ( ( $stylesheet_id = get_post_meta( $post->ID, $post_type.'_stylesheet', true ) ) !== false
				&& ( $stylesheet_url = wp_get_attachment_url( $stylesheet_id ) ) !== false ) :
		?>
				<link rel='stylesheet' href="<?php echo $stylesheet_url; ?>" type='text/css' media='all' />
		<?php 			endif;
		endif;
		?>

		<script type="text/javascript">
			var THEME_STATIC_URL = '<?php echo THEME_STATIC_URL?>';
		</script>

	</head>
	<!--[if lt IE 7 ]>  <body class="ie ie6 <?php echo body_classes()?><?php echo !is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 7 ]>     <body class="ie ie7 <?php echo body_classes()?><?php echo !is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 8 ]>     <body class="ie ie8 <?php echo body_classes()?><?php echo !is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 9 ]>     <body class="ie ie9 <?php echo body_classes()?><?php echo !is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body class="<?php echo body_classes()?><?php echo !is_front_page() ? ' subpage': ''?>"> <!--<![endif]-->

		<div class="alert alert-block alert-error" style="display:none;" id="browser_support">
    		<p>Your browser, !BROWSER!, is not supported by this website. Various features may work
			in unexpected ways or not at all. The following browsers are supported:
			Internet Explorer 8+, Firefox 10+, Chrome 17+, Safari 4+, Mobile Safari 5+. and Android 2.2+.</p>
    	</div>

		<div class="container" id="content-wrap">
			<div class="row<?php if(is_front_page()):?> front-page<?php endif ?>" id="header-wrap">
				<div id="header">
					<h1 class="span4"><a href="<?php echo bloginfo('url')?>"><?php echo bloginfo('name')?></a></h1>
					<div class="span8" id="<?php if(is_front_page() == false) : ?>menu-wrap<?php endif; ?>">
						<?php echo wp_nav_menu(array(
							'theme_location' => 'header-menu',
							'container' => 'false',
							'menu_class' => 'menu '.get_header_styles(),
							'menu_id' => 'header-menu',
							'walker' => new Bootstrap_Walker_Nav_Menu()
							));
						?>
					</div>

				</div>
			</div>
