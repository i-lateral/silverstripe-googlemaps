<?php

/**
 * Inject our map data into the Content Controller
 * 
 */
class GoogleMapController extends Extension {
    
    public function onBeforeInit() {
        Requirements::themedCSS('GoogleMaps', 'googlemaps');
        if(
            $this->owner->Maps()->exists()
            && $this->owner->ShowMap
            && !$this->owner->StaticMap
        ) {
            $config = SiteConfig::current_site_config();
            $key = ($config->APIKey) ? "&key={$config->APIKey}" : '';
            
            Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
            Requirements::javascript("http://maps.googleapis.com/maps/api/js?sensor=false" . $key);
            Requirements::javascript('googlemaps/javascript/gmap3.min.js');
        }
    }
 	   
    public function onAfterInit() {
        if(
            $this->owner->Maps()->exists()
            && $this->owner->ShowMap
            && !$this->owner->StaticMap
        ) {
        	if ($this->owner->OnlyOneMap == false) {
				foreach($this->owner->Maps() as $map) {
	                $vars = array(
	                    'MapID'         => "google-map-dynamic-{$map->ID}",
	                    'Content'       => $map->Content,
	                    'Address'       => ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : 'false',
	                    'Latitude'      => ($map->Latitude) ? $map->Latitude : 'false',
	                    'Longitude'     => ($map->Longitude) ? $map->Longitude : 'false',
	                    'Zoom'          => $map->ZoomLevel
	                );
	            	
                	Requirements::javascriptTemplate(
	                    'googlemaps/javascript/GoogleMap.js',
                    	$vars
	                );
				}
			}
			else {
				$centerAdress = "";
				$centerLatitude;
				$centerLongitude;
				$markerValues = "";
				$zoom = 10;
				$mapID = "";
				$autofit = ($this->owner->AutoFit) ? ",autofit: {}" : "";
				for ($i = 0; $i<count($this->owner->Maps()); $i++) {
					$map = $this->owner->Maps();
					$map = $map[$i];
	            	if ($i == 0) {
	        			$centerAdress = ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : 'false';
						$zoom = $map->ZoomLevel;
						$mapID = "google-map-dynamic-{$map->ID}";
						$centerLatitude = ($map->Latitude) ? $map->Latitude : 'false';
						$centerLongitude = ($map->Longitude) ? $map->Longitude : 'false';
	            	}
					$address = ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : 'false';
					$markerValues .= "{";						
						$markerValues .= 'address:"'.$address.'",';
						$markerValues .= "latLng:[";
							$markerValues .= ($map->Latitude) ? $map->Latitude : 'false'.',';
							$markerValues .= ($map->Longitude) ? $map->Longitude : 'false';
			 			$markerValues .= "],";
						$markerValues .= "data: '".$map->Content."',";
						$markerValues .= "options:{icon: 'http://maps.google.com/mapfiles/marker_yellow.png'}";
	                $markerValues .= "}";
					if ($i != count($this->owner->Maps())-1)
						$markerValues .= ",";
	            }
				
				Requirements::customScript('
					jQuery(document).ready(function() {
					    jQuery(".'.$mapID.'").gmap3({
					        map:{
					            address: "'.$centerAdress.'",
					            options:{
					              center:['.$centerLatitude.','.$centerLongitude.'],
					              zoom: '.$zoom.'
					            }
					        },
					        marker:{
					        	values:[
					            '.$markerValues.'
					            ],
					            options:{
					              draggable: false
					            },
					            events:{
					              click: function(marker, event, context){
					                var map = $(this).gmap3("get"),
					                  infowindow = $(this).gmap3({get:{name:"infowindow"}});
					                if (infowindow){
			                  		  infowindow.open(map, marker);
					                  infowindow.setContent(context.data);
					                } else {
					                  $(this).gmap3({
					                    infowindow:{
					                      anchor:marker, 
					                      options:{content: context.data}
					                    }
					                  });
					                }
					              }
					    		}
					        }'.$autofit.'
					    });
					});
				');		
			}
		}
    }

    public function GoogleMaps() {
        if($this->owner->Maps()->exists() && $this->owner->ShowMap) {
            $config = SiteConfig::current_site_config();
            $vars = array(
                'Maps' => $this->owner->Maps()
            );
        
            return $this->owner->renderWith('GoogleMaps',$vars);
        } else
            return false;
    }
}
