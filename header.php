<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="format-detection" content="telephone=no" />
		<meta content="width=device-width; initial-scale=1.0" name="viewport" />
		<title><?=get_settings('blogname');?></title>
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/default.css" charset="utf-8" media="all">
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style.css" charset="utf-8" media="all">
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/content.css" charset="utf-8" media="all">
		<link rel="shortcut icon" href="<?=esc_url( home_url( '/' ) )?>favicon.ico" />
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/colorbox.css" charset="utf-8" media="all">
		<script src="<?php bloginfo('template_url'); ?>/js/jquery.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/jquery.ui.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/functions.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/core.js"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/jquery.colorbox-min.js"></script>
		<script type="text/javascript">
			config.baseURL = '<?php echo esc_url( home_url( '/' ) ); ?>';
			config.ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			<?php if(is_home()){?>
				config.isHome = 1;
			<?php }else{?>
				config.isHome = 0;
			<?php }?>
		</script>
		<script src="<?php bloginfo('template_url'); ?>/js/cms.js"></script>
	</head>
	<body>
		<div id="box">
			<div id="header">
				<div id="logo"><a href="<?=site_url();?>"></a></div>
			</div>
			<div id="banners">
				<div id="menubar">
					<?php
					$types = get_terms("cat_product",array(
						'hide_empty'=>false
					));
					$toptypes = tommy_get_tax($types,0);
					?>
					<ul class="menu">
						<li class="home"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
						<li><a href="<?=get_permalink(5);?>">About us</a></li>
						<li><a href="<?=get_permalink(8);?>">Services</a></li>
						<li>
							<a href="<?=get_permalink(14);?>">Products</a>
							<ul class="submenu">
								<?php foreach($toptypes as $type){?>
								<li>
									<?php $subtypes = tommy_get_tax($types,$type->term_id);?>
									<?php if($subtypes){?>
									<ul class="thirdmenu">
										<?php foreach($subtypes as $sb){?>
										<li></li>
										<?php }?>
									</ul>
									<?php }?>
								</li>
								<?php }?>
							</ul>
							<ul class="overlay">
								<?php foreach($toptypes as $type){?>
								<li>
									<a href="<?=get_term_link($type->slug,'cat_product');?>"><?=$type->name;?></a>
									<?php $subtypes = tommy_get_tax($types,$type->term_id);?>
									<?php if($subtypes){?>
									<ul class="h_overlay">
										<?php foreach($subtypes as $sb){?>
										<li><a href="<?=get_term_link($sb->slug,'cat_product');?>"><?=$sb->name;?></a></li>
										<?php }?>
									</ul>
									<?php }?>
								</li>
								<?php }?>
							</ul>
						</li>
						<li><a href="<?=get_permalink(99);?>">Decorating</a></li>
						<li><a href="<?=get_permalink(101);?>">Our facilities</a></li>
						<li><a href="<?=get_permalink(103);?>">Packaging</a></li>
						<li><a href="<?=get_permalink(17);?>">Contact us</a></li>
						<li><a href="<?=get_permalink(158);?>">Design</a></li>
					</ul>
				</div>
				<?php if(is_home()){?>
				<div class="banner" rel=1></div>
				<?php }?>
			</div>
			<div id="main">