<form role="search" method="get" id="searchform" action="<?=home_url('/');?>">
	<div id="search">
		<label class="textfield_wrap">
			<div class="border_left"></div>
			<input class="textfield" type="text" name="s" id="s" placeholder="Search" />
			<div class="border_right"></div>
		</label>
		<label class="textfield_wrap" style="margin-left:10px;font-weight:600;border-bottom:1px solid #505050;cursor:pointer;">
			
			<span class="textfield" style="padding:4px 7px; border:1px solid #c8c8c8;border-top:none;background:none;" id="search_submit" class="textfield">Go</span>
			
		</label>
		<span id="advanced_search">Advanced</span>
		<?php if(is_single() && get_post_type() == 'product'){?>
		<a href="" onclick="javascript:window.print();return false;" style="float: right;"><img src="<?php bloginfo('template_url'); ?>/images/printer.png" style="width: 30px;"></a>
		<?php }?>
	</div>
	<ul id="advanced_search">
		<?php 
		$types = get_terms("cat_product",array(
			'hide_empty'=>false,
			'parent'=>0
		));
		?>
		<li class="search_title">Category:</li>
		<li><select name="product_type"><option value="0"></option>
			<?php foreach($types as $type){?>
				<option value="<?=$type->slug;?>"><?=$type->name;?></option>
			<?php }?>
		</select></li>
		<li class="search_title">Capacity:</li>
		<li>
			<label class="textfield_wrap">
				<div class="border_left"></div>
				<input type="text" name="product_cap_min" class="p_cap textfield" />
				<div class="border_right"></div>
			</label>
			<div style="height:100%;float:left;color:gray;line-height:23px;padding:0 10px;">-</div>
			<label class="textfield_wrap">
				<div class="border_left"></div>
				<input type="text" class="p_cap textfield" name="product_cap_max" />
				<div class="border_right"></div>
			</label>
		</li>
		<li class="search_title">Neck Finish:</li>
		<li>
			<label class="textfield_wrap">
				<div class="border_left"></div>
				<input type="text" class="textfield" name="product_nf" style="width:92px;" />
				<div class="border_right"></div>
			</label>
		</li>
		<li class="search_title">Material:</li>
		<li>
			<label class="textfield_wrap">
				<div class="border_left"></div>
				<input type="text" name="product_mat" class="textfield" style="width:92px;" />
				<div class="border_right"></div>
			</label>
		</li>
	</ul>
</form>