<div id="content" class="full-width">
 <div class="content">

<h1>Ons Assortiment</h1>

<?php foreach($categories as $category): ?>

    <br><div class='box'>
    
    <h1><?php echo $category->name; ?></h1>
    <hr>
	
	<table class='products'>
	<?php foreach($category->products as $product): ?>
	
		<tr class="">
                        <td class='nummer'><?php echo $product->serial; ?></td>
                        <td class='naam'><?php echo $product->name; ?></td>
                </tr>
	
        <?php endforeach; ?>
	</table>

	<?php foreach($category->children as $subcategory): ?>
	
		<h2><?php echo $subcategorie->name; ?></h2>
		
		$producten = $subcategorie->getProducts();
		<table class='products'>
		<?php foreach($subcategorie->products as $product): ?>
			<tr class=''>
				<td class='nummer'><?php echo $product->serial; ?></td>
				<td class='naam'><?php echo $product->name; ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endforeach; ?>
	</div>
<?php endforeach; ?>

</div></div>