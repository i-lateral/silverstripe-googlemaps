<?php

/**
 *@author nicolaas[at] sunnysideup.co.nz
 *
 *
 **/


class GoogleMapBasic extends DataObjectDecorator {

    public function extraStatics() {
        return array (
            'db' => array(
                'ShowMap'           => 'Boolean',
                'StaticMap'         => 'Boolean',
                'Address'           => 'Text',
                'LatLng'            => 'Varchar',
                'ZoomLevel'         => 'Int',
                'InfoWindowContent' => 'HTMLText'
            )
        );
    }
    
    static function get_key($url = 0) {
        $siteconfig = SiteConfig::current_site_config();
        
        if(!isset($siteconfig->APIKey))
            user_error("No Google Map API key set");
        else
            return $siteconfig->APIKey;
    }

    protected static $js_location = '';
    static function set_js_location($s) {self::$js_location = $s;}
    static function get_js_location() {return self::$js_location;}

    protected static $include_in_classes = array();
    static function set_include_in_classes($a) {self::$include_in_classes = $a;}
    static function get_include_in_classes() {return self::$include_in_classes;}

    protected static $exclude_from_classes = array();
    static function set_exclude_from_classes($a) {self::$exclude_from_classes = $a;}
    static function get_exclude_from_classes() {return self::$exclude_from_classes;}

    public function updateCMSFields(FieldSet &$fields) {
        $siteconfig = SiteConfig::current_site_config();
        
        if($this->canHaveMap()) {
            $fields->addFieldToTab("Root.Behaviour", new CheckboxField("ShowMap", "Show map (reload to see additional options)"));

            if($this->owner->ShowMap) {
                $fields->addFieldToTab("Root.Content.Map", new CheckboxField("StaticMap", "Show map as picture only"));
                $fields->addFieldToTab("Root.Content.Map", new LiteralField('MapLocationHelp', '<p>Set either an address or Latitude/Longitude to generate the map</p>'));
                $fields->addFieldToTab("Root.Content.Map", new TextField("Address"));
                $fields->addFieldToTab("Root.Content.Map", new TextField("LatLng", "Latitude and Longitude (Lat,Long)"));
                $fields->addFieldToTab("Root.Content.Map", new NumericField("ZoomLevel", "Zoom (1 = world, 20 = too close)"));
                $fields->addFieldToTab("Root.Content.Map", new HtmlEditorField("InfoWindowContent", "Info Window Content", 5));
            }
        }
    }

    protected function canHaveMap() {
        $include = self::get_include_in_classes();
        $exclude = self::get_exclude_from_classes();
        if(!is_array($exclude) || !is_array($include)) {
            user_error("include or exclude classes is NOT an array", E_USER_NOTICE);
            return true;
        }
        if(!count($include) && !count($exclude)) {
            return true;
        }
        if(count($include) && in_array($this->owner->ClassName, $include)) {
            return true;
        }
        if(count($exclude) && !in_array($this->owner->ClassName, $exclude)) {
            return true;
        }
    }
}

class GoogleMapBasic_Controller extends Extension {

    public function GoogleMapBasic() {
        $siteconfig = SiteConfig::current_site_config();
        
        if($this->owner->ShowMap && ($this->owner->Address || $this->owner->LatLng)) {
            if($this->owner->StaticMap)
                    return true;
            else {
                $fileLocation = GoogleMapBasic::get_js_location();
                if(! $fileLocation)
                    $fileLocation = 'googlemapbasic/javascript/GoogleMapBasic.js';
                
                $key = ($siteconfig->APIKey) ? "&key={$siteconfig->APIKey}" : '';
                
                if($this->owner->LatLng) {
                    $latlng = explode(',',$this->owner->LatLng);
                    $lat = $latlng[0];
                    $lng = $latlng[1];
                } else {
                    $lat = '';
                    $lng = '';
                }
                
                $vars = array(
                    'lat'     => $lat,
                    'lng'    => $lng,
                    'address' => $this->owner->Address,
                    'content' => $this->owner->InfoWindowContent,
                    'zoom'    => (int)$this->owner->ZoomLevel
                );
                
                Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
                Requirements::javascript("http://maps.googleapis.com/maps/api/js?sensor=false" . $key);
                Requirements::javascriptTemplate($fileLocation, $vars);
                Requirements::themedCSS('GoogleMapBasic');
                
                return _t("GoolgeMapBasic.MAPLOADING", "map loading...");
            }
        }
        
        return false;
    }

    public function GoogleMapBasicStaticMapSource($width = 512, $height = 512) {
        $src = 'http://maps.googleapis.com/maps/api/staticmap?';
        $src .= 'center='.urlencode($this->owner->Address);
        $src .= '&amp;zoom='.$this->owner->ZoomLevel;
        $src .= '&amp;size='.$width.'x'.$height.'';
        $src .= '&amp;maptype=roadmap';
        $src .= '&amp;markers=color:red%7C'.urlencode(urlencode($this->owner->Address));
        $src .= '&amp;sensor=false';
        return $src;
    }

    public function GoogleMapBasicExternalLink () {
        if($this->owner->ShowMap && $this->owner->Address) {
            return $link = 'http://maps.google.com/maps?q='.urlencode($this->owner->Address).'&amp;z='.$this->owner->ZoomLevel;
        }
    }

    public function GoogleMapBasicExternalLinkHTML () {
        if($this->owner->ShowMap && $this->owner->Address) {
            return '<p id="GoogleMapBasicExternalLink"><a href="'.$this->GoogleMapBasicExternalLink().'" target="_map">'._t("GoogleMapBasic.OPENINGOOGLEMAPS", "open in Google Maps").'</a></p>';
        }
    }

    public function cleanJS($s) {
        $s = Convert::raw2js($s);
        $s = str_replace("\r\n", " ", $s);
        $s = str_replace("\n", " ", $s);
        $s = str_replace('/', '\/', $s);
        return $s;
    }
}
