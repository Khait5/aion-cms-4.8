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

<h3>Restablecer Seguridad de la Cuenta</h3>
<p>Al restablecer la información de seguridad de tu cuenta, podrás elegir nuevas preguntas y respuestas de seguridad y elegir un nuevo número PIN.</p>
<br />

<?php
try {
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	$accountSecurity = $Account->getExtraSecurityData();
	
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	if(!is_array($accountSecurity)) throw new Exception('No has establecido la información de seguridad de tu cuenta.');
	
	if(check($_GET['verify'])) {
		
		if(!in_array($_GET['verify'], array('sq', 'spin'))) throw new Exception('¡Lo siento! Tu solicitud no puede ser completada, inténtalo de nuevo más tarde.');
		
		if($_GET['verify'] == 'sq') {
			
			# verify security questions
			echo '<div class="col-md-8 col-md-offset-2">';
				if(check($_POST['pwd_ans1'], $_POST['pwd_ans2'])) {
					try {
						if($_POST['pwd_ans1'] != $accountSecurity['answer_1']) throw new Exception('Las respuestas ingresadas no son correctas.');
						if($_POST['pwd_ans2'] != $accountSecurity['answer_2']) throw new Exception('Las respuestas ingresadas no son correctas.');
						
						# reset security
						if(!$Account->resetAccountSecurity()) throw new Exception('Hubo un error, por favor contacta a soporte. [2]');
						
						logSystem::add('account security reset');
						redirect('usercp/account/');
						
						$disableSqForm = true;
					} catch(Exception $ex) {
						message($ex->getMessage(), 'error');
					}
				}
				if($disableSqForm != true) {
					echo '<p>Por favor responde a las preguntas de seguridad de tu cuenta:</p><br />';
					echo '<form action="" method="post">';
						echo '<div class="form-group">';
							echo '<label for="q_1">'.$accountSecurity['question_1'].'</label>';
							echo '<input type="text" name="pwd_ans1" class="form-control" id="q_1" autofocus/>';
						echo '</div>';
						echo '<div class="form-group">';
							echo '<label for="q_2">'.$accountSecurity['question_2'].'</label>';
							echo '<input type="text" name="pwd_ans2" class="form-control" id="q_2"/>';
						echo '</div>';
						echo '<button type="submit" class="btn btn-primary" name="pwd_submit" value="ok">Verificar</button>';
					echo '</form>';
				}
			echo '</div>';
		}
		
		if($_GET['verify'] == 'spin') {
			
			# verify security questions
			echo '<div class="col-md-8 col-md-offset-2">';
				if(isset($_POST['pwd_pin']) && check($_POST['pwd_pin'])) {
					try {
						if($_POST['pwd_pin'] != $accountSecurity['security_pin']) throw new Exception('El PIN de seguridad ingresado no es válido.');
						
						# reset security
						if(!$Account->resetAccountSecurity()) throw new Exception('Hubo un error, por favor contacta a soporte.');
						
						logSystem::add('account security reset');
						redirect('usercp/account/');
						
						$disablePinForm = true;
					} catch(Exception $ex) {
						message($ex->getMessage(), 'error');
					}
				}
				if($disablePinForm != true) {
					echo '<p>Por favor ingresa el PIN de seguridad de 4 dígitos de tu cuenta:</p><br />';
					echo '<form action="" method="post">';
						echo '<div class="form-group">';
							echo '<label for="pin">PIN de Seguridad</label>';
							echo '<input type="text" name="pwd_pin" class="form-control" maxlength="4" id="pin" autofocus/>';
						echo '</div>';
						echo '<button type="submit" class="btn btn-primary" name="pwd_submit" value="ok">Verificar</button>';
					echo '</form>';
				}
			echo '</div>';
		}
		
		
	} else {
		
		echo '<div class="col-md-6 col-md-offset-3 text-center">';
			echo '<h3>Elige un método de verificación</h3><br /><br />';
			if(check($accountSecurity['question_1'], $accountSecurity['question_2'], $accountSecurity['answer_1'], $accountSecurity['answer_2'])) echo '<a href="'.module_url('usercp/reset/verify/sq/', true).'" class="btn btn-primary btn-lg btn-block">Preguntas de Seguridad</a>';
			if(check($accountSecurity['security_pin'])) echo '<a href="'.module_url('usercp/reset/verify/spin/', true).'" class="btn btn-primary btn-lg btn-block">PIN de Seguridad</a>';
		echo '</div>';
		
	}
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>