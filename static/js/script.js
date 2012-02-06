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

			function scale() {
				var front_page_image = $('#front-page #feature-wrap img'),
					body_width = $(window).width(),
					body_height = $(window).height();
				
				if(ie7) {
					body_height = body_height * 1.5; // why? who knows
				}

				var aspect_ratio = (front_page_image.width() / front_page_image.height());

				var new_width = Math.round(body_height * aspect_ratio);
				
				front_page_image.width((body_width > new_width ? body_width : new_width));
			}

			// Front Page Image Scaling
			$(window)
				.resize(function() {
					scale();
				})
				.load(function() { 
					// $(window).load waits for the images to be ready
					// but $(document).load does not
					scale();
				})

			// Photo Set
			$('.photoset')
				.each(function(index, photoset) {
					var photoset     = $(photoset);
					var images       = photoset.find('.images li'),
						left         = photoset.find('.left'),
						right        = photoset.find('.right'),
						page_summary = photoset.find('.pages');
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

						page_summary.empty();
						for(var i = 1; i <= pages;i++) {
							if(i == current_page) {
								page_summary.append('<a data-page="' + i + '" class="active">&bull;</a>')
							} else {
								page_summary.append('<a data-page="' + i + '">&bull;</a>')
							}
						}
						page_summary
							.find('a')
								.click(function(event) {
									event.preventDefault();
									var page_num = parseInt($(this).attr('data-page'), 10);
									if(page_num != current_page) {
										var range_start = (current_page - 1) * 3,
											range_end   = current_page * 3,
											new_range_start = (page_num - 1) * 3,
											new_range_end   = page_num * 3;
										images
											.slice(range_start,range_end)
												.fadeOut('slow', function() {
													images.slice(new_range_start, new_range_end).fadeIn()
												});
										current_page = page_num;
										reset_navigation();
									}
								});
					}

					right
						.click(function(event) {
							event.preventDefault();
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
						.click(function(event) {
							event.preventDefault();
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