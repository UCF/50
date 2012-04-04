		</div><!-- #blueprint-container -->
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
	<? if(isset($post) && $post->post_type == 'timeline' && !isset($_GET['json'])) { ?>
		<script type="text/javascript">
			$().ready(function() {
				var timeline = new VMM.Timeline();
				timeline.init('<?=get_permalink($post->ID);?>?json=true');
			});
		</script>
	<? } ?> 
	</body>
</html>