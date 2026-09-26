<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block votenreward"></div>
<br /><br />

<h3>Clasificación de Votos</h3>
<p>Obtén aún más recompensas manteniéndote en la cima de la clasificación de votos mensual.</p>

<br /><br />

<h4>Cómo funciona:</h4>
<ol>
	<li>Selecciona tu personaje principal (se mostrará en la clasificación)</li>
	<li>Inscríbete para participar (tienes que inscribirte cada mes)</li>
	<li>¡Vota!</li>
</ol>

<br /><br />

<h4>Recompensas (<?php echo date("F"); ?>):</h4>
<table class="table table-bordered table-striped">
	<tr>
		<td>1er lugar</td>
		<td>
			1x Objeto a elección de nuestra Tienda Web<br /><br />
		</td>
	</tr>
	<tr>
		<td>2do lugar</td>
		<td>
			7 Días de VIP<br /><br />
		</td>
	</tr>
</table>

<br /><br />

<div class="col-md-6 col-md-offset-3 text-center">
	<?php
	try {
		
		$currentYear = (int) date("Y");
		$currentMonth = (int) date("m");
		$currentDay = (int) date("d");
		
		$db = Handler::loadDB();
		$sdb = Handler::loadDB('siel');
		
		$sielCharacters = $sdb->queryFetch("SELECT * FROM `players` WHERE `account_id` = ?", array($_SESSION['userid']));
		
		$checkVoteCount = $db->queryFetchSingle("SELECT * FROM `aioncms`.`votes_count` WHERE `id` = ? AND `year` = ? AND `month` = ?", array($_SESSION['userid'], $currentYear, $currentMonth));
		if(is_array($checkVoteCount) && check($checkVoteCount['character'])) {
			
			# ALREADY PARTICIPATING
			
			echo '<h4>¡Ya estás participando!</h4>';
			echo '<h6>aquí están tus votos hasta ahora</h6>';
			echo '<br />';
			echo '<br />';
			echo '<p>'.number_format($checkVoteCount['votes']).' votos</p>';
			
		} else {
			
			# NOT PARTICIPATING
			
			echo '<h4>¡Empecemos!</h4>';
			echo '<h6>elige tu personaje principal</h6>';
			echo '<br />';
			
			if(!is_array($sielCharacters)) {
				throw new Exception('No tienes ningún personaje en SIEL.');
			}
			
			# opt-in process
			if(isset($_POST['character_submit']) && check($_POST['character_submit'])) {
				try {
					if(!check($_POST['character_name'])) throw new Exception('El personaje que seleccionaste no es válido.');
					
					$playerInfo = $sdb->queryFetchSingle("SELECT * FROM `players` WHERE `name` = ? AND `account_id` = ?", array($_POST['character_name'], $_SESSION['userid']));
					if(!is_array($playerInfo)) throw new Exception('El personaje que seleccionaste no es válido. [NiA]');
					
					$voteCount = $db->queryFetchSingle("SELECT * FROM `aioncms`.`votes_count` WHERE `id` = ? AND `year` = ? AND `month` = ?", array($_SESSION['userid'], $currentYear, $currentMonth));
					if(!is_array($voteCount)) {
						# not in vote count table
						if($currentDay > 5) throw new Exception('¡Lo siento! Sólo puedes inscribirte dentro de los primeros 5 días de cada mes.');
						
						$optIn = $db->query("INSERT INTO `aioncms`.`votes_count` (`id`, `year`, `month`, `character`, `last_update`) VALUES (?, ?, ?, ?, now())", array($_SESSION['userid'], $currentYear, $currentMonth, $playerInfo['name']));
						if(!$optIn) throw new Exception('Hubo un error, por favor contacta a soporte. [F-OI]');
						
						# load info again
						$voteCount = $db->queryFetchSingle("SELECT * FROM `aioncms`.`votes_count` WHERE `id` = ? AND `year` = ? AND `month` = ?", array($_SESSION['userid'], $currentYear, $currentMonth));
					}
					
					if(!check($voteCount['character'])) {
						# update character name
						$updateCharacter = $db->query("UPDATE `aioncms`.`votes_count` SET `character` = ?, `last_update` = now() WHERE `id` = ? AND `year` = ? AND `month` = ?", array($playerInfo['name'], $_SESSION['userid'], $currentYear, $currentMonth));
						if(!$updateCharacter) throw new Exception('Hubo un error, por favor contacta a soporte. [F-UCN]');
					}
					
					redirect('usercp/voteranking/');
					
				} catch(Exception $ex) {
					message($ex->getMessage(), 'warning');
				}
			}
			
			# form
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<select class="form-control" name="character_name">';
						if(is_array($sielCharacters)) {
							foreach($sielCharacters as $player) {
								echo '<option value="'.$player['name'].'">'.$player['name'].'</option>';
							}
						}
					echo '</select>';
				echo '</div>';
				echo '<button type="submit" class="btn btn-primary btn-block" name="character_submit" value="1">Inscribirse</button>';
			echo '</form>';
		
		}
		

	} catch(Exception $ex) {
		message($ex->getMessage(), 'error');
	}
	?>
</div>