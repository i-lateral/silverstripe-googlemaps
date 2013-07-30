<?php

/**
 * Google Map Objects represent a google map that needs to be rendered into a
 * page.
 *
 */
class GoogleMap extends DataObject {
    private $api_key;

    public static $db = array(
        'Title'             => 'Varchar',
        'Content'           => 'HTMLText',
        'Address'           => 'Text',
        'PostCode'          => 'Varchar',
        'Latitude'          => 'Varchar',
        'Longitude'         => 'Varchar',
        'ZoomLevel'         => 'Int',
        'Sort'              => 'Int'
    );
    
    public static $has_one = array(
        'Parent' => 'SiteTree'
    );
    
    public static $casting = array(
        'FullAddress'   => 'HTMLText',
        'Location'      => 'Text',
        'Link'          => 'Text',
        'ImgURL'        => 'Text'
    );
    
    public static $summary_fields = array(
        'Title',
        'Address',
        'PostCode',
        'Latitude',
        'Longitude'
    );
    
    public static $default_sort = 'Sort';
    
    public static $defaults = array(
        'ZoomLevel' => 10
    );
    
    public function getCMSFields() {    
        $fields = parent::getCMSFields();
        
        $fields->removeByname('ParentID');
        $fields->removeByname('MapLocationHelp');
        $fields->removeByname('Address');
        $fields->removeByname('PostCode');
        $fields->removeByname('Latitude');
        $fields->removeByname('Longitude');
        $fields->removeByname('ZoomLevel');
        $fields->removeByname('Content');
        
        if($this->ID) {
            $fields->addFieldToTab(
                "Root.Main",
                HtmlEditorField::create(
                    "Content",
                    "Content to be displayed with this map"
                )
                ->addExtraClass('stacked')
                ->setRows(15)
            );
            
            $config_fields = ToggleCompositeField::create(
                'MapConfig',
                'Configuration Options',
                array(
                    LiteralField::create('MapLocationHelp', '<p class="field">Set EITHER an address / post code OR latitude / longitude to generate a map</p>'),
                    TextAreaField::create("Address"),
                    TextField::create("PostCode", "Post Code"),
                    TextField::create("Latitude"),
                    TextField::create("Longitude"),
                    NumericField::create("ZoomLevel", "Zoom (1 = world, 20 = close)"),
                )
            )->setHeadingLevel(4);

            $fields->addFieldToTab('Root.Main', $config_fields);
        }
        
        return $fields;
    }
    
    private function url_safe_address() {
        $address  = str_replace('/n', ',', $this->Address);
        $address .= ',' . $this->PostCode;
        
        return urlencode($address);
    } 
    
    /**
     * Get the location for this map, either address / postcode or lat / long
     *
     * @return String
     */
    public function getLocation() {
        $location = false;

        if($this->Address && $this->PostCode)
            $location = $this->url_safe_address();
        
        if($this->Latitude && $this->Longitude)
            $location = $this->Latitude . ',' . $this->Longitude;
        
        return $location;
    }
    
    /**
     * Get a XML rendered version of the text address and post code
     *
     * @return String
     */
    public function getFullAddress() {
        return Convert::raw2xml($this->Address . '/n' . $this->PostCode);
    }
    
    /**
     * Link to Google Maps for directions etc
     *
     * @return String
     */
    public function Link() {
        $link = false;
        $location = $this->getLocation();
        
        if($location) {
            $link  = 'http://maps.google.com/maps?q=';
            $link .= $location;
            $link .= '&amp;z='.$this->ZoomLevel;
        }
    
        return $link;
    }
    
    /**
     * URL for static map image
     *
     * @return String
     */
    public function ImgURL($width = 256, $height = 256) {
        $link = false;
        $location = $this->getLocation();
        
        if($location) {
            $link = 'http://maps.googleapis.com/maps/api/staticmap?';
            $link .= 'center=' . $location;
            $link .= '&zoom=' . $this->ZoomLevel;
            $link .= '&size=' . $width . 'x' . $height . '';
            $link .= '&maptype=roadmap';
            $link .= '&markers=color:red%7C' . $location;
            $link .= '&sensor=false';
        }
        
        return $link;
    }
    
    public function canCreate($member = null) {
        return $this->Parent()->canCreate();
    }
    
    public function canView($member = null) {
        return $this->Parent()->canView();
    }
    
    public function canEdit($member = null) {
        return $this->Parent()->canEdit();
    }
    
    public function canDelete($member = null) {
        return $this->Parent()->canDelete();
    }
}
