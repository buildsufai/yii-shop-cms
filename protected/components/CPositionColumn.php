<?php
/**
 * CPreviewColumn class file.
 *
 * @author Michael de Hart <derinus@gmail.com>
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2010 Cloud Engineering
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CPreviewColumn represents a grid view column that renders a thumbnail or icon in each of its data cells.
 *
 * Op het moment maakt het alleen gebruik van het Thumb widget om die weer te geen.
 * Later moet er ook support voor het weergeven van geluid en misschien video in.
 *
 * @author Michael de Hart <derinus@gmail.com>
 * @version $Id$
 * @package application.components
 * @since 1.0
 */
class CPositionColumn extends CGridColumn
{
        /**
         * @var string the attribute name of the data model. The corresponding attribute value will be rendered
         * in each data cell. If {@link value} is specified, this property will be ignored
         * unless the column needs to be sortable.
         * @see value
         * @see sortable
         */
        public $name;

        public $save_image = null;
        public $saveUrl = null;
        public $object_key = 'id'; //Primary key for post array
        public $form_id = '';
        /**
         * @var array the HTML options for the checkboxes.
         */
        public $positionHtmlOptions=array();

        /**
         * @var array the HTML options for the data cell tags.
         */
        public $htmlOptions=array('class'=>'position-column');
        /**
         * @var array the HTML options for the header cell tag.
         */
        public $headerHtmlOptions=array('class'=>'position-column');
        /**
         * @var array the HTML options for the footer cell tag.
         */
        public $footerHtmlOptions=array('class'=>'position-column');

        /**
         * Initializes the column.
         * This method registers necessary client script for the checkbox column.
         */
        public function init()
        {
                if(isset($this->positionHtmlOptions['name']))
                        $name=$this->positionHtmlOptions['name'];
                else
                {
                        $name=$this->name;
                        if(substr($name,-2)!=='[]')
                                $name.='[]';
                        $this->positionHtmlOptions['name']=$name;
                }
                $name=strtr($name,array('['=>"\\[",']'=>"\\]"));

                $cbcode="$('#{$this->form_id}').attr('action', '".$this->saveUrl."'); ";
                $cbcode.="$('#{$this->form_id}').submit();";

                $js=<<<EOD
$('#{$this->id}_save').live('click',function() {
        $cbcode
});
EOD;
                Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
        }


        /**
         * Renders the header cell content.
         * This method will render a link that can trigger the sorting if the column is sortable.
         */
        protected function renderHeaderCellContent()
        {
                if($this->name!==null)
                {
                        if($this->grid->dataProvider instanceof CActiveDataProvider)
                                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                        else
                                echo CHtml::encode($this->name);
                        if($this->save_image == null) //TODO: file_excists werkt niet
                            echo "&nbsp;".CHtml::link('Save', null, array('id'=>$this->id."_save"));
                        else
                            echo "&nbsp;".CHtml::link(CHtml::image($this->save_image), null, array('id'=>$this->id."_save"));
                }
                else
                        parent::renderHeaderCellContent();
        }

        /**
         * Renders the data cell content.
         * This method evaluates {@link value} or {@link name} and renders the result.
         * @param integer $row the row number (zero-based)
         * @param mixed $data the data associated with the row
         */
        protected function renderDataCellContent($row,$data)
        {
            $product_key = $data->{$this->object_key};
            $options=$this->positionHtmlOptions;
            $name="Positions[$product_key]";
            $options['id']=$this->id.'_'.$row;
            $options['size']=4;
            echo CHtml::textField($name, CHtml::value($data,$this->name), $options);
        }

}
