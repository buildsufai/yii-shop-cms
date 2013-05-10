
<div style="margin: 20px 0px 0px 20px; font-size: 17px; font-weight: bold;">Uw bestelling bij <a target="_blank" href="<?php echo Yii::app()->request->hostInfo; ?>"><?php echo Yii::app()->webshop->name; ?></a></div>
<div style="margin-left: 20px; font-size: 12px; margin-right: 20px;">
    <br />
<strong>Beste <?php echo $model->customer->name; ?>,</strong><br><br>
Bedankt voor uw bestelling.<br>
<br>
 <br>
Wij proberen er alles aan te doen om aan uw verwachtingen te voldoen
en hopen dat u tevreden zult zijn.<br>
<br>
<br>
<strong style="font-size: 14px;">Nieuwe bestelling Nr <?php echo $model->id; ?>: <?php echo $model->create_date; //TODO: formate date?>
</strong><br>
<br>
<table cellspacing="1" cellpadding="1" border="0" style="width: 100%; font-size: 12px;">
    <tbody>
        <tr>
            <td><strong>Adres gegevens</strong></td>
        </tr>
        <tr>
            <td valign="top">
                <?php if(empty($model->company)): ?>
                    <?php echo $model->shipping_name; ?><br>
                <?php else: ?>
                    <?php echo $model->company?><br>
                    TAV: <?php echo $model->shipping_name; ?><br>
                <?php endif; ?>
                <?php echo $model->shipping_address; ?> <br>
                <?php echo $model->shipping_postalcode; ?>&nbsp; <?php echo $model->shipping_city; ?><br>
            </td>
        </tr>
    </tbody>
</table>
<br>
<strong>Bestelling afronden</strong><br>
<br>
<?php if($model->shipping_methode == Order::SHIPPING_PICKUP): ?>
U heeft ervoor gekozen om de bestelling bij ons op te halen.<br>
Wij sturen u een email zodra uw bestelling voor u klaar ligt.<br>
U kunt de bestelling gewoon betalen zodra u deze bij ons ophaald<br>

<?php elseif($model->shipping_methode == Order::SHIPPING_POSTAL): ?>
U heeft ervoor gekozen om uw bestelling door ons te laten verzenden.<br>
Zodra wij uw bestelling verzenden zullen wij u hiervan via email op de hoogte stellen<br>
<br>
<strong>Betaling nog niet voldaan?</strong><br>
Wij zullen u bestelling verzenden zodra wij het aankoop bedrag van u ontvangen hebben.<br>
Heeft u het aankoop bedrag nog niet naar ons over gemaakt bieden wij de volgende betaal methodes:<br>
<br>
<strong>Via iDeal:</strong> <a href="<?php echo Yii::app()->request->hostInfo . $this->createUrl('/sales/payment/index', array('id'=>$model->id)); ?>">Online betalen</a><br>
<br>
<strong>Bank transactie</strong><br>
Rekening nr: <?php echo Yii::app()->params['bank_nr']; ?> (Rabobank)<br />
ter attentie van: <?php echo Yii::app()->webshop->name; ?><br />
vermeld het volgende order nummer bij de transactie: <strong>#<?php echo $model->id; ?></strong>
<?php endif; ?>
<br>
<fieldset><legend>Bestelling</legend>
    <table cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
        <tbody><tr>
            <th style="text-align: left;">Aantal</th>
            <th style="text-align: left;">Artikelnr</th>
            <th style="text-align: left;">Product</th>
            <th style="text-align: right;">Prijs</th>
            <th style="text-align: right;">Totaal</th>

        </tr>

        <?php foreach($model->orderDetails as $detail): ?>
        <tr>
            <td><?php echo $detail->quantity; ?></td>
            <td><?php echo $detail->sku; ?></td>
            <td><?php echo $detail->name; ?></td>

            <td style="text-align: right;"><?php echo $detail->priceText; ?></td>
            <td style="text-align: right;"><?php echo $detail->totalText; ?></td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td style="text-align: right;" colspan="4">Subtotaal:</td>
            <td style="text-align: right;"><?php echo $model->subTotalPriceText; ?></td>
        </tr>
        <tr>
            <td style="border: medium none; text-align: right;" colspan="4">Verzend- en administratiekosten:</td>
            <td style="border: medium none; text-align: right;"><?php echo $model->shippingCosts ;?></td>
        </tr>

        <tr>
            <td style="border: medium none; text-align: right;" colspan="4">
                Totaal&nbsp;<span style="font-size: 11px;">(Incl.&nbsp;21% BTW)</span>:
            </td>
            <td style="border: medium none; text-align: right; font-weight: bold;"><?php echo $model->totalPriceText; ?></td>

        </tr>

    </tbody></table>
</fieldset>


</div>