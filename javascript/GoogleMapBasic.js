/*
THANK YOU Marcel Nogueira d' Eurydice FOR THE INSPIRATION!
TO DECLARE USING Requirements::customScript IN PHP
*/

jQuery(document).ready(function () { GoogleMapBasic.init() });

var GoogleMapBasic = {
    lat: "$Lat",
    lng: "$Lng",
    address: "$Address",
    content: '$Content',
    zoom: $Zoom,
    map_id: "GoogleMapBasic",
    
    init: function() {
        // Test to see if address variable has been set, if not, fall back to lat/long
        if(this.address) {
            this.geocode_address(this.address);
        } else {
            this.generate_map(new google.maps.LatLng(this.lat,this.lng));
        }
    },
    
    geocode_address: function(address) {
        // Create a Google Geocoder instance...
        var geocoder = new google.maps.Geocoder();

        // And pass it our address
        geocoder.geocode({'address': address}, function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
                // Now, generate a map using the new location
                GoogleMapBasic.generate_map(results[0].geometry.location);
            } else {
                alert("Unable to locate address for the following reason: " + status);
            }
        });
    },

    generate_map: function(latlng) {
        var myOptions = {
            center: latlng,
            zoom: this.zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById(this.map_id), myOptions);

        var marker= new google.maps.Marker({
            position: latlng,
            clickable: true, 
            map: map
        });

        if(this.content) {
            var infowindow = new google.maps.InfoWindow({
                content: this.content
            }).open(map,marker);
        }
    }
};
