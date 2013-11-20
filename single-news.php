<?php
get_header();
?>
<div class="inter">
	<div class="content">
		<?=tommy_get_sidebar("search");?>
		<h2 class="main_title">
			<img src="<?php bloginfo('template_url'); ?>/images/cycle.png" />
			<?=$post->post_title;?>
		</h2>
		<?=tommy_get_sidebar("featured",array('post'=>$post));?>
		<div class="item tommy_content">
			<?=apply_filters("the_content",$post->post_content);?>
		</div>
		<div class="related">
			<?=tommy_get_sidebar("contact");?>
		</div>
		<div class="clear"></div>
		<div style="height:100px;"></div>
	</div>
</div>
<?php
get_footer();
?>