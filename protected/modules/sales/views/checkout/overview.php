<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shipping-form',
	'enableAjaxValidation'=>false,
)); ?>
    
<h2>Bestelling overzicht</h2>

<?php //echo CHtml::errorSummary(array_merge(array($model), $model->details)); ?>
<?php echo CHtml::errorSummary($model); ?>
    <fieldset>
        <legend>Verzend en betaal gegevens</legend>

        <div class="one_third">
            <b>Verzend methode</b><br>
            <?php echo $model->shippingText; ?><br>
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a><br>
            <br>

          </div>
          <div class="one_third">             <b>Verzend adres</b><br>
            <?php echo $model->shipping_address; ?><br>
            <?php echo $model->shipping_city; ?><br>
            <?php echo $model->shipping_postalcode; ?><br>
            <?php echo Country::getByID($model->shipping_country_code); ?><br>
            <?php echo $model->shipping_phone_nb; ?><br>
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a>
          </div>
          <div class="one_third last"><b><?php echo $model->getAttributeLabel('comment'); ?></b><br>
            <?php echo $model->comment; ?><br />
            <a href="<?php echo $this->createUrl('shipping'); ?>">Wijzigen</a>
          </div>

    </fieldset>


    <fieldset>
        <legend>Bestelling</legend>
	<?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'shopping-cart',
            'dataProvider' => new CArrayDataProvider($model->orderDetails),
            //'selectableRows' => 1,
            'emptyText' =>'Geen producten in deze bestelling',
            'template'=>'{items}',
            'columns' => array(

                array(
                    'header'=>'Naam',
                    'value' => '$data->name',
                ),
                array(
                    'header'=>'Aantal',
                    'value' => '$data->quantity',
                ),
                array(
                    'header' => 'Prijs',
                    'value' => '$data->priceText',
                    'htmlOptions'=>array('style'=>'text-align: right;'),
                    'headerHtmlOptions'=>array('style'=>'text-align: right;'),
                ),
                //'shipping_costs',
                array(
                    'header' => 'Totaal prijs',
                    'value' => '$data->totalText',
                    'htmlOptions'=>array('style'=>'text-align: right;'),
                    'headerHtmlOptions'=>array('style'=>'text-align: right;'),
                ),
            ),
        ));
        ?>
        <div id="totalen" style="text-align: right; margin-right: 6px;">
            Subtotaal: <?php echo $model->subTotalPriceText; ?><br>
            Verzendkosten: <?php echo Yii::app()->numberFormatter->formatCurrency($model->shipping_costs, "EUR"); ?><br>
            <strong>Totaal (incl BTW): <?php echo $model->totalPriceText; ?></strong>
        </div>
    </fieldset>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Bevestigen', array('name'=>'submit')); ?>
    </div>
<?php $this->endWidget(); ?>