<h1>Registratie voltooid</h1>
<p>
    Uw account is succesvol aangemaakt!
</p>
<p>
    Met uw account op <?php echo Yii::app()->webshop->name; ?> heeft u de mogelijkheid
    om terug te kijken in uw bestelling geschiedenis. En de status van uw orders in de
    gaten te houden.
</p>
<p>
    Als u verder nog vragen heeft over het gebruik van onze online shop kunt u ons
    bereiken via het volgende email adres <a href="mailto:<?php echo Yii::app()->webshop->email; ?>"><?php echo Yii::app()->webshop->email; ?></a>
</p>
<p>
    Een bevestigings email is verzonden naar het door u ingevulde email adres.
    Hierin kunt u uw login gegevens terug vinden bewaar deze email dus goed.
</p>
<div class="row buttons">
        <?php echo CHtml::link('<span>Login</span>', array('login'), array('class'=>'button_link')); ?>
    </div>