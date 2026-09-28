<?php
/**
 * AionCMS - Custom Info Page (Red de Tierra / Terminal de Supervivencia)
 * https://aioncms.com
 */

// 1. COMPROBACIÓN DE CONEXIÓN AL PLANETA
$ip_servidor = '127.0.0.1';
$puerto_login = 2106;
$socket = @fsockopen($ip_servidor, $puerto_login, $errno, $errstr, 1);
if ($socket) {
    $estado_atreia = '<span style="color: #00ffcc; text-shadow: 0 0 8px #00ffcc; font-weight: bold; letter-spacing: 1px;">ONLINE</span>';
    fclose($socket);
} else {
    $estado_atreia = '<span style="color: #ff3333; text-shadow: 0 0 8px #ff3333; font-weight: bold; letter-spacing: 1px;">OFFLINE (SEÑAL PERDIDA)</span>';
}

// =====================================================================
// SISTEMA DE SEMILLA TEMPORAL (Actualización Territorial cada 30 min)
// =====================================================================
$semilla_tiempo = floor(time() / 1800); 
$tiempo_actualizacion = $semilla_tiempo * 1800; // Recuperamos el timestamp exacto del inicio del ciclo
$fecha_actualizacion = date('d-m-Y H:i', $tiempo_actualizacion); // Formato: DIA-MES-AÑO HORA-MINUTOS

mt_srand($semilla_tiempo);

// 2. PORCENTAJES GLOBALES (Para la barra superior)
$pct_elyos = mt_rand(28, 33);
$pct_asmos = mt_rand(28, 33);
$pct_balaur = 100 - ($pct_elyos + $pct_asmos);

// 3. GENERADOR DE ZONAS DE CONTROL TÁCTICO (Para el mapa visual)
function getControlZone($pct_elyos, $pct_asmo) {
    $roll = mt_rand(1, 100);
    if ($roll <= $pct_elyos) return 'rgba(0, 191, 255, 0.7)'; // Azul Elyos
    if ($roll <= ($pct_elyos + $pct_asmo)) return 'rgba(255, 50, 50, 0.7)'; // Rojo Asmo
    return 'rgba(46, 204, 113, 0.7)'; // Verde Balaur
}

// LOWER ABYSS (Pelea entre Elyos/Asmos, poco Balaur)
$lower_left = getControlZone(45, 45);
$lower_right = getControlZone(45, 45);
$lower_center = getControlZone(40, 40);

// UPPER ABYSS (Mucho Balaur, algo de Elyos/Asmos)
$upper_left = getControlZone(20, 20);
$upper_center = getControlZone(15, 15);
$upper_right = getControlZone(20, 20);

// NÚCLEO (Dominio Balaur casi absoluto)
$core_zone = getControlZone(5, 5);


// =====================================================================
// RESTAURAR ALEATORIEDAD EN VIVO (Para el radar de entidades)
// =====================================================================
mt_srand(); 

