<?php
get_header();
$cats = get_terms("cat_product",array(
						'hide_empty'=>false,
						'parent'=>0
					));

?>
<div class="inter">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="title"><?=$post->post_title;?></h2>
		<?=tommy_get_sidebar("featured",array('post'=>$post));?>
		<ul class="products">
			<?php foreach($cats as $index=>$cat){?>
			<li class="<?=($index < 4 ? 'first' : '');?><?=(($index + 1) % 4 == 0 ? ' last' : '');?>">
				<a href="<?=get_term_link($cat->slug,'cat_product');?>">
					<?php
					$p_id = get_option("cat_img_" . $cat->term_id,0);
					if($p_id){
						$p = get_post($p_id);
					}
					else{
						$p = null;
					}
					?>
					<img src="<?=$p->guid;?>" class="thumb2" />
				</a>
				<a href="<?=get_term_link($cat->slug,'cat_product');?>" class="cat_title"><?=$cat->name;?></a>
			</li>
			<?php }?>
		</ul>
	</div>
</div>
<?php 
get_footer();
?>