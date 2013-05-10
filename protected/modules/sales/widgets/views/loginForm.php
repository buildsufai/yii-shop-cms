<div class="form">
	<?php echo CHtml::form(); ?>
		<div class="row">
			<?php echo CHtml::activeLabel($user,'email'); ?>
			<?php echo CHtml::activeTextField($user,'email') ?>
			<?php echo CHtml::error($user,'email'); ?>
		</div>
		
		<div class="row">
			<?php echo CHtml::activeLabel($user,'password'); ?>
			<?php echo CHtml::activePasswordField($user,'password') ?>
			<?php echo CHtml::error($user,'password'); ?>
		</div>
                <?php if($this->enableRememberMe): ?>
                        <p class="forgetmenot"><?php echo CHtml::activeCheckBox($user,'rememberMe'); ?><label>Onthouden?</label></p>
                        <p class="forget_password"><a href="#">Wachtwoord kwijt?</a></p>
                <?php endif; ?>
		
		<div class="action submit">
			<?php echo CHtml::submitButton('Inloggen'); ?>
                    <a href="<?php echo Yii::app()->controller->createUrl('/sales/account/register'); ?>">Registeren</a>
		</div>
	
	<?php echo CHtml::endForm(); ?>
</div>