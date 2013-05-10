<?php
/**
 * CartMenu
 *
 * @version 0.01
 * @author Michael de Hart <info@cloudengineering.nl>
 */
class CartMenu extends CWidget
{
	public $htmlOptions=array();
	public $tagName='ul';
        public $itemTemplate = '<li id="cart_{id}"><img src="{thumb}" width="32" height="32" /><span class="text">{title}</span><span class="amount">Aantal: {quantity}</span><div class="price">{price}</div></li>';

	public function run()
	{
            echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
            
            foreach(Yii::app()->shoppingCart->getPositions() as $i=>$item)
            {
                echo strtr($this->itemTemplate,array(
                    '{id}'=>$i,
                    '{title}'=>$item->name,
                    '{price}'=>$item->priceText,
                    '{quantity}'=>$item->quantity,
                    '{thumb}'=>$item->thumb)
                )."\n";
            }
        
            echo CHtml::closeTag($this->tagName)."\n";
	}
}