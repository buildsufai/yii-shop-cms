<?php $this->pageTitle='Contact'; ?>
<?php $this->layout='full'; ?>

<div id="content">
    
    <div class="grid_12 content">

   <h1>Contact</h1>
    <fieldset>
        
        <?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>
        
        <div class="form">

        <?php $form=$this->beginWidget('CActiveForm'); ?>

                <?php echo $form->errorSummary($model); ?>

                <div class="row">
                        <?php echo $form->label($model,'name'); ?>
                        <?php echo $form->textField($model,'name'); ?>
                </div>

                <div class="row">
                        <?php echo $form->label($model,'email'); ?>
                        <?php echo $form->textField($model,'email'); ?>
                </div>

                <div class="row">
                        <?php echo $form->label($model,'body'); ?>
                        <?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>60)); ?>
                </div>

                <div class="row submit">
                        <?php echo CHtml::submitButton('Verzenden'); ?>
                </div>

        <?php $this->endWidget(); ?>

        </div><!-- form -->
        <?php endif; ?>
        
    </fieldset>
        <br />
        <?php
            $this->widget('application.extensions.gmap.GMap', array(
                'id' => 'gmap', //id of the <div> container created
                'height' => '250px', // height of the gmap
                'width' => '100%', // width of the gmap
                'key' => Yii::app()->administration->google_maps_key, //goole API key, should be obtained for each site,it's free
                'label' => Yii::app()->administration->name, //text written in the text bubble
                'address' => array(
                    'address' => Yii::app()->administration->address, //address of the place
                    'city' => Yii::app()->administration->place, //city
                //'state' => 'CA'//state
                //'country' => 'USA' - country
                //'zip' => Yii::app()->administration->postalcode, // - zip or postal code
                )
            ));
    ?>
    </div>
    
    <div class="grid_4 sidebar">
        <div class="contact">
        <h4>Adres gegevens</h4>
        
        <div class="row">
            <label>Telefoon</label> <?php echo Yii::app()->administration->phone_nb; ?><br />
        </div>
        <div class="row">
            <label><?php echo Yii::t('lang', 'E-Mail'); ?></label> <a href="mailto:<?php echo Yii::app()->administration->email; ?>"><?php echo Yii::app()->administration->email; ?></a>
        </div>
        <div class="row">
            <label><?php echo Yii::t('lang', 'Address'); ?></label><div class="adres"><?php echo Yii::app()->administration->address; ?><br />
            <?php echo Yii::app()->administration->postalcode . " &nbsp;" . Yii::app()->administration->place; ?><br />
            <?php echo Country::getById(Yii::app()->administration->country_code); ?></div>
        </div>
        
        <h4>Rekening gegevens</h4>
        
        <div class="row">
            <label>Rabobank te</label> St. Michielsgestel<br />
        </div>
        <div class="row">
            <label>ten name van</label>Fralio-shop
        </div>
        <div class="row">
            <label>Rekening nummer</label>1032.77.412
        </div>
        
        <h4>Bedrijfsgegevens</h4>
        
        <div class="row">
            <label>KvK Nummer</label>17277993<br />
        </div>
        <div class="row">
            <label>Ingeschreven te</label>'s Hertogenbosch
        </div>
        <div class="row">
            <label>BTW Nummer</label>NL822050377B01
        </div>
        </div>
    </div>
    
</div>





