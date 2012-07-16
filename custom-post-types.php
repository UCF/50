<?php

/**
 * Abstract class for defining custom post types.  
 * 
 **/
abstract class CustomPostType{
	public 
		$name           = 'custom_post_type',
		$plural_name    = 'Custom Posts',
		$singular_name  = 'Custom Post',
		$add_new_item   = 'Add New Custom Post',
		$edit_item      = 'Edit Custom Post',
		$new_item       = 'New Custom Post',
		$public         = True,  # I dunno...leave it true
		$use_title      = True,  # Title field
		$use_editor     = True,  # WYSIWYG editor, post content field
		$use_revisions  = True,  # Revisions on post content and titles
		$use_thumbnails = False, # Featured images
		$use_order      = False, # Wordpress built-in order meta data
		$use_metabox    = False, # Enable if you have custom fields to display in admin
		$use_shortcode  = False, # Auto generate a shortcode for the post type
		                         # (see also objectsToHTML and toHTML methods)
		$taxonomies     = array('post_tag'),
		$built_in       = False;
	
	
	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.  Returns an array of objects.
	 **/
	public function get_objects($options=array()){

		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options('name'),
		);
		$options = array_merge($defaults, $options);
		$objects = get_posts($options);
		return $objects;
	}
	
	
	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 **/
	public function get_objects_as_options($options=array()){
		$objects = $this->get_objects($options);
		$opt     = array();
		foreach($objects as $o){
			switch(True){
				case $this->options('use_title'):
					$opt[$o->post_title] = $o->ID;
					break;
				default:
					$opt[$o->ID] = $o->ID;
					break;
			}
		}
		return $opt;
	}
	
	
	/**
	 * Return the instances values defined by $key.
	 **/
	public function options($key){
		$vars = get_object_vars($this);
		return $vars[$key];
	}
	
	
	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 **/
	public function fields(){
		return array();
	}
	
	
	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 **/
	public function supports(){
		#Default support array
		$supports = array();
		if ($this->options('use_title')){
			$supports[] = 'title';
		}
		if ($this->options('use_order')){
			$supports[] = 'page-attributes';
		}
		if ($this->options('use_thumbnails')){
			$supports[] = 'thumbnail';
		}
		if ($this->options('use_editor')){
			$supports[] = 'editor';
		}
		if ($this->options('use_revisions')){
			$supports[] = 'revisions';
		}
		return $supports;
	}
	
	
	/**
	 * Creates labels array, defining names for admin panel.
	 **/
	public function labels(){
		return array(
			'name'          => __($this->options('plural_name')),
			'singular_name' => __($this->options('singular_name')),
			'add_new_item'  => __($this->options('add_new_item')),
			'edit_item'     => __($this->options('edit_item')),
			'new_item'      => __($this->options('new_item')),
		);
	}
	
	
	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 **/
	public function metabox(){
		if ($this->options('use_metabox')){
			return array(
				'id'       => $this->options('name').'_metabox',
				'title'    => __($this->options('singular_name').' Fields'),
				'page'     => $this->options('name'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
			);
		}
		return null;
	}
	
	
	/**
	 * Registers metaboxes defined for custom post type.
	 **/
	public function register_metaboxes(){
		if ($this->options('use_metabox')){
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
			);
		}
	}
	
	
	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 **/
	public function register(){
		$registration = array(
			'labels'     => $this->labels(),
			'supports'   => $this->supports(),
			'public'     => $this->options('public'),
			'taxonomies' => $this->options('taxonomies'),
			'_builtin'   => $this->options('built_in')
		);
		
		if ($this->options('use_order')){
			$registration = array_merge($registration, array('hierarchical' => True,));
		}
		
		register_post_type($this->options('name'), $registration);
		
		if ($this->options('use_shortcode')){
			add_shortcode($this->options('name').'-list', array($this, 'shortcode'));
		}
	}
	
	
	/**
	 * Shortcode for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 **/
	public function shortcode($attr){
		$default = array(
			'type' => $this->options('name'),
		);
		if (is_array($attr)){
			$attr = array_merge($default, $attr);
		}else{
			$attr = $default;
		}
		return sc_object_list($attr);
	}
	
	
	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}
		
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		ob_start();
		?>
		<ul class="<?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li>
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	
	
	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$html = '<a href="'.get_permalink($object->ID).'">'.$object->post_title.'</a>';
		return $html;
	}
}


