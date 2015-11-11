(function($) {
	$(document).ready(function() {
		
		$('.$MapID').gmap3({
			map:{
				options: {
					center: [$Latitude,$Longitude],
					zoom: $Zoom
				}
			}
		});
		
	});
}(jQuery));
