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

<h3>Mejorar Cuenta</h3>

<?php
try {
	
	// 1= premium
	// 2= vip
	// 0= normal
	
	$Account = new Account();
	$Account->setId($_SESSION['userid']);
	$accountData = $Account->getAccountData();
	
	if(!is_array($accountData)) throw new Exception('No se pudo cargar la información de tu cuenta.');
	
	$premiumCost = config('premium_cost', true);
	$vipCost = config('vip_cost', true);
	
	
	echo '<p>Actualmente tienes <strong>'.number_format($accountData['toll']).'</strong> crédito(s). <a href="'.module_url('donate/', true).'" class="btn btn-success btn-xs">agregar créditos</a></p><br />';
	
	if(check($_GET['type'])) {
		try {
			switch($_GET['type']) {
				case 'premium':
					# GET PREMIUM
					if($premiumCost > $accountData['toll']) throw new Exception('No tienes suficientes créditos.');
					if($accountData['membership'] == 1) throw new Exception('Tu cuenta ya tiene membresía premium.');
					if($accountData['old_membership'] == 1) throw new Exception('Tu cuenta ya tiene membresía premium.');
					
					$upgradeAccount = $Account->setPremium();
					if(!$upgradeAccount) throw new Exception('Tu cuenta no pudo ser mejorada, por favor contacta al Administrador.');
					
					$subtractCredits = $Account->subtractCredits($premiumCost);
					if(!$subtractCredits) throw new Exception('Ocurrió un error, por favor contacta al Administrador.');
					
					logSystem::add('upgraded membership (premium)');
					
					redirect('usercp/upgrade/');
					break;
				case 'vip':
					# GET VIP
					if($vipCost > $accountData['toll']) throw new Exception('No tienes suficientes créditos.');
					
					$upgradeAccount = $Account->setVip(30);
					if(!$upgradeAccount) throw new Exception('Tu cuenta no pudo ser mejorada, por favor contacta al Administrador.');
					
					$subtractCredits = $Account->subtractCredits($vipCost);
					if(!$subtractCredits) throw new Exception('Ocurrió un error, por favor contacta al Administrador.');
					
					logSystem::add('upgraded membership (vip)');
					
					redirect('usercp/upgrade/');
					break;
				default:
					throw new Exception('Tu petición no pudo ser completada.');
			}
		} catch(Exception $ex) {
			message($ex->getMessage(), 'error');
		}
	}
} catch(Exception $ex) {
	redirect('usercp/');
}
?>
<div class="upgrade_premium">
	<span style="color:#00ffc6;font-size:24px;font-weight:bold;">MEMBRESÍA PREMIUM</span><br />
	<span style="color:#ffffff;font-size: 18px;font-weight:bold;"><?php echo number_format($premiumCost); ?> Créditos | De por vida</span>
	
	<br /><br />
	<span style="font-weight:bold;color:#51b19c;">400x</span> Exp<br />
	<span style="font-weight:bold;color:#51b19c;">150x</span> Drop<br />
	<span style="font-weight:bold;color:#51b19c;">400x</span> Group Exp<br />
	<span style="font-weight:bold;color:#51b19c;">400x</span> Gathering Exp<br />
	<span style="font-weight:bold;color:#51b19c;">400x</span> Crafting Exp<br />
	<span style="font-weight:bold;color:#51b19c;">100x</span> Quest Exp<br />
	<span style="font-weight:bold;color:#51b19c;">15x</span> Kinah Rate<br />
	<span style="font-weight:bold;color:#51b19c;">15x</span> Abyss points Rate<br />
	<span style="font-weight:bold;color:#51b19c;">2x</span> Pet Feeding Rate<br />
	<span style="font-weight:bold;color:#51b19c;">2x</span> Gathering Rate<br />
	<span style="font-weight:bold;color:#51b19c;">4x</span> Sell Limit<br />
	<span style="font-weight:bold;color:#51b19c;">1.75x</span> PvP Arena Reward<br />
	<span style="font-weight:bold;color:#51b19c;">20x</span> Crit Craft Rate<br />
	<span style="font-weight:bold;color:#51b19c;">30x</span> Combo Craft Rate<br />
	<span style="font-weight:bold;color:#51b19c;">New</span> Character Profiles<br />
	<span style="font-weight:bold;color:#51b19c;">New</span> Legion Profile<br />
	
	<br />
	<?php
	if($accountData['membership'] == 1) {
		# has premium
		echo '<span style="font-size: 18px;font-weight:bold;color:#9effea;">¡Tu cuenta tiene membresía Premium!</span>';
	} else {
		if($accountData['old_membership'] == 1) {
			# has premium
			echo '<span style="font-size: 18px;font-weight:bold;color:#9effea;">¡Tu cuenta tiene membresía Premium!</span>';
			
		} else {
			# not premium
			echo '<a href="#" class="btn btn-default" data-toggle="modal" data-target="#upgradePremium">Mejorar Cuenta</a>';
		}
	}
	?>
	
