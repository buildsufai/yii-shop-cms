
<div style="margin: 20px 0px 0px 20px; font-size: 17px; font-weight: bold;">
    Uw bestelling bij <?php echo Yii::app()->administration->name; ?>
</div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">
    <br />
<strong>Beste <?php echo $model->customer->name; ?>,</strong><br><br>
Bedankt voor uw bestelling.<br>
<br>
Wij hebben de volgende bestelling van u mogen ontvangen:<br>
Bestellingen worden normaal gesproken de volgende dag door ons geleverd.
<br>
<br>
<strong>Bestel nr:</strong> <?php echo $model->id; ?><br>
<strong>Bestel Datum:</strong> <?php echo $model->createDateText; ?><br>
<strong>Bezorg Datum:</strong> <?php echo $model->deliverDateText; ?><br>
<strong>Opmerking:</strong> <?php echo nl2br($model->comment); ?>
<br>
<br>
<fieldset>
    <legend>Adres gegevens</legend>
    
    <table cellspacing="0" cellpadding="3" border="0" style="font-size: 12px;">
    <tr>
        <td><strong><?php echo $model->customer->getAttributeLabel('name'); ?></strong></td>
        <td><?php echo $model->customer->name; ?></td>
    </tr>
    <tr>
        <td style="background-color: #f0edea; vertical-align: top; width: 100px;"><strong><?php echo $model->customer->getAttributeLabel('address'); ?></strong></td>
        <td style="background-color: #f0edea;"><?php echo nl2br($model->customer->address); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $model->customer->getAttributeLabel('phone_nb'); ?></strong></td>
        <td><?php echo $model->customer->phone_nb; ?></td>
    </tr>
    <tr>
        <td style="background-color: #f0edea;"><strong><?php echo $model->customer->getAttributeLabel('email'); ?></strong></td>
        <td style="background-color: #f0edea;"><?php echo $model->customer->email; ?></td>
    </tr>
    </table>
</fieldset>

<br>
<fieldset><legend>Bestelling</legend>
    <table cellspacing="0" cellpadding="3" border="0" style="font-size: 12px;">
        <tbody><tr>
            <th style="padding-right: 20px; text-align: left;">Aantal</th>
            <th style="padding-right: 20px; text-align: left;">Artikel nr</th>
            <th style="padding-right: 20px; text-align: left;">Product</th>

        </tr>
        <?php $even = false; ?>
        <?php foreach($model->orderDetails as $detail): ?>
        <?php $background = ($even) ? ' style="background-color: #f0edea;"' : ''; ?>
        <tr>
            <td<?php echo $background; ?>><?php echo $detail->quantity; ?></td>
            <td<?php echo $background; ?>><?php echo $detail->serial; ?></td>
            <td<?php echo $background; ?>><?php echo $detail->name; ?></td>
        </tr>
        <?php $even = !$even; ?>
        <?php endforeach; ?>


    </tbody></table>
</fieldset>


</div>