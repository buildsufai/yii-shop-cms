<?php
class FilterForm extends CFormModel
{
    public $name;
    public $manufacturer;
    public $search_minprice;
    public $search_maxprice;
    public $search_properties;
    
    public function rules()
    {
        return array(
            array('name, manufacturer, search_minprice, search_maxprice, search_properties', 'safe'),
        );
    }
}
