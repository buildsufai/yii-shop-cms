<?php
$this->pageTitle='Login';
$this->breadcrumbs=array(
    'Login',
);
?>
<div class="header_bot">
    
        <div class="container_16">
    <div class="ui-widget-header">
        <h1>Inloggen of Registreren</h1>
    </div>
<Br />
<div class="col_1_2">

    <fieldset>
        <legend>Ik ben een nieuwe klant</legend>
        Als u een nieuwe klant bij ons bent hebben wij eerst uw naam en adres gegevens nodig.
        Deze gebruiken wij om uw bestelling naar u te verzenden.
    <br /><br />
    <div class="row buttons">
        <?php echo CHtml::link('<span>Account maken</span>', array('register'), array('class'=>'button_link')); ?>
    </div>
    </fieldset>
</div>
<div class="col_1_2 omega">
    <fieldset>
        <legend>Ik ben een bestaande klant</legend>
        <div class="form">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'enableAjaxValidation'=>false,
        )); ?>

                <div class="row">
                        <?php echo $form->labelEx($model,'email'); ?>
                        <?php echo $form->textField($model,'email', array('size'=>25)); ?>
                        <?php echo $form->error($model,'email'); ?>
                </div>

                <div class="row">
                        <?php echo $form->labelEx($model,'password'); ?>
                        <?php echo $form->passwordField($model,'password', array('size'=>25)); ?>
                        <?php echo $form->error($model,'password'); ?>
                </div>

                <div class="row">
                    <?php echo $form->label($model,'rememberMe'); ?>
                        <?php echo $form->checkBox($model,'rememberMe'); ?>
                        <?php echo $form->error($model,'rememberMe'); ?>
                    <br />
                </div>

                <div class="row buttons">

                        <?php echo CHtml::submitButton('Login'); ?>
                </div>

        <?php $this->endWidget(); ?>
        </div><!-- form -->
    </fieldset>
</div>
            
        </div>
    <div class="clear"></div>
</div>
