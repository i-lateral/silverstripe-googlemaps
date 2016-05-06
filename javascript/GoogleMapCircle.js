(function($) {
	$(document).ready(function() {
		
		$('.$MapID').gmap3({
			circle:{
				options:{
					center: [$Latitude,$Longitude],
					radius : $Radius,
					fillColor : "$Color",
					fillOpacity: 0.3,
					strokeColor : "$Color",
					strokeOpacity: 0.7,
					strokeWeight: 1
				}
			}
		});
		
	});
}(jQuery));