</div>

<div class="upgrade_vip">
	<span style="color:#ffcc00;font-size:24px;font-weight:bold;">MEMBRESÍA V.I.P.</span><br />
	<span style="color:#ffffff;font-size: 18px;font-weight:bold;"><?php echo number_format($vipCost); ?> Créditos | 30 Días</span>
	
	<br /><br />
	<span style="font-weight:bold;color:#b19351;">500x</span> Exp<br />
	<span style="font-weight:bold;color:#b19351;">200x</span> Drop<br />
	<span style="font-weight:bold;color:#b19351;">500x</span> Group Exp<br />
	<span style="font-weight:bold;color:#b19351;">500x</span> Gathering Exp<br />
	<span style="font-weight:bold;color:#b19351;">500x</span> Crafting Exp<br />
	<span style="font-weight:bold;color:#b19351;">150x</span> Quest Exp<br />
	<span style="font-weight:bold;color:#b19351;">20x</span> Kinah Rate<br />
	<span style="font-weight:bold;color:#b19351;">20x</span> Abyss points Rate<br />
	<span style="font-weight:bold;color:#b19351;">3x</span> Pet Feeding Rate<br />
	<span style="font-weight:bold;color:#b19351;">3x</span> Gathering Rate<br />
	<span style="font-weight:bold;color:#b19351;">6x</span> Sell Limit<br />
	<span style="font-weight:bold;color:#b19351;">2x</span> PvP Arena Reward<br />
	<span style="font-weight:bold;color:#b19351;">25x</span> Crit Craft Rate<br />
	<span style="font-weight:bold;color:#b19351;">35x</span> Combo Craft Rate<br />
	<span style="font-weight:bold;color:#b19351;">New</span> Web Enchantment Tool<br />
	<span style="font-weight:bold;color:#b19351;">1.5x</span> Voting Rewards<br />
	
	<br />
	
	<span style="font-weight:bold;">* Todos los objetos son intercambiables con la Membresía VIP usando el <a href="https://aioncms.com/" target="#"> Item Pak</a> *</span>
	
	<br /><br />
	<?php
	if($accountData['membership'] == 2) {
		# has vip
		echo '<span style="font-size: 18px;font-weight:bold;color:#fff79e;">¡Tu cuenta tiene membresía VIP!</span><br />';
		echo '<span style="font-size: 18px;font-weight:bold;">Fecha de Expiración: '.$accountData['expire'].'</span><br /><br />';
		echo '<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#extendVip">Extender</a>';
	} else {
		if($accountData['old_membership'] == 2) {
			# has vip
			echo '<span style="font-size: 18px;font-weight:bold;color:#fff79e;">¡Tu cuenta tiene membresía VIP!</span><br />';
			echo '<span style="font-size: 18px;font-weight:bold;">Fecha de Expiración: '.$accountData['expire'].'</span><br /><br />';
			echo '<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#extendVip">Extender</a>';
			
		} else {
			# not vip
			echo '<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#upgradeVip">Mejorar Cuenta</a>';
		}
	}
	?>
	
</div>

<!-- Premium -->
<div class="modal fade" id="upgradePremium" tabindex="-1" role="dialog" aria-labelledby="upgradePremiumConfirm">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="upgradePremiumConfirm">Mejorar a Premium</h4>
			</div>
			<div class="modal-body">
				Tus créditos serán deducidos una vez que mejores tu cuenta. Para proceder por favor haz clic en el botón "Mejorar" a continuación.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				<a href="<?php module_url('usercp/upgrade/type/premium/'); ?>" class="btn btn-success">Mejorar</a>
			</div>
		</div>
	</div>
</div>

<!-- Vip -->
<div class="modal fade" id="upgradeVip" tabindex="-1" role="dialog" aria-labelledby="upgradeVipConfirm">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="upgradeVipConfirm">Mejorar a V.I.P.</h4>
			</div>
			<div class="modal-body">
				Tus créditos serán deducidos una vez que mejores tu cuenta. Para proceder por favor haz clic en el botón "Mejorar" a continuación.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				<a href="<?php module_url('usercp/upgrade/type/vip/'); ?>" class="btn btn-success">Mejorar</a>
			</div>
		</div>
	</div>
</div>

<!-- Vip (extend) -->
<div class="modal fade" id="extendVip" tabindex="-1" role="dialog" aria-labelledby="extendVipConfirm">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="extendVipConfirm">Extender V.I.P. por 30 días.</h4>
			</div>
			<div class="modal-body">
				Tus créditos serán deducidos una vez que extiendas tu membresía V.I.P. Para proceder por favor haz clic en el botón "Extender" a continuación.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				<a href="<?php module_url('usercp/upgrade/type/vip/'); ?>" class="btn btn-success">Extender</a>
			</div>
		</div>
	</div>
</div>