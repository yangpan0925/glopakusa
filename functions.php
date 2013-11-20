<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
function gb_setup() {
	add_theme_support( 'post-thumbnails' );	
	add_post_type_support('post','page-attributes');
}
gb_setup();
/**
 * Create custom post types
 */
function gb_create_post_types(){
  register_post_type( 'product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'product' ),
		'add_new_item' =>  __( 'Add New Product' ),
		'edit_item' =>  __( 'Edit Product' ),
		'new_item' =>  __( 'New Product' ),
		'view_item' =>  __( 'View Product' ),
		'search_items' => __( 'Search Products' ),
		'not_found' => __( 'No Products found' ),
		'not_found_in_trash' => __( 'No Products found in trash' )
      ),
      'description' => 'Post type for Glopak Products.',
      'capability_type' =>  'post',
      'public' => true,
      'exclude_from_search' => false,
      'show_in_nav_menus' => false,
	  'supports' => array(
		  'title',
		  'editor',
		  'revisions',
		  'thumbnail',
		  'author',
		  'page-attributes',
	  ),
	  'taxonomies' => array(
		'cat_product'
	  )
    )
  );
	$sponsors_labels = array(
		'name' => _x( 'Types', 'taxonomy general name' ),
		'singular_name' => _x( 'Type', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search' ),
		'all_items' => __( 'All' ),
		'parent_item' => __( 'Parent' ),
		'parent_item_colon' => __( 'Parent:' ),
		'edit_item' => __( 'Edit' ), 
		'update_item' => __( 'Update' ),
		'add_new_item' => __( 'Add' ),
		'new_item_name' => __( 'New' ),
		'menu_name' => __( 'Types' )
	);
	register_taxonomy('cat_product', 'product', array(
			'hierarchical' => true,
			'labels' => $sponsors_labels,
			'show_ui' => true,
			'query_var' => true
		)
	);
  register_post_type('contact',
    array(
      'labels' => array(
        'name' => __( 'Contacts' ),
        'singular_name' => __( 'Contact' ),
		'add_new_item' =>  __( 'Add New' ),
		'edit_item' =>  __( 'Edit' ),
		'new_item' =>  __( 'New' ),
		'view_item' =>  __( 'View' ),
		'search_items' => __( 'Search' ),
		'not_found' => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in trash' )
      ),
      'description' => 'Post type for Glopak',
	  'capability_type' =>  'post',
	  'map_meta_cap'=>true,
      'public' => true,
      'exclude_from_search' => true,
      'show_in_nav_menus' => false,
	  'supports' => array(
		  'title',
		  'editor'
	  )
    )
  );
  register_post_type('news',
    array(
      'labels' => array(
        'name' => __( 'News' ),
        'singular_name' => __( 'News' ),
		'add_new_item' =>  __( 'Add New' ),
		'edit_item' =>  __( 'Edit' ),
		'new_item' =>  __( 'New' ),
		'view_item' =>  __( 'View' ),
		'search_items' => __( 'Search' ),
		'not_found' => __( 'Nothing found' ),
		'not_found_in_trash' => __( 'Nothing found in trash' )
      ),
      'description' => 'Post type for Glopak',
	  'capability_type' =>  'post',
	  'map_meta_cap'=>true,
      'public' => true,
      'exclude_from_search' => true,
      'show_in_nav_menus' => false,
	  'supports' => array(
		  'title',
		  'editor'
	  ),
	  'rewrite'=>array('slug'=>'news')
    )
  );
}
add_action( 'init', 'gb_create_post_types' );

function tommy_add_custom_taxonomy_fields($taxonomy){
	$template = get_template_directory() . '/custom_cat_product.php';
	$html = tommy_render($template,null);
	echo $html;
}
add_action('cat_product_add_form_fields','tommy_add_custom_taxonomy_fields');

function tommy_add_custom_taxonomy($term_id){

}
add_action('create_cat_product','tommy_add_custom_taxonomy');

function tommy_edit_custom_taxonomy($tag,$taxonomy=null){
	$post_id = get_option("feature_img_". $tag->term_id,0);
	$tag_id = get_option("cat_img_" . $tag->term_id,0);
	$data = array();
	if($post_id){
		$data = get_post($post_id);
	}
	$cat = array();
	if($tag_id){
		$cat = get_post($tag_id);
	}
	$template = get_template_directory() . '/custom_cat_product.php';
	echo tommy_render($template,array('post'=>$data,'term_id'=>$tag->term_id,'cat'=>$cat));
}
add_action('cat_product_edit_form','tommy_edit_custom_taxonomy');

