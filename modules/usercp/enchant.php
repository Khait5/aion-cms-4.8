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

<h3>Encantamiento de Objeto</h3>
<p>¡Herramienta web para encantar tus objetos!</p>
<br />

<?php

if(!check($_GET['server'])) redirect('usercp/');
if(!check($_GET['player'])) redirect('usercp/');
if(!check($_GET['item'])) redirect('usercp/');

try {
	
	//if(!$_SESSION['is_staff']) throw new Exception('Try again later.');
	
	if(!isServerValid($_GET['server'])) throw new Exception('Your request could not be completed, please try again later.');
	
	# load site database
	$db = Handler::loadDB();
	
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
	
	# configs
	$enchantableItems = config('enchantable_items', true);
	$enchantablePrice = config('enchant_price', true);
	
	# is item enchantable?
	if(!array_key_exists($itemData['item_id'], $enchantableItems)) throw new Exception('¡Lo siento! Este objeto no puede ser encantado.');
	
	# check enchant price
	if(!array_key_exists($itemData['enchant'], $enchantablePrice)) throw new Exception('¡Lo siento! Este objeto ha alcanzado el nivel máximo de encantamiento permitido en el sitio web.');
	$enchantPrice = $enchantablePrice[$itemData['enchant']];
	
	# VIP discount
	if($accountData['membership'] == 2 && strtotime($accountData['expire']) > time()) {
		$enchantPrice = floor($enchantPrice-($enchantPrice*0.1));
	}
	
	# item name
	$itemName = getItemName($itemData['item_id']);
	if(!check($itemName)) throw new Exception('¡Lo siento! No pudimos identificar este objeto, por favor contacta a soporte.');
	
	$itemSkills = $enchantableItems[$itemData['item_id']];
	if(!is_array($itemSkills)) throw new Exception('¡Lo siento! No pudimos identificar este objeto, por favor contacta a soporte. [2]');
	
	$nextEnchantLevel = $itemData['enchant']+1;
	
	# check free tokens
	$useEnchantToken = false;
	$checkTokens = $db->queryFetchSingle("SELECT * FROM `aioncms`.`website_enchant_tokens` WHERE `account_id` = ?", array($accountData['id']));
	if(is_array($checkTokens)) {
		if($checkTokens['tokens'] >= 1) {
			$useEnchantToken = true;
		}
	}
	
	if(isset($_POST['submit_enchant']) && check($_POST['submit_enchant'])) {
		try {
			
			# check online
			if(isOnline($_SESSION['userid'], 'all')) throw new Exception('Tu cuenta está conectada, por favor desconéctate.');
			
			# check credits
			if($useEnchantToken == false) if($accountData['toll'] < $enchantPrice) throw new Exception('¡Lo siento! No tienes suficientes créditos para encantar este objeto.');
			
			if($itemData['enchant'] == 19) {
				# choose random skill
				$randomSkill = $itemSkills[mt_rand(0, 2)];
				if(!check($randomSkill)) throw new Exception('Hubo un problema encantando tu objeto, por favor contacta a soporte. [0]');
				
				$enchant = $sdb->query("UPDATE `inventory` SET `enchant` = `enchant` + 1, `is_amplified` = 1, `buff_skill` = ? WHERE `item_unique_id` = ?", array($randomSkill, $itemData['item_unique_id']));
			} else {
				# regular enchant +1
				$enchant = $sdb->query("UPDATE `inventory` SET `enchant` = `enchant` + 1, `is_amplified` = 1 WHERE `item_unique_id` = ?", array($itemData['item_unique_id']));
			}
			
			# error in query
			if(!$enchant) throw new Exception('Hubo un problema encantando tu objeto, por favor contacta a soporte.');
			
			# deduct credits
			if($useEnchantToken == false) {
				$subtractCredits = $Account->subtractCredits($enchantPrice);
				if(!$subtractCredits) throw new Exception('Ocurrió un error, por favor contacta al Administrador. [E-CS]');
			}
			
			# deduct token
			if($useEnchantToken == true) {
				$deductToken = $db->query("UPDATE `aioncms`.`website_enchant_tokens` SET `tokens` = `tokens` - 1 WHERE `account_id` = ?", array($accountData['id']));
				if(!$deductToken) throw new Exception('Ocurrió un error, por favor contacta al Administrador. [E-TS]');
			}
			
			# success message
			if($useEnchantToken == true) {
				logSystem::add('item enchanted to +' . $nextEnchantLevel . ' (token)');
			} else {
				logSystem::add('item enchanted to +' . $nextEnchantLevel);
			}
			
			if($itemData['enchant'] == 19) {
				message('¡Tu objeto ha sido encantado exitosamente! Ahora puedes proceder a tu <a href="'.module_url('usercp/inventory/server/'.$_GET['server'].'/player/'.$_GET['player'].'/', true).'" style="font-weight:bold;">inventario</a>.', 'success');
			} else {
				redirect('usercp/enchant/server/'.$_GET['server'].'/player/'.$_GET['player'].'/item/'.$itemData['item_unique_id'].'/');
			}
			
			
			
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
					if($useEnchantToken == true) {
						# use free token
						echo '<h3>GRATIS</h3>';
						echo '<p>(tienes <strong>'.$checkTokens['tokens'].'</strong> tokens restantes)</p>';
						
					} else {
						# use credits
						if($accountData['membership'] == 2 && strtotime($accountData['expire']) > time()) {
							echo '<h3 style="color:#ffae00;">'.$enchantPrice.' créditos<br />(10% de descuento VIP)</h3>';
						} else {
							echo '<h3>'.$enchantPrice.' créditos</h3>';
						}
					}
					echo '<br />';
					
					echo '<p>100% de éxito</p>';
					echo '<p>Asegúrate de que tu cuenta esté desconectada antes de encantar.</p>';
					echo '<br />';
					
					if($itemData['enchant'] == 19) {
						//echo '<p>This item will receive a random skill.</p>';
						
						$skill_1 = getSkillName($itemSkills[0]);
						$skill_2 = getSkillName($itemSkills[1]);
						$skill_3 = getSkillName($itemSkills[2]);
						
						echo '<p>Este objeto recibirá una de las siguientes habilidades:</p>';
						echo '<p style="font-weight:bold;color:#73009e;">'.$skill_1.'</p>';
						echo '<p style="font-weight:bold;color:#73009e;">'.$skill_2.'</p>';
						echo '<p style="font-weight:bold;color:#73009e;">'.$skill_3.'</p>';
						echo '<br />';
					}
					
					echo '<form action="" method="post">';
						echo '<button type="submit" name="submit_enchant" value="ok" class="btn btn-success">Encantar a +'.$nextEnchantLevel.'</button>';
					echo '</form>';
					
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}





