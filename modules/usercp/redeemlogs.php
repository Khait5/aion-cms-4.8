<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block usercp"></div>
<br /><br />

<h3>Mis Códigos Canjeados</h3>
<p>Lista de códigos que ya has canjeado.</p>
<br /><br />

<?php
try {
	
	$RedeemCode = new RedeemCode();
	$RedeemCode->setUser($_SESSION['username']);
	
	$redeemLogs = $RedeemCode->getUserLogs();
	if(!is_array($redeemLogs)) throw new Exception('Aún no has canjeado ningún código.');
	
	echo '<table class="table table-striped table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Fecha</th>';
			echo '<th>Código</th>';
			echo '<th>Recompensa</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($redeemLogs as $row) {
		echo '<tr>';
			echo '<td>'.$row['date_redeemed'].'</td>';
			echo '<td>'.$row['redeem_code'].'</td>';
			echo '<td>'.number_format($row['redeem_credit_amount']).' crédito(s)</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'warning');
}
?>