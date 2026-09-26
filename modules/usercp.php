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

<h3>MENÚ</h3>

<?php
echo '<div class="usercp-menu-container">';
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/acc_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/account/', true).'">Mi Cuenta</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/char_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/characters/', true).'">Mis Personajes</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/tokens_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('tickets/list/', true).'">Mis Tickets de Soporte</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/items_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('shop/', true).'">Tienda Web</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/wsitems_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('shop/', true).'">Ofertas Semanales</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/premium_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/upgrade/', true).'">Obtener Premium | Membresía VIP</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/changepass_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/password/', true).'">Cambiar Contraseña</a></div>';
	echo '</div>';
	
	/* echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/bansystem_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/bansystem/', true).'">Ban System</a></div>';
	echo '</div>'; */
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/forumevents_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('forumevents/', true).'">Eventos del Foro</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/xfer_ot_icon.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/timexchange/', true).'">Intercambio de Tiempo Online</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/lottery_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('lottery/', true).'">Lotería</a></div>';
	echo '</div>';
	
	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/redeem_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/redeem/', true).'">Canjear Código</a></div>';
	echo '</div>';
	
	/* echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/referrals_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('usercp/referrals/', true).'">Referral System</a></div>';
	echo '</div>'; */

	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/acc_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('lore/', true).'">Lore</a></div>';
	echo '</div>';

	echo '<div class="item">';
		echo '<div class="itemicon"><img src="'.template_img(true).'usercp_icons/acc_ico.png" /></div>';
		echo '<div class="itemlink"><a href="'.module_url('conexion_tierra/', true).'">Terminal</a></div>';
	echo '</div>';

echo '</div>';