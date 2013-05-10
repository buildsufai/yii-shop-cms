<?php
$this->pageTitle = 'Login';
?>

<h2>Inloggen</h2>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 25)); ?>
<?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 25)); ?>
<?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'rememberMe'); ?>
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
<?php echo $form->error($model, 'rememberMe'); ?>
        <br />
    </div>

    <div class="row buttons">

<?php echo CHtml::submitButton('Login'); ?>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<h2>Heeft u nog geen account?</h2>

<p>
    Als u een nieuwe klant bij ons bent hebben wij eerst uw naam en adres 
    gegevens nodig. Deze gebruiken wij om uw bestelling naar u te verzenden. 
    Uw bestellingen worden bewaard in uw account en kunnen zo bekeken en 
    opnieuw besteld worden.
</p>
<div class="row buttons">
<?php echo CHtml::link('Account maken', array('register')); ?>
</div>
