<p>Upload Feature Image</p>
<input type="file" name="feature_img" />
<?php if(isset($post) && $post){?>
<p>
	<img src="<?=$post->guid;?>" /><br/>
	<a href="" id="remove_feature_img" post_id="<?=$post->ID;?>" term_id="<?=$term_id;?>">remove</a>
</p>
<?php }?>
<p>Upload Category Image</p>
<input type="file" name="feature_img" />
<?php if(isset($cat) && $cat){?>
<p>
	<img src="<?=$cat->guid;?>" /><br/>
	<a href="" id="remove_feature_img" post_id="<?=$post->ID;?>" term_id="<?=$term_id;?>">remove</a>
</p>
<?php }?>

<script type='text/javascript'>
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
  jQuery(document).ready(function(){
      jQuery('#edittag').attr('enctype','multipart/form-data');
      if(jQuery('a#remove_feature_img').length > 0){
      	jQuery('a#remove_feature_img').click(function(e){
      		e.preventDefault();
      		var post_id = jQuery(this).attr('post_id');
      		var term_id = jQuery(this).attr('term_id');
      		var _this = jQuery(this);
      		jQuery.post(ajaxurl,{'action':'remove','post_id':post_id,'term_id':term_id},function(resp){
      			if(resp == "ok"){
      				_this.prev().prev().remove();
      				_this.remove();
      			}
      			else{
      				console.log(resp);
      			}
      		});
      	});
      }
  });
</script>