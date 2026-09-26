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

<h3>Verificar Dirección de Email</h3>
<p>Verificar la dirección de email de tu cuenta te ayuda a mantener tu cuenta más segura.</p>
<br />

<?php
try {
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	$accountSecurity = $Account->getExtraSecurityData();
	
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	if($accountSecurity['email_confirmed'] == 1) throw new Exception('Tu dirección de email ya está verificada.');
	
	if(check($_GET['send'])) {
		# send verification email
		try {
			
			$verificationCode = md5(md5('338nr72o3rcn8g32fx') . md5($_SESSION['username']));
			$verificationLink = __PAGE_URL__ . 'verification/email/key/' . $verificationCode . '/';
			
			$email = new Email();
			$email->setTemplate('VERIFY_EMAIL');
			$email->addVariable('{USERNAME}', $accountData['name']);
			$email->addVariable('{VERIFICATION_LINK}', $verificationLink);
			$email->addAddress($accountData['email']);
			$email->send();
			
		} catch(Exception $ex) {
			throw new Exception('No pudimos enviarte el email de verificación, por favor contacta a soporte.');
		}
		
		message('<strong>¡Casi listo!</strong> Hemos enviado un email de verificación a <strong>'.$accountData['email'].'</strong>. Una vez que hagas clic en el enlace que te enviamos, tu email será verificado.', 'success');
		
		logSystem::add('requested email verification');
	}
	
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>Email:</td>';
			echo '<td>'.$accountData['email'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td><a href="'.module_url('usercp/verifyemail/send/1', true).'" class="btn btn-primary">Enviar Email de Verificación</a></td>';
		echo '</tr>';
	echo '</table>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>