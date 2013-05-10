<div class="header_bot">
    
        <div class="container_16">

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo CHtml::errorSummary($model); ?>

    <?php if (Yii::app()->customer->hasFlash('accountSaved')): ?>
        <div class="alert_success">
            <?php echo Yii::app()->customer->getFlash('accountSaved'); ?>
        </div>
    <?php endif; ?>


    <fieldset>
        <legend>Persoonlijke Informatie</legend>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name', array('size'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

        <div class="row">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->textField($model,'company', array('size'=>30)); ?>
		<?php echo $form->error($model,'company'); ?>
	</div>

        <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('size'=>30)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_nb'); ?>
		<?php echo $form->textField($model,'phone_nb'); ?>
		<?php echo $form->error($model,'phone_nb'); ?>
	</div>
    </fieldset>

    <fieldset>
        <legend>Login gegevens</legend>
        Laat deze velden leeg om uw wachtwoord niet te wijzigen
        <div class="row">
		<?php echo $form->labelEx($model,'old_password'); ?>
		<?php echo $form->passwordField($model,'old_password'); ?>
		<?php echo $form->error($model,'old_password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->passwordField($model,'new_password'); ?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>

	<div class="row">
                <?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat'); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>
    </fieldset>

    <fieldset>
        <legend>Adres gegevens</legend>
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address', array('size'=>40)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postalcode'); ?>
		<?php echo $form->textField($model,'postalcode', array('size'=>10)); ?>
		<?php echo $form->error($model,'postalcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
                <?php echo $form->textField($model,'city', array('size'=>30)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'country_code'); ?>
                <?php echo $form->dropDownList($model, 'country_code', array('nl'=>'Nederland')); ?>
		<?php echo $form->error($model,'country_code'); ?>
	</div>
    </fieldset>

    <div class="row buttons">
		<?php echo CHtml::submitButton('Opslaan'); ?>
	</div>

    <fieldset>
        <legend>Bestelling geschiedenis</legend>

	<?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'orders-grid',
                    'dataProvider' => new CArrayDataProvider($model->orders),
                    'columns' => array(
                        //array('name'=>'serial_number', 'header'=>'thumb'),
                        //'id',
                        array(
                            'header'=>'Order nr',
                            'name' => 'id',
                        ),
                        array(
                            'name' => 'status',
                            'value' => '$data->statusText',
                            'filter' => Order::model()->statusTypes,
                        ),
                        array(
                            'header'=>'Verzenden',
                            'filter'=> Order::model()->shippingMethodes,
                            'value'=> '$data->shippingText',
                        ),
                        array(
                            'header'=>'Bestel datum',
                            'name'=>'create_date',
                            'value'=> 'Yii::app()->dateFormatter->formatDateTime($data->create_date, "long", null)',
                        ),
                        array(
                            'header'=>'prijs',
                            'name'=>'totalPrice',
                            'value'=>'$data->totalPriceText',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{factuur} {payment}',
                            'buttons'=>array(
                                'factuur' => array(
                                    'label'=>'Factuur',
                                    'url'=>'Yii::app()->controller->createUrl("invoice", array("id"=>$data->id))',
                                    'visible'=>'!empty($data->invoice_id)',
                                ),
                                'payment' => array(
                                    'label'=>'Afrekenen',
                                    'url'=>'Yii::app()->controller->createUrl("/sales/payment/index", array("id"=>$data->id))',
                                    'visible'=>'empty($data->invoice_id) && $data->isPayable()',
                                ),
                            ),
                        ),
                    ),
                ));
        ?>

    </fieldset>
<?php $this->endWidget(); ?>
</div><!-- form -->

        </div>
</div>