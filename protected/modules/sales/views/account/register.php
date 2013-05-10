<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableAjaxValidation'=>false,
)); ?>
    <?php echo CHtml::errorSummary($model); ?>

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

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
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

    <div class="row">
        
        <?php echo $form->checkBox($model, 'agree_terms'); ?>
        <?php echo $form->labelEx($model,'agree_terms', array('style'=>'width: 300px;')); ?>
        <?php echo $form->error($model,'agree_terms'); ?>
        
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Aanmelden'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->