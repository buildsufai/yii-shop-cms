<?php
$this->widget('application.extensions.fancybox.EFancyBox', array(
    'target' => 'a[rel=product_img]',
    'config' => array(
        'transitionIn' => 'elastic',
        'transitionOut' => 'elastic',
    ),
    )
);
?>

<h1><?php echo $model->name; ?></h1><hr />
<?php if($model->isBuyable): ?>
<div style="float: right;">
    <a class="button_link" href="<?php echo $this->createUrl('/sales/account/addProduct', array('product_id'=>$model->id)); ?>">In Winkelwagen</a>
</div>
<?php endif; ?>
    
<div class="images">
<?php $media = $model->getImage(Product::MEDIA_BIG_IMAGE); ?>
<?php if ($media): ?>

    <div class="big">
        <?php
        Yii::app()->controller->widget("application.components.Thumb", array(
            "groupName" => 'product_img',
            "imageurl" => $media->url,
            "url" => Yii::app()->request->baseUrl . $media->url,
            "alt" => $media->name,
            "width" => 320,
            "height" => 240,
            "resize" => Thumb::RESIZE_DEFAULT,
                )
        ); ?>

        </div>

    <?php endif; ?>

<?php foreach ($model->media as $media): ?>
<?php if ($media->type != Product::MEDIA_BIG_IMAGE && $media->type != Product::MEDIA_UNPUBLISHED): ?>
                <div>
                    <?php
                    Yii::app()->controller->widget("application.components.Thumb", array(
                        "groupName" => 'product_img',
                        "imageurl" => $media->url,
                        "url" => Yii::app()->request->baseUrl . $media->url,
                        "alt" => $media->name,
                        "height" => 120,
                        "resize" => Thumb::RESIZE_ADAPTIVE,
                            )
                    ); ?>
                </div>
<?php endif; ?>

<?php endforeach; ?>
            </div>

            
<div class="price">
                <strong>Prijs:</strong> <?php echo $model->priceText; ?>
</div>
              <div class="description">
                 
<?php echo $model->description;?>
</div>
<div class="clearboth"></div>