class Example extends CustomPostType{
	public 
		$name           = 'example',
		$plural_name    = 'Examples',
		$singular_name  = 'Example',
		$add_new_item   = 'Add New Example',
		$edit_item      = 'Edit Example',
		$new_item       = 'New Example',
		$public         = True,
		$use_categories = True,
		$use_thumbnails = True,
		$use_editor     = True,
		$use_order      = True,
		$use_title      = True,
		$use_shortcode  = True,
		$use_metabox    = True;
	
	
	public function objectsToHTML($objects){
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		$outputs = array();
		foreach($objects as $o){
			$outputs[] = $class->toHTML($o);
		}
		
		return implode(', ', $outputs);
	}
	
	
	public function toHTML($object){
		return $object->post_title;
	}
	
	
	public function fields(){
		return array(
			array(
				'name'  => 'Helpy Help',
				'desc'  => 'Help Example, static content to assist the nice users.',
				'id'    => $this->options('name').'_help',
				'type'  => 'help',
			),
			array(
				'name' => 'Text',
				'desc' => 'Text field example',
				'id'   => $this->options('name').'_text',
				'type' => 'text',
			),
			array(
				'name' => 'Textarea',
				'desc' => 'Textarea example',
				'id'   => $this->options('name').'_textarea',
				'type' => 'textarea',
			),
			array(
				'name'    => 'Select',
				'desc'    => 'Select example',
				'default' => '(None)',
				'id'      => $this->options('name').'_select',
				'options' => array('Select One' => 1, 'Select Two' => 2,),
				'type'    => 'select',
			),
			array(
				'name'    => 'Radio',
				'desc'    => 'Radio example',
				'id'      => $this->options('name').'_radio',
				'options' => array('Key One' => 1, 'Key Two' => 2,),
				'type'    => 'radio',
			),
			array(
				'name'  => 'Checkbox',
				'desc'  => 'Checkbox example',
				'id'    => $this->options('name').'_checkbox',
				'type'  => 'checkbox',
			),
		);
	}
}

class FrontPage extends CustomPostType{
	public 
		$name           = 'frontpage',
		$plural_name    = 'Front Pages',
		$singular_name  = 'Front PAge',
		$add_new_item   = 'Add New Front Page',
		$edit_item      = 'Edit Front Page',
		$new_item       = 'New Front Page',
		$public         = True,
		$use_categories = false,
		$use_thumbnails = True,
		$use_editor     = True,
		$use_order      = false,
		$use_title      = True,
		$use_shortcode  = false,
		$use_metabox    = false;
		
}

class PhotoSet extends CustomPostType{
	public 
		$name           = 'photoset',
		$plural_name    = 'Photo Sets',
		$singular_name  = 'Photo Set',
		$add_new_item   = 'Add New Photo Set',
		$edit_item      = 'Edit Photo Set',
		$new_item       = 'New Photo Set',
		$public         = True,
		$use_categories = False,
		$use_thumbnails = True,
		$use_editor     = False,
		$use_order      = True,
		$use_title      = True,
		$use_shortcode  = True,
		$use_metabox    = False;
	
	public function get_objects($options=array()){
		//Overriden to order by menu_order
		parent::get_objects(array_merge(array('orderby'=>'menu_order'), $options));
	}

