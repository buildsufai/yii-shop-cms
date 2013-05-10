<?php $this->beginContent('//layouts/master'); ?>
<div class="middle">
    <div class="  container">
<?php

$this->widget('application.modules.catalog.widgets.slider.ProductSlider', array(
    'title'=>'Aanbiedingen',
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));

?>
    </div>
    </div>

<div class="header_bot">
    <div class="container_16">


        <div id="content" class="content">
            <?php echo $content; ?>
        </div>
        <div class="clearfix"></div>
        
    </div>
</div>

<?php $this->endContent(); ?>