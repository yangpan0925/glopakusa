<?php
get_header();
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8eVQP-SHq0mzY_duIyMj-xW5XUhyK_cg&sensor=false"></script>
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
	<div id="map"></div>
</div>
<script type="text/javascript">
	var address = '<?=get_post_meta(17,"contact_address",true);?>';
	var contentString = '<div id="content">'+
    '<div id="siteNotice">'+
    '</div>'+
    '<h2 id="firstHeading" class="firstHeading">Location</h2>'+
    '<div id="bodyContent">'+ address +
    '</div>'+
    '</div>';
    var infowindow = new google.maps.InfoWindow({
    	content: contentString
	});
	if(address){
		var s = new Storage();
		var l = s.getItem('address',true);
		if(!l || l != address){
			s.removeItem('kb',true);
		}
		var geocode = s.getItem('kb',true);
		var mapOptions = {
	      zoom: 8,
	      mapTypeId: google.maps.MapTypeId.ROADMAP,
	      mapTypeControl:false,
	      draggable : false,
	      overviewMapControl:false,
	      zoomControl : false,
	   	  streetViewControl : false,
	   	  scrollwheel: false,
	   	  scaleControl: false
	    };
		var map = new google.maps.Map(document.getElementById("map"),mapOptions);
		if(!geocode){
			var geocoder = new google.maps.Geocoder();
		 	geocoder.geocode( { 'address': address}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK){
		      	var kb = results[0].geometry.location.jb;
		      	var lb = results[0].geometry.location.kb;
		      	s.setItem('kb',kb,true);
		      	s.setItem('lb',lb,true);
		      	s.setItem('address',address,true);
		      	
		        map.setCenter(results[0].geometry.location);
		        var marker = new google.maps.Marker();
		        marker.setMap(map);
		        var lt = new google.maps.LatLng(kb,lb);
		        marker.setPosition(lt);
		        marker.setVisible(true);
		        marker.setTitle(address);
		        marker.setClickable(true);
		        google.maps.event.addListener(marker,'click',function(){
		        	infowindow.open(map,marker);
		        });
		      } else {
		        console.log("Geocode was not successful for the following reason: " + status);
		      }
		    });
		}
		else{
			var kb = geocode;
			var lb = s.getItem('lb',true);
			var lt = new google.maps.LatLng(kb,lb);
			map.setCenter(lt);
			var marker = new google.maps.Marker();
		    marker.setMap(map);
		    marker.setPosition(lt);
	        marker.setVisible(true);
	        marker.setTitle(address);
	        marker.setClickable(true);
	        google.maps.event.addListener(marker,'click',function(){
	        	infowindow.open(map,marker);
	        });
		}
	}
</script>
<?php
get_footer();
?>