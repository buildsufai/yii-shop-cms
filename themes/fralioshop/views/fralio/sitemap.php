<h1>Sitemap</h1>

<hr/>
<div class="col_1_2">
	<h3>Pagina's</h3>
<ul>
	<?php foreach($categories as $cat): ?>
		<?php if(count($cat->content)>0): ?>
			<li><?php echo $cat->title; ?>
				<ul style="margin-left: 10px;">
				<?php foreach($cat->content as $content): ?>
					<li><?php echo CHtml::link($content->title, $content->getUrl()); ?></li>
				<?php endforeach; ?>
				</ul>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
</div>

<div class="col_1_2">
	<h3>Product categorien</h3>
	<ul>
		<?php foreach($shopcats as $pcat): ?>
			<?php if(count($pcat->subcategories)>0): ?>
			<li><?php echo $pcat->name; ?>
				<ul style="margin-left: 10px;">
				<?php foreach($pcat->subcategories as $subcat): ?>
					<li><?php echo CHtml::link($subcat->name, $subcat->getUrl()); ?></li>
				<?php endforeach; ?>
				</ul>
			</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>