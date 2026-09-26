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

<h3>Canjear Código</h3>
<p>Pega el código que se te dio en la siguiente caja y haz clic en "¡Canjear Ahora!" para recibir tus créditos.</p>

<?php
try {
	
	if(check($_POST['redeem_submit'], $_POST['redeem_code'])) {
		try {
			
			$RedeemCode = new RedeemCode();
			$RedeemCode->setCode($_POST['redeem_code']);
			$RedeemCode->redeemCode();
			
			message('¡Tu código ha sido canjeado exitosamente!', 'success');
			
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2" style="margin-top:30px;">';
		echo '<form class="form-horizontal" action="" method="post">';
			echo '<div class="form-group">';
				echo '<div class="col-sm-12">';
					echo '<input type="text" class="form-control" name="redeem_code" required>';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<div class="col-sm-12">';
					echo '<button type="submit" name="redeem_submit" value="submit" class="btn btn-primary">¡Canjear Ahora!</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
	
	echo '<div class="col-xs-8 col-xs-offset-2 text-center" style="margin-top:30px;">';
		echo '<a href="'.module_url('usercp/redeemlogs/', true).'" class="btn btn-xs btn-default">Ver Mis Códigos Canjeados</a>';
	echo '</div>';
	
	
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>