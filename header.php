<!DOCTYPE html>
<html lang="en-US"<?= (is_front_page() ? ' class="front-page"':'')?>>
	<head>
		<?="\n".header_()."\n"?>
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
		
	</head>
	<!--[if lt IE 7 ]>  <body class="ie ie6 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 7 ]>     <body class="ie ie7 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 8 ]>     <body class="ie ie8 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 9 ]>     <body class="ie ie9 <?=body_classes()?>"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body class="<?=body_classes()?>"> <!--<![endif]-->
		<? get_template_part('includes/blackbar'); ?>
		<div id="blueprint-container" class="container">
			<div id="header-wrap" class="clearfix<? if(is_front_page()):?> front-page<? endif ?>">
				<div id="header" class="span-24 last">
					<h1 class="span-5 sans"><a href="<?=bloginfo('url')?>"><?=bloginfo('name')?></a></h1>
					<div class="span-19 last">
					<?=get_menu('header-menu', 'menu horizontal', 'header-menu')?>
					</div>
				</div>
			</div>