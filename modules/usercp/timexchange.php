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

<h3>Intercambio de Tiempo en Línea</h3>
<p>Desde aquí puedes intercambiar tu tiempo en línea por Kinah.</p>

<br />

<?php

try {
	
	//sthrow new Exception('Under development');
	
	// RATES
	$kps = 1000;
	$kpm = $kps*60;
	$regular_users = 100; // %
	$premium_users = 125; // %
	$vip_users = 150; // %
	$kLimit = 9999999999;
	
	// GET ACCOUNT INFO
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	
	if(!is_array($accountData)) throw new Exception('Could not load your account\'s information.');
	
	// LOAD SERVER DATABASES
	$db = Handler::loadDB();
	$sdb = Handler::loadDB('siel');
	
	// GET ACCOUNT CHARACTERS
	$sielCharacters = $sdb->queryFetch("SELECT * FROM `players` WHERE `account_id` = ?", array($_SESSION['userid']));
	
	// No characters? go home!
	if(!is_array($sielCharacters)) {
		
		throw new Exception('Debes tener al menos un personaje para usar esta característica.');
		
	} else {
		$accountCharacters = array();
		if(is_array($sielCharacters)) {
			foreach($sielCharacters as $cinfo) {
				$accountCharacters[$cinfo['id']] = array($cinfo['name'], 'siel', $cinfo['online'], $cinfo['id']);
			}
		}
	}
	
	// ACCOUNT RATES
	switch($accountData['membership']) {
		case 1:
			$rate = floor($kps*($premium_users/100));
			break;
		case 2:
			if(strtotime($accountData['expire']) > time()) {
				$rate = floor($kps*($vip_users/100));
			} else {
				if($accountData['old_membership'] == 1) {
					$rate = floor($kps*($premium_users/100));
				} else {
					$rate = floor($kps*($regular_users/100));
				}
			}
			break;
		default:
			$rate = floor($kps*($regular_users/100));
	}
	
	// EXCHANGE PROCESS
	if(check($_POST['exchangetime'], $_POST['exchangetime_character'])) {
		try {
			# DISABLED
			//throw new Exception("Online time exchange is currently under maintenance, please try again later.");
			
			# check if character is in array
			if(!array_key_exists($_POST['exchangetime_character'], $accountCharacters)) throw new Exception("Seleccionaste un personaje inválido.");
			
			# check if account is online
			foreach($accountCharacters as $aCharacters) {
				if($aCharacters[2] == 1) throw new Exception("Debes estar desconectado para intercambiar tiempo en línea.");
			}
			
			# check last exchange date
			$leX = $db->queryFetchSingle("SELECT * FROM `aioncms`.`players_lastexchange` WHERE `account_id` = ?", array($accountData['id']));
			if(is_array($leX)) {
				// CHECK LAST EXCHANGE DATE
				$lastExchangeTimestamp = strtotime($leX['last_exchange']);
				$lastExchangeOffset = time() - $lastExchangeTimestamp;
				
				switch($accountData['membership']) {
					case 1:
						if($lastExchangeOffset < 60*60*24) throw new Exception("Puedes intercambiar tu tiempo en línea una vez cada 24 horas.");
						break;
					case 2:
						if($lastExchangeOffset < 60*60) throw new Exception("Puedes intercambiar tu tiempo en línea una vez por hora.");
						break;
					default:
						if($lastExchangeOffset < 60*60*48) throw new Exception("Puedes intercambiar tu tiempo en línea una vez cada 48 horas.");
				}
				
				$leX_Action = 'update';
			} else {
				$leX_Action = 'insert';
			}
			
			# check online time
			$oT = $db->queryFetchSingle("SELECT * FROM `aioncms`.`players_onlinetime` WHERE `account_id` = ?", array($accountData['id']));
			if(!is_array($oT)) throw new Exception("No tienes tiempo en línea registrado.");
			
			# server database
			$srvdb = $sdb;
			
			# check if user has kinah
			$inventory = $srvdb->queryFetchSingle("SELECT `item_unique_id`,`item_count` FROM `inventory` WHERE item_id = ? AND item_owner = ?", array('182400001', $accountCharacters[$_POST['exchangetime_character']][3])); // kinah 182400001
			if(!is_array($inventory)) throw new Exception("Debes tener kinah en tu inventario.");
			
			# check kinah limit
			$exchangeKinah = $oT['total_onlinetime'] * $rate;
			debug($exchangeKinah);
			
			if($exchangeKinah < 1) throw new Exception("No tienes suficiente tiempo en línea para transferir.");
			
			if(($exchangeKinah+$inventory['item_count']) > $kLimit) {
				debug('1');
				# EXCEEDENT EXCHANGE
				#################################
				
				$allowedToExchange = $kLimit-$inventory['item_count']; // what he is allowed to enchange
				$onlineKinahLeft = $exchangeKinah-$allowedToExchange; // what he got left of online kinah
				$newOnlineTime = $onlineKinahLeft/$rate; // his new online time
				
				if($allowedToExchange == 0) throw new Exception('Has alcanzado el límite máximo de Kinah permitido en ese personaje, por favor elige otro.');
				
				# update user inventory (kinah)
				$sendKinah = $srvdb->query("UPDATE `inventory` SET item_count = item_count + ? WHERE item_unique_id = ? AND item_owner = ?", array($allowedToExchange, $inventory['item_unique_id'], $accountCharacters[$_POST['exchangetime_character']][3]));
				if(!$sendKinah) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde.");
				
				# update last transfer
				if($leX_Action == 'update') {
					$log = $db->query("UPDATE `aioncms`.`players_lastexchange` SET last_exchange = now() WHERE account_id = ?", array($accountData['id']));
					if(!$log) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde. (2)");
				} else {
					$log = $db->query("INSERT INTO `aioncms`.`players_lastexchange` (account_id, last_exchange) VALUES (?, now())", array($accountData['id']));
					if(!$log) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde. (3)");
				}
				
				# update online time
				$rOT = $db->query("UPDATE `aioncms`.`players_onlinetime` SET `total_onlinetime` = ? WHERE `account_id` = ?", array($newOnlineTime, $accountData['id']));
				
				# log
				$db->query("INSERT INTO `aioncms`.`players_exchange_logs` (account_id, exchange_date, exchange_amount, old_kinah) VALUES (?, now(), ?, ?)", array($accountData['id'], $allowedToExchange, $inventory['item_count']));
				
				# success
				message(number_format($allowedToExchange).' kinah enviados a '.$accountCharacters[$_POST['exchangetime_character']][0], 'success');
				
				logSystem::add('exchanged online time');
				
			} else {
				debug('2');
				# REGULAR EXCHANGE
				#################################
				
				# update user inventory (kinah)
				$sendKinah = $srvdb->query("UPDATE `inventory` SET item_count = item_count + ? WHERE item_unique_id = ? AND item_owner = ?", array($exchangeKinah, $inventory['item_unique_id'], $accountCharacters[$_POST['exchangetime_character']][3]));
				if(!$sendKinah) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde.");
				
				# update last transfer
				if($leX_Action == 'update') {
					$log = $db->query("UPDATE `aioncms`.`players_lastexchange` SET last_exchange = now() WHERE account_id = ?", array($accountData['id']));
					if(!$log) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde. (2)");
				} else {
					$log = $db->query("INSERT INTO `aioncms`.`players_lastexchange` (account_id, last_exchange) VALUES (?, now())", array($accountData['id']));
					if(!$log) throw new Exception("Tu intercambio no puede ser completado en este momento, intenta de nuevo más tarde. (3)");
				}
				
				# remove online time
				$rOT = $db->query("UPDATE `aioncms`.`players_onlinetime` SET `total_onlinetime` = 0 WHERE `account_id` = ?", array($accountData['id']));
				
				# log
				$db->query("INSERT INTO `aioncms`.`players_exchange_logs` (account_id, exchange_date, exchange_amount, old_kinah) VALUES (?, now(), ?, ?)", array($accountData['id'], $exchangeKinah, $inventory['item_count']));
				
				# success
				message(number_format($exchangeKinah).' kinah enviados a '.$accountCharacters[$_POST['exchangetime_character']][0], 'success');
				
				logSystem::add('exchanged online time');
				
			}
			
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
		}
	}
	
	// GET ONLINE TIME
	$onlineTime = $db->queryFetchSingle("SELECT * FROM `aioncms`.`players_onlinetime` WHERE `account_id` = ?", array($accountData['id']));
	if(is_array($onlineTime)) {
		$myOnlineTime = ($onlineTime['total_onlinetime'] > 0 ? sec_to_hms($onlineTime['total_onlinetime']) : array(0,0,0));
		$kinahEquiv = $onlineTime['total_onlinetime'] * $rate;
	} else {
		$myOnlineTime = array(0,0,0);
		$kinahEquiv = 0;
	}
	
	// DISPLAY FORM
	echo '<form action="'.module_url('usercp/timexchange/', true).'" method="post">';
	echo '<input type="hidden" name="exchangetime" value="1"/>';
	echo '<table class="table" width="100%" cellpadding="2" align="center" style="margin-top: 25px;">';
		echo '<tbody>';
			echo '<tr width="120%" align="center">';
				echo '<td><span style="font-weight:bold;color:#ff0000;">Tiempo Total en Línea</span></td>';
				echo '<td><span style="font-weight:bold;color:#ff0000;">Equivalente en Kinah</span></td>';
				echo '<td><span style="font-weight:bold;color:#ff0000;">Personaje</span></td>';
			echo '</tr>';
			echo '<tr class="odd" width="100%" align="center">';
				echo '<td><span><span style="font-size:20px;font-weight:bold;">' . $myOnlineTime[0] . '</span> hora(s) <span style="font-size:20px;font-weight:bold;">' . $myOnlineTime[1] . '</span> minuto(s)</span></td>';
				echo '<td><span>'.number_format($kinahEquiv).'</span></td>';
				echo '<td><span>';
					echo '<select name="exchangetime_character">';
						foreach($accountCharacters as $cid => $cinfo) {
							echo '<option value="'.$cid.'">'.$cinfo[0].' ('.$cinfo[1].')</option>';
						}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';
	
	echo '<div class="row text-center">';
		echo '<input type="submit" class="btn btn-primary" name="submit_exchange" value="Intercambiar" />';
	echo '</div>';
	
	echo '</form>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}
?>

<br /><br />

<ul class="timexchange-conditions">
	<li><strong>TARIFAS</strong></li>
	<li>Usuarios Regulares: <?php echo number_format(floor($kpm*60)); ?> Kinah/hora</li>
	<li>Usuarios Premium: <?php echo number_format(floor(($kpm*($premium_users/100))*60)); ?> Kinah/hora</li>
	<li>Usuarios VIP: <?php echo number_format(floor(($kpm*($vip_users/100))*60)); ?> Kinah/hora</li>
</ul>

<ul class="timexchange-conditions">
	<li><strong>CONDICIONES</strong></li>
	<li>* Los usuarios regulares pueden intercambiar su tiempo en línea una vez cada 48 horas.</li>
	<li>* Los usuarios premium pueden intercambiar su tiempo en línea una vez cada 24 horas.</li>
	<li>* Los usuarios VIP pueden intercambiar su tiempo en línea una vez por hora.</li>
	<li>* Aprovecharse del sistema de cualquier manera resultará en un baneo permanente.</li>
</ul>