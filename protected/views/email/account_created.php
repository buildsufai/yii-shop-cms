
<div style="margin: 20px 0px 0px 20px; font-size: 17px; font-weight: bold;">
    <?php echo $title; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">
    <br />
<strong>Beste <?php echo $model->name; ?>,</strong><br><br>
<br>
Wij hebben voor u een account aangemaakt op bakkerijvandoorn.nl:<br>
<br>
U kunt inloggen met de volgende gegevens:<br>
<br>
<strong>Klant nr:</strong> <?php echo $model->id; ?><br>
<strong>Wachtwoord:</strong> <?php echo $model->key; ?><br>
<br>
Na het inloggen kunt u het wachtwoord wijzigen.<br>
Om in te loggen ga naar <a href="<?php echo Yii::app()->request->hostInfo; ?>"><?php echo Yii::app()->request->hostInfo; ?></a> en klik op "Zakelijk"

<br>


</div>