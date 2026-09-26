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

<h3>Preguntas de Seguridad</h3>
<p>Configurar las preguntas de seguridad te ayudará a confirmar la propiedad de tu cuenta.</p>
<br />

<?php
try {
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	$accountSecurity = $Account->getExtraSecurityData();
	
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	if(check($accountSecurity['question_1'], $accountSecurity['question_2'], $accountSecurity['answer_1'], $accountSecurity['answer_2'])) throw new Exception('Ya has configurado las preguntas de seguridad de tu cuenta.');
	
	$securityQuestions = config('security_questions', true);
	
	if(isset($_POST['sq_submit']) && check($_POST['sq_submit'])) {
		try {
			# filters
			if(!check($_POST['question_1'])) throw new Exception('Por favor completa todos los campos requeridos.');
			if(!check($_POST['answer_1'])) throw new Exception('Por favor completa todos los campos requeridos.');
			if(!check($_POST['question_2'])) throw new Exception('Por favor completa todos los campos requeridos.');
			if(!check($_POST['answer_2'])) throw new Exception('Por favor completa todos los campos requeridos.');
			
			if(!in_array($_POST['question_1'], $securityQuestions)) throw new Exception('Tu petición no pudo ser completada.');
			if(!in_array($_POST['question_2'], $securityQuestions)) throw new Exception('Tu petición no pudo ser completada.');
			
			if($_POST['question_1'] == $_POST['question_2']) throw new Exception('No puedes usar la misma pregunta dos veces.');
			
			if(!Validator::Length($_POST['answer_1'], 50, 3)) throw new Exception('Tus respuestas deben tener entre 3 y 50 caracteres.');
			if(!Validator::Length($_POST['answer_2'], 50, 3)) throw new Exception('Tus respuestas deben tener entre 3 y 50 caracteres.');
			
			if(!Validator::Chars($_POST['answer_1'], array("a-z","A-Z","0-9"," "))) throw new Exception('Las respuestas solo pueden contener letras, números y espacios.');
			if(!Validator::Chars($_POST['answer_2'], array("a-z","A-Z","0-9"," "))) throw new Exception('Las respuestas solo pueden contener letras, números y espacios.');
			
			# save questions
			$saveQuestions = $Account->setSecurityQuestions($_POST['question_1'], $_POST['answer_1'], $_POST['question_2'], $_POST['answer_2']);
			if(!$saveQuestions) throw new Exception("Tu petición no pudo ser completada. Si este problema persiste contacta al administrador. [E-A004]");
			
			logSystem::add('set security questions');
			
			redirect('usercp/account/');
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
			logSystem::add('set security questions error', 5);
		}
	}
	
	echo '<form action="'.module_url('usercp/securityquestions/', true).'" method="post">';
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>1ra Pregunta y Respuesta:</td>';
			echo '<td>';
				echo '<select name="question_1" class="form-control">';
				shuffle($securityQuestions);
				foreach($securityQuestions as $question) {
					echo '<option value="'.$question.'">'.$question.'</option>';
				}
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td><input type="text" name="answer_1" class="form-control" placeholder="respuesta..."/></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td colspan="2"><br /></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td>2da Pregunta y Respuesta:</td>';
			echo '<td>';
				echo '<select name="question_2" class="form-control">';
				shuffle($securityQuestions);
				foreach($securityQuestions as $question) {
					echo '<option value="'.$question.'">'.$question.'</option>';
				}
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td><input type="text" name="answer_2" class="form-control" placeholder="respuesta..."/></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td></td>';
			echo '<td><button type="submit" name="sq_submit" value="ok" class="btn btn-primary">Guardar Preguntas de Seguridad</button></td>';
		echo '</tr>';
	echo '</table>';
	echo '</form>';
	
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>