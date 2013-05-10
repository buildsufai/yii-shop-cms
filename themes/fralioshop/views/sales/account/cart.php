<?php
$this->breadcrumbs=array(
    'Winkelwagen',
);
?>

<div class="header_bot sidebarRight">
    
        <div class="container_16">
        
            <div class="grid_12 content">
                
            <ol class="checkout_progress">
                <li class="current">1. Winkelwagen</li>
                <li class="">2. Gegevens verzamelen</li>
                <li class="last">3. Bestelling plaatsen</li>
            </ol>
<br />
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'cart-form',
            'enableAjaxValidation' => false,
        ));
?>

<div class="styled_table table_blue">
<table class="items">
    <thead>
        <tr>
            <th>Verw?</th>
            <th>Afbeelding</th>
            <th>Product naam</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Totaal</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td class="checkbox-column">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Totaal:</td><td><?php echo Yii::app()->shoppingCart->costText; ?></td>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach(Yii::app()->shoppingCart->getPositions() as $item): ?>
        <tr>
            <?php $id = $item->getId(); ?>
            <td width="75"><?php echo CHtml::checkBox("CartItems[$id][markedDeleted]" ); ?></td>
            <td><?php echo CHtml::image($item->thumb); ?></td>
            <td><?php echo CHtml::link($item->name, $item->url); ?></td>
            <td><?php echo CHtml::textField("CartItems[$id][quantity]", $item->quantity, array('size'=>3, 'maxlength'=>4)); ?></td>
            <td><?php echo $item->costText; ?></td>
            <td><?php echo $item->sumPriceText; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<div class="row submit">
        <?php echo CHtml::submitButton('Mandje bijwerken', array('style'=>'float: left;', 'class'=>'button_link')); ?>
        <?php echo CHtml::link('<span>Naar de kassa</span>', array('/sales/checkout/shipping'), array('style'=>'float: right;','class'=>'button_link btn_pink')); ?>
    <div class="clearboth"> </div>
</div>

<?php $this->endWidget(); ?>

</div>
            <div class="grid_4 sidebar">
							<div class="sidebar_content related_products" >
								<h4>Aanbevolen producten</h4>
								<ul class="related_products">
									<?php foreach($related as $relProduct): ?>
									<li>
											<div class="image_frame"><?php if($relProduct->thumb) echo CHtml::image($relProduct->thumb, $relProduct->name); ?></div>
											<div class="text">
													<h4><a href="<?php echo $relProduct->getUrl(); ?>"><?php echo $relProduct->name; ?></a></h4>
													<span class="merk"><?php echo $relProduct->manufacturer; ?></span><br />
													<span class="price"><?php echo $relProduct->priceText; ?></span>
											</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>
            </div>
            
            <div class="grid_12 bargain">
                
                <h3>Kassakoopjes</h3>
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => new CArrayDataProvider(Product::model()->bargain()->findAll()),
                    'itemView' => '//catalog/default/_productlist_item',
                    'template' => "{pager}\n{items}\n<div class='clearboth'></div>{pager}",
                    'emptyText' => 'Geen kassakoopjes beschikbaar.',
                    'ajaxUpdate' => false,
                ));
                ?>
            </div>
            
        <div class="clearfix"></div>
        </div>
        
    </div>