<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class XHtml extends CHtml
{
    
    public static function cloudButton($name, $caption = '', $icon = '', $link = null, $onclick=null, $color='')
    {
        if(!empty($onclick))
        {
            if(strpos($onclick,'js:')!==0)
                $onclick='js:'.$onclick;
            $click = CJavaScript::encode($onclick);
            Yii::app()->clientScript->registerScript(__CLASS__.'#'.$name,"jQuery('#{$name}').click($click);");
        }
            
        if(!empty($icon) && !empty($caption) ) //text and icon
        {
            $href= (!empty($link)) ? ' href="'.$link.'"' : ""; 
            $style = (!empty($color)) ? " button-$color" : " button-grey"; 
            $html = '<a id="'.$name.'" name="'.$name.'"'.$href.' class="button'.$style.' button-text-icon" role="button">
            <span class="button-icon icon '.$icon.'"></span>
            <span class="button-text">'.$caption.'</span>
            </a>';
            return $html;
        }
        else if( empty($icon) && !empty($caption) ) //text only button
        {
            return "TODO";
        }
        else if( !empty($icon) && empty($caption) ) // icon only button
        {
            return "TODO";
        }
        
    }
    
    public static function redButton()
    {
        
    }
    
    public static function greyButton()
    {
        
    }
    
    public static function activeImageSelector($model, $attribute, $htmlOptions=array())
    {
        self::resolveNameID($model,$attribute,$htmlOptions);
	return self::activeInputField('hidden',$model,$attribute,$htmlOptions);
    }
    
    
    
    
    public static function hintLabel($model,$attribute, $text=false, $htmlOptions=array())
    {
        if(isset($htmlOptions['for']))
        {
            $for=$htmlOptions['for'];
            unset($htmlOptions['for']);
        }
        else
            $for=self::getIdByName(self::resolveName($model,$attribute));
        
            $label=$model->getAttributeLabel($attribute);
        if($model->hasErrors($attribute))
                self::addErrorCss($htmlOptions);
        if($text)
            return self::makeHintLabel($label,$text,$for,$htmlOptions);
        else
            return self::makeHintLabel($label,$attribute,$for,$htmlOptions);
    }
    
    public static function makeHintLabel($label, $attribute, $for,$htmlOptions=array())
    {
        if($for===false)
            unset($htmlOptions['for']);
        else
            $htmlOptions['for']=$for;
        if(isset($htmlOptions['required']))
        {
            if($htmlOptions['required'])
            {
                if(isset($htmlOptions['class']))
                    $htmlOptions['class'].=' '.self::$requiredCss;
                else
                    $htmlOptions['class']=self::$requiredCss;
                $label=self::$beforeRequiredLabel.$label.self::$afterRequiredLabel;
            }
            unset($htmlOptions['required']);
        }
        
        // Set up tooltips
        Yii::app()->clientScript->registerScript("hintLabel", "$('acronym').each(function() {
    var target = $(this);
    var tooltip = $('<div>')
        .addClass('tooltip-box')
        .html(target.attr('title'))
        .hide()
        .appendTo('body');
    target.removeAttr('title');

    target.hover(function() {
      // in
      var targetRect = target.offset();
      targetRect.width = target.width();
      targetRect.height = target.height();

      tooltip.css({
        left: ($(window).width() - tooltip.width()) / 2 + 'px',
        top: ($(window).height() - tooltip.height()) / 2 + 'px'
      });
      //tooltip.addClass('below');
      tooltip.fadeIn(500);
    }, function() {
      // out
      tooltip.hide();
    });
    tooltip.click( function() { tooltip.hide(); } );
  });", CClientScript::POS_READY);

        //TODO: make hint multi language? will use en_gb for now
        return self::tag('label', $htmlOptions, $label.'<acronym title="' . str_replace('"','&quot;', Yii::t('help', $attribute, array(), null, 'en_gb') ) . '">i</acronym>'
         );
    }
}
?>
