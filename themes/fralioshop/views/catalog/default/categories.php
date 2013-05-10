<h1><?php echo $model->name; ?></h1>
<?php foreach($model->subcategories as $category): ?>

<div class="product-category">
    <h2><?php echo $category->name; ?></h2>
    
    <?php if(empty($category->subcategories)): ?>
    
    <div style="float:left; width: 60%;">
        <?php echo CHtml::link('Producten bekijken', $category->getUrl()); ?>
    </div>
    <div class="category-info">
        <?php echo CHtml::image($category->getThumb()); ?>
    </div>
    
    <?php else: ?>
    
    <ul>
        <?php foreach($category->subcategories(5) as $subcat): ?>
        <li><?php echo CHtml::link($subcat->name, $subcat->url); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="category-info">
    <?php echo CHtml::image($category->getThumb()); ?>
    <?php echo CHtml::link('Meer', $category->getUrl()); ?>
    </div>
    <?php endif; ?>
</div>

<?php endforeach; ?>
