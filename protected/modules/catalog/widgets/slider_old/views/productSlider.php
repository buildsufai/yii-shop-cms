<div id="slider">
    <a id="slide_prev">prev</a>
    <a id="slide_next">next</a>
    <div id="pager"></div>

    <div id="slideframe">
        
        <?php foreach($models as $model): ?>
        
        <div class="slide">
            
            <h2><?php echo CHtml::link( $model->name, $model->url); ?></h2>
            
            <div class="image_frame" style="float: left;">
                <?php if($model->thumb) echo CHtml::link(
                    CHtml::image($model->thumb, $model->name),
                    $model->url
                ); ?>
            </div>
            
            <div style="float: right">
                
                

                <span class="price"><?php echo $model->priceText; ?></span>
               
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
        
        <?php endforeach; ?>



    </div>

</div>