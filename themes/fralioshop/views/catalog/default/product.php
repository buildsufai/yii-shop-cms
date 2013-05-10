<?php $this->widget('ext.fancybox.EFancyBox', array(
    'target'=>'a[rel=content_img]',
    'config'=>array(
        'transitionIn'	=> 'elastic',
	'transitionOut'	=> 'elastic',
),
    )
); ?>

<?php

if(!empty($this->metaKeywords))
	Yii::app()->clientScript->registerMetaTag($this->metaKeywords, 'keywords');
if(!empty($this->metaDescription))
	Yii::app()->clientScript->registerMetaTag($this->metaDescription, 'description');

?>

<?php
    $this->breadcrumbs=array(
        $model->category->name => array('/catalog/default/productline', 'alias'=>$model->category->alias),
        $model->name
    );
?>

<div class="header_bot">
    
        <div class="container_16">
        
            <div class="grid_11 content">
            
<h1><?php echo $model->name; ?></h1>

    <div class="product_images">
                
        <div class="big_image">
            <?php $headerimage = $model->getImage(ProductMedia::MEDIA_HEADER_IMAGE); 
            if($headerimage)
                echo CHtml::link(CHtml::image($headerimage->media->getImageUrl('big'), $headerimage->name), $headerimage->media->getImageUrl('large'), array('rel'=>'content_img'));
            ?>
            <?php //echo $model->getImage(ProductMedia::MEDIA_HEADER_IMAGE, 'big'); ?>
            <?php //echo CHtml::link($model->getImage(ProductMedia::MEDIA_HEADER_IMAGE, 'big'), $image->media->getImageUrl('large'), array('rel'=>'content_img')); ?>

        </div>
        <?php foreach($model->getImages(ProductMedia::MEDIA_GALLERY_IMAGE) as $image): ?>
        <div class="small_image">
            <?php echo CHtml::link(CHtml::image($image->media->getImageUrl('small'), $image->name), $image->media->getImageUrl('large'), array('rel'=>'content_img')); ?>

            <?php //echo CHtml::image($image->media->getImageUrl('small'), '$image->name'); ?>
        </div>
        <?php endforeach ?>
        
        
    </div>
