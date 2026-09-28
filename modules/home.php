<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="container-fluid home-main-content">
	<div class="row">
		<div class="col-md-4 col-sm-12 left-side">
			<div class="left-side-container">
				<?php if(!isLoggedIn()) { ?>
				<div class="login-box-responsive" style="margin-bottom: 20px;">
					<form action="<?php module_url(); ?>login/" method="post">
						<input type="text" class="form-control" maxlength="25" name="login_username" placeholder="Usuario" autofocus style="margin-bottom:10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff;"/>
						<input type="password" class="form-control" name="login_password" placeholder="Contraseña" style="margin-bottom:10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff;"/><br />
						<button type="submit" class="btn btn-primary btn-block" name="login_submit" value="ok">Iniciar Sesión</button>
					</form>
				</div>
				<?php } else { ?>
				<div class="account-box-responsive" style="margin-bottom: 20px; background: rgba(0,0,0,0.5); padding: 15px; border: 1px solid #444;">
					<div style="text-align:center;">
						Bienvenido de nuevo, <?php echo $_SESSION['username']; ?><br />
						<a href="<?php module_url(); ?>usercp/" style="color:#63c2ff;text-decoration:none;">[ panel de usuario ]</a> | <a href="<?php module_url(); ?>logout/" style="color:#ff6363;text-decoration:none;">[ salir ]</a>
						<br />
						<div style="font-size:11px;margin-top: 10px;">
							<span style="color:#cccccc;">Hora del Servidor</span><br />
							<span style="color:#00ff00;"><?php echo date("Y-m-d h:i A"); ?></span>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="left-text-container" style="background: rgba(0,0,0,0.5); padding: 15px; border: 1px solid #444;">
					<p>Bienvenido al Servidor Privado <span style="color:#d6be93;">ColombianAion</span>.</p>
					<p>¡ColombianAge 4.8 ya está disponible!</p>
					<div class="left-text-container-newsblock" style="margin-top: 15px;">
						<?php
						$forumRss = 'FORUM_NEWS_RSS_LINK';
						$loadRssXml = @simplexml_load_file($forumRss);
						if($loadRssXml) {
							$eventsFeed = $loadRssXml->channel;
							
							echo '<span class="newsheader" style="color: #ffcc00; font-weight: bold; display: block; margin-bottom: 10px;">Últimas Noticias:</span>';
							echo '<table class="table table-condensed table-responsive" style="color: #ddd;">';
								$newsIndex = 0;
								foreach($eventsFeed->item as $item) {
									if($newsIndex > 8) continue;
									//if(!preg_match('/Carl/', $item->author) && !preg_match('/Lautaro/', $item->author)) continue;
									$timestamp = strtotime($item->pubDate);

									echo '<tr>';
										echo '<td class="newstitle" style="border-top: 1px solid #444;"><a href="'.$item->link.'" target="_blank" style="color: #888;">'.$item->title.'</a></td>';
										echo '<td class="newsdate" style="border-top: 1px solid #444; opacity: 0.5;">'.date("M j", $timestamp).'</td>';
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

		<div class="col-md-4 col-sm-12 middle">
			<div class="middle-container" style="background: rgba(0,0,0,0.5); padding: 15px; border: 1px solid #444;">
				<a href="<?php module_url(); ?>register/" class="btn btn-warning btn-block btn-lg" style="margin-bottom: 20px; font-weight: bold;">Registrarse Ahora</a>

				<div class="home-rankings-container-responsive">

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
					<div class="tab-content" style="padding-top: 15px;">
						<div role="tabpanel" class="tab-pane active" id="abyss">
						<?php
						try {
							
							//$db = Handler::loadDB();
							//$sdb = Handler::loadDB('siel');

							// ABYSS RANKING
							$rankingData = loadCacheFile('rankings.abyss.siel.cache');
							if(!$rankingData) throw new Exception("There was a problem loading the ranking data, please try again later.");
							
							$result = rankingCacheToArray($rankingData);
							
							echo '<table class="table table-striped table-condensed table-responsive" style="color: #ddd;">';
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

								echo '<table class="table table-striped table-condensed table-responsive" style="color: #ddd;">';
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

								echo '<table class="table table-striped table-condensed table-responsive" style="color: #ddd;">';
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

								echo '<table class="table table-striped table-condensed table-responsive" style="color: #ddd;">';
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
							
							echo '<table class="table table-striped table-condensed table-responsive" style="color: #ddd;">';
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

		<div class="col-md-4 col-sm-12 right-side">
			<div class="right-side-container" style="background: rgba(0,0,0,0.5); padding: 15px; border: 1px solid #444;">
				<a href="#" class="btn btn-info btn-block btn-lg" style="margin-bottom: 20px; font-weight: bold;">Descargar Cliente</a>

				<div class="connect-title" style="color: #d6be93; font-size: 20px; font-weight: bold; margin-bottom: 15px;">Guía de Conexión</div>
				<div class="right-text-container">
					<p><span style="color:#d6be93;font-size:16px;">Registro de Cuenta</span><br />
					Crea tu Cuenta <a href="<?php module_url('register/'); ?>" class="alt" style="color: #63c2ff;">Haz clic aquí para registrarte</a></p>

					<p><span style="color:#d6be93;font-size:16px;">Descarga e Instala Aion</span><br />
					1. Descarga e Instala uTorrent <a href="http://www.utorrent.com/downloads/complete/os/win/track/stable" target="_blank" class="alt" style="color: #63c2ff;">Aquí</a><br />
					1. Descarga Aion <a href="#" target="_blank" class="alt" style="color: #63c2ff;">Torrent</a> o <a href="#" target="_blank" class="alt" style="color: #63c2ff;">Mega</a><br />
					2. Descarga nuestro Lanzador <a href="#" target="_blank" class="alt" style="color: #63c2ff;">Aquí</a></p>

					<p><span style="color:#d6be93;font-size:16px;">Conectándose a Aion</span><br />
					1. Abre la carpeta de AION<br />
					2. ¡Ejecuta AION.exe para conectar!</p>
				</div>
			</div>
		</div>
	</div>
</div>
