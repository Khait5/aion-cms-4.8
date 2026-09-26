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

<h3>Impulso de Nivel</h3>
<br />
<?php

try {
	
	//if($_SESSION['username'] != 'daeron91') throw new Exception('We\'re working on this, check back later!');
	
	# is player and server defined?
	if(!check($_GET['player'])) redirect('usercp/characters/');
	if(!check($_GET['server'])) redirect('usercp/characters/');
	
	# choose server database
	$sdb = Handler::loadDB('siel');
	
	# character info
	$character = $sdb->queryFetchSingle("SELECT * FROM `players` WHERE `name` = ? AND `account_id` = ?", array($_GET['player'], $_SESSION['userid']));
	if(!is_array($character)) redirect('usercp/characters/');
	
	# player level
	$characterLevel = expToLevel($character['exp']);
	if($characterLevel < 70) throw new Exception('Tu personaje no es elegible para este servicio.');
	if($characterLevel > 75) throw new Exception('Tu personaje no es elegible para este servicio.');
	
	# is account online? (both siel and lumiel)
	if(isOnline($_SESSION['userid'], 'all')) throw new Exception('Tu cuenta está conectada, por favor desconéctate.');
	
	# load db
	$db = Handler::loadDB();
	
	# check online time
	$onlineTime = $db->queryFetchSingle("SELECT * FROM `aioncms`.`players_onlinetime` WHERE `account_id` = ?", array($_SESSION['userid']));
	if(!is_array($onlineTime)) throw new Exception('Debes tener al menos 2 horas de tiempo en línea para impulsar el nivel de tu personaje.');
	
	$onlineHours = floor($onlineTime['total_onlinetime']/120);
	if($onlineHours < 2) throw new Exception('Debes tener al menos 2 horas de tiempo en línea para impulsar el nivel de tu personaje.');
	
	# remove online time
	$timeToRemove = 7200;
	$rOT = $db->query("UPDATE `aioncms`.`players_onlinetime` SET `total_onlinetime` = `total_onlinetime` - ? WHERE `account_id` = ?", array($timeToRemove, $_SESSION['userid']));
	if(!$rOT) throw new Exception('Algo salió mal, por favor contacta a soporte. [OTE]');
	
	# set exp
	$setExp = 10000000000;
	$updateExp = $sdb->query("UPDATE `players` SET `exp` = exp + ? WHERE `id` = ? AND `account_id` = ?", array($setExp, $character['id'], $character['account_id']));

	if(!$updateExp) throw new Exception('Algo salió mal, por favor contacta a soporte. [EXP]');
	
	logSystem::add('character level boosted');
	message('¡El personaje ganó exitosamente 10mil millones de exp!', 'success');
	echo '<div class="col-md-2"><a href="usercp/character" class="btn btn-xs btn-block btn-primary">Volver</a></div>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}