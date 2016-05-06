jQuery(document).ready(function() {
    jQuery('.$MapID').gmap3({
        map:{
            address: "$Address",
            options: {
                center: [$Latitude,$Longitude],
                zoom: $Zoom
            }
        }
    });
});