function tommy_update_custom_taxonomy($tt_id){
	$taxonomy = $_POST['taxonomy'];
	if($taxonomy == 'cat_product'){
		if(isset($_FILES['feature_img'])){
			$article = $_FILES['feature_img'];
			$article_name = $article['name'];
			$article_path = $article['tmp_name'];
			$wp_filetype = wp_check_filetype($article_name, null );
			$wp_upload_dir = wp_upload_dir();
			if(move_uploaded_file($article_path, $wp_upload_dir['path'] . '/' . $article_name)){
				 $attachement = array(
					'guid' => $wp_upload_dir['url'] . '/' . $article_name,
					'post_mime_type'=>$wp_filetype['type'],
					'post_title'=>preg_replace('/\.[^.]+$/', '', $article_name),
					'post_status'=>'inherit',
					'post_type'=>'attachment'
				);
				$attach_id = wp_insert_attachment($attachement,$wp_upload_dir['path'] . '/' . $article_name,$post_id);
				update_option("feature_img_" . $tt_id,$attach_id);
			}
		}
		if(isset($_FILES['cat_img'])){
			$article = $_FILES['cat_img'];
			$article_name = $article['name'];
			$article_path = $article['tmp_name'];
			$wp_filetype = wp_check_filetype($article_name, null );
			$wp_upload_dir = wp_upload_dir();
			if(move_uploaded_file($article_path, $wp_upload_dir['path'] . '/' . $article_name)){
				 $attachement = array(
					'guid' => $wp_upload_dir['url'] . '/' . $article_name,
					'post_mime_type'=>$wp_filetype['type'],
					'post_title'=>preg_replace('/\.[^.]+$/', '', $article_name),
					'post_status'=>'inherit',
					'post_type'=>'attachment'
				);
				$attach_id = wp_insert_attachment($attachement,$wp_upload_dir['path'] . '/' . $article_name,$post_id);
				update_option("cat_img_" . $tt_id,$attach_id);
			}
		}
	}
}
add_action('edit_term_taxonomy','tommy_update_custom_taxonomy');

function tommy_get_page($id){
	$pages = get_pages();
	if(!$pages){
		return null;
	}
	foreach($pages as $p){
		if($p->ID == $id){
			return $p;
		}
	}
	return null;
}
/**
***Ajax call
**/
function vote_ajax_roll(){
    die();
}
add_action('wp_ajax_roll', 'vote_ajax_roll');
add_action('wp_ajax_nopriv_roll', 'vote_ajax_roll');

function cat_ajax_remove(){
	$post_id = $_POST['post_id'];
	$term_id= $_POST['term_id'];
	delete_option("feature_img_".$term_id);
	if(wp_delete_post($post_id)){
		echo "ok";
	}
	else{
		echo "error";
	}
	exit();
	die();
}
add_action('wp_ajax_remove', 'cat_ajax_remove');
add_action('wp_ajax_nopriv_remove', 'cat_ajax_remove');
/**
 * Get feature image url of top page of current page
 * @param int $page_id current page id
 * @return string image url
 */
function tommy_get_top_page_feature($page_id){
	$pages = get_pages();
	while($parent = get_parent_page($pages,$page_id)){
		$page_id = $parent;
	}
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($page_id));
	return $feat_image;
}
/**
 * Get top page id of current page
 * @param int $page_id current page id
 * @return top page id
 */
function tommy_get_top_page($page_id){
	$pages = get_pages();
	while($parent = get_parent_page($pages,$page_id)){
		$page_id = $parent;
	}
	return $page_id;
}
/**
 * Get the parent page id
 * @param Array of Std Objects $pages all pages
 * @param int $page_id current page id
 * @return int direct parent page id
 */
function tommy_get_parent_page($pages,$page_id){
	if(!$pages){
		return null;
	}
	foreach($pages as $p){
		if($p->ID == $page_id){
			return $p->post_parent;
		}
	}
	return null;
}
function tommy_getSubString($feed,$num){
	preg_match_all("/<a.*? href=\"(.*?)\".*?>(.*?)<\/a>/i",$feed,$results);
	$words = $results[2];
	$htmls = $results[0];
	$str = strip_tags($feed);
	if(strlen($str) > $num){
		$str = substr($str,0,$num);
		$pos = strrpos($str,' ');
		$str = substr($str,0,$pos);
		$str = str_replace($words,$htmls,$str);
		return $str . '...';
	}
	return $feed;
}
/**
 * Get custom side bar
 */
