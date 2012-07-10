<?php
require_once('functions/base.php');   			# Base theme functions
require_once('functions/feeds.php');			# Where functions related to feed data live
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.

/**
 * Get a random post from the FrontPage post type 
 *
 *
 * @author Chris Conover
 **/
function get_front_page_post() {
	$front_page_posts  = get_posts(array('numberposts'=>1,'orderby'=>'rand', 'post_type'=>'frontpage'));
	$post_thumbnail_id = get_post_thumbnail_id($front_page_posts[0]->ID);
	$featured_image    = wp_get_attachment_image_src($post_thumbnail_id,'single-post-thumbnail');
	return array(
		'post'           => $front_page_posts[0],
		'featured_image' => $featured_image);
}

/**
 * Truncates a string based on word count
 *
 * @return string
 * @author Chris Conover
 **/
function truncate($string, $word_count=30) {
	$parts = explode(' ', $string, $word_count);
	return implode(' ', array_slice($parts, 0, count($parts) - 1)).'...';
}

?>