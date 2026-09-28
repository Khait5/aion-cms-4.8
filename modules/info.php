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

// 2. SIMULADOR DE RADAR (Entidades en tiempo real)
$humanos_elyos = 24500 + rand(-20, 30);
$humanos_asmos = 25200 + rand(-25, 25);
$radar_balaur = 18750 + rand(-40, 50);

// 3. GENERADOR DE ZONAS DE CONTROL TÁCTICO
// Función para calcular quién controla una zona basándose en probabilidades
function getControlZone($pct_elyos, $pct_asmo) {
    $roll = rand(1, 100);
    if ($roll <= $pct_elyos) return 'rgba(0, 191, 255, 0.7)'; // Azul Elyos
    if ($roll <= ($pct_elyos + $pct_asmo)) return 'rgba(255, 50, 50, 0.7)'; // Rojo Asmo
    return 'rgba(46, 204, 113, 0.7)'; // Verde Balaur
}

// Generación de los colores de cada fortaleza en el mapa
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

/* CONTENEDOR DEL MAPA (Basado en la imagen cuadrada) */
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
.interactive-map {
    position: relative;
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    aspect-ratio: 1 / 1; /* Mantiene la proporción cuadrada de tu imagen */
    background-image: url('/static/abyss.webp');
    background-size: cover;
    background-position: center;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0,0,0,0.8);
    overflow: hidden;
}

/* EFECTO DE RADAR (Línea que barre el mapa) */
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

/* NODOS TÁCTICOS (Manchas de control en las islas) */
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

/* LEYENDA DEL MAPA */
.map-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 15px;
    font-size: 12px;
}
.legend-item { display: flex; align-items: center; gap: 5px; color: #fff; }
.legend-color { width: 12px; height: 12px; border-radius: 50%; }
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
            <span class="stat-value stat-elyos"><?php echo number_format($humanos_elyos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ASMODIANOS DETECTADOS:</span>
            <span class="stat-value stat-asmo"><?php echo number_format($humanos_asmos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> ANOMALÍAS BALAUR (ABISMO):</span>
            <span class="stat-value stat-balaur"><?php echo number_format($radar_balaur); ?> Entidades</span>
        </div>

        <div class="abyss-map-container">
            <div class="abyss-title">MAPA TÁCTICO DE LA FALLA DIMENSIONAL</div>
            
            <div class="interactive-map">
                <div class="radar-sweep"></div>
                
                <!-- ZONAS DEL ABISMO SUPERIOR (Mucho Balaur) -->
                <div class="control-node" style="top: 25%; left: 25%; background: radial-gradient(circle, <?php echo $upper_left; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 22%; left: 50%; background: radial-gradient(circle, <?php echo $upper_center; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 25%; left: 75%; background: radial-gradient(circle, <?php echo $upper_right; ?> 0%, transparent 70%);"></div>
                
                <!-- ZONA DEL NÚCLEO CENTRAL (Casi siempre Balaur) -->
                <div class="control-node" style="top: 50%; left: 50%; width: 35%; height: 35%; background: radial-gradient(circle, <?php echo $core_zone; ?> 0%, transparent 70%);"></div>
                
                <!-- ZONAS DEL ABISMO INFERIOR (Peleado entre Elyos y Asmos) -->
                <div class="control-node" style="top: 75%; left: 30%; background: radial-gradient(circle, <?php echo $lower_left; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 80%; left: 50%; background: radial-gradient(circle, <?php echo $lower_center; ?> 0%, transparent 70%);"></div>
                <div class="control-node" style="top: 75%; left: 70%; background: radial-gradient(circle, <?php echo $lower_right; ?> 0%, transparent 70%);"></div>
            </div>

            <div class="map-legend">
                <div class="legend-item"><div class="legend-color" style="background: rgba(0, 191, 255, 0.8); box-shadow: 0 0 5px #00bfff;"></div> Sector Elíseo</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(255, 50, 50, 0.8); box-shadow: 0 0 5px #ff4d4d;"></div> Sector Asmodiano</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(46, 204, 113, 0.8); box-shadow: 0 0 5px #2ecc71;"></div> Anomalía Balaur</div>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify;">
                * INFO: Actualización satelital en tiempo real. Las zonas marcadas en el mapa representan el control estratégico de las masas de tierra fragmentadas. El sector inferior experimenta alta volatilidad por la guerra de las dos facciones supervivientes.
            </div>
        </div>
    </div>
</div>
<br /><br />