	public function objectsToHTML($objects){
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		// Photoset Navigation
		$outputs = array('<ul class="photoset-nav">');
		foreach($objects as $o){
			$outputs[] = '<li><a href="#photoset-'.$o->post_title.'">'.$o->post_title.'</a></li>';
		}
		$outputs[] = '</ul>';

		// Photosets
		/*
		foreach($objects as $o){
			// Attachemnts - Assume they are all images
			$images = get_posts(array(
				'post_type'   => 'attachment',
				'numberposts' => -1,
				'post_status' => NULL,
				'post_parent' => $o->ID,
				'orderby'     => 'menu_order',
				'order'       => 'ASC'));

			$outputs[] = '<fieldset class="photoset" id="photoset-'.$o->post_title.'">';
			$outputs[] = '<legend>'.$o->post_title.'</legend>';

			$count = 0;
			$images_html       = '';
			$description_html  = '';
			foreach($images as $image) {
				$details = wp_get_attachment_image_src($image->ID, 'large');
				if($details !== False) {
					if(($count % 3) == 0) {
						if(strlen($images_html) == 0) {
							$images_html       = '<ul class="images">';
							$description_html  = '<ul class="descriptions">';
						} else {
							$outputs[]         = $images_html.'</ul>'.$description_html.'</ul>';
							$images_html       = '<ul class="images">';
							$description_html  = '<ul class="descriptions">';
						}
					}
					$css   = ($count % 3) == 0 ? ' class="no-margin-left" ' : '';

					$images_html      .= '<li'.$css.'><a href="'.$details[0].'"><img src="'.$details[0].'" /></a></li>';
					$description_html .= '<li'.$css.'><p>'.$image->post_content.'</p></li>';
				}
				$count++;
			}
			if(strlen($images_html) != 0) {
				$outputs[] = $images_html.'</ul>'.$description_html.'</ul>';
			}
			//$outputs[] = '</div>';
			$outputs[] = '<div class="span6"><ul class="pagination"><li><a class="left" href="#">&larr;</a></li>';
			for($i = 1; $i <= ceil(count($images) / 3); $i++) {
				$outputs[] = '<li><a class="page" href="#">'.$i.'</a></li>';
			}
			$outputs[] = '<li><a class="right" href="#">&rarr;</a></li><a class="show_all" href="#">Show All</a></ul></div>';
			$outputs[] = '<div class="instructions span6">Click on an image to see it larger.</div>';
			$outputs[] = '</fieldset>';
		}


		*/
		
		
		// Photosets
		foreach($objects as $o){
			// Attachemnts - Assume they are all images
			$images = get_posts(array(
				'post_type'   => 'attachment',
				'numberposts' => -1,
				'post_status' => NULL,
				'post_parent' => $o->ID,
				'orderby'     => 'menu_order',
				'order'       => 'ASC'));

			$outputs[] = '<fieldset class="photoset" id="photoset-'.$o->post_title.'">';
			$outputs[] = '<legend>'.$o->post_title.'</legend>';

			$count = 0;
			foreach($images as $image) {
				$details = wp_get_attachment_image_src($image->ID, 'large');
				
				$css   = ($count % 3) == 0 ? ' no-margin-left' : '';
				
				$outputs[] = '<div class="span3'.$css.'">
							      <p class="photo-wrap"><a href="'.$details[0].'"><img src="'.$details[0].'" /></a></p>
								  <p class="photo-desc">'.$image->post_content.'</p>
							  </div>';
				$count++;
			}/*
			$outputs[] = '<div class="span12"><ul class="pagination"><li><a class="left" href="#">&larr;</a></li>';
			for($i = 1; $i <= ceil(count($images) / 3); $i++) {
				$outputs[] = '<li><a class="page" href="#">'.$i.'</a></li>';
			}
			$outputs[] = '<li><a class="right" href="#">&rarr;</a></li><li><a class="show_all" href="#">Show All</a></li></ul></div>';
			$outputs[] = '<div class="instructions span12">Click on an image to see it larger.</div>';*/
			$outputs[] = '</fieldset>';
		}
		

		return implode("\n", $outputs);
	}
	
	
	
	
	public function toHTML($object){
		return $object->post_title;
	}
	
}

class Story extends CustomPostType{
	public 
		$name           = 'story',
		$plural_name    = 'Stories',
		$singular_name  = 'Story',
		$add_new_item   = 'Add New Story',
		$edit_item      = 'Edit Story',
		$new_item       = 'New Story',
		$public         = True,
		$use_tags       = True,
		$use_thumbnails = False,
		$use_editor     = True,
		$use_order      = False,
		$use_title      = True,
		$use_shortcode  = True,
		$use_metabox    = True;

