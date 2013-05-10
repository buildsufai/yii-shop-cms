
<div style="margin: 20px 0px 0px 20px; font-size: 17px; font-weight: bold;">
    Uw bestelling bij <?php echo Yii::app()->administration->name; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">
    <br />
<strong>Beste <?php echo $model->naam; ?>,</strong><br><br>
Bedankt voor uw bestelling.<br>
<br>
Wij hebben de volgende bestelling van u mogen ontvangen:<br>
<br>
<br>
<strong>Afhaalvestiging:</strong> <?php echo $model->filiaal; ?><br></br>
<strong>Uw naam:</strong> <?php echo $model->naam; ?><br>
<strong>Telefoon nr:</strong> <?php echo $model->telefoon; ?><br>
<strong>Email:</strong> <?php echo $model->email; ?><br>
<strong>Uw Adres:</strong> <?php echo nl2br($model->adres); ?>
<br>

<br>
<fieldset><legend>Bestelling</legend>
    <table cellspacing="0" cellpadding="3" border="0" style="font-size: 12px;">
        <tbody><tr>
            <th style="padding-right: 20px; text-align: left;">Product naam</th>
            <th style="padding-right: 20px; text-align: left;">st. prijs</th>
            <th style="padding-right: 20px; text-align: left;">Aantal</th>
            <th style="padding-right: 20px; text-align: left;">Kosten</th>

        </tr>
        <?php $totaal_prijs = 0; ?>
        <?php $even = false; ?>
        <?php foreach($model->getProducts() as $i => $value): ?>
        <?php if(is_numeric($products[$i]) && $products[$i] > 0): ?>
        <?php $background = ($even) ? ' style="background-color: #f0edea;"' : ''; ?>
        <tr>
            <td<?php echo $background; ?>><?php echo $value['naam']; ?></td>
            <td<?php echo $background; ?>><?php echo Yii::app()->numberFormatter->formatCurrency($value['prijs'], 'EUR'); ?></td>
            <td<?php echo $background; ?>><strong><?php echo $products[$i]; ?></strong></td>
            <?php $kostenp = $products[$i] * $value['prijs']; ?>
            <td<?php echo $background; ?>><?php echo Yii::app()->numberFormatter->formatCurrency($kostenp, 'EUR'); ?></td>
        </tr>
        <?php $totaal_prijs += $kostenp ?>
        <?php $even = !$even; ?>
        <?php endif; ?>
        <?php endforeach; ?>

        <tr>
            <td colspan="4" style="text-align: right;"><strong>Totaal prijs: <?php echo Yii::app()->numberFormatter->formatCurrency($totaal_prijs, 'EUR'); ?></strong></td>
        </tr>
    </tbody></table>
    
</fieldset>


</div>