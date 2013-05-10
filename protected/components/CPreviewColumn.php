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
class CPreviewColumn extends CGridColumn
{
        /**
         * @var string the label to the hyperlinks in the data cells. Note that the label will not
         * be HTML-encoded when rendering. This property is ignored if {@link labelExpression} is set.
         * @see labelExpression
         */
        public $alt;
        /**
         * @var string the URL to the image. If this is set, an image link will be rendered.
         */
        //public $imageUrl;
        /**
         * @var string the URL of the hyperlinks in the data cells.
         * This property is ignored if {@link urlExpression} is set.
         * @see urlExpression
         */
        public $url='javascript:void(0)';
        
        public $width = '150';
        public $height = '120';
        /**
         * @var array the HTML options for the data cell tags.
         */
        public $htmlOptions=array('class'=>'preview-column');
        /**
         * @var array the HTML options for the header cell tag.
         */
        public $headerHtmlOptions=array('class'=>'preview-column');
        /**
         * @var array the HTML options for the footer cell tag.
         */
        public $footerHtmlOptions=array('class'=>'preview-column');
        /**
         * @var array the HTML options for the hyperlinks
         */
        public $thumbHtmlOptions=array();

        /**
         * Renders the data cell content.
         * This method renders a thumbnail or icon in the data cell.
         * @param integer the row number (zero-based)
         * @param mixed the data associated with the row
         */
        protected function renderDataCellContent($row,$data)
        {
        	$imageurl = $this->evaluateExpression($this->url,array('data'=>$data,'row'=>$row));
					$alttext = '';
					if($this->alt != null)
						$alttext = $this->evaluateExpression($this->alt,array('data'=>$data,'row'=>$row));
        	
        	//TODO: make the previewcolumn work for multiple filestypes
        	//echo CHtml::image(Yii::app()->request->baseUrl . $imageurl, $alttext, array('width'=>$this->width, 'height'=>$this->height));
        	if($imageurl)
                   echo CHtml::image($imageurl, $alttext, array('width'=>$this->width));
			
			/*
                if($this->urlExpression!==null)
                        $url=$this->evaluateExpression($this->urlExpression,array('data'=>$data,'row'=>$row));
                else
                        $url=$this->url;
                if($this->labelExpression!==null)
                        $label=$this->evaluateExpression($this->labelExpression,array('data'=>$data,'row'=>$row));
                else
                        $label=$this->label;
                $options=$this->linkHtmlOptions;
                if(is_string($this->imageUrl))
                        echo CHtml::link(CHtml::image($this->imageUrl,$label),$url,$options);
                else
                        echo CHtml::link($label,$url,$options);
                        */
        }
}
