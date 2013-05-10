<?php
$this->breadcrumbs=array(
    'Winkelwagen',
);
?>

<h2>Winkelwagen</h2>

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'cart-form',
            'enableAjaxValidation' => false,
        ));
?>

<table class="items">
    <thead>
        <tr>
            <th>Afbeelding</th>
            <th>Product naam</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Totaal</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Totaal:</td><td><?php echo Yii::app()->shoppingCart->costText; ?></td>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach(Yii::app()->shoppingCart->getPositions() as $item): ?>
        <tr>
            <td><?php echo CHtml::image($item->thumb); ?></td>
            <td><?php echo CHtml::link($item->name, $item->url); ?></td>
            <td><?php echo $item->quantity; ?></td>
            <td><?php echo $item->priceText; ?></td>
            <td><?php echo $item->sumPriceText; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<div class="row submit">
        <?php echo CHtml::submitButton('Mandje bijwerken', array('style'=>'float: left;', 'class'=>'button_link')); ?>
        <?php echo CHtml::link('Naar de kassa', array('/sales/checkout/shipping')); ?>
</div>

<?php $this->endWidget(); ?>
