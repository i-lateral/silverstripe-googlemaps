(function($) {
	$(document).ready(function() {
		
		$('.$MapID').gmap3({
			marker: {
				latLng: [$Latitude,$Longitude],
				options: {
					clickable: true,
					visible: true
				},
				events: {
					click: function(marker, event, context){
						var map = $(this).gmap3("get"),
							infowindow = $(this).gmap3({get:{name:"infowindow"}});
						if (infowindow){
							infowindow.open(map, marker);
						} else {
							$(this).gmap3({
								infowindow:{
									anchor:marker, 
									options:{content: '$Content'}
								}
							});
						}
						
					}
				}
			},
			infowindow: {
				anchor: $(this).gmap3({get:{name:"marker"}}),
				open: true,
				options:{
					content: '$Content'
				}
			}
		});
		
	});
}(jQuery));