function tommy_get_sidebar($name,$data = null){
	$file = get_template_directory() . '/sidebar-' . $name . '.php';
	return tommy_render($file,$data);
}
/**
 * Get feautre image of a post
 */
function tommy_get_feature_image($post_id){
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_id));
	return $feat_image;
}
function tommy_get_thumb($post_id){
	$id = get_post_thumbnail_id($post_id);
	return get_post($id);
}
/**
 * Mail function
 * @param Assoc Array $data data that to be rendered
 * @param String $subject email subject
 * @param String $template template file
 * @return boolean 
 */
function tommy_mail($to,$from,$subject,$data,$template,$add_headers=null){
	$template =  get_template_directory() . '/'.$template . '.php';
	$html = tommy_render($template,$data);
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
	$headers[] = "From:<".$from.">\r\n";
	if($add_headers){
		foreach($add_headers as $k=>$v){
			$headers[] = $k . ": " . $v . "\r\n";
		}
	}
	if(wp_mail($to,$subject,$html,$headers)){
		return true;
	}
	return false;
}
function tommy_get_top_tax($tax,$slug){
	$types = get_terms($tax,array(
						'hide_empty'=>false
	));
	$toptypes = tommy_get_tax($types,0);
	foreach($toptypes as $tt){
		$subtypes = tommy_get_tax($types,$tt->term_id);
		if($subtypes){
			foreach($subtypes as $st){
				$temp[] = $st->slug;
			}
			if(in_array($slug,$temp)){
				return $tt;
			}
		}
	}
	return null;
}
function tommy_get_related_posts($post_type,$id,$num,$tax=null,$slug=null){
	$option = array(
		'post_type'=>$post_type,
		'posts_per_page'=>-1
	);
	if($tax){
		$option[$tax] = $slug;
	}
	$posts = get_posts($option);
	foreach($posts as $p){
		$refer[$p->ID] = $p;
		if($p->ID != $id){
			$temp[] = $p->ID;
		}
	}
	if(!isset($temp)){
		return null;
	}
	$len = count($temp);
	$results = null;
	for($i=0;$i<$len&&$i<$num;$i++){
		$j = rand(0,$len-1);
		$results[] = $refer[$temp[$j]];
		$temp = array_remove_by_key($temp, $j);
		$len = count($temp);
	}
	return $results;
	
}
function array_remove_by_key($arr,$k){
	$res = null;
	foreach($arr as $i=>$a){
		if($i != $k){
			$res[] = $a;
		}
	}
	return $res;
}
/**
 * Render PHP template
 * @param String $template template absolute path
 * @param Assoc Array $data
 * @return String html
 */
