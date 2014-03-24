<!DOCTYPE html>
<html lang="en-US"<?= (is_front_page() ? ' class="front-page"':'')?>>
	<head>
		<?="\n".header_()."\n"?>
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" />
		<!--[if IE]>
		<link href="http://cdn.ucf.edu/webcom/-/css/blueprint-ie.css" rel="stylesheet" media="screen, projection">
		<![endif]-->
		<?php if(GA_ACCOUNT or CB_UID):?>
		
		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>
			
			var GA_ACCOUNT  = '<?=GA_ACCOUNT?>';
			var _gaq        = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>
			
			var CB_UID      = '<?=CB_UID?>';
			var CB_DOMAIN   = '<?=CB_DOMAIN?>';
			<?php endif?>
			
		</script>
		<?php endif;?>
		
		<?  $post_type = get_post_type($post->ID);
			if(($stylesheet_id = get_post_meta($post->ID, $post_type.'_stylesheet', True)) !== False
				&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) { ?>
				<link rel='stylesheet' href="<?=$stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
		<script type="text/javascript">
			var THEME_STATIC_URL = '<?=THEME_STATIC_URL?>';
		</script>
		
	</head>
	<!--[if lt IE 7 ]>  <body class="ie ie6 <?=body_classes()?><?=!is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 7 ]>     <body class="ie ie7 <?=body_classes()?><?=!is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 8 ]>     <body class="ie ie8 <?=body_classes()?><?=!is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if IE 9 ]>     <body class="ie ie9 <?=body_classes()?><?=!is_front_page() ? ' subpage': ''?>"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body class="<?=body_classes()?><?=!is_front_page() ? ' subpage': ''?>"> <!--<![endif]-->
			
		<div class="alert alert-block alert-error" style="display:none;" id="browser_support">
    		<p>Your browser, !BROWSER!, is not supported by this website. Various features may work
			in unexpected ways or not at all. The following browsers are supported:
			Internet Explorer 8+, Firefox 10+, Chrome 17+, Safari 4+, Mobile Safari 5+. and Android 2.2+.</p>
    	</div>
		
		<div class="container" id="content-wrap">
			<div class="row<? if(is_front_page()):?> front-page<? endif ?>" id="header-wrap">
				<div id="header">
					<h1 class="span4"><a href="<?=bloginfo('url')?>"><?=bloginfo('name')?></a></h1>
					<div class="span8" id="<? if(is_front_page() == false):?>menu-wrap<? endif ?>">
						<?=wp_nav_menu(array(
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