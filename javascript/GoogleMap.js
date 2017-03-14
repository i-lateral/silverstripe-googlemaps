(function($) {
	$(document).ready(function() {
	    $('.$MapID')
	        .gmap3({
	            center: [$Latitude,$Longitude],
	            zoom: $Zoom
	        })
	        .marker({
	            position: [$Latitude,$Longitude]
	        });
	});
}(jQuery));