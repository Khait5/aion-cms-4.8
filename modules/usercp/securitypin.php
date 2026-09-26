<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block accsecurity"></div>
<br /><br />

<h3>PIN de Seguridad</h3>
<p>Configurar el PIN de seguridad te ayudará a confirmar la propiedad de tu cuenta.</p>
<br />

<?php
try {
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	$accountSecurity = $Account->getExtraSecurityData();
	
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	if(check($accountSecurity['security_pin'])) throw new Exception('Ya has configurado tu PIN de seguridad.');
	
	if(isset($_POST['sp_submit']) && check($_POST['sp_submit'])) {
		try {
			# filters
			if(!check($_POST['sp_pin'])) throw new Exception('Por favor completa todos los campos requeridos.');
			if(!Validator::Length($_POST['sp_pin'], 4, 4)) throw new Exception('Tu PIN de seguridad debe contener 4 dígitos.');
			if(!Validator::UnsignedNumber($_POST['sp_pin'])) throw new Exception('Tu PIN de seguridad debe contener 4 dígitos.');

			# save pin
			$savePin = $Account->setSecurityPIN($_POST['sp_pin']);
			if(!$savePin) throw new Exception("Tu petición no pudo ser completada. Si este problema persiste contacta al administrador. [E-A004]");
			
			logSystem::add('set security pin');
			redirect('usercp/account/');
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
			logSystem::add('set security pin error', 5);
		}
	}
	
	echo '<form action="'.module_url('usercp/securitypin/', true).'" method="post">';
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>PIN de Seguridad:</td>';
			echo '<td><input type="text" name="sp_pin" class="form-control" placeholder="1234..." maxlength="4" autofocus/></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td></td>';
			echo '<td><button type="submit" name="sp_submit" value="ok" class="btn btn-primary">Guardar PIN de Seguridad</button></td>';
		echo '</tr>';
	echo '</table>';
	echo '</form>';
	
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>