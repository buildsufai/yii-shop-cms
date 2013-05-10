<h3><?php echo $title; ?></h3>
<ul>
    <?php if($new > 0): ?>
        <li>
		<?php echo CHtml::link(CHtml::encode('Nieuw'), Yii::app()->controller->createUrl('/catalog/default/productline', array('line'=>'nieuw'))); ?>
	</li>
    <?php endif; ?>
	<?php foreach($categories as $item): ?>
	<li>
		<?php echo CHtml::link(CHtml::encode($item->name), $item->getUrl()); ?>
	</li>
	<?php endforeach; ?>
</ul>