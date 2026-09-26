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

<h3>Solicitud de Destrabe de Personaje</h3>
<p>¡Tu solicitud será revisada lo antes posible!</p>

<?php
try {
	
	if(!isServerValid($_GET['server'])) throw new Exception('Tu petición no pudo ser completada, por favor intenta de nuevo más tarde.');
	
	# load server database
	if($_GET['server'] == 'siel') {
		$sdb = Handler::loadDB('siel');
	} else {
		$sdb = Handler::loadDB('lumiel');
	}
	
	# player data
	$playerData = $sdb->queryFetchSingle("SELECT * FROM `players` WHERE `account_id` = ? AND `name` = ?", array($_SESSION['userid'], $_GET['player']));
	if(!is_array($playerData)) throw new Exception('Tu petición no pudo ser completada, por favor intenta de nuevo más tarde. [2]');
	
	# check for existing requests
	$existingRequest = $sdb->queryFetchSingle("SELECT * FROM `aioncms`.`unstick` WHERE `player` = ?", array($playerData['name']));
	if(is_array($existingRequest)) throw new Exception('Ya tienes una solicitud de destrabe para este personaje.');
	
	# add new request
	$addRequest = $sdb->query("INSERT INTO `aioncms`.`unstick` (`player`,`race`,`account`) VALUES (?, ?, ?)", array($playerData['name'], $playerData['race'], $_SESSION['username']));
	if(!$addRequest) throw new Exception('Tu petición no pudo ser completada, por favor intenta de nuevo más tarde. [3]');
	
	message('¡La solicitud de destrabe de personaje ha sido enviada!', 'success');
	logSystem::add('submitted unstuck request ('.$playerData['name'].')');
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'error');
}

?>