<div style="margin: 20px 0px 16px 20px; font-size: 17px; font-weight: bold;">
    <?php echo $title; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">Beste <?php echo $model->order->customer->name; ?>,<br>
<br>
Hierbij willen wij u op de hoogte brengen dat de status van uw bestelling is gewijzigd<br>
<br>
De status van uw bestelling is ingesteld op: <b><?php echo $model->statusText; ?></b><br>
<br>
Wij hopen u hierbij voldoende te hebben geinformeerd.<br>
<br>
Met vriendelijke groet,<br>
<br>
<?php echo Yii::app()->webshop->name; ?>
 </div>