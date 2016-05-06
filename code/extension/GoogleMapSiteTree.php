<?php

/**
 * This class adds maps support to the CMS, allowing you to tick "Show maps"
 * under the settings pane. This then adds the Maps gridfield to the content
 * fields. 
 *
 * @author nicolaas[at] sunnysideup.co.nz
 * @author morven [at] i-lateral.com
 *
 **/
class GoogleMapSiteTree extends DataExtension
{

    private static $db = array(
        'ShowMap'   => 'Boolean',
        'StaticMap' => 'Boolean'
    );
    
    private static $has_many = array(
        'Maps' => "GoogleMap"
    );
    
    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->ShowMap) {
            $maps_field = new GridField(
                'Maps',
                '',
                $this->owner->Maps(),
                $config = GridFieldConfig_RecordEditor::create()
            );
            
            $config->addComponent(new GridFieldOrderableRows('Sort'));

            // Add creation button if member has create permissions
            if ($this->owner->canCreate()) {
                $config->removeComponentsByType('GridFieldAddNewButton');
                $add_button = new GridFieldAddNewButton('toolbar-header-left');
                $add_button->setButtonName(_t("GoogleMaps.AddGoogleMap", "Add Google Map"));
                $config->addComponent($add_button);
            }
            
            $fields->addFieldToTab('Root.Maps', $maps_field);
        }
    
        return $fields;
    }
    
    public function updateSettingsFields(FieldList $fields)
    {
        $maps_group = FieldGroup::create(
            CheckboxField::create("ShowMap", "Enable maps on this page?"),
            CheckboxField::create("StaticMap", "Render maps as images?")
        )->setTitle('Google Maps');
    
        $fields->addFieldToTab("Root.Settings", $maps_group);
        
        return $fields;
    }
}
