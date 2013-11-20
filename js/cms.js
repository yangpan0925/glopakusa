function AppController(){
	Controller.call(this,"app");
	this.html = $('body');
	
}
AppController.prototype = new Controller();
AppController.prototype.constructor = AppController;

AppController.prototype.index = function(){
	this.initMenu();
	this.initFooter();
	$(window).resize(function(){
		var app = dispatcher.getComponent('app');
		app.initFooter();
		
	});
	if(config.isHome <= 0){
		$('#banners').height(41);
	}
	if($('form#searchform').length > 0){
		$('form#searchform').submit(function(){
			if(!$('#s').val()){
				$('#s').val(' ');
			}
			return true;
		});
		$('span#search_submit').click(function(){
			$('form#searchform').submit();
		});
		$('span#advanced_search').click(function(){
			$('ul#advanced_search').slideToggle({
				height:152,
				opacity:1
			});
		});
		$("#s").keyup(function(event){
			if(event.keyCode == 13){
			$('form#searchform').submit();
			}
		});
	}
	if($('li.fea_item').length > 0){
		fea_item_len = this.fea_item_len = $('li.fea_item').length;
		var fea_items = $('li.fea_item');
		if(this.fea_item_len > 4){
			var start = 0;
			var end = 3;
			for(var i=4;i<this.fea_item_len;i++){
				fea_items.eq(i).hide();
			}
			fea_items.eq(this.fea_item_len-1).removeClass('last');
			fea_items.eq(end).addClass('last');
			$('li.arrow_right').click(function(e){
				var app = dispatcher.getComponent('app');
				if(end == fea_item_len - 1){
					return false;
				}
				fea_items.eq(end).removeClass('last');
				end++;
				start++;
				fea_items.eq(end).addClass('last');
				for(var i=0;i<start;i++){
					fea_items.eq(i).hide();
				}
				for(var i=start;i<=end;i++){
					fea_items.eq(i).show();
				}
				for(var i=end+1;i<fea_item_len;i++){
					fea_items.eq(i).hide();
				}
			});
			$('li.arrow_left').click(function(e){
				var app = dispatcher.getComponent('app');
				if(end == 3){
					return false;
				}
				fea_items.eq(end).removeClass('last');
				end--;
				start--;
				fea_items.eq(end).addClass('last');
				for(var i=0;i<start;i++){
					fea_items.eq(i).hide();
				}
				for(var i=start;i<=end;i++){
					fea_items.eq(i).show();
				}
				for(var i=end+1;i<fea_item_len;i++){
					fea_items.eq(i).hide();
				}
			});
		}
		
	}
	$('img.gallery').colorbox({
		rel:'group',
		onComplete : function(){
			var href = $(this).parent().attr("href");
			
			$('img.cboxPhoto').click(function(e){
				$.colorbox.close();
				window.open(href,"_self");
			});
		}
	});
}
AppController.prototype.initMenu = function(){
	$('ul.menu > li').mouseover(function(){
		if($(this).find('ul.submenu').length > 0){
			$(this).find('ul.submenu').show().next().show();
		}
	}).mouseleave(function(e){
		if($(this).find('ul.overlay').length > 0){
			var submenu = $(this).find('ul.overlay');
			setTimeout(function(){
				console.log(submenu.isHovered);
				if(submenu.attr("isHovered") == undefined || submenu.attr("isHovered") <= 0){
					submenu.hide().prev().hide();
				}
			},20);
		}
	});
	$('ul.overlay').mouseover(function(){
		$(this).attr("isHovered",1);
	}).mouseleave(function(){
		$(this).attr("isHovered",0);
		$(this).hide().prev().hide();
	});
	$('ul.overlay > li').mouseenter(function(){
		if($(this).find('ul.h_overlay').length > 0){
			var i = $(this).parent().children().index(this);
			$(this).find('ul.h_overlay').show().css("top",i*39+"px");
			$(this).parent().prev().children().eq(i).find("ul.thirdmenu").show().css("top",i*39+"px");
		}
	});
	$('ul.overlay > li').mouseleave(function(){
		if($('ul.h_overlay').length > 0){
			$('ul.h_overlay').hide();
			$('ul.thirdmenu').hide();
		}
	});
	$('ul.h_overlay').mouseleave(function(){
		$(this).hide();
		var i = $(this).parent().parent().children().index($(this).parent());
		$(this).parent().parent().prev().children().eq(i).find("ul.thirdmenu").hide();
	});
}
AppController.prototype.initFooter = function(){
	var w = $('ul.footer_menu').width();
	var cw = $('#footer').width();
	var left = parseInt((cw-w)/2);
	$('ul.footer_menu').css('marginLeft',left+'px');
}
dispatcher.register(new AppController());