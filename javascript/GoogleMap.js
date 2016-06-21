jQuery(document).ready(function() {
    jQuery('.$MapID')
        .gmap3({
            center: [$Latitude,$Longitude],
            zoom: $Zoom
        })
        .marker({
            position: [$Latitude,$Longitude]
        });
});
