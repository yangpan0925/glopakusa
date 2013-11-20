<?php
get_header();
?>
<div class="home">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="title">Featured Products</h2>
		<h4 class="subtitle">See our featured products for your need</h4>
		<ul class="featured">
			<?php
			$wp = new WP_QUERY(array(
				'posts_per_page'=>12,
				'post_type'=>'product',
				'meta_query' => array(
			       array(
			           'key' => 'product_featured',
			           'value' => 'a:1:{i:0;s:3:"Yes";}',
			           'compare' => '==',
			       )
   				)
			));
			$products = $wp->get_posts();
			/*
			$products = get_posts(array(
				'posts_per_page'=>12,
				'post_type'=>'product'
			));
			 */
			$len = count($products);
			?>
			<li class="arrow_left"><img src="<?php bloginfo('template_url'); ?>/images/arrow_left.png" /></li>
			<?php foreach($products as $index=>$product){?>
			<li class="fea_item <?=($index==$len-1 ? 'last' : '');?>"><a href="<?=get_permalink($product->ID);?>" class="feature_img"><img class="thumb1" src="<?=tommy_get_feature_image($product->ID);?>" /></a></li>
			<?php }?>
			<li class="arrow_right"><img src="<?php bloginfo('template_url'); ?>/images/arrow_right.png" /></li>
			<div class="clear"></div>
		</ul>
		<table width="100%">
			<tr>
				<td width="50%" style="vertical-align: top">
					<h2 class="title">News</h2>
					<?php
					$news = get_posts(array("post_type"=>"news"));
					?>
					<div class="subtitle">
						<?php foreach($news as $index=>$n){?>
						<p class="para"><a href="<?=get_permalink($n->ID);?>" style="font-size:12px;color:#484848;"><?=$n->post_title;?></a></p>
						<?php }?>
					</div>
				</td>
				<td width="50%" style="vertical-align: top">
					<?php $id = 139; $pp = get_post($id);?>
					<h2 class="title"><?=$pp->post_title;?></h2>
					<div class="subtitle" style="padding-right:0px;">
						<p class="para"><?=tommy_getSubstring($pp->post_content,350);?></p>
						<p class="para"><a href="<?=get_permalink($pp->ID);?>" style="color:#282828;font-size:12px;">Read More »</a></p>
					</div>
				</td>
			</tr>
		</table>
		
	</div>
</div>
<?php 
get_footer();
?>