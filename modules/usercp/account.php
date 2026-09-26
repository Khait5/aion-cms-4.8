<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?><div class="page-header-block usercp"></div>
<br /><br />

<h3>Mi Cuenta</h3>
<p>Desde aquí podrás gestionar la seguridad de tu cuenta. Recuerda mantener tu información privada, nunca la compartas con nadie.</p>

<?php
try {
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	$accountSecurity = $Account->getExtraSecurityData();
	
	if(!is_array($accountData)) throw new Exception('Could not load your account\'s information.');
	
	if($accountData['ip_force'] == 1)  {
		echo '<br />';
		message('Tu cuenta está actualmente baneada, por favor revisa el sistema de baneos para más información.', 'error');
	}
	
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>Cuenta:</td>';
			echo '<td>'.$accountData['name'].' <a href="'.module_url('usercp/validate/', true).'">(validar)</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Contraseña:</td>';
			echo '<td>&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226; <a href="'.module_url('usercp/password/', true).'">(cambiar)</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Estado:</td>';
			echo '<td>'.($accountData['activated'] == 1 ? 'Activada' : 'Activación Pendiente (email)').'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Email:</td>';
			echo '<td>'.$accountData['email'].' <a href="'.module_url('usercp/email/', true).'">(solicitar cambio)</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Fecha de Registro:</td>';
			echo '<td>'.(check($accountData['creation_date']) ? date("F jS, Y", strtotime($accountData['creation_date'])) : '<i>Desconocido</i>').'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Tipo de Cuenta:</td>';
			echo '<td>'.$Account->accountTypeTxt($accountData['membership']).'</td>'; // 0= normal 1= premium 2= vip
		echo '</tr>';
		if($accountData['membership'] == 2) {
		echo '<tr>';
			echo '<td>Expiración VIP:</td>';
			echo '<td>'.date("F jS, Y", strtotime($accountData['expire'])).'</td>'; // vip expiration
		echo '</tr>';
		}
		echo '<tr>';
			echo '<td>Última IP:</td>';
			echo '<td>'.$accountData['last_ip'].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Créditos:</td>';
			echo '<td>'.$accountData['toll'].' <a href="'.module_url('donate/', true).'">(Agregar Créditos)</a></td>';
		echo '</tr>';
	echo '</table>';
	
	$securityEmail = ($accountSecurity['email_confirmed'] == 1 ? '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span>' : '<a href="'.module_url('usercp/verifyemail/', true).'" class="btn btn-xs btn-primary">Verificar Ahora</a>');
	$securityQuestions = (check($accountSecurity['question_1'], $accountSecurity['question_2'], $accountSecurity['answer_1'], $accountSecurity['answer_2']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span>' : '<a href="'.module_url('usercp/securityquestions/', true).'" class="btn btn-xs btn-primary">Configurar</a>');
	$securityPin = (check($accountSecurity['security_pin']) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span>' : '<a href="'.module_url('usercp/securitypin/', true).'" class="btn btn-xs btn-primary">Configurar</a>');
	$securityLock = '<a href="'.module_url('usercp/accountlock/', true).'" class="btn btn-xs btn-primary">Configurar</a>';
	
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>Verificar Email:</td>';
			echo '<td>'.$securityEmail.'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Preguntas de Seguridad:</td>';
			echo '<td>'.$securityQuestions.'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>PIN de Seguridad:</td>';
			echo '<td>'.$securityPin.'</td>';
		echo '</tr>';
	echo '</table>';
	
	if(check($accountSecurity['security_pin']) || check($accountSecurity['question_1'], $accountSecurity['question_2'], $accountSecurity['answer_1'], $accountSecurity['answer_2'])) {
		echo '<table class="my-account-table">';
			echo '<tr>';
				echo '<td></td>';
				echo '<td><a href="'.module_url('usercp/reset/', true).'" class="btn btn-xs btn-danger">Restablecer Seguridad de Cuenta</a></td>';
			echo '</tr>';
		echo '</table>';
	}
	
	echo '<div class="account-safety">';
		echo '<h4>Consejos de Seguridad:</h4>';
		echo '<ul>';
			echo '<li><span style="font-weight:bold;color: #0072ff;">Usa una contraseña fuerte.</span> Evita que adivinen tu contraseña haciéndola de al menos 8 caracteres, con mayúsculas, minúsculas y números. ¡Recuerda nunca usar la misma contraseña para varios sitios!</li>';
			echo '<li><span style="font-weight:bold;color: #0072ff;">Nunca des el id de tu cuenta.</span> Nuestro equipo nunca te pedirá el id de tu cuenta, ¡sólo te pediremos el nombre del personaje!</li>';
			echo '<li><span style="font-weight:bold;color: #0072ff;">¿Juegas desde un cibercafé?.</span> Asegúrate siempre que la computadora que usas tenga un antivirus/anti-malware instalado. Si no lo tiene, recomendamos <a href="https://www.malwarebytes.org/" target="_blank">Malwarebytes</a>.</li>';
		echo '</ul>';
	echo '</div>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}

?>