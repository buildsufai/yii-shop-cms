<?php
$this->pageTitle=Yii::app()->name . ' - Error';
?>

<div class="header_bot">
    <div class="container_16">
    <h1>Error <?php echo $code; ?></h1>
    
	<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
    
<div class="clearfix"></div>
    </div>
</div>