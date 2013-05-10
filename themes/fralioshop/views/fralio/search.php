<h1><?php echo Yii::t('lang', 'Search results'); ?></h1>


<?php $this->widget('zii.widgets.CListView', array(
        'dataProvider'=>new CArrayDataProvider($results, array(
            'pagination'=>array(
                'pageSize'=>200,
            ),
        )),
        'itemView'=>'_search_item',
        'template'=>"{items}\n{pager}",
    )); ?>