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

<h3>Validar Cuenta</h3>
<p>Usa esto solo si un miembro del equipo te lo pidió.</p>
<br />
<br />
<br />
<br />

<?php

if(check($_POST['acc_submit'], $_POST['acc_pwd'])) {
	try {
		$Account = new Account();
		$Account->setId($_SESSION['userid']);
		$accountData = $Account->getAccountData();
		
		if(!is_array($accountData)) throw new Exception('Petición incorrecta.');
		$encryptPwd = base64_encode(sha1($_POST['acc_pwd'], true));
		if($accountData['password'] != $encryptPwd) throw new Exception('Tu contraseña no es correcta.');
		
		echo '<div class="col-md-6 col-md-offset-3 text-center">';
			echo '<h4>Código de Validación</h4><br />';
			echo '<p style="color:red;">'.md5($_SESSION['username'] . md5('validateUser3000')).'</p>';
		echo '</div>';
		
	} catch(Exception $ex) {
		message($ex->getMessage(), 'warning');
	}
} else {
?>
<div class="col-md-6 col-md-offset-3 text-center">
	<h4>Ingresa la contraseña de tu cuenta</h4><br />
	<form method="post" action="">
		<div class="form-group">
			<input class="form-control" type="password" name="acc_pwd" /><br />
			<button type="submit" name="acc_submit" value="ok" class="btn btn-success">Crear Código de Validación</button>
		</div>
	</form>
</div>
<?php } ?>