function tommy_render($template,$data){
	if($data){
		foreach($data as $key=>$value){
			$$key = $value;
		}	
	}
	ob_start();
	include $template;
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
/**
 * Get formated schedule date
 * @param int $post_id post id
 * @param string $time_source time custom field name
 * @param string $date_source date custom field name
 * @return string formatted datetime
 */
function get_schedule_date($post_id,$time_source='the_sub_subtitle',$date_source='schedule_date'){
	$time = get_post_meta($post_id,$time_source,true);
	$date = get_post_meta($post_id,$date_source,true);
	if($date){
		$t = strtotime($date);
		$formated_date = date('l, F jS, Y',$t); 
	}
	else{
		$formated_date = '';
	}
	if($time != 'Subtitle'){
		$time = ' @ ' . $time; 
		return $formated_date .' ' . $time;
	}
	return $formated_date;
}
/**
 * Convert '\n' to <br>
 * @param string $content
 * @return string
 */
function tommy_the_content_filter($content){
	if(!$content){
		return $content;
	}
	$arr = explode("\n",$content);
	$num = count($arr);
	$res = '';
	for($i=0;$i<$num;$i++){
		$str = $arr[$i];
		$str = trim($str);
		if(preg_match('/^<\/?.+>$/',$str) || preg_match('/^<.+>.*<\/.+>$/',$str)){
			$res .= $str;
		}
		else{
			$res .= $str. '<br>';
		}
	}
	return $res;
}
/**
 * Get all categories of a taxonomy
 * @param string $type category type : taxonomy
 * @return Std Object
 */
function tommy_get_categories($type){
	global $wpdb;
	$terms = $wpdb->prefix . 'terms';
	$taxonomies = $wpdb->prefix . 'term_taxonomy';
	$sql = "SELECT * FROM $terms INNER JOIN $taxonomies ON {$terms}.term_id={$taxonomies}.term_id WHERE {$taxonomies}.taxonomy='$type' AND {$taxonomies}.parent=0";
	return $wpdb->get_results($sql);
}
/**
 * Get a post include category
 * @param int $post_id post id
 * @param string $taxonomy category type
 * @return Std Object
 */
function tommy_get_post($post_id,$taxonomy='category'){
	$post = get_post($post_id);
	$cats = wp_get_object_terms($post_id,$taxonomy);
	if($cats){
		$cat = $cats[0];
		$post->cat_id = (int)($cat->term_id);
		$post->category_name = $cat->name;
	}
	return $post;
}
/**
 * Get posts by category slug
 * @param string $cat_name category slug
 * @param string $taxonomy category type
 * @param string $post_type post type
 * @return array Std Objects
 */
function get_posts_by_cat($cat_name,$taxonomy='category',$post_type='post'){
	$option = array(
		'post_type' => $post_type,
		"'".$taxonomy."'" => $cat_name
	);
	$posts = get_posts($option);
	return $posts;
}
/**
 * Get direct subpages of a parent page
 * @param int $post_id page id
 * @return array Std Objects
 */
function tommy_get_subpages($post_id){
	global $wpdb;
	$sql = "SELECT ID,post_title FROM " . $wpdb->prefix . "posts WHERE post_status='publish' AND post_parent=" . $post_id;
	return $wpdb->get_results($sql);
}
function tommy_get_tax($types,$parent){
	$new_types = null;
	foreach($types as $type){
		if($type->parent == $parent){
			$new_types[] = $type;
		}
	}
	return $new_types;
}
function tommy_get_tax_by_post($tax,$post_id){
	$terms = get_the_terms($post_id,$tax);
	if(!$terms){
		return null;
	}
	foreach($terms as $t){
		$temp[] = $t->slug;
	}
	return $temp[0];
}
function gb_filter_title($posts=null,$key=null){
	if(!$posts){
		return null;
	}
	if(!$key){
		return $posts;
	}
	$new_posts = null;
	foreach($posts as $p){
		if(preg_match("/$key/i",$p->post_title)){
			$new_posts[] = $p;
		}
	}
	return $new_posts;
}
function gb_filter_cap($posts=null,$cap_min=null,$cap_max=null){
	if(!$posts){
		return null;
	}
	if(!$cap_min || !$cap_max){
		return $posts;
	}
	if($cap_min > $cap_max){
		return $posts;
	}
	$new_posts = null;
	foreach($posts as $p){
		$cap = get_post_meta($p->ID,'product_capacity',true);
		if(!$cap){
			continue;
		}
		else{
			$arr = explode("-",$cap);
			$min = (int)$arr[0];
			$max = (int)$arr[1];
			if($cap_min == $min && $cap_max == $max){
				$new_posts[] = $p;
			}
		}
	}
	return $new_posts;
}
/**
 * Add custom fields to custom post type
 */
function contact_columns_headers($columns){
	$columns['telephone'] = 'Phone';
	$columns['email'] = 'Email';
	return $columns;
}
add_filter('manage_contact_posts_columns','contact_columns_headers');

function contact_columns_content($column_name,$post_id){
	echo get_post_meta($post_id,$column_name,true);
}
add_action('manage_contact_posts_custom_column', 'contact_columns_content',10,2);

/**
 * Add custom fields to custom post type
 */
function product_columns_headers($columns){
	$columns['product_diameter'] = 'Diameter';
	$columns['product_height'] = 'Height';
	$columns['product_width'] = 'Width';
	$columns['product_materials'] = 'Materials';
	$columns['product_neckfinish'] = "Neck Finish";
	$columns['product_capacity'] = "Capacity";
	$columns['product_featured'] = "Featured";
	return $columns;
}
add_filter('manage_product_posts_columns','product_columns_headers');

function product_columns_content($column_name,$post_id){
	if($column_name != 'product_featured'){
		echo get_post_meta($post_id,$column_name,true);
	}
	else{
		$d = get_post_meta($post_id,$column_name,true);
		if(is_array($d) && $d[0] == 'Yes'){
			echo "Yes";
		}
		else{
			echo "No";
		}
	}
}
add_action('manage_product_posts_custom_column', 'product_columns_content',10,2);

function remove_post_custom_fields() {
	remove_meta_box( 'submitdiv' , 'contact' , 'normal' ); 
}
add_action( 'admin_menu' , 'remove_post_custom_fields' );

?>