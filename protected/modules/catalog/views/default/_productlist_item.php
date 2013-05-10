<div class="product_wrapper">
<div class="product_inner">
    <div class="product">
    <h2 style="text-align: center; width: 100%;"><?php echo CHtml::link( $data->name, $this->createUrl('product', array('sku'=>$data->sku))); ?></h2>
    <?php if(!empty($data->thumb)): ?>
    <div class="image_frame">
        <?php CHtml::link( CHtml::image($data->thumb, $data->name), $this->createUrl('/catalog/default/product', array('sku'=>$data->sku)) ); ?>

    </div>
    <?php endif; ?>
    
    <p><?php echo $data->getShortDescription(90); ?><br/></p>
   
    </div>
     <div class="price">
         <?php if($data->isBuyable): ?>
            <a class="cart" href="<?php echo $this->createUrl('/sales/account/addProduct', array('product_id'=>$data->id)); ?>">Bestellen</a>
         <?php else: ?>
            <a class="cart" href="<?php echo $this->createUrl('/site/contact'); ?>">Contact</a>
         <?php endif; ?>
         <?php echo $data->priceText; ?>
     </div>
</div>
</div>