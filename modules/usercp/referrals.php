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

<h3>Sistema de Referidos</h3>
<p>¡Invita a tus amigos a jugar en Aion y recibe una recompensa de <strong><?php echo number_format(config('referral_credits_reward')); ?> créditos</strong> por cada amigo referido que alcance <strong><?php echo config('referral_required_onlinetime_hours'); ?> horas</strong> de tiempo de juego en línea!</p>
<br /><br />

<h4>Mi Enlace de Referido</h4>
<p>¡Comparte este enlace con todos tus amigos!</p>
<input type="text" class="form-control" value="<?php echo config('referral_link_base') . $_SESSION['userid']; ?>" readonly/>
<br /><br />


<h4>Mis Referidos</h4>
<?php
try {
	
	$ReferralSystem = new ReferralSystem();
	$ReferralSystem->setUsername($_SESSION['username']);
	
	$referrals = $ReferralSystem->getAccountReferrals();
	if(!is_array($referrals)) throw new Exception('Aún no has referido a ningún amigo u.u');
	
	echo '<table class="table table-striped table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Amigo</th>';
			echo '<th>Fecha de Ingreso</th>';
			echo '<th>Estado</th>';
			echo '<th>Fecha de Recompensa</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($referrals as $row) {
		
		echo '<tr>';
			echo '<td>'.$row['username'].'</td>';
			echo '<td>'.$row['join_date'].'</td>';
			echo '<td>'.($row['status'] == 1 ? '¡Completado!' : 'En Progreso...').'</td>';
			echo '<td>'.($row['status'] == 1 ? $row['reward_date'] : '').'</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	
} catch(Exception $ex) {
	message($ex->getMessage(), 'warning');
}
?>