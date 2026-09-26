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

<h3>Personalizar Perfil de Mi Legión</h3>
<p>Personaliza el perfil de tu legión con la configuración que se muestra a continuación.</p>
<br /><br />
<?php
try {
	
	// check id
	if(!check($_GET['id'])) throw new Exception('El id de la legión proporcionado no es válido.');
	if(!Validator::UnsignedNumber($_GET['id'])) throw new Exception('El id de la legión proporcionado no es válido.');
	
	// legion info
	$LegionProfile = new LegionProfile();
	$LegionProfile->setId($_GET['id']);
	$cacheData = $LegionProfile->getProfileInfo();
	if(!is_array($cacheData)) throw new Exception('Hubo un error cargando el perfil de tu legión, contacta a soporte.');
	
	// is banned from customizing ?
	if($cacheData['profile']['banned'] == 1) throw new Exception('Has sido baneado de personalizar el perfil de tu legión, contacta a soporte.');
	
	// get playerlist
	$sdb = Handler::loadDB('siel');
	$characters = $sdb->queryFetch("SELECT * FROM `players` WHERE `account_id` = ?", array($_SESSION['userid']));
	if(!is_array($characters)) throw new Exception('No tienes ningún personaje en tu cuenta.');
	foreach($characters as $character) {
		$characterList[] = strtolower($character['name']);
	}
	
	// check owner
	if(!in_array(strtolower($cacheData['members']['BRIGADE_GENERAL'][0]['name']), $characterList)) throw new Exception('No tienes permiso para personalizar el perfil de esta legión.');
	
	// pending approval
	if($cacheData['profile']['requires_approval'] == 1) {
		message('El fondo de tu perfil personalizado está pendiente de aprobación, enviar un nuevo fondo personalizado está deshabilitado hasta que se procese tu solicitud. Aún puedes cambiar cualquier otra configuración.', 'warning');
	}
	
	// form submit
	if(isset($_POST['profile_submit']) && check($_POST['profile_submit'])) {
		try {
			
			$LegionProfileUpdate = new LegionProfile();
			$LegionProfileUpdate->setId($_GET['id']);
			if(check($_POST['profile_color'])) $LegionProfileUpdate->setCustomColor($_POST['profile_color']);
			if(check($_POST['profile_message'])) $LegionProfileUpdate->setCustomMessage($_POST['profile_message']);
			if(check($_POST['profile_youtube'])) $LegionProfileUpdate->setYoutubeVideo($_POST['profile_youtube']);
			if($cacheData['profile']['requires_approval'] == 0) {
				if(check($_POST['profile_background'])) $LegionProfileUpdate->setCustomBackground($_POST['profile_background']);
			}
			$LegionProfileUpdate->saveProfile();
			message('¡El perfil de tu legión ha sido actualizado exitosamente!', 'success');
			
			// reload info
			$cacheData = $LegionProfileUpdate->getProfileInfo();
			
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
		}
	}
	
	// settings
	echo '<form action="'.module_url('usercp/legionprofile/id/' . $_GET['id'], true).'" method="post">';
	echo '<table class="my-account-table">';
		echo '<tr>';
			echo '<td>Legión</td>';
			echo '<td><a href="'.generateLegionProfileUrl($_GET['id'], $cacheData['name']).'" target="_blank">'.$cacheData['name'].'</a></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td colspan="2"><br /></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td>Color Principal</td>';
			echo '<td>';
				echo '<select name="profile_color" class="form-control">';
					echo '<option value="gray" '.($cacheData['profile']['custom_color'] == 'gray' ? 'selected' : null).'>Gris (por defecto)</option>';
					echo '<option value="red" '.($cacheData['profile']['custom_color'] == 'red' ? 'selected' : null).'>Rojo</option>';
					echo '<option value="green" '.($cacheData['profile']['custom_color'] == 'green' ? 'selected' : null).'>Verde</option>';
					echo '<option value="blue" '.($cacheData['profile']['custom_color'] == 'blue' ? 'selected' : null).'>Azul</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td colspan="2"><br /></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td>Mensaje Personalizado</td>';
			echo '<td><textarea name="profile_message" class="form-control" maxlength="250" style="height:150px;">'.$cacheData['profile']['custom_message'].'</textarea></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td></td>';
			echo '<td><span style="font-size:11px;">Tu mensaje personalizado puede contener 250 caracteres como máximo.</span></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td colspan="2"><br /></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td>Video de YouTube</td>';
			echo '<td><input type="text" name="profile_youtube" class="form-control" value="'.$cacheData['profile']['youtube_video'].'"/></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td><span style="font-size:11px;">Proporciona la url completa del video, ejemplo:<br /><span style="color:red;font-weight:bold;">https://www.youtube.com/watch?v=dQw4w9WgXcQ</span></span></td>';
		echo '</tr>';
		
		echo '<tr>';
			echo '<td colspan="2"><br /></td>';
		echo '</tr>';
		
		if($cacheData['profile']['requires_approval'] == 0) {
			echo '<tr>';
				echo '<td>Fondo Personalizado</td>';
				echo '<td><input type="text" name="profile_background" class="form-control" placeholder="Déjalo vacío para mantener el actual..."/></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td></td>';
				echo '<td><span style="font-size:11px;">Proporciona un enlace directo a la imagen, ejemplo:<br /><span style="color:red;font-weight:bold;">https://i.imgur.com/qdvB89p.jpg</span><br ><br />Hosts de Imágenes: <a href="https://imgur.com/" target="_blank">Imgur</a></span></td>';
			echo '</tr>';
		}
		
		echo '<tr>';
			echo '<td></td>';
			echo '<td><button type="submit" name="profile_submit" value="ok" class="btn btn-primary">Guardar Cambios</button></td>';
		echo '</tr>';
	echo '</table>';
	echo '</form>';
	
	
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
	//redirect('usercp/');
}

?>