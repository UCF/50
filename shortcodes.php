<?php

/**
 * Create a javascript slideshow of each top level element in the
 * shortcode.  All attributes are optional, but may default to less than ideal
 * values.  Available attributes:
 *
 * height     => css height of the outputted slideshow, ex. height="100px"
 * width      => css width of the outputted slideshow, ex. width="100%"
 * transition => length of transition in milliseconds, ex. transition="1000"
 * cycle      => length of each cycle in milliseconds, ex cycle="5000"
 * animation  => The animation type, one of: 'slide' or 'fade'
 *
 * Example:
 * [slideshow height="500px" transition="500" cycle="2000"]
 * <img src="http://some.image.com" .../>
 * <div class="robots">Robots are coming!</div>
 * <p>I'm a slide!</p>
 * [/slideshow]
 **/
function sc_slideshow($attr, $content=null){
	$content = cleanup(str_replace('<br />', '', $content));
	$content = DOMDocument::loadHTML($content);
	$html    = $content->childNodes->item(1);
	$body    = $html->childNodes->item(0);
	$content = $body->childNodes;

	# Find top level elements and add appropriate class
	$items = array();
	foreach($content as $item){
		if ($item->nodeName != '#text'){
			$classes   = explode(' ', $item->getAttribute('class'));
			$classes[] = 'slide';
			$item->setAttribute('class', implode(' ', $classes));
			$items[] = $item->ownerDocument->saveXML($item);
		}
	}

	$animation = ($attr['animation']) ? $attr['animation'] : 'slide';
	$height    = ($attr['height']) ? $attr['height'] : '100px';
	$width     = ($attr['width']) ? $attr['width'] : '100%';
	$tran_len  = ($attr['transition']) ? $attr['transition'] : 1000;
	$cycle_len = ($attr['cycle']) ? $attr['cycle'] : 5000;

	ob_start();
	?>
	<div
		class="slideshow <?php echo $animation?>"
		data-tranlen="<?php echo $tran_len?>"
		data-cyclelen="<?php echo $cycle_len?>"
		style="height: <?php echo $height?>; width: <?php echo $width?>;"
	>
		<?php foreach($items as $item):?>
		<?php echo $item?>
		<?php endforeach;?>
	</div>
	<?php 	$html = ob_get_clean();

	return $html;
}
add_shortcode('slideshow', 'sc_slideshow');



/**
 * Returns HTML for WordPress search form
 *
 * @author Chris Conover
 * @return string
 **/
function sc_search_form($search_post_type = '') {
	ob_start();?>
	<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' )?>">
		<div>
			<label for="s">Search:</label>
			<input type="text" value="<?php echo isset( $_GET['s'] ) ? htmlentities( $_GET['s'] ) : ''; ?>" name="s" class="search-field" id="s" placeholder="Search" />
			<button type="submit" class="search-submit">Search</button>
			<?php if($search_post_type != '') { ?>
			<input type="hidden" name="post_type" value="<?php echo $search_post_type?>" />
			<?php } ?>
		</div>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode('search-form', 'sc_search_form');


function sc_person_picture_list($atts) {
	$atts['type']	= ($atts['type']) ? $atts['type'] : null;
	$row_size 		= ($atts['row_size']) ? (intval($atts['row_size'])) : 5;
	$categories		= ($atts['categories']) ? $atts['categories'] : null;
	$org_groups		= ($atts['org_groups']) ? $atts['org_groups'] : null;
	$limit			= ($atts['limit']) ? (intval($atts['limit'])) : -1;
	$join			= ($atts['join']) ? $atts['join'] : 'or';
	$people 		= sc_object_list(
						array(
							'type' => 'person',
							'limit' => $limit,
							'join' => $join,
							'categories' => $categories,
							'org_groups' => $org_groups
						),
						array(
							'objects_only' => True,
						));

	ob_start();

	?><div class="person-picture-list"><?php
	$count = 0;
	foreach($people as $person) {

		$image_url = get_featured_image_url($person->ID);

		$link = ($person->post_content != '') ? True : False;
		if( ($count % $row_size) == 0) {
			if($count > 0) {
				?></div><?php
			}
			?><div class="row"><?php
		}

		?>
		<div class="span2 person-picture-wrap">
			<?php if($link) {?><a href="<?php echo get_permalink($person->ID)?>"><?php } ?>
				<img src="<?php echo $image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
				<div class="name"><?php echo Person::get_name($person)?></div>
				<div class="title"><?php echo get_post_meta($person->ID, 'person_jobtitle', True)?></div>
				<?php if($link) {?></a><?php }?>
		</div>
		<?php
		$count++;
	}
	?>	</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('person-picture-list', 'sc_person_picture_list');


/**
 * Include the defined publication, referenced by pub title:
 *
 *     [publication name="Where are the robots Magazine"]
 **/
function sc_publication($attr, $content=null){
	$pub_name = @$attr['name'];
	$pub_id   = @$attr['id'];

	if (!$pub_name and is_numeric($pub_id)){
		$pub = get_post($pub_id);
	}
	if (!$pub_id and $pub_name){
		$pub = get_page_by_title($pub_name, OBJECT, 'publication');
	}

	$iframe = get_publication_iframe($pub->ID);
	// If a featured image is set, use it; otherwise, get the thumbnail from issuu
	$thumb = (get_the_post_thumbnail($pub->ID, 'publication_thumb', TRUE) !== '') ? get_the_post_thumbnail($pub->ID, 'publication_thumb', TRUE) : get_publication_thumb($pub->ID);

	ob_start(); ?>

	<div class="pub">
		<a class="track pub-track" title="<?php echo $pub->post_title?>" data-toggle="modal" href="#pub-modal-<?php echo $pub->ID?>">
			<?php echo $thumb?><span><?php echo $pub->post_title?></span>
		</a>
		<p class="pub-desc"><?php echo $pub->post_content?></p>
		<div class="modal hide fade" id="pub-modal-<?php echo $pub->ID?>" role="dialog" aria-labelledby="<?php echo $pub->post_title?>" aria-hidden="true">
			<?php echo $iframe?>
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
	</div>

	<?php 	return ob_get_clean();
}
add_shortcode('publication', 'sc_publication');



?>
