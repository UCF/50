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
			// Adapted from polanski.co
			$(window)
				.resize(function() {
					var front_page_image = $('#front-page #feature-wrap img'),
						window_width     = $(window).width(),
						window_height    = $(window).height();
					
					if(ie7) {
						window_height = window_height * 1.5; // why? who knows
					}

					var aspect_ratio = front_page_image.width() / front_page_image.height();

					var target_width = Math.round(window_height * aspect_ratio);
					
					front_page_image.width((window_width > target_width ? window_width : target_width));
				})
				.load(function() { 
					// $(window).load waits for the images to be ready
					// but $(document).load does not
					// If the image isn't all the way loaded, jQuery
					// will report 0 for the height and width
					$(this).trigger('resize');
				});

			// Photo Set
			$('.photoset')
				.each(function(index, photoset) {
					var photoset     = $(photoset);
					var images       = photoset.find('.images li'),
						left         = photoset.find('.left'),
						right        = photoset.find('.right'),
						in_progress  = false;
					var pages        = Math.ceil(images.length / 3),
						current_page = 1;


					// Lightbox
					images.find('a').lightBox({
						imageLoading  : THEME_STATIC_URL + '/img/jquery-lightbox/lightbox-ico-loading.gif',
						imageBtnClose : THEME_STATIC_URL + '/img/jquery-lightbox/lightbox-btn-close.gif',
						imageBtnPrev  : THEME_STATIC_URL + '/img/jquery-lightbox/lightbox-btn-prev.gif',
						imageBtnNext  : THEME_STATIC_URL + '/img/jquery-lightbox/lightbox-btn-next.gif'
					});

					// Hide images on pages greater than 1
					images.filter(':gt(2)').hide();

					// Hide pagination if there is only 1 page
					if(pages == 1) {
						photoset.find('.pagination,#show_all').css('visibility', 'hidden');
					}

					// Set first page as active
					photoset.find('.page:first').addClass('active');

					// Hide pages greater than 3. They'll be rotated in on click
					photoset.find('.page:gt(2)').hide();

					function activate_current_page() {
						photoset
							.find('.page').removeClass('active').end()
							.find('.page:eq(' + (current_page - 1) + ')').addClass('active');
					}

					right
						.click(function(event) {
							event.preventDefault();
							if(!in_progress && current_page != pages) {
								in_progress = true;
								var range_start = (current_page - 1) * 3,
									range_end   = current_page * 3;
								images
									.slice(range_start,range_end)
										.fadeOut('slow', function() {
											images
												.slice(range_end, range_end + 3)
													.fadeIn('slow', function() {
														in_progress = false;
													});
										});
								current_page += 1;
								activate_current_page();
								if( (current_page % 3) == 0 && current_page != pages) {
									photoset
										.find('.page:visible:first').hide().end()
										.find('.page:eq('+ current_page + ')').show();
								}
							}
						});
					left
						.click(function(event) {
							event.preventDefault();
							if(!in_progress && current_page != 1) {
								in_progress = true;
								var range_end   = (current_page - 1) * 3,
									range_start = (current_page - 2) * 3;
								images
									.slice(range_end, range_end + 3)
										.fadeOut('slow', function() {
											images
												.slice(range_start, range_end)
													.fadeIn('slow', function() {
														in_progress = false;
													});
										});
								current_page -= 1;
								activate_current_page();
								if( ((current_page + 1) % 3) == 0 && current_page != 1) {
									photoset
										.find('.page:visible:last').hide().end()
										.find('.page:eq('+ (current_page - 2) + ')').show();
								}
							}
						});
				});

				// Hide post body until the More button is clicked
				var post_body = $('#front-page #post-body');
				post_body.hide();
				$('#more')
					.click(function() {
						post_body.show();
						$(this).hide();
					});

				// Hide stories until `View More Stories` is clicked
				$('.stories > li:gt(5)').hide();
				$('.more-stories')
					.click(function(event) {
						event.preventDefault();
						$(this).prev('ul').find('li').show();
						$(this).hide();
					});
		})();
	});
}else{console.log('jQuery dependancy failed to load');}