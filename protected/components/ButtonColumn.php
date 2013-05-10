<?php
class ButtonColumn extends CButtonColumn 
{
		/**
         * @var string the attribute name of the data model. The corresponding attribute value will be rendered
         * in each data cell. If {@link value} is specified, this property will be ignored
         * unless the column needs to be sortable.
         * @see value
         * @see sortable
         */
        public $name;
        /**
         * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
         * as the content of the data cells. In this expression, the variable
         * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
         * and <code>$this</code> the column object.
         */
        public $value;
        /**
         * @var string the type of the attribute value. This determines how the attribute value is formatted for display.
         * Valid values include those recognizable by {@link CGridView::formatter}, such as: raw, text, ntext, html, date, time,
         * datetime, boolean, number, email, image, url. For more details, please refer to {@link CFormatter}.
         * Defaults to 'text' which means the attribute value will be HTML-encoded.
         */
        public $type='text';
        /**
         * @var boolean whether the column is sortable. If so, the header celll will contain a link that may trigger the sorting.
         * Defaults to true. Note that if {@link name} is not set, or if {@link name} is not allowed by {@link CSort},
         * this property will be treated as false.
         * @see name
         */
        public $sortable=true;
        /**
         * @var mixed the HTML code representing a filter input (e.g. a text field, a dropdown list)
         * that is used for this data column. This property is effective only when
         * {@link CGridView::enableFiltering} is set true.
         * If this property is not set, a text field will be generated as the filter input;
         * If this property is an array, a dropdown list will be generated that uses this property value as
         * the list options.
         * @since 1.1.1
         */
        public $filter;

        /**
         * Initializes the column.
         */
        public function init()
        {
                parent::init();
                if($this->name===null)
                        $this->sortable=false;
                if($this->name===null && $this->value===null)
                        throw new CException(Yii::t('zii','Either "name" or "value" must be specified for CDataColumn.'));
        }

        /**
         * Renders the filter cell content.
         * This method will render the {@link filter} as is if it is a string.
         * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
         * Otherwise if {@link filter} is not false, a text field is rendered.
         * @since 1.1.1
         */
        protected function renderFilterCellContent()
        {
                if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strpos($this->name,'.')===false)
                {
                        if(is_array($this->filter))
                                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id'=>false,'prompt'=>''));
                        else if($this->filter===null)
                                echo CHtml::activeTextField($this->grid->filter, $this->name, array('id'=>false));
                        else
                                echo $this->filter;
                }
                else
                        parent::renderFilterCellContent();
        }

        /**
         * Renders the header cell content.
         * This method will render a link that can trigger the sorting if the column is sortable.
         */
        protected function renderHeaderCellContent()
        {
                if($this->grid->enableSorting && $this->sortable && $this->name!==null)
                        echo $this->grid->dataProvider->getSort()->link($this->name,$this->header);
                else if($this->name!==null && $this->header===null)
                {
                        if($this->grid->dataProvider instanceof CActiveDataProvider)
                                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                        else
                                echo CHtml::encode($this->name);
                }
                else
                        parent::renderHeaderCellContent();
        }

        /**
         * Renders the data cell content.
         * This method evaluates {@link value} or {@link name} and renders the result.
         * @param integer the row number (zero-based)
         * @param mixed the data associated with the row
         */
        protected function renderDataCellContent($row,$data)
        {
                if($this->value!==null)
                        $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
                else if($this->name!==null)
                        $value=CHtml::value($data,$this->name);
                echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
                echo "<br />";
                echo "<div class=\"row-buttons\">";
                $tr=array();
                ob_start();
                foreach($this->buttons as $id=>$button)
                {
                        $this->renderButton($id,$button,$row,$data);
                        $tr['{'.$id.'}']=ob_get_contents();
                        ob_clean();
                }
                ob_end_clean();
                echo strtr($this->template,$tr);
                echo "</div>";
        }
        
        /**
         * Renders a link button.
         * @param string the ID of the button
         * @param array the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
         * See {@link buttons} for more details.
         * @param integer the row number (zero-based)
         * @param mixed the data object associated with the row
         */
        protected function renderButton($id,$button,$row,$data)
        {
        	if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
                        return;
            $label=isset($button['label']) ? $button['label'] : $id;
            $url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';  
            $options=isset($button['options']) ? $button['options'] : array();
            
        	echo CHtml::link($label, $url , $options);

        		//TODO: add support for icons on buttons
                /*if(isset($button['imageUrl']) && is_string($button['imageUrl']))
                        echo CHtml::link(CHtml::image($button['imageUrl'],$label),$url,$options);
                else
                        echo CHtml::link($label,$url,$options);*/
        }
        
        
}