	public function objectsToHTML($objects){
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		$outputs = array('<ul class="stories row">');

		$o_count = 1;
		foreach($objects as $o){

			if($o_count == 4) {
				$outputs[] = '</ul><ul class="stories row">';
			}

			$outputs[] = '<li'.((($o_count - 1) % 3) == 0 ? ' class="no-margin-left span4"':' class="span4"').'>';
			$outputs[] = '<a href="'.get_permalink($o->ID).'">';
			$outputs[] = '<div class="title"><strong>'.$o->post_title.'</strong></div>';
			$outputs[] = '<div class="content">'.truncate(strip_tags($o->post_content), 40).'</div></a>';
			$outputs[] = '<ul class="tags">';

			$tags      = wp_get_post_tags($o->ID);
			$num_tags  = count($tags);
			$tag_count = 1;
			foreach($tags as $tag) {
				$outputs[] = '<li class="span4"><a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a>'.($tag_count != $num_tags ? ',':'').'</li>';
				$tag_count++;
			}
			
			$outputs[] = '</ul></li>';
			$o_count++;
		}
		$outputs[] = '</ul>';
		$outputs[] = '<a class="more-stories clear"><img src="'.get_bloginfo('stylesheet_directory').'/static/img/more.png" /><span>View More Stories</span></a>';

		return implode("\n", $outputs);
	}

	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Name',
				'desc' => '',
				'id'   => $prefix.'name',
				'type' => 'text',
			),
			array(
				'name' => 'Email',
				'desc' => '',
				'id'   => $prefix.'email',
				'type' => 'text',
			),
			array(
				'name' => 'Class Year',
				'desc' => '',
				'id'   => $prefix.'class_year',
				'type' => 'text',
			),
			array(
				'name' => 'Photo',
				'desc' => '',
				'id'   => $prefix.'photo',
				'type' => 'text',
			)
		);
	}
}

class Timeline extends CustomPostType{
	public 
		$name           = 'timeline',
		$plural_name    = 'Timelines',
		$singular_name  = 'Timeline',
		$add_new_item   = 'Add New Timeline',
		$edit_item      = 'Edit Timeline',
		$new_item       = 'New Timeline',
		$public         = True,
		$use_categories = False,
		$use_tags       = False,
		$use_thumbnails = True,
		$use_editor     = True,
		$use_order      = False,
		$use_title      = True,
		$use_shortcode  = False,
		$use_metabox    = True;
	
	
	public function objectsToHTML($objects){
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		$outputs = array();
		foreach($objects as $o){
			$outputs[] = $class->toHTML($o);
		}
		
		return implode(', ', $outputs);
	}
	
	
	public function toHTML($object){
		return $object->post_title;
	}

	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Start Year',
				'desc' => 'Format: YYYY',
				'id'   => $prefix.'start_year',
				'type' => 'text',
			)
		);
	}
}

class TimelineEvent extends CustomPostType{
	public 
		$name           = 'timeline_event',
		$plural_name    = 'Timeline Events',
		$singular_name  = 'Timeline Event',
		$add_new_item   = 'Add New Timeline Event',
		$edit_item      = 'Edit Timeline Event',
		$new_item       = 'New Timeline Event',
		$public         = True,
		$use_categories = True,
		$use_tags       = False,
		$use_thumbnails = True,
		$use_editor     = True,
		$use_order      = False,
		$use_title      = True,
		$use_shortcode  = False,
		$use_metabox    = True;
	
	
	public function objectsToHTML($objects){
		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;
		
		$outputs = array();
		foreach($objects as $o){
			$outputs[] = $class->toHTML($o);
		}
		
		return implode(', ', $outputs);
	}
	
	
	public function toHTML($object){
		return $object->post_title;
	}
	
	
	public function fields(){
		$prefix = $this->options('name').'_';
		$timeline_options = array();
		foreach(get_posts(array('post_type'=>'timeline','orderby'=>'title')) as $timeline) {
			$timeline_options[$timeline->post_title] = $timeline->ID;
		}
		return array(
			array(
				'name' => 'Start Date',
				'desc' => 'Format: YYYY,MM,DD. Day can be ommitted in needed.',
				'id'   => $prefix.'start_date',
				'type' => 'text',
			),
			array(
				'name' => 'End Date',
				'desc' => 'Format: YYYY,MM,DD. Day can be ommitted. If left blank, end date will default to the start date.',
				'id'   => $prefix.'end_date',
				'type' => 'text',
			),
			array(
				'name'    => 'Timelines',
				'desc'    => 'Which timeline should this even be associated with?',
				'default' => '(None)',
				'id'      => $prefix.'timeline',
				'options' => $timeline_options,
				'type'    => 'select',
			)
		);
	}
}
?>