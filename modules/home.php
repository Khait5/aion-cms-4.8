<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="home-main-content">
	<div class="left-side">
		<div class="left-side-container">
			<?php if(!isLoggedIn()) { ?>
			<div class="login-box">
				<form action="<?php module_url(); ?>login/" method="post">
					<input type="text" class="login-username" maxlength="25" name="login_username" autofocus/>
					<input type="password" class="login-password" name="login_password"/><br />
					<!--<a href="<?php base_url(); ?>lock/" target="_blank" class="btn btn-xs btn-primary login-lock"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></a>-->
					<button type="submit" class="login-submit" name="login_submit" value="ok"></button>
				</form>
			</div>
			<?php } else { ?>
			<div class="account-box">
				<div class="account-box-content">
					<div style="text-align:center;padding-top:20px;">
						Bienvenido de nuevo, <?php echo $_SESSION['username']; ?><br />
						<a href="<?php module_url(); ?>usercp/" style="color:#63c2ff;text-decoration:none;">[ panel de usuario ]</a> | <a href="<?php module_url(); ?>logout/" style="color:#ff6363;text-decoration:none;">[ salir ]</a>
						<br />
						<div style="font-size:11px;margin-top: 10px;">
							<span style="color:#cccccc;">Hora del Servidor</span><br />
							<span style="color:#00ff00;"><?php echo date("Y-m-d h:i A"); ?></span>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<div class="left-text-container">
				<p>Bienvenido al Servidor Privado <span style="color:#d6be93;">AionCMS</span>.</p>
				<p>¡ColombianAge 4.8 ya está disponible!</p>
				<div class="left-text-container-newsblock">
					<?php
					$forumRss = 'FORUM_NEWS_RSS_LINK';
					$loadRssXml = @simplexml_load_file($forumRss);
					if($loadRssXml) {
						$eventsFeed = $loadRssXml->channel;
						
						echo '<span class="newsheader">Últimas Noticias:</span>';
						echo '<table class="newstable">';
							$newsIndex = 0;
							foreach($eventsFeed->item as $item) {
								if($newsIndex > 8) continue;
								//if(!preg_match('/Carl/', $item->author) && !preg_match('/Lautaro/', $item->author)) continue;
								$timestamp = strtotime($item->pubDate);
								
								echo '<tr>';
									echo '<td class="newstitle"><a href="'.$item->link.'" target="_blank">'.$item->title.'</a></td>';
									echo '<td class="newsdate">'.date("M j", $timestamp).'</td>';
								echo '</tr>';
								
								$newsIndex++;
							}
						echo '</table>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="middle">
		<div class="middle-container">
			<a href="<?php module_url(); ?>register/" class="register-button"></a>
			
			<div class="home-rankings-container">
				
				<div class="home-rankings">
				<!-- Nav tabs -->
				<div class="text-center">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#abyss" aria-controls="abyss" role="tab" data-toggle="tab">Abismo</a></li>
						<li role="presentation"><a href="#gp" aria-controls="gp" role="tab" data-toggle="tab">PG</a></li>
						<li role="presentation"><a href="#kills" aria-controls="kills" role="tab" data-toggle="tab">Asesinatos</a></li>
						<li role="presentation"><a href="#legions" aria-controls="legions" role="tab" data-toggle="tab">Legiones</a></li>
						<li role="presentation"><a href="#votes" aria-controls="votes" role="tab" data-toggle="tab">Votos</a></li>
					</ul>
				</div>

				<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="abyss">
					<?php
					try {
						
						//$db = Handler::loadDB();
						//$sdb = Handler::loadDB('siel');

						// ABYSS RANKING
						$rankingData = loadCacheFile('rankings.abyss.siel.cache');
						if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
						
						$result = rankingCacheToArray($rankingData);
						
						echo '<table class="abysstable">';
							echo '<tr>';
								echo '<th>Nombre</th>';
								echo '<th>Puntos del Abismo</th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
							echo '</tr>';
							$i = 1;
							foreach($result as $row) {
								if($i >= 10) continue;
								echo '<tr>';
									echo '<td>'.$row[1].'</td>';
									echo '<td>'.number_format((int) $row[6]).'</td>';
									echo '<td>'.getRaceImg($row[3]).'</td>';
									echo '<td>'.getClassImg($row[4]).'</td>';
									echo '<td>'.getGenderImg($row[5]).'</td>';
								echo '</tr>';
								
								$i++;
							}
						echo '</table>';
						
					} catch(Exception $ex) {
						//message($ex->getMessage(), 'error');
					}
					?>
					</div>
					<div role="tabpanel" class="tab-pane" id="gp">
					<?php
						try {

							// GLORY RANKING
							$rankingData = loadCacheFile('rankings.glory.siel.cache');
							if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
							
							$result = rankingCacheToArray($rankingData);
							
							echo '<table class="abysstable">';
								echo '<tr>';
								echo '<th>Nombre</th>';
								echo '<th>Puntos de Gloria</th>';
									echo '<th></th>';
									echo '<th></th>';
									echo '<th></th>';
								echo '</tr>';
								$i = 1;
								foreach($result as $row) {
									if($i >= 10) continue;
									echo '<tr>';
										echo '<td>'.$row[1].'</td>';
										echo '<td>'.number_format((int) $row[6]).'</td>';
										echo '<td>'.getRaceImg($row[3]).'</td>';
										echo '<td>'.getClassImg($row[4]).'</td>';
										echo '<td>'.getGenderImg($row[5]).'</td>';
									echo '</tr>';
									
									$i++;
								}
							echo '</table>';
							
						} catch(Exception $ex) {
							//message($ex->getMessage(), 'error');
						}
					?>
					</div>
					<div role="tabpanel" class="tab-pane" id="kills">
					<?php
						try {
							
							//$db = Handler::loadDB();
							//$sdb = Handler::loadDB('siel');
							
							// KILLS RANKING
							$rankingData = loadCacheFile('rankings.kills.siel.cache');
							if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
							
							$result = rankingCacheToArray($rankingData);
							
							echo '<table class="abysstable">';
								echo '<tr>';
								echo '<th>Nombre</th>';
								echo '<th>Asesinatos</th>';
									echo '<th></th>';
									echo '<th></th>';
									echo '<th></th>';
								echo '</tr>';
								
								$i = 1;
								foreach($result as $row) {
									if($i >= 10) continue;
									echo '<tr>';
										echo '<td>'.$row[1].'</td>';
										echo '<td>'.number_format((int) $row[6]).'</td>';
										echo '<td>'.getRaceImg($row[3]).'</td>';
										echo '<td>'.getClassImg($row[4]).'</td>';
										echo '<td>'.getGenderImg($row[5]).'</td>';
									echo '</tr>';
									
									$i++;
								}
							echo '</table>';
							
						} catch(Exception $ex) {
							//message($ex->getMessage(), 'error');
						}
					?>
					</div>
					<div role="tabpanel" class="tab-pane" id="legions">
					<?php
						try {
							
							// LEGIONS RANKING
							$rankingData = loadCacheFile('rankings.legions.siel.cache');
							if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
							
							$result = rankingCacheToArray($rankingData);
							
							echo '<table class="legionstable">';
								echo '<tr>';
									echo '<th>Legión</th>';
									echo '<th>Puntos</th>';
								echo '</tr>';
								
								$i = 1;
								foreach($result as $row) {
									if($i >= 10) continue;
									echo '<tr>';
										echo '<td>'.$row[1].'</td>';
										echo '<td>'.number_format((int) $row[3]).'</td>';
									echo '</tr>';
									
									$i++;
								}
							echo '</table>';
							
						} catch(Exception $ex) {
							//message($ex->getMessage(), 'error');
						}
					?>
					</div>
					<div role="tabpanel" class="tab-pane" id="votes">
					<?php
					try {
						
						// VOTES RANKING
						$rankingData = loadCacheFile('rankings.votes.cache');
						if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
						
						$result = rankingCacheToArray($rankingData);
						
						echo '<table class="abysstable">';
							echo '<tr>';
								echo '<th>Nombre</th>';
								echo '<th>Votos</th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
							echo '</tr>';
							$i = 1;
							foreach($result as $row) {
								if($i >= 10) continue;
								echo '<tr>';
									echo '<td>'.$row[0].'</td>';
									echo '<td>'.$row[5].'</td>';
									echo '<td>'.getRaceImg($row[3]).'</td>';
									echo '<td>'.getClassImg($row[4]).'</td>';
									echo '<td>'.getGenderImg($row[2]).'</td>';
									
								echo '</tr>';
								
								$i++;
							}
						echo '</table>';
						
					} catch(Exception $ex) {
						//message($ex->getMessage(), 'error');
					}
					?>
					</div>
				</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="right-side">
		<div class="right-side-container">
			<a href="https://aioncms.com/" class="download-button"></a>
			
			<div class="connect-title"></div>
			<div class="right-text-container">
				<p><span style="color:#d6be93;font-size:16px;">Registro de Cuenta</span><br />
				Crea tu Cuenta <a href="<?php module_url('register/'); ?>" class="alt">Haz clic aquí para registrarte</a></p>

				<p><span style="color:#d6be93;font-size:16px;">Descarga e Instala Aion</span><br />
				1. Descarga e Instala uTorrent <a href="http://www.utorrent.com/downloads/complete/os/win/track/stable" target="_blank" class="alt">Aquí</a><br />
				1. Descarga Aion <a href="#" target="_blank" class="alt">Torrent</a> o <a href="#" target="_blank" class="alt">Mega</a><br />
				2. Descarga nuestro Lanzador <a href="#" target="_blank" class="alt">Aquí</a></p>

				<p><span style="color:#d6be93;font-size:16px;">Conectándose a Aion</span><br />
				1. Abre la carpeta de AION<br />
				2. ¡Ejecuta AION.exe para conectar!</p>
			</div>
		</div>
	</div>
</div>
