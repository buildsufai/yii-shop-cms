<div id="slider">

      <ol>
        
        <?php foreach($models as $model): ?>
        
        <li>
					<h2><span><?php echo $model->name; ?></span></h2>
					<div>
            <h2><?php echo CHtml::link( $model->name, $model->url); ?></h2>
            
            <div class="image_frame" style="float: left; height: 190px; width: 100px;">
                <?php if($model->thumb) echo CHtml::link(
                    CHtml::image($model->thumb, $model->name),
                    $model->url
                ); ?>
            </div>
            
            <div style="float: right">
                
                

                <span class="price">
									<?php echo $model->strokedPriceText; ?>
								</span>
               
            </div>
            <div class="info">
             <div class="product_status">Status: <span class="<?php echo ($model->status == Product::STATUS_ONLINE) ? 'instock' : 'soldout'; ?>"> <?php echo $model->statusText; ?></span></div>
            <p><?php echo $model->getShortDescription(90); ?></p>
            </div>
            <div style="clear: right;"></div>	
                    <div style="float: right;">
            <a class="button_link btn_pink" href="<?php echo Yii::app()->controller->createUrl('/sales/account/addProduct', array('product_id'=>$model->id)); ?>"><span>IN WINKELWAGEN</span></a>	
            </div>
					</div>
        </li>
        
        <?php endforeach; ?>



    </ol>

</div>