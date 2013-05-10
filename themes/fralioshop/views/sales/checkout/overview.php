        
            <div class="grid_12 content">
                
            <ol class="checkout_progress">
                <li class="done">1. Winkelwagen</li>
                <li class="done">2. Gegevens verzamelen</li>
                <li class="current last">3. Bestelling plaatsen</li>
            </ol>
<br />

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shipping-form',
	'enableAjaxValidation'=>false,
)); ?>

<h2>Bestelling overzicht</h2>

<?php //echo CHtml::errorSummary(array_merge(array($model), $model->details)); ?>
<?php echo CHtml::errorSummary($model); ?>
<?php echo CHtml::errorSummary($model->orderDetails); ?>
    <fieldset>
        <legend>Verzend en betaal gegevens</legend>

        <div class="col col_1_5 alpha">
            <b>Verzend methode</b><br>
            <?php echo $model->shippingText; ?><br>
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a><br>
            <br>

          </div>
          <div class="col col_1_4">             <b>Verzend adres</b><br>
            <?php echo $model->shipping_address; ?><br>
            <?php echo $model->shipping_city; ?><br>
            <?php echo $model->shipping_postalcode; ?><br>
            <?php echo Country::getByID($model->shipping_country_code); ?><br>
            <?php echo $model->shipping_phone_nb; ?><br>
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a>
          </div>
          <div class="col col_1_4 omega"><b><?php echo $model->getAttributeLabel('comment'); ?></b><br>
            <?php echo $model->comment; ?><br />
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a>
          </div>

    </fieldset>

        <div class="styled_table table_blue">
            <table class="items">
                <thead>
                    <tr>
                        <th>Product naam</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Totaal</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="checkbox-column">&nbsp;</td><td>&nbsp;</td>
                        <td>Subtotaal:<Br>
                            Verzendkosten: <br>
                            Totaal (incl BTW):</td>
                        <td><?php echo Yii::app()->shoppingCart->costText; ?><br>
                            <?php echo Yii::app()->numberFormatter->formatCurrency( Yii::app()->shoppingCart->shippingCosts, "EUR"); ?><br>
                            <?php echo Yii::app()->numberFormatter->formatCurrency( Yii::app()->shoppingCart->cost + $model->shippingCosts, 'EUR'); ?></td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach(Yii::app()->shoppingCart->getPositions() as $item): ?>
                    <tr>
                        <td><?php echo CHtml::link($item->name, $item->url); ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo $item->priceText; ?></td>
                        <td><?php echo $item->sumPriceText; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Bevestigen', array('name'=>'submit')); ?>
    </div>
<?php $this->endWidget(); ?>
</div>