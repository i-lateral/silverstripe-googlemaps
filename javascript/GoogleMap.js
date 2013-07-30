jQuery(document).ready(function() {
    jQuery('.$MapID').gmap3({
        map:{
            address: "$Address",
            options: {
                center: [$Latitude,$Longitude],
                zoom: $Zoom
            }
        },
        marker: {
            address: "$Address",
            latLng: [$Latitude,$Longitude],
            clickable: true
        },
        infowindow:{
            margin: this.marker,
            latLng: [$Latitude,$Longitude],
            options:{
                content: '$Content'
            }
        }
    });
});
