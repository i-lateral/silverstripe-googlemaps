<?php

/**
 * @author morven
 *
 */
 
class GoogleMapSiteConfig extends DataExtension
{
    private static $db = array(
        'APIKey'   => 'Varchar(100)'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Main', TextField::create('APIKey', 'Google Maps API Key'));
    }
}
