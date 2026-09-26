<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block itemenchant"></div>
<br /><br />

<h3>Lista de Objetos Encantables</h3>
<p>Los siguientes objetos están disponibles para ser encantados usando la herramienta web.</p>
<br />

<?php
try {
	
	$enchantableItems = config('enchantable_items', true);
	
	echo '<table class="table">';
	foreach($enchantableItems as $itemId => $skills) {
		
		$itemName = getItemName($itemId);
		if(!check($itemName)) continue;
		
		echo '<tr>';
			echo '<td>'.$itemName.'</td>';
		echo '</tr>';
	}
	echo '</table>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}