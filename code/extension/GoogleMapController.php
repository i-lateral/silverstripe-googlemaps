<?php

/**
 * Inject our map data into the Content Controller
 * 
 */
class GoogleMapController extends Extension
{

    public function onAfterInit()
	{
		// load static requirements
        Requirements::themedCSS('GoogleMaps', 'googlemaps');
		
		// load dynamic maps and requirements
        if (
            $this->owner->Maps()->exists()
            && $this->owner->ShowMap
            && !$this->owner->StaticMap
        ) {

			// load requirements
            $config = SiteConfig::current_site_config();
            $key = ($config->APIKey) ? "&key={$config->APIKey}" : '';
            Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
            Requirements::javascript("http://maps.googleapis.com/maps/api/js?sensor=false" . $key);
            Requirements::javascript('googlemaps/javascript/gmap3.min.js');

			// load maps
			if ($this->owner->OnlyOneMap == false) {
				foreach($this->owner->Maps() as $map) {
	                $vars = array(
	                    'MapID'         => "google-map-dynamic-{$map->ID}",
	                    'Address'       => ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : 'false',
	                    'Latitude'      => ($map->Latitude) ? $map->Latitude : 'false',
	                    'Longitude'     => ($map->Longitude) ? $map->Longitude : 'false',
	                    'Zoom'          => $map->Zoom
	                );

                	Requirements::javascriptTemplate(
	                    'googlemaps/javascript/GoogleMap.js',
                    	$vars
	                );
				}
			} else {
				$centerAdress = "";
				$centerLatitude;
				$centerLongitude;
				$markerValues = "";
				$zoom = 10;
				$mapID = "";
				$autofit =  ($this->owner->AutoFit) ? ',"autofit"' : null;
				$markers = array();

				for ($i = 0; $i < $this->owner->Maps()->count(); $i++) {
					$map = $this->owner->Maps();
					$map = $map[$i];
					$address = ($map->Address) ? str_replace('/n', ',', $map->Address) . ',' . $map->PostCode : null;

	            	if ($i == 0) {
	        			$centerAdress = ($map->Address) ? "address: '".str_replace('/n', ',', $map->Address) . ',' . $map->PostCode ."'," : '';
						$zoom = $map->Zoom;
						$mapID = "google-map-dynamic-{$map->ID}";
						$centerLatitude = ($map->Latitude) ? $map->Latitude : 'false';
						$centerLongitude = ($map->Longitude) ? $map->Longitude : 'false';
	            	}


					$markerValue = "{";

					if ($address) {
						$markerValue .= ' address:"'.$address.'",';
					} else {
						$markerValue .= " position: [";
						$markerValue .= ($map->Latitude) ? $map->Latitude : 'false';
						$markerValue .= ",";
						$markerValue .= ($map->Longitude) ? $map->Longitude : 'false';
						$markerValue .= "],";
					}

	                $markerValue .= "}";
					$markers[] = $markerValue;
	            }

				$js = 'jQuery(document).ready(function() {
					jQuery(".'.$mapID.'").gmap3({
						center: ['.$centerLatitude.','.$centerLongitude.'],
						zoom: '.$zoom.'
					})';

				$js .= '.marker([' . implode(",", $markers) . '])';

				if ($this->owner->AutoFit) {
					$js .= ".fit();";
				} else {
					$js .= ";";
				}

				$js .= "});";
				
				Requirements::customScript($js);		
			}
		}
    }

    public function GoogleMaps()
	{
        if ($this->owner->Maps()->exists() && $this->owner->ShowMap) {
            $config = SiteConfig::current_site_config();
            $vars = array(
                'Maps' => $this->owner->Maps()
            );

            return $this->owner->renderWith('GoogleMaps',$vars);
        } else {
            return false;
		}
    }
}