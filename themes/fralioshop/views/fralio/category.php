<?php $this->pageTitle = Yii::t('lang', $model->title); ?>
<div class='page-content <?php echo $model->alias; ?>'>

    <h1><?php //echo $model->title; ?></h1>

    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => new CArrayDataProvider($model->content,
            array('pagination'=>array(
                'pageSize'=>10,
            ),
        )),
        'itemView' => '_content_list',
        'template' => "{items}\n{pager}",
    ));
    ?>

</div>