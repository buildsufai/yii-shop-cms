<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shipping-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo CHtml::errorSummary($model); ?>

    <fieldset>
        <legend>Verzend adres</legend>
        <div class="row">
		<?php echo $form->labelEx($model,'shipping_name'); ?>
		<?php echo $form->textField($model,'shipping_name', array('size'=>40)); ?>
		<?php echo $form->error($model,'shipping_name'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->textField($model,'company', array('size'=>40)); ?>
		<?php echo $form->error($model,'company'); ?>
	</div>
	<div class="row">
            <?php echo $form->labelEx($model,'shipping_address'); ?>
            <?php echo $form->textField($model,'shipping_address', array('size'=>40)); ?>
            <?php echo $form->error($model,'shipping_address'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'shipping_city'); ?>
            <?php echo $form->textField($model,'shipping_city', array('size'=>30)); ?>
            <?php echo $form->error($model,'shipping_city'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'shipping_postalcode'); ?>
            <?php echo $form->textField($model,'shipping_postalcode', array('size'=>10)); ?>
            <?php echo $form->error($model,'shipping_postalcode'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'shipping_country_code'); ?>
            <?php echo $form->dropDownList($model, 'shipping_country_code', array('nl'=>'Nederland')); ?>
            <?php echo $form->error($model,'shipping_country_code'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'shipping_phone_nb'); ?>
            <?php echo $form->textField($model,'shipping_phone_nb'); ?>
            <?php echo $form->error($model,'shipping_phone_nb'); ?>
        </div>

	
    </fieldset>
<?php if(count($model->shippingMethodes) > 1): ?>
    <fieldset>
        <legend><?php echo $model->getAttributeLabel('shipping_methode'); ?></legend>
            <div class="wide">
            <?php echo $form->radioButtonList($model,'shipping_methode', $model->shippingMethodes); ?>
             <?php echo $form->error($model,'shipping_methode'); ?>
            </div>
    </fieldset>
<?php else: ?>
    <?php echo $form->hiddenField($model, 'shipping_methode'); ?>
<?php endif; ?>

    <fieldset>
        <legend>Extra opties</legend>

	<div class="row">
            <div class="wide">
                <?php echo $form->labelEx($model, 'comment'); ?>
            </div>
		<?php echo $form->textArea($model,'comment'); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>
    </fieldset>

    <div class="row buttons">
	<?php echo CHtml::submitButton('Afronden'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->