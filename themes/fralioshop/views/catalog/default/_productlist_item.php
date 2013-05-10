<div class="product_item">
    <div class="image_frame">
        <?php if($data->thumb) echo CHtml::link(
                CHtml::image($data->getThumb('small'), $data->name),
                $data->url
            ); ?>
    </div>
    <div class="right">
    
        <div class="like-buttons">
					<?php if($data->rating != 0): ?>
					<?php $this->widget('CStarRating',array('model'=>$data, 'attribute'=>'rating', 'readOnly'=>true, 'htmlOptions'=>array('style'=>'float:right;'))); ?>
						<?php //echo "Waardering: ". $data->rating; ?>
					<?php endif; ?>
					<div style="clear:both;"></div>
				</div>
        <!--<a href="#" class="like">Leuk</a><a href="#" class="hate">Niet leuk</a>
        
        <div class="like-bar"><div class="like-bar-progress"></div></div>-->
         <?php if(!empty($data->sale_price)): ?>
                    <span class="oldprice"><?php echo $data->priceText; ?></span><br />
                    <span class="saleprice"><?php echo $data->salePriceText; ?></span>
        <?php else: ?>
                    <span class="price"><?php echo $data->priceText; ?></span>
         <?php endif; ?>
        <div class="product_status">Status: <span class="<?php echo ($data->status == Product::STATUS_ONLINE) ? 'instock' : 'soldout'; ?>"> <?php echo $data->statusText; ?></span></div>
    </div>
    <div class="info">
    <h2><?php echo CHtml::link( $data->name, $data->url); ?></h2>
    <p><?php echo $data->getShortDescription(90); ?></p>
    </div>
    <div style="clear: right;"></div>	
            <div style="float: right;">
    <a class="button_link btn_grey" href="<?php echo $data->url; ?>"><span>MEER INFORMATIE</span></a>
    <a class="button_link btn_pink" href="<?php echo $this->createUrl('/sales/account/addProduct', array('product_id'=>$data->id)); ?>"><span>IN WINKELWAGEN</span></a>	
    </div>
        <div class="clearfix"></div>	
</div>