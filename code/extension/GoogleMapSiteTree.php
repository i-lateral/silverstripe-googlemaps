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
class GoogleMapSiteTree extends DataExtension {

    public static $db = array(
        'ShowMap'   => 'Boolean',
        'StaticMap' => 'Boolean'
    );
    
    public static $has_many = array(
        'Maps' => "GoogleMap"
    );
    
    public function updateCMSFields(FieldList $fields) {
        $maps_field = new GridField(
            'Maps',
            '',
            $this->owner->Maps(),
            GridFieldConfig_RecordEditor::create()
        );
        
        // Tidy up category config and remove default add button
		$field_config = $maps_field->getConfig();
		$field_config
            ->removeComponentsByType('GridFieldAddNewButton')
            ->addComponent(new GridFieldSortableRows('Sort'));

        // Add creation button if member has create permissions
        if($this->owner->canCreate()) {
		    $add_button = new GridFieldAddNewButton('toolbar-header-left');
		    $add_button->setButtonName('Add Google Map');
		    
            $field_config->addComponent($add_button);
        }
        
        $fields->addFieldToTab('Root.Maps', $maps_field);
    
        return $fields;
    }
    
    public function updateSettingsFields(FieldList $fields) {
        $maps_group = FieldGroup::create(
            CheckboxField::create("ShowMap", "Enable maps on this page?"),
            CheckboxField::create("StaticMap", "Render maps as images?")
        )->setTitle('Google Maps');
    
        $fields->addFieldToTab("Root.Settings", $maps_group);
        
        return $fields;
    }
}
