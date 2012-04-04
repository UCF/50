<?php disallow_direct_load('single.php');?>
<?php  the_post();?>
<? 
if(isset($_GET['json'])) {

	$to_json = array(
		'timeline' => array(
			'headline'  => $post->post_title,
			'type'      => 'default',
			'startDate' => get_post_meta($post->ID, 'timeline_start_year', True),
			'text'      => $post->post_content,
			'asset'     => array(
				'media'   => '',
				'credit'  => '',
				'caption' => ''
			),
			'date' => array()
		)
	);

	// Timeline title image and caption
	if(has_post_thumbnail($post->ID)) {
		$thumbnail_id   = get_post_thumbnail_id($post->ID);
		$thumbnail_post = get_post($thumbnail_id);
		$thumbnail_src  = wp_get_attachment_image_src($thumbnail_id, 'single-post-thumbnail');
		$to_json['timeline']['asset']['media']   = $thumbnail_src[0];
		$to_json['timeline']['asset']['caption'] = $thumbnail_post->post_excerpt;
	}

	// Timeline events
	$timeline_events = get_posts(array(
		'post_type'   => 'timeline_event',
		'numberposts' => -1,
		'meta_key'    => 'timeline_event_timeline',
		'meta_value'  => $post->ID
	));
	foreach($timeline_events as $timeline_event) {
		$timeline_event_json = array(
			'startDate' => get_post_meta($timeline_event->ID, 'timeline_event_start_date', True),
			'headline'  => $timeline_event->post_title,
			'text'      => $timeline_event->post_content,
			'asset'     => array(
				'media'   => '',
				'credit'  => '',
				'caption' => ''
			)
		);

		$end_date = get_post_meta($timeline_event->ID, 'timeline_event_end_date', True);
		$timeline_event_json['endDate'] = ($end_date == '') ? $timeline_event_json['startDate'] : $end_date;

		if(has_post_thumbnail($timeline_event->ID)) {
			$event_thumbnail_id   = get_post_thumbnail_id($timeline_event->ID);
			$event_thumbnail_post = get_post($event_thumbnail_id);
			$event_thumbnail_src  = wp_get_attachment_image_src($event_thumbnail_id, 'single-post-thumbnail');
			$timeline_event_json['asset']['media']   = $event_thumbnail_src[0];
			$timeline_event_json['asset']['caption'] = $event_thumbnail_post->post_excerpt;
		}
		$to_json['timeline']['date'][] = $timeline_event_json;
	}
	header('Content-Type:application/json;');
	echo json_encode($to_json);
} else { ?>
<!DOCTYPE html>
<html>
<body>
	<div id="timeline"></div>
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
<?
}
?>