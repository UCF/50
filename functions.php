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


/**
 * Change all gravity forms to submit to the page they are on
 **/
function change_form_action($form_tag, $form) {
	return preg_replace("/action='(.*?)'/", "action='.'", $form_tag);
}
add_filter('gform_form_tag', 'change_form_action', 10, 2);


/**
 * Get the publication's Issuu ID based on the Wordpress embed code provided by Issuu
 * @return string
 **/
function get_publication_docid($pub_id) {
	$embedcode_explode 	= preg_split("/documentId=/", get_post_meta($pub_id, 'publication_embed', TRUE)); //split up shortcode at documentid
	$embedcode_explode 	= preg_split("/ name=/", $embedcode_explode[1]); //remove the rest of the embed code, leaving the document id
	$docID 		= $embedcode_explode[0];
	return $docID;
}


/**
 * Get publication's thumbnail from Issuu based on the document ID found in the pub's embed shortcode:
 * @return string
 **/
function get_publication_thumb($pub_id) {
	$docID = get_publication_docid($pub_id);
	$pub = get_post($pub_id);
	$thumb 		= "<img src='http://image.issuu.com/".$docID."/jpg/page_1_thumb_large.jpg' alt='".$pub->post_title."' title='".$pub->post_title."' />"; 
	return $thumb;
}

/**
 * Get publication's actual iframe embed code based on the document ID found in the pub's embed shortcode:
 * @return string
 **/
function get_publication_iframe($pub_id) {
	$docID = get_publication_docid($pub_id);
	$iframe = '<div><object style="width:100%;height:100%"><param name="movie" value="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf?mode=mini&amp;backgroundColor=%23222222&amp;documentId='.$docID.'" /><param name="allowfullscreen" value="true"/><param name="menu" value="false"/><param name="wmode" value="transparent"/><embed src="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf" type="application/x-shockwave-flash" allowfullscreen="true" menu="false" wmode="transparent" style="width:100%;height:100%" flashvars="mode=mini&amp;backgroundColor=%23222222&amp;documentId='.$docID.'" /></object></div>';
	return $iframe;
}

/**
 * Add ID attribute to registered University Header script.
 **/
function add_id_to_ucfhb($url) {
    if ( (false !== strpos($url, 'bar/js/university-header.js')) || (false !== strpos($url, 'bar/js/university-header-full.js')) ) {
      remove_filter('clean_url', 'add_id_to_ucfhb', 10, 3);
      return "$url' id='ucfhb-script";
    }
    return $url;
}
add_filter('clean_url', 'add_id_to_ucfhb', 10, 3);

?>
