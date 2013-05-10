<div style="margin: 20px 0px 16px 20px; font-size: 17px; font-weight: bold;">
    <?php echo $title; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">Beste <?php echo $model->order->shipping_name; ?>,<br>
<br>
Uw bestelling is vandaag door ons verzonden.<br>
<br>
Houdt u alstublieft rekening met het volgende: <br>
<ul>
	<li>Wij hebben geen invloed op het tijdstip van bezorgen. </li>
	<li>Indien u niet thuis bent, zal de postbode het pakket een dag later opnieuw aanbieden. Mocht u bij deze tweede bezorging ook niet thuis zijn, dan kunt u het pakket bij het postkantoor ophalen. U ontvangt dan een bericht van de postbode in uw brievenbus waarop staat waar u het pakket kunt afhalen. Hiervoor heeft u 3 weken de tijd. </li>
</ul>
<br>
Met vriendelijke groet,<br>
<br>
<?php echo Yii::app()->webshop->name; ?>
 </div>