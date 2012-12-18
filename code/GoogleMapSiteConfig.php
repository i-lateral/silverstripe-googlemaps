<?php

/**
 * @author morven
 *
 */
 
class GoogleMapSiteConfig extends DataExtension {
    public static $db = array(
        'APIKey'   => 'Varchar(100)'
    );

    function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab('Root.Maps', TextField::create('APIKey'));
    }
}
