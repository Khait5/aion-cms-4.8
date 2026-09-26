<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block itemenchant"></div>
<br /><br />

<h3>Encantamiento de Objeto: Cambiar Habilidad</h3>
<p>Puedes cambiar la habilidad de tu objeto usando esta herramienta.</p>
<br />

<?php

if(!check($_GET['server'])) redirect('usercp/');
if(!check($_GET['player'])) redirect('usercp/');
if(!check($_GET['item'])) redirect('usercp/');

try {
	
	//if(!$_SESSION['is_staff']) throw new Exception('Try again later.');
	
	if(!isServerValid($_GET['server'])) throw new Exception('Your request could not be completed, please try again later.');
	
	# load server database
	$sdb = Handler::loadDB('siel');
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	
	# player data
	$playerData = $sdb->queryFetchSingle("SELECT * FROM `players` WHERE `account_id` = ? AND `name` = ?", array($accountData['id'], $_GET['player']));
	if(!is_array($playerData)) throw new Exception('Tu petición no pudo ser completada, por favor intenta de nuevo más tarde. [2]');
	
	# check item
	$itemData = $sdb->queryFetchSingle("SELECT * FROM `inventory` WHERE `item_owner` = ? AND `is_equipped` = ? AND `item_unique_id` = ?", array($playerData['id'], 0, $_GET['item']));
	if(!is_array($itemData)) throw new Exception('¡Uy! ¡No pudimos encontrar este objeto en tu inventario!');
	
	# check if item is +20
	if($itemData['enchant'] != 20) throw new Exception('Este objeto aún no tiene una habilidad.');
	
	# configs
	$enchantableItems = config('enchantable_items', true);
	$enchantablePrice = config('enchant_price', true);
	$skillChangePrice = config('enchant_skill_change_price', true);
	
	# is item enchantable? (in the list)
	if(!array_key_exists($itemData['item_id'], $enchantableItems)) throw new Exception('¡Lo siento! No se puede cambiar la habilidad de este objeto.');

	# item name
	$itemName = getItemName($itemData['item_id']);
	if(!check($itemName)) throw new Exception('¡Lo siento! No pudimos identificar este objeto, por favor contacta a soporte.');
	
	$itemSkills = $enchantableItems[$itemData['item_id']];
	if(!is_array($itemSkills)) throw new Exception('¡Lo siento! No pudimos identificar este objeto, por favor contacta a soporte. [2]');
	
	
	if(isset($_POST['submit_skillchange']) && check($_POST['submit_skillchange'])) {
		try {
			
			# check online
			if(isOnline($_SESSION['userid'], 'all')) throw new Exception('Tu cuenta está conectada, por favor desconéctate.');
			
			# check credits
			if($accountData['toll'] < $skillChangePrice) throw new Exception('¡Lo siento! No tienes suficientes créditos para cambiar la habilidad de este objeto.');
			
			# choose random skill
			$possibleSkills = array();
			
			if($itemData['buff_skill'] != $itemSkills[0]) $possibleSkills[] = $itemSkills[0];
			if($itemData['buff_skill'] != $itemSkills[1]) $possibleSkills[] = $itemSkills[1];
			if($itemData['buff_skill'] != $itemSkills[2]) $possibleSkills[] = $itemSkills[2];
			
			$randomSkill = $possibleSkills[mt_rand(0, 1)];
			if(!check($randomSkill)) throw new Exception('Hubo un problema encantando tu objeto, por favor contacta a soporte. [0]');
			
			# change item skill
			$changeSkill = $sdb->query("UPDATE `inventory` SET `buff_skill` = ? WHERE `item_unique_id` = ? AND `enchant` = 20 AND `is_amplified` = 1", array($randomSkill, $itemData['item_unique_id']));
			
			# remove old BT skill from player
			$removeOldSkill = $sdb->query("DELETE FROM `player_skills` WHERE `player_id` = ? AND `skill_id` = ?", array($playerData['id'], $itemData['buff_skill']));
			
			# error in query
			if(!$changeSkill) throw new Exception('Hubo un problema cambiando la habilidad de tu objeto, por favor contacta a soporte.');
			
			# deduct credits
			$subtractCredits = $Account->subtractCredits($skillChangePrice);
			if(!$subtractCredits) throw new Exception('Ocurrió un error, por favor contacta al Administrador. [E-CS]');
			
			# success message
			logSystem::add('item skill changed to ' . $randomSkill);
			$newSkillName = getSkillName($randomSkill);
			message('¡La habilidad de tu objeto ha sido cambiada a <strong>'.$newSkillName.'</strong> exitosamente! Ahora puedes proceder a tu <a href="'.module_url('usercp/inventory/server/'.$_GET['server'].'/player/'.$_GET['player'].'/', true).'" style="font-weight:bold;">inventario</a>.', 'success');
			
			
			
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
		}
	} else {
		
		echo '<div class="col-md-10 col-md-offset-1 text-center">';
			echo '<div class="panel panel-default">';
				echo '<div class="panel-body">';
					
					echo '<h2>'.$itemName.' +'.$itemData['enchant'].'</h2>';
					echo '<h4>'.$_GET['player'].' ('.$_GET['server'].')</h4>';
					echo '<br />';
					
					echo '<p>costo</p>';
					echo '<h3>'.$skillChangePrice.' créditos</h3>';
					echo '<br />';
					
					echo '<p>100% de éxito</p>';
					echo '<p>Asegúrate de que tu cuenta esté desconectada antes de encantar.</p>';
					echo '<br />';
					
					$skill_1 = getSkillName($itemSkills[0]);
					$skill_2 = getSkillName($itemSkills[1]);
					$skill_3 = getSkillName($itemSkills[2]);
					
					echo '<p>La habilidad de este objeto se cambiará a una de las siguientes:</p>';
					if($itemData['buff_skill'] != $itemSkills[0]) echo '<p style="font-weight:bold;color:#73009e;">'.$skill_1.'</p>';
					if($itemData['buff_skill'] != $itemSkills[1]) echo '<p style="font-weight:bold;color:#73009e;">'.$skill_2.'</p>';
					if($itemData['buff_skill'] != $itemSkills[2]) echo '<p style="font-weight:bold;color:#73009e;">'.$skill_3.'</p>';
					echo '<br />';
					
					echo '<form action="" method="post">';
						echo '<button type="submit" name="submit_skillchange" value="ok" class="btn btn-success">Cambiar Habilidad</button>';
					echo '</form>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}





