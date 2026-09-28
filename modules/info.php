<?php
/**
 * AionCMS - Custom Info Page (Red de Tierra / Terminal de Supervivencia)
 * https://aioncms.com
 */

// 1. CONFIGURACIÓN DE BASE DE DATOS (Rellena con tus datos)
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = 'TU_CONTRASEÑA_AQUI'; // Cambia esto por la contraseña de tu MySQL
$db_name = 'aion_gs'; // Base de datos del GameServer (Donde están players y siege_locations)

// 2. COMPROBACIÓN DE CONEXIÓN AL PLANETA (Ping al Login Server)
$ip_servidor = '127.0.0.1';
$puerto_login = 2106;
$socket = @fsockopen($ip_servidor, $puerto_login, $errno, $errstr, 1);
if ($socket) {
    $estado_atreia = '<span style="color: #00ffcc; text-shadow: 0 0 8px #00ffcc; font-weight: bold; letter-spacing: 1px;">ONLINE</span>';
    fclose($socket);
} else {
    $estado_atreia = '<span style="color: #ff3333; text-shadow: 0 0 8px #ff3333; font-weight: bold; letter-spacing: 1px;">OFFLINE (SEÑAL PERDIDA)</span>';
}

// 3. EXTRACCIÓN DE TELEMETRÍA (Facciones y Abismo)
$humanos_elyos = 0;
$humanos_asmos = 0;
$fort_elyos = 0;
$fort_asmos = 0;
$fort_balaur = 0;
$radar_balaur = "Señal no disponible";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

    // Conteo de Supervivientes (Personajes creados)
    $stmt_ely = $pdo->query("SELECT COUNT(*) FROM players WHERE race = 'ELYOS'");
    $humanos_elyos = $stmt_ely->fetchColumn();

    $stmt_asmo = $pdo->query("SELECT COUNT(*) FROM players WHERE race = 'ASMODIANS'");
    $humanos_asmos = $stmt_asmo->fetchColumn();

    // Conteo de Fortalezas (Control del Abismo)
    $stmt_siege = $pdo->query("SELECT race, COUNT(*) as total FROM siege_locations GROUP BY race");
    $fortalezas = $stmt_siege->fetchAll(PDO::FETCH_ASSOC);

    foreach ($fortalezas as $fort) {
        if ($fort['race'] == 'ELYOS') $fort_elyos = $fort['total'];
        if ($fort['race'] == 'ASMODIANS') $fort_asmos = $fort['total'];
        if ($fort['race'] == 'BALAUR') $fort_balaur = $fort['total'];
    }

    // Estimación del radar de anomalías (Más fortalezas controladas = más balaures detectados)
    $radar_balaur = 15200 + ($fort_balaur * 3500);

} catch (PDOException $e) {
    $radar_balaur = "ERROR DE LECTURA DB";
}

// 4. CÁLCULO DE PORCENTAJES DEL MAPA TÁCTICO
$total_fort = $fort_elyos + $fort_asmos + $fort_balaur;
if ($total_fort == 0) $total_fort = 1; // Evitar división por cero

$pct_elyos = round(($fort_elyos / $total_fort) * 100);
$pct_asmos = round(($fort_asmos / $total_fort) * 100);
$pct_balaur = round(($fort_balaur / $total_fort) * 100);
?>

<style>
.terminal-container {
    background-color: #0a0a0c;
    border: 1px solid #1f3a3d;
    box-shadow: inset 0 0 20px rgba(0, 255, 204, 0.05);
    padding: 30px;
    font-family: 'Courier New', Courier, monospace;
    color: #a3c2c2;
    max-width: 800px;
    margin: 0 auto;
}
.terminal-header {
    border-bottom: 2px solid #1f3a3d;
    padding-bottom: 10px;
    margin-bottom: 20px;
    font-size: 18px;
    color: #00ffcc;
    text-transform: uppercase;
}
.stat-row {
    margin-bottom: 15px;
    font-size: 16px;
}
.stat-label {
    display: inline-block;
    width: 280px;
    color: #669999;
}
.stat-value {
    color: #e6f2f2;
    font-weight: bold;
}
.abyss-map-container {
    margin-top: 40px;
    background: #050505;
    border: 1px solid #333;
    padding: 20px;
}
.abyss-title {
    color: #ff9933;
    margin-bottom: 15px;
    text-align: center;
    letter-spacing: 2px;
}
.control-bar {
    display: flex;
    height: 30px;
    background: #111;
    border: 1px solid #444;
    border-radius: 3px;
    overflow: hidden;
}
.bar-segment {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    text-shadow: 1px 1px 2px #000;
    transition: width 1s ease-in-out;
}
.elyos-bg { background: linear-gradient(90deg, #1a5276, #2980b9); }
.asmo-bg { background: linear-gradient(90deg, #7b241c, #c0392b); }
.balaur-bg { background: linear-gradient(90deg, #145a32, #27ae60); }
</style>

<div class="page-header-block info"></div>
<div class="server-info-container" style="padding: 20px;">
    
    <div class="terminal-container">
        <div class="terminal-header">
            [ TERMINAL DE RED DE TIERRA - ESTADO DEL PLANETA ]
        </div>

        <div class="stat-row">
            <span class="stat-label">> CONEXIÓN A ATREIA:</span>
            <span class="stat-value"><?php echo $estado_atreia; ?></span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ELYOS DETECTADOS:</span>
            <span class="stat-value"><?php echo number_format($humanos_elyos); ?> Supervivientes</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ASMODIANOS DETECTADOS:</span>
            <span class="stat-value"><?php echo number_format($humanos_asmos); ?> Supervivientes</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> ANOMALÍAS BALAUR (ABISMO):</span>
            <span class="stat-value" style="color: #ffaa00;"><?php echo is_numeric($radar_balaur) ? number_format($radar_balaur) . ' Entidades (Estimado)' : $radar_balaur; ?></span>
        </div>

        <div class="abyss-map-container">
            <div class="abyss-title">MAPA TÁCTICO DE LA FALLA DIMENSIONAL (ABISMO)</div>
            <p style="text-align: center; font-size: 12px; color: #777; margin-top: -10px; margin-bottom: 20px;">Control territorial de las Fortalezas de Partículas</p>
            
            <div class="control-bar">
                <?php if($pct_elyos > 0): ?>
                    <div class="bar-segment elyos-bg" style="width: <?php echo $pct_elyos; ?>%;">Elyos <?php echo $pct_elyos; ?>%</div>
                <?php endif; ?>
                
                <?php if($pct_balaur > 0): ?>
                    <div class="bar-segment balaur-bg" style="width: <?php echo $pct_balaur; ?>%;">Balaur <?php echo $pct_balaur; ?>%</div>
                <?php endif; ?>
                
                <?php if($pct_asmos > 0): ?>
                    <div class="bar-segment asmo-bg" style="width: <?php echo $pct_asmos; ?>%;">Asmodian <?php echo $pct_asmos; ?>%</div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify;">
                * INFO: El Sector Elíseo y el Sector Asmodiano mantienen una disputa activa por el control de la falla. La raza dominante de Dragones Ancestrales (Balaur) incrementa sus defensas en base a la inestabilidad energética.
            </div>
        </div>
    </div>
</div>
<br /><br />
