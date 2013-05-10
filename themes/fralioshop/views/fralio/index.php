<div id="category-list">

<?php foreach($models as $model): ?>

<div class="product-category">
    <h2><?php echo $model->name; ?></h2>
    <ul>
        <?php foreach($model->subcategories(5) as $cat): ?>
            <li><?php echo CHtml::link($cat->name, $cat->url); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="category-info">
    <?php echo CHtml::image($model->getThumb()); ?>
    <?php echo CHtml::link('Meer', $model->getUrl()); ?>
    </div>
</div>

<?php endforeach; ?>

</div>