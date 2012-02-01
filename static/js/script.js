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

			// Photo Set
			$('.photoset')
				.each(function(index, photoset) {
					var photoset     = $(photoset);
					var images       = photoset.find('.images li'),
						left         = photoset.find('.left'),
						right        = photoset.find('.right'),
						page_summary = photoset.find('.pagination .pages');
					var pages        = Math.ceil(images.length / 3),
						current_page = 1;

					images.filter(':gt(2)').hide();

					function reset_navigation() {
						if(current_page == 1) {
							left.css('visibility', 'hidden');
						} else if(current_page > 1) {
							left.css('visibility', 'visible');
						}
						if(pages == 1 || current_page == pages || pages == 0) {
							right.css('visibility', 'hidden')
						} else if(pages > 1) {
							right.css('visibility', 'visible');
						}
						if(pages > 1) {
							page_summary.text('Page ' + current_page + ' of ' + pages);
						}
					}

					right
						.click(function() {
							var range_start = (current_page - 1) * 3,
								range_end   = current_page * 3;

							images
								.slice(range_start,range_end)
									.fadeOut('slow', function() {
										images.slice(range_end).fadeIn();
									});
							current_page += 1;
							reset_navigation();
						});
					
					left
						.click(function() {
							var range_end   = (current_page - 1) * 3,
								range_start = (current_page - 2) * 3;
							images
								.slice(range_end)
									.fadeOut('slow', function() {
										images.slice(range_start, range_end).fadeIn();
									});
							current_page -= 1;
							reset_navigation();
						});

					reset_navigation();
				});
		})();
	});
}else{console.log('jQuery dependancy failed to load');}