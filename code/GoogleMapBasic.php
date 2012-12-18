<?php

/**
 *@author nicolaas[at] sunnysideup.co.nz
 *
 *
 **/


class GoogleMapBasic extends DataExtension {

    public static $db = array(
        'ShowMap'           => 'Boolean',
        'StaticMap'         => 'Boolean',
        'Address'           => 'Text',
        'LatLng'            => 'Varchar',
        'ZoomLevel'         => 'Int',
        'InfoWindowContent' => 'HTMLText'
    );
    
    static function get_key($url = 0) {
        $siteconfig = SiteConfig::current_site_config();
        
        if(!isset($siteconfig->APIKey))
            user_error("No Google Map API key set");
        else
            return $siteconfig->APIKey;
    }

    protected static $include_in_classes = array();
    static function set_include_in_classes($a) {self::$include_in_classes = $a;}
    static function get_include_in_classes() {return self::$include_in_classes;}

    protected static $exclude_from_classes = array();
    static function set_exclude_from_classes($a) {self::$exclude_from_classes = $a;}
    static function get_exclude_from_classes() {return self::$exclude_from_classes;}

    public function updateCMSFields(FieldList $fields) {
        $siteconfig = SiteConfig::current_site_config();
        
        if($this->canHaveMap()) {
            if($this->owner->ShowMap) {
                $fields->addFieldToTab("Root.Maps", CheckboxField::create("StaticMap", "Show map as picture only"));
                $fields->addFieldToTab("Root.Maps", LiteralField::create('MapLocationHelp', '<p>Set either an address or Latitude/Longitude to generate the map</p>'));
                $fields->addFieldToTab("Root.Maps", TextField::create("Address"));
                $fields->addFieldToTab("Root.Maps", TextField::create("LatLng", "Latitude and Longitude (Lat,Long)"));
                $fields->addFieldToTab("Root.Maps", NumericField::create("ZoomLevel", "Zoom (1 = world, 20 = too close)"));
                $fields->addFieldToTab("Root.Maps", HtmlEditorField::create("InfoWindowContent", "Info Window Content")->setRows(5));
            }
        }
    }
    
    public function updateSettingsFields(FieldList $fields) {
        if($this->canHaveMap()) {
            $maps_group = FieldGroup::create(
                CheckboxField::create("ShowMap", "Show map (reload to see options under 'Content')")
            );
            $maps_group->setTitle('Google Maps');
        
            $fields->addFieldToTab("Root.Settings", $maps_group);
        }
        
        return $fields;
    }

    protected function canHaveMap() {
        $include = self::get_include_in_classes();
        $exclude = self::get_exclude_from_classes();
        
        if(!is_array($exclude) || !is_array($include)) {
            user_error("include or exclude classes is NOT an array", E_USER_NOTICE);
            return true;
        }
        
        if(!count($include) && !count($exclude))
            return true;
            
        if(count($include) && in_array($this->owner->ClassName, $include))
            return true;
            
        if(count($exclude) && !in_array($this->owner->ClassName, $exclude))
            return true;
    }
}

class GoogleMapBasic_Controller extends Extension {

    public function GoogleMapBasic() {
        $siteconfig = SiteConfig::current_site_config();
        
        if($this->owner->ShowMap && ($this->owner->Address || $this->owner->LatLng)) {
            if($this->owner->StaticMap)
                    return true;
            else {
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
                Requirements::css('googlemapbasic/css/GoogleMapBasic.css');
                
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
