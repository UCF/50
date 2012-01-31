if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		$('#header-menu a, #front-page #post-content a').addClass('ignore-external');

		Webcom.slideshow($);
		Webcom.chartbeat($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		
		/* Theme Specific Code Here */
		(function() {
			var ie7 = false;

			if($.browser.msie && $.browser.version == '7.0') {
				ie7 = true
			}

			// Front Page Image Scaling
			$(window).resize(function() {
				var front_page_image = $('#front-page #feature-wrap img'),
					body_width = $(window).width(),
					body_height = $(window).height();
				
				if(ie7) {
					body_height = body_height * 1.5; // why? who knows
				}

				var aspect_ratio = (front_page_image.width() / front_page_image.height());

				var new_width = Math.round(body_height * aspect_ratio);
				
				front_page_image.width((body_width > new_width ? body_width : new_width));
				
			})
			$(window).trigger('resize')
		})();
	});
}else{console.log('jQuery dependancy failed to load');}