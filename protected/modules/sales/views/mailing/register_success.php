<div style="margin: 20px 0px 16px 20px; font-size: 17px; font-weight: bold;">
    <?php echo $title; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">
Beste <?php echo $model->name; ?>,<br />
<br />
Welkom bij <?php echo Yii::app()->webshop->name; ?>. Een account is aangemaakt met de volgende gegevens:<br />
<br />
Uw email adres: <strong><?php echo $model->email; ?></strong><br />
Uw wachtwoord: <strong><?php echo $model->real_password; ?></strong><br />
<br />
Bewaar deze gegevens zorgvuldig. Bij het afronden van een volgende bestelling kunt u deze gegevens gebruiken om nog sneller te bestellen.<br />
<br />
Met vriendelijke groet,<br />
<br />
<?php echo Yii::app()->webshop->name; ?>
 </div>