// 4. SIMULADOR DE RADAR (Entidades en tiempo real)
$humanos_elyos = 24500 + mt_rand(-20, 30);
$humanos_asmos = 25199 + mt_rand(-25, 25);
$radar_balaur = 37850 + mt_rand(-60, 90); 
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
    width: 290px;
    color: #669999;
}
.stat-value {
    color: #e6f2f2;
    font-weight: bold;
}
.stat-elyos { color: #00bfff; }
.stat-asmo { color: #ff4d4d; }
.stat-balaur { color: #2ecc71; }

/* CONTENEDOR DEL MAPA Y BARRA DE PROGRESO */
.abyss-map-container {
    margin-top: 40px;
    background: #020202;
    border: 1px solid #1f3a3d;
    padding: 20px;
}
.abyss-title {
    color: #ff9933;
    margin-bottom: 15px;
    text-align: center;
    letter-spacing: 2px;
}

/* BARRA DE PORCENTAJES SUPERIOR */
.control-bar {
    display: flex;
    height: 28px;
    background: #000;
    border: 1px solid #222;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.8);
}
.bar-segment {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    text-shadow: 1px 1px 2px #000;
    transition: width 0.5s ease-in-out;
    border-right: 1px solid #000;
}
.bar-segment:last-child { border-right: none; }
.elyos-bar { background: linear-gradient(180deg, #154360, #2980b9); box-shadow: inset 0 0 10px #3498db; }
.balaur-bar { background: linear-gradient(180deg, #145a32, #27ae60); box-shadow: inset 0 0 10px #2ecc71; }
.asmo-bar { background: linear-gradient(180deg, #7b241c, #c0392b); box-shadow: inset 0 0 10px #e74c3c; }

/* MAPA VISUAL INTERACTIVO */
.interactive-map {
    position: relative;
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    aspect-ratio: 1 / 1; 
    background-image: url('/static/abyss.webp');
    background-size: cover;
    background-position: center;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0,0,0,0.8);
    overflow: hidden;
}

/* EFECTO DE RADAR */
.radar-sweep {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, rgba(0, 255, 204, 0) 0%, rgba(0, 255, 204, 0.1) 50%, rgba(0, 255, 204, 0) 100%);
    background-size: 100% 200%;
    animation: sweep 4s linear infinite;
    pointer-events: none;
    z-index: 10;
}
@keyframes sweep {
    0% { background-position: 0 -100%; }
    100% { background-position: 0 200%; }
}

/* NODOS TÁCTICOS */
.control-node {
    position: absolute;
    width: 25%;
    height: 25%;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    filter: blur(8px);
    animation: pulse 3s infinite alternate;
    mix-blend-mode: screen;
}
@keyframes pulse {
    0% { opacity: 0.6; transform: translate(-50%, -50%) scale(0.9); }
    100% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
}

/* LEYENDA */
.map-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 15px;
    font-size: 12px;
}
.legend-item { display: flex; align-items: center; gap: 5px; color: #fff; }
.legend-color { width: 12px; height: 12px; border-radius: 50%; }

.last-update {
    text-align: center;
    color: #669999;
    font-size: 13px;
    margin-top: 20px;
    letter-spacing: 1px;
}
.last-update span {
    color: #00ffcc;
    font-weight: bold;
}
</style>

<div class="page-header-block info"></div>
<div class="server-info-container" style="padding: 20px;">
    
    <div class="terminal-container">
        <div class="terminal-header">
            [ ESTADO DEL PLANETA ATREIA ]
        </div>

        <div class="stat-row">
            <span class="stat-label">> CONEXIÓN A ATREIA:</span>
            <span class="stat-value"><?php echo $estado_atreia; ?></span>
        </div>
                <div class="terminal-header" style="margin-top: 30px;">
           [ GUERRA ACTUAL POR EL ABISMO ]
        </div>
        
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ELYOS:</span>
            <span class="stat-value stat-elyos"><?php echo number_format($humanos_elyos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ASMODIANOS:</span>
            <span class="stat-value stat-asmo"><?php echo number_format($humanos_asmos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> BALAUR:</span>
            <span class="stat-value stat-balaur"><?php echo number_format($radar_balaur); ?> Entidades</span>
        </div>

        <div class="abyss-map-container">
            <div class="abyss-title">MAPA TÁCTICO DE LA FALLA DIMENSIONAL</div>
            
            <!-- BARRA DE PORCENTAJES SUPERIOR -->
            <div class="control-bar">
                <?php if($pct_elyos > 0): ?>
                    <div class="bar-segment elyos-bar" style="width: <?php echo $pct_elyos; ?>%;">Elyos <?php echo $pct_elyos; ?>%</div>
                <?php endif; ?>
                
                <?php if($pct_balaur > 0): ?>
                    <div class="bar-segment balaur-bar" style="width: <?php echo $pct_balaur; ?>%;">Balaur <?php echo $pct_balaur; ?>%</div>
                <?php endif; ?>
                
                <?php if($pct_asmos > 0): ?>
                    <div class="bar-segment asmo-bar" style="width: <?php echo $pct_asmos; ?>%;">Asmodian <?php echo $pct_asmos; ?>%</div>
                <?php endif; ?>
            </div>
            
            <!-- MAPA CON ZONAS DE CALOR RADIALES -->
            <div class="interactive-map">
                <div class="radar-sweep"></div>
                
                <!-- ZONAS DEL ABISMO SUPERIOR -->
                <div class="control-node" style="top: 25%; left: 25%; background: radial-gradient(circle, <?php echo $upper_left; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 22%; left: 50%; background: radial-gradient(circle, <?php echo $upper_center; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 25%; left: 75%; background: radial-gradient(circle, <?php echo $upper_right; ?> 0%, transparent 70%);"></div>
                
                <!-- ZONA DEL NÚCLEO CENTRAL -->
                <div class="control-node" style="top: 50%; left: 50%; width: 35%; height: 35%; background: radial-gradient(circle, <?php echo $core_zone; ?> 0%, transparent 70%);"></div>
                
                <!-- ZONAS DEL ABISMO INFERIOR -->
                <div class="control-node" style="top: 75%; left: 30%; background: radial-gradient(circle, <?php echo $lower_left; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 80%; left: 50%; background: radial-gradient(circle, <?php echo $lower_center; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 75%; left: 70%; background: radial-gradient(circle, <?php echo $lower_right; ?> 0%, transparent 70%);"></div>
            </div>

            <div class="map-legend">
                <div class="legend-item"><div class="legend-color" style="background: rgba(0, 191, 255, 0.8); box-shadow: 0 0 5px #00bfff;"></div> Sector Elíseo</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(255, 50, 50, 0.8); box-shadow: 0 0 5px #ff4d4d;"></div> Sector Asmodiano</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(46, 204, 113, 0.8); box-shadow: 0 0 5px #2ecc71;"></div> Anomalía Balaur</div>
            </div>
            
            <div class="last-update">
                > ACTUALIZADO: <span><?php echo $fecha_actualizacion; ?></span>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify;">
                * INFO: Actualización satelital cada 30 minutos. Las zonas marcadas en el mapa representan el control estratégico de las masas de tierra fragmentadas. El sector inferior experimenta alta volatilidad por la guerra de las dos facciones supervivientes.
            </div>
        </div>
    </div>
</div>
<br /><br />
