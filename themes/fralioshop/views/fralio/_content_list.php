<div class='content-item'>
    <a href="<?php echo $data->getUrl(); ?>"><h1><?php echo $data->title; ?></h1></a>
        <span class="datetime"><?php echo $data->createDateText; ?></span><br />

         <?php foreach($data->contentMedia as $media): ?>
            <?php if($media->type == ContentMedia::MEDIA_HEADER_IMAGE): ?>
                <div class="big" style="float:left; margin-right: 15px;">

                    <?php echo CHtml::link(
                        CHtml::image($media->media->getImageUrl('thumb'), $media->name),
                        $data->getUrl(), 
                        array('rel'=>'fancy_img')
                        ); ?>


                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php echo $data->description; ?>

</div>
<div class="clearboth"></div>
<br />
    <hr />