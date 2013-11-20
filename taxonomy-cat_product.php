<?php
get_header();
$cat_slug = get_query_var('cat_product');
$cat = get_term_by('slug',$cat_slug,'cat_product');
$products = get_posts(array(
	'numberposts'=>-1,
	'cat_product'=>$cat->slug,
	'post_type'=>'product',
	'post_status'=>'publish'
));
if(isset($_GET['tp'])){
	$tp = $_GET['tp'];
}
else{
	$tp = 1;
}
$wp = new WP_QUERY(array(
							'post_type' =>"product",
							'post_status' => 'publish',
							'posts_per_page' => 32,
							'paged' => $tp,
							'cat_product'=>$cat->slug
						)
					);
$products = $wp->get_posts();
$total_page = $wp->max_num_pages;
$tt = tommy_get_top_tax('cat_product',$cat->slug);

?>
<div class="inter">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="title"><?=$cat->name;?></h2>
		<?php
		if($cat->parent){
			$term_id = $cat->parent;
		}
		else{
			$term_id = $cat->term_id;
		}
		$post_id = get_option("feature_img_" . $term_id);
		if($post_id){
			$p = get_post($post_id);
		?>
		<div class="feature_img">
			<img src="<?=$p->guid;?>" />
		</div>
		<h4 class="subtitle"><?=$p->post_title;?></h4>
		<?php }?>
		<ul class="products">
			<?php foreach($products as $index=>$product){?>
			<li class="<?=($index<4 ? 'first' : '');?><?=(($index+1)%4 == 0 ? ' last' : '');?>">
				<a href="<?=get_permalink($product->ID);?>">
					<img rel="group" href="<?=tommy_get_feature_image($product->ID);?>" src="<?=tommy_get_feature_image($product->ID);?>" class="thumb2 gallery" title="<?php $img_id = get_post_thumbnail_id($product->ID); $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true); echo $alt_text;?>" />
				</a>
			</li>
			<?php }?>
		</ul>
		<?php if($total_page > 1){?>
		<div class="page_wrapper">
			<ul class="pages">
				<?php for($i=$total_page;$i>0;$i--){?>
				<li><a href="?tp=<?=$i;?>"><?=$i;?></a></li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
	</div>
</div>
<?php 
get_footer();
?>