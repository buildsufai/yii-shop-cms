<h1><?php echo $model->name; ?></h1>
<?php foreach($model->subcategories as $category): ?>

<?php print_r($model->getAllIds()); ?>

<div class="product-category">
    <h2><?php echo $category->name; ?></h2>
    <ul>
        <li>Test</li>
        <li>Test</li>
        <li>Test</li>
    </ul>
    <?php echo CHtml::image($category->getThumb()); ?>
    <button>Meer</button>
</div>

<?php endforeach; ?>
