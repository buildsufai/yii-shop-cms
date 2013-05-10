<h1><?php echo $category->name; ?></h1>
<hr />

<?php if(count($category->children) > 0): ?>
<div class="subcategorien" style="width: 100%;">
    <h2>Sub categorieën</h2>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider' => new CArrayDataProvider($category->children),
        'itemView' => '_subcategory',
        'template' => "{items}\n",
        'emptyText' => 'Geen subrubrieken.',
        'ajaxUpdate' => false,
    )); ?>
</div>
<hr />
<?php endif; ?>

<h3>Producten</h3>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model->search(),
    'itemView' => '_productlist_item',
    'template' => "{pager}\n{items}\n<div class='clearboth'></div>{pager}",
    'emptyText' => 'Deze rubriek is nog leeg.',
    'ajaxUpdate' => false,
));
?>