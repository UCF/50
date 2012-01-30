if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		Webcom.slideshow($);
		Webcom.chartbeat($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		
		/* Theme Specific Code Here */
		(function() {
			
			$(window).resize(function() {
				var front_page_image = $('#front_page #feature_wrap img'),
					body_width = $(window).width(),
					body_height = $(window).height();
				
				var aspect_ratio = (front_page_image.width() / front_page_image.height());

				var new_width = Math.round(body_height * aspect_ratio);
				
				front_page_image.width((body_width > new_width ? body_width : new_width));
				
			})
			$(window).trigger('resize')
		})();
	});
}else{console.log('jQuery dependancy failed to load');}