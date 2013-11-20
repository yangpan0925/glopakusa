<?php get_header(); ?>
<?php
global $query_string;
if(isset($_GET['tp'])){
	$tp = $_GET['tp'];
}
else{
	$tp = 1;
}
$option = array();
if($_GET['product_type']){
	$option['cat_product'] = trim($_GET['product_type']);
}
$meta_query = array();
if($_GET['product_nf']){
	$meta_query[] = array(
		'key'=>'product_neckfinish',
		'value'=>'%' . trim($_GET['product_nf']) . "%",
		'compare'=>'LIKE'
	);
}
if($_GET['product_mat']){
	$meta_query[] = array(
		'key'=>'product_materials',
		'value'=>'%' . trim($_GET['product_mat']) . "%",
		'compare'=>'LIKE'
	);
}
if($meta_query){
	$option['meta_query'] = $meta_query;
}
$cap_min = null;
$cap_max = null;
if($_GET['product_cap_min']){
	$cap_min = (int)$_GET['product_cap_min'];
}
if($_GET['product_cap_max']){
	$cap_max = (int)$_GET['product_cap_max'];
}
if($option){
	$option['post_type'] = "product";
	$option['posts_per_page'] = -1;
	$option['paged'] = $tp;
	$search = new WP_Query($option);
	$posts = $search->get_posts();
	if($_GET['s']){
		$posts = gb_filter_title($posts,trim($_GET['s']));
	}
	$posts = gb_filter_cap($posts,$cap_min,$cap_max);
}
else{
	$search = new WP_Query(array('post_type'=>'product','posts_per_page'=>-1));
	$posts = $search->get_posts();
	if(trim($_GET['s'])){
		$posts = gb_filter_title($posts,$_GET['s']);
	}
	$posts = gb_filter_cap($posts,$cap_min,$cap_max);
}

if($posts){
	$products = null;
	$total = count($posts);
	$total_page = ceil($total / 12);
	$start = ($tp - 1) * 12;
	for($i=$start;$i<$start + 12 && $i<$total; $i++){
		$products[] = $posts[$i];
	}
}
else{
	$products = null;
	$total_page = 1;
}
?>
<div class="inter">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="title">Search Results</h2>
		<ul class="products">
			<?php if($products){foreach($products as $index=>$product){?>
			<li class="<?=($index<4 ? 'first' : '');?><?=(($index+1)%4 == 0 ? ' last' : '');?>">
				<a href="<?=get_permalink($product->ID);?>">
					<img src="<?=tommy_get_feature_image($product->ID);?>" class="thumb2" title="<?php $img_id = get_post_thumbnail_id($product->ID); $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true); echo $alt_text;?>"/>
				</a>
			</li>
			<?php }
			}else{?>
				none
			<?php }?>
		</ul>
		<?php if($total_page > 1){?>
		<div class="page_wrapper">
			<ul class="pages">
				<?php for($i=$total_page;$i>0;$i--){?>
				<li><a href="<?=$_SERVER['REQUEST_URI'] . '&tp=' . $i;?>"><?=$i;?></a></li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
	</div>
</div>
<?php get_footer(); ?>