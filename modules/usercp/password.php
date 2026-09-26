<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block changepwd"></div>
<br /><br />

<h3>Cambiar Contraseña</h3>

<br />

<?php
# Change Password Process
if(isset($_POST['pwd_submit']) && check($_POST['pwd_submit'])) {
	try {
		
		if(!check($_POST['pwd_old'], $_POST['pwd_new'], $_POST['pwd_new_confirm'])) throw new Exception('Por favor completa todos los campos.');
		if($_POST['pwd_old'] == $_POST['pwd_new']) throw new Exception('Por favor elige una nueva contraseña diferente.');
		if($_POST['pwd_new'] != $_POST['pwd_new_confirm']) throw new Exception('Las contraseñas nuevas no coinciden, por favor intenta de nuevo.');
		
		
		$Account = new Account();
		$Account->setId($_SESSION['userid']);
		$accountData = $Account->getAccountData();
		
		if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
		if(!$Account->validatePassword($_POST['pwd_old'])) throw new Exception('Tu contraseña actual no es correcta.');
		if(check($accountData['hash'], $accountData['confirmed'])) {
			if(Validator::UnsignedNumber($accountData['hash'])) {
				if(time() > ($accountData['hash']+3600)) {
					# expired password change
					# do nothing
				} else {
					throw new Exception('Tienes un cambio de contraseña activo, por favor revisa la bandeja de entrada de tu email.');
				}
			}
		}
		
		$verificationCode = time();
		$verificationLink = __PAGE_URL__ . 'verification/password/key/' . $verificationCode . '/';
		
		# send verification email
		try {
			
			$email = new Email();
			$email->setTemplate('CHANGE_PWD');
			$email->addVariable('{USERNAME}', $accountData['name']);
			$email->addVariable('{VERIFICATION_LINK}', $verificationLink);
			$email->addAddress($accountData['email']);
			$email->send();
			
		} catch(Exception $ex) {
			throw new Exception('No pudimos enviarte el email de verificación, por favor contacta a soporte.');
		}
		
		# update account data
		$setPasswordChange = $Account->setPasswordChange($_POST['pwd_new'], $verificationCode);
		if(!$setPasswordChange) throw new Exception('Hubo un problema al cambiar tu contraseña, por favor contacta a soporte.');
		
		message('¡Casi listo! Hemos enviado un email de verificación a <strong>'.$accountData['email'].'</strong>. Sigue las instrucciones dadas en el email para cambiar tu contraseña.', 'success');
		logSystem::add('password change request');
		
		# destroy current session (logout user)
		$_SESSION = array();
		session_destroy();
		
		# redirect home in 10 seconds
		echo '<meta http-equiv="refresh" content="10; url='.__BASE_URL__.'" />';
		
	} catch(Exception $ex) {
		message($ex->getMessage(), 'error');
		logSystem::add('password change error', 5);
	}
}
?>

<form action="<?php module_url(); ?>usercp/password/" method="post">
<table class="login-form">
	<tr>
		<td>Contraseña Actual:</td>
		<td><input type="password" name="pwd_old" autofocus/></td>
	</tr>
	<tr>
		<td>Nueva Contraseña:</td>
		<td><input type="password" name="pwd_new" /></td>
	</tr>
	<tr>
		<td>Confirmar Nueva Contraseña:</td>
		<td><input type="password" name="pwd_new_confirm" /></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div style="width: 300px;font-size: 12px;">
				Después de enviar este formulario, te enviaremos un enlace de verificación a <strong><?php echo $_SESSION['email']; ?></strong>. <br /><br />
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><button type="submit" name="pwd_submit" value="ok">Cambiar Contraseña</button></td>
	</tr>
</table>
</form>