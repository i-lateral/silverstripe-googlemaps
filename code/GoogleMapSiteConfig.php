<?php
/**
 * OomphMember is an extension to Member that allows for additional information
 * such as nickname and bio
 *
 * @author morven
 */
class GoogleMapSiteConfig extends DataObjectDecorator {
    public function extraStatics() {
        return array(
            'db' => array(
                'APIKey'   => 'Varchar(100)'
            )
        );
    }

    function updateCMSFields(FieldSet &$fields) {
        $fields->addFieldToTab('Root.Maps', new TextField('APIKey'));
    }
}