<div style="clear:both;"></div>
                <h4>Product beschrijving</h4>
                <p><?php echo $model->description;?></p>
                
                <div class="tabs_framed">
									
									<?php if(isset($_POST['Review']))
										Yii::app()->clientScript->registerScript('activetab', "$('.tabs li a[href=\#tabs_1_2]').click();", CClientScript::POS_READY); ?>
                
									<ul class="tabs">
                        <li class="current"><a href="#tabs_1_1">Technische beschrijving</a></li>
                        <li><a href="#tabs_1_2">Recenties <?php if(count($model->reviews)>0) echo "(".count($model->reviews).")"; ?></a></li>
                    </ul>
                    
                    <div class="tabcontent" id="tabs_1_1">
                       <div class="styled_table table_pink">
                       
                       <?php echo $model->description2; ?>
                       
                        </div>
                    </div>
                    
                    <div class="tabcontent" id="tabs_1_2">
											<?php if(!Yii::app()->user->hasFlash('success')): ?>
											<fieldset class="review_form">
												<legend>Review toevoegen</legend>
												<?php echo CHtml::beginForm(); ?>
												<?php // echo CHtml::errorSummary($review); ?>
												<div class="row">
													<div style="width:50%; float: left;">
														<?php echo CHtml::activeLabel($review,'author'); ?> 
														<?php echo CHtml::activeTextField($review, 'author'); ?>
														<?php echo CHtml::error($review,'author'); ?>
													</div>
													<div style="width:50%; float: left;">
														<?php //echo CHtml::activeLabel($review,'rate'); ?>
														<?php $this->widget('CStarRating',array('model'=>$review, 'attribute'=>'rate', 'minRating'=>1, 'maxRating'=>5)); ?>
														<?php //echo CHtml::activeDropDownList($review,'rate', $review->rates, array('prompt'=>'')); ?>
														<?php echo CHtml::error($review,'rate'); ?>
													</div>
													<div style="clear:both;"></div>
												</div>

												<div class="row">
														<?php echo CHtml::activeLabel($review,'description'); ?>
														<?php echo CHtml::activeTextArea($review,'description',array('rows'=>6, 'cols'=>50)); ?>
														<?php echo CHtml::error($review,'description'); ?>
												</div>
												<div class="row buttons">
													<?php echo CHtml::submitButton('Verzenden'); ?>
												</div>
												
												<?php echo CHtml::endForm(); ?>
											</fieldset>
											<?php else: ?>
											Bedankt voor het toevoegen van een recensie
											<?php endif; ?>
											<ul class="post_list recent_posts">
												<?php foreach($model->reviews as $review): ?>
                            <li><?php echo $review->description; ?></li>
													<?php endforeach; ?>
											</ul>
              </div>
  
                </div>
                
                
                
            </div>
            
            <div class="grid_5 content omega">
							<?php $file = "/files/shared/manufacturers/".strtolower($model->manufacturer).".jpg"; 
							//echo $file;
							?>
							
            <div class="manufacturer">
                <?php if(file_exists(Yii::getPathOfAlias('webroot').$file))
											echo CHtml::image(Yii::app()->baseUrl.$file, $model->manufacturer);
										else
											echo $model->manufacturer;
							?>
                
            </div>
                
                <div class="product_details greybox">
                
									<table>
											<tr>
													<th>Artikel nummer</th>
													<td><?php echo $model->sku; ?></td>
											</tr>
											<?php if(!empty($model->sale_price)): ?>
											<tr>
													<th>Catalogusprijs</th>
													<td class="oldprice"><?php echo $model->priceText; ?></td>
											</tr>
											<tr>
													<th>Aanbiedingsprijs</th>
													<td class="saleprice"><?php echo $model->salePriceText; ?></td>
											</tr>
											<?php else: ?>
											<tr>
													<th>Prijs</th>
													<td class="price"><?php echo $model->priceText; ?></td>
											</tr>
											<?php endif; ?>
											<tr>
													<th>Beschikbaarheid</th>
													<td class="<?php echo ($model->inStock) ? 'instock' : 'soldout'; ?>"><?php echo $model->statusText; ?></td>
											</tr>
											<tr>
													<th>Verzendkosten</th>
													<td><?php echo $model->shippingCosts; ?></td>
                    </tr>
                    </table>
                    <a class="button_link btn_pink" href="<?php echo $this->createUrl('/sales/account/addProduct', array('product_id'=>$model->id)); ?>"><span>IN WINKELWAGEN</span></a>
                </div>
                
                <div class="social_bar">
                    <?php $this->widget('application.extensions.social.social', array(
                        'networks' => array(
                            'facebook'=>array(
                                'href'=>Yii::app()->request->hostInfo . Yii::app()->request->url,//asociate your page http://www.facebook.com/page 
                                'action'=>'like',//recommend, like
                                'colorscheme'=>'light',
                                'width'=>'100px',
                                ),
                            'twitter'=>array(
                                'data-via'=>'', //http://twitter.com/#!/YourPageAccount if exists else leave empty
                                'width'=>'85px',
                                ), 
                            'googleplusone'=>array(
                                "size"=>"medium",
                                "annotation"=>"bubble",
                                'width'=>'75px',
                            ), 
                        )
                    ));?>
                </div>
                <div class="related_products">
                
                    <h4>Toebehorende producten</h4>
                    
                    <ul>
                        <?php $loops = 0; foreach($model->relatedProducts as $relProduct): ?>
                        <li>
                            <div class="image_frame"><?php if($relProduct->thumb) echo CHtml::image($relProduct->thumb, $relProduct->name); ?></div>
                            <div class="text">
                                <h4><a href="<?php echo $relProduct->getUrl(); ?>"><?php echo $relProduct->name; ?></a></h4>
                                <span class="merk"><?php echo $relProduct->manufacturer; ?></span><br />
                                <span class="price"><?php echo $relProduct->priceText; ?></span>
                            </div>
                        </li>
                        <?php $loops++; if($loops==5) break; ?>
                        <?php endforeach; ?>
                    </ul>
                
                </div>
                
                
                
            </div>
        <div class="clearfix"></div>
        </div>
        
    </div>