<?php

/**
 * Google Map Objects represent a google map that needs to be rendered into a
 * page.
 *
 */
class GoogleMap extends DataObject
{
    private $api_key;

    private static $db = array(
        'Title'             => 'Varchar',
        'Address'           => 'Text',
        'PostCode'          => 'Varchar',
        'Latitude'          => 'Varchar',
        'Longitude'         => 'Varchar',
        'Zoom'              => 'Int',
        'Sort'              => 'Int'
    );

    private static $has_one = array(
        'Parent' => 'SiteTree'
    );

    private static $casting = array(
        'FullAddress'   => 'HTMLText',
        'Location'      => 'Text',
        'Link'          => 'Text',
        'ImgURL'        => 'Text'
    );

    private static $summary_fields = array(
        'Title',
        'Address',
        'PostCode',
        'Latitude',
        'Longitude'
    );

    private static $default_sort = 'Sort';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByname('ParentID');
        $fields->removeByname('Latitude');
        $fields->removeByname('Longitude');
        $fields->removeByname('Zoom');
        $fields->removeByname('Sort');

        $fields->addFieldToTab(
            "Root.Main",
            HeaderField::create(
                "InfoHeader",
                _t("GoogleMaps.InfoHeader", "Information about this location")
            ),
            "Title"
        );


        $fields->addFieldsToTab(
            "Root.Map",
            array(
                HeaderField::create(
                    "MapHeader",
                    _t("GoogleMaps.MapHeader", "Generate your map")
                ),
                GoogleMapField::create($this, "Find the location"),
                ReadOnlyField::create("Latitude"),
                ReadOnlyField::create("Longitude"),
                ReadOnlyField::create("Zoom")
            )
        );

        return $fields;
    }

    private function url_safe_address()
    {
        $address  = str_replace('/n', ',', $this->Address);
        $address .= ',' . $this->PostCode;

        return urlencode($address);
    }

    /**
     * Get the location for this map, either address / postcode or lat / long
     *
     * @return String
     */
    public function getLocation()
    {
        $location = false;

        if ($this->Address && $this->PostCode) {
            $location = $this->url_safe_address();
        }

        if ($this->Latitude && $this->Longitude) {
            $location = $this->Latitude . ',' . $this->Longitude;
        }

        return $location;
    }

    /**
     * Get a XML rendered version of the text address and post code
     *
     * @return String
     */
    public function getFullAddress()
    {
        return Convert::raw2xml($this->Address . '/n' . $this->PostCode);
    }

    /**
     * Link to Google Maps for directions etc
     *
     * @return String
     */
    public function Link()
    {
        $link = false;
        $location = $this->getLocation();

        if ($location) {
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
    public function ImgURL($width = 256, $height = 256)
    {
        $link = false;
        $location = $this->getLocation();

        if ($location) {
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

    public function canCreate($member = null)
    {
        return $this->Parent()->canCreate();
    }

    public function canView($member = null)
    {
        return $this->Parent()->canView();
    }

    public function canEdit($member = null)
    {
        return $this->Parent()->canEdit();
    }

    public function canDelete($member = null)
    {
        return $this->Parent()->canDelete();
    }
}
