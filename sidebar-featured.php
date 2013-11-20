<?php if(has_post_thumbnail($post->ID)){?>
<div class="feature_img">
	<img src="<?=tommy_get_feature_image($post->ID);?>" title="<?php $img_id = get_post_thumbnail_id($post->ID); $alt_text = get_post_meta($img_id , '_wp_attachment_image_alt', true); echo $alt_text;?>"/>
</div>
<?php $t = tommy_get_thumb($post->ID);?>
<h4 class="subtitle"><?=$t->post_title;?></h4>
<?php }?>