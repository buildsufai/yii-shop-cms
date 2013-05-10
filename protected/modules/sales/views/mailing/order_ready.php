<div style="margin: 20px 0px 16px 20px; font-size: 17px; font-weight: bold;">
    <?php echo $title; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">Beste <?php echo $model->order->customer->name; ?>,<br>
<br>
Hierbij willen wij u op de hoogte brengen dat de bestelling die u geplaats heeft voor u klaar staat<br>
Wilt u eerst even contact (telefonisch of via de mail) met ons opnemen om een dag en tijd af te spreken, aangezien de winkel maar 1 dag in de week geopend is.<Br>
<br>
U kunt uw bestelling ophalen op het volgende adres:<br>
<br>
<strong><?php echo Yii::app()->webshop->name; ?></strong><br>
<?php echo Yii::app()->webshop->address; ?><br>
<?php echo Yii::app()->webshop->postalcode . " " . Yii::app()->webshop->place; ?><br>
<br>
<br>
Wij hopen u hierbij voldoende te hebben geinformeerd.<br>
<br>
Met vriendelijke groet,<br>
<br>
<?php echo Yii::app()->webshop->name; ?>
 </div>