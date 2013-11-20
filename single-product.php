<?php
get_header();
$cats = tommy_get_tax_by_post('cat_product',$post->ID);

?>
<div class="inter">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="title"><?=$post->post_title;?></h2>
		<div class="item">
			<div class="item_picture">
				<img src="<?=tommy_get_feature_image($post->ID);?>" class="full" title="<?php $img_id = get_post_thumbnail_id($post->ID); $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true); echo $alt_text;?>"/>
			</div>
			<ul class="item_pro">
				<?php if($product_sku = get_post_meta($post->ID,'product_number',true)){?>
				<li class="subtitle">ITEM #:<?=$product_sku;?></li>
				<?php }?>
				<?php if($product_diameter = get_post_meta($post->ID,'product_diameter',true)){?>
				<li class="subtitle">DIAMETER:<?=$product_diameter;?></li>
				<?php }?>
				<?php if($product_height = get_post_meta($post->ID,'product_height',true)){?>
				<li class="subtitle">HEIGHT:<?=$product_height;?></li>
				<?php }?>
				<?php if($product_width = get_post_meta($post->ID,'product_width',true)){?>
				<li class="subtitle">WIDTH:<?=$product_width;?></li>
				<?php }?>
				<?php if($product_mat = get_post_meta($post->ID,'product_materials',true)){?>
				<li class="subtitle">MATERIALS:<?=$product_mat;?></li>
				<?php }?>
				<?php if($product_neckfinish = get_post_meta($post->ID,'product_neckfinish',true)){?>
				<li class="subtitle">NECKFINISH:<?=$product_neckfinish;?></li>
				<?php }?>
				<?php if($product_cap = get_post_meta($post->ID,'product_capacity',true)){?>
				<li class="subtitle">CAPACITY:<?=$product_cap;?></li>
				<?php }?>
			</ul>
		</div>
		<?php $related = tommy_get_related_posts("product",$post->ID,3,'cat_product',$cats);if($related){?>
		<div class="related" style="margin-top:0px;">
			<h2 class="title" style="margin-top:-44px;">Related Products</h2>
			<ul class="item_lists">
				<?php foreach($related as $i=>$r){?>
				<li class="<?=($i==0 ? 'first' : '');?>"><a href="<?=get_permalink($r->ID);?>">
					<img src="<?=tommy_get_feature_image($r->ID)?>" class="thumb3" title="<?php $img_id = get_post_thumbnail_id($r->ID); $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true); echo $alt_text;?>"/>
				</a></li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
		<div class="clear"></div>
		<div style="height:100px;"></div>
	</div>
</div>
<?php 
get_footer();
?>