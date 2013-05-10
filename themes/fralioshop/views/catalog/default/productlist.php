<?php
$this->breadcrumbs=array(
    $category->name
);
?>
<?php Yii::app()->clientScript->registerScript('collapseFilters', "		
$('#filters ul').each(function(){
	if($(this).children().size() > 4){
		$(this).find('li:gt(3)')
		.hide()
		.end()
		.append(
			$('<li><a href=\"#\">Meer...</a></li>').click( function(){
				$(this).siblings(':hidden').show().end().remove(); return false;
			})
		);
	}
})
", CClientScript::POS_READY); ?>

<?php Yii::app()->clientScript->registerScript('autosubmit', "
	$('#filters form').find(':input:not(:submit):not(:button)').change(function(){
		$('#filters form').submit();
	});
	$('#filters form').find(':submit').hide();
", CClientScript::POS_READY); ?>
        
            <div id="filters" class="grid_4 sidebar filters">
                <?php echo CHtml::form(); ?>
                <h3>Filter de resultaten</h3><span><a href="#" onclick="$('#filterReset').val('true'); $(this).closest('form').submit();">Reset</a>
                <?php echo CHtml::hiddenField('filterReset', 'false'); ?>
                <h4>Product naam</h4>
                <?php echo CHtml::activeTextField($filter, 'name'); ?>
                
                <h4>Fabrikant</h4>
                <?php echo CHtml::activeCheckBoxList($filter, 'manufacturer', $category->getManufacturers(), array('separator'=>"\n", 'container'=>'ul', 'template'=>'<li>{input} {label}</li>')); ?>

                <h4>Prijs</h4>
                Van: <?php echo CHtml::activeTextField($filter, 'search_minprice'); ?> 
                Tot: <?php echo CHtml::activeTextField($filter, 'search_maxprice'); ?> 
                
                <?php foreach($category->propertyGroups as $group): ?>
                    <h4><?php echo $group->name; ?> <?php if(!empty($group->description)) echo "<acronym title=\"$group->description\">info</acronym>"; ?> </h4>
                    <?php if($group->type == PropertyGroup::TYPE_CHOICE || $group->type == PropertyGroup::TYPE_MULTIPLE): ?>
                        <?php echo CHtml::activeCheckBoxList($filter, "search_properties[$group->id]", CHtml::listData($group->properties, 'id', 'name'), array('separator'=>"\n", 'container'=>'ul', 'template'=>'<li>{input} {label}</li>') ); ?>
                    <?php elseif($group->type == PropertyGroup::TYPE_SELECT): ?>
                        <?php echo CHtml::activeRadioButtonList($filter, "search_properties[$group->id]", array(null=>'Geen voorkeur') + CHtml::listData($group->properties, 'id', 'name') , array('separator'=>"\n", 'container'=>'ul', 'template'=>'<li>{input} {label}</li>')); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php echo CHtml::submitButton(); ?>
                    
                <?php echo CHtml::endForm(); ?>
            </div>
            
            <div class="grid_12 content">
            
            <h2><?php echo $category->name; ?></h2>
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


<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $model->filter(),
    'itemView' => '_productlist_item',
    'template' => "{pager}\n{items}\n<div class='clearboth'></div>{pager}",
    'emptyText' => 'Geen producten gevonden.',
    'ajaxUpdate' => false,
));
?>
</div>