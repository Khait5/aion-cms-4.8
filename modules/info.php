<?php
/**
 * AionCMS - Custom Info Page (Red de Tierra / Terminal de Supervivencia)
 * https://aioncms.com
 */

// 1. COMPROBACIÓN DE CONEXIÓN AL PLANETA (Ping al Login Server)
$ip_servidor = '127.0.0.1';
$puerto_login = 2106;
$socket = @fsockopen($ip_servidor, $puerto_login, $errno, $errstr, 1);
if ($socket) {
    $estado_atreia = '<span style="color: #00ffcc; text-shadow: 0 0 8px #00ffcc; font-weight: bold; letter-spacing: 1px;">ONLINE</span>';
    fclose($socket);
} else {
    $estado_atreia = '<span style="color: #ff3333; text-shadow: 0 0 8px #ff3333; font-weight: bold; letter-spacing: 1px;">OFFLINE (SEÑAL PERDIDA)</span>';
}

// 2. SIMULADOR DE RADAR (Fluctuación muy reducida y estable)
// Ahora varía solo un máximo de 30-50 unidades por recarga.
$humanos_elyos = 24500 + rand(-20, 30);
$humanos_asmos = 25200 + rand(-25, 25);
$radar_balaur = 18750 + rand(-40, 50);

// 3. MAPA TÁCTICO DE ZONAS (Fluctuación territorial mínima)
// Varía solo entre 33% y 35% para no marear visualmente, manteniendo la inmersión.
$pct_elyos = rand(33, 35);
$pct_asmos = rand(33, 35);
$pct_balaur = 100 - ($pct_elyos + $pct_asmos);
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
    position: relative;
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
.abyss-map-container {
    margin-top: 40px;
    background: #020202;
    border: 1px solid #1f3a3d;
    padding: 20px;
    position: relative;
}
.abyss-title {
    color: #ff9933;
    margin-bottom: 15px;
    text-align: center;
    letter-spacing: 2px;
}
.control-bar {
    display: flex;
    height: 50px;
    background: #000;
    border: 2px solid #222;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0,0,0,0.8);
}
.bar-segment {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 11px;
    text-shadow: 1px 1px 3px #000;
    transition: width 0.5s ease-in-out;
    border-right: 1px solid #000;
}
.bar-segment:last-child {
    border-right: none;
}
.bar-segment span {
    font-size: 15px;
    font-weight: bold;
}
/* Colores de zona con brillo interno táctico */
.elyos-zone { background: linear-gradient(180deg, #154360, #2980b9); box-shadow: inset 0 0 15px #3498db; }
.balaur-zone { background: linear-gradient(180deg, #145a32, #27ae60); box-shadow: inset 0 0 15px #2ecc71; }
.asmo-zone { background: linear-gradient(180deg, #7b241c, #c0392b); box-shadow: inset 0 0 15px #e74c3c; }

/* Filtro de pantalla CRT sutil sobre el mapa */
.scanlines {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%);
    background-size: 100% 4px;
    pointer-events: none;
}
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
            <span class="stat-value"><?php echo number_format($humanos_elyos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> HUMANOS ASMODIANOS DETECTADOS:</span>
            <span class="stat-value"><?php echo number_format($humanos_asmos); ?> Entidades</span>
        </div>
        <div class="stat-row">
            <span class="stat-label">> ANOMALÍAS BALAUR (ABISMO):</span>
            <span class="stat-value" style="color: #ffaa00;"><?php echo number_format($radar_balaur); ?> Entidades</span>
        </div>

        <div class="abyss-map-container">
            <div class="scanlines"></div>
            <div class="abyss-title">MAPA TÁCTICO DE LA FALLA DIMENSIONAL (ABISMO)</div>
            <p style="text-align: center; font-size: 12px; color: #777; margin-top: -10px; margin-bottom: 20px; position: relative; z-index: 2;">Análisis de control territorial mediante tecnología de partículas</p>
            
            <div class="control-bar" style="position: relative; z-index: 2;">
                <?php if($pct_elyos > 0): ?>
                    <div class="bar-segment elyos-zone" style="width: <?php echo $pct_elyos; ?>%;">
                        ZONA AZUL
                        <span><?php echo $pct_elyos; ?>%</span>
                    </div>
                <?php endif; ?>
                
                <?php if($pct_balaur > 0): ?>
                    <div class="bar-segment balaur-zone" style="width: <?php echo $pct_balaur; ?>%;">
                        ZONA VERDE
                        <span><?php echo $pct_balaur; ?>%</span>
                    </div>
                <?php endif; ?>
                
                <?php if($pct_asmos > 0): ?>
                    <div class="bar-segment asmo-zone" style="width: <?php echo $pct_asmos; ?>%;">
                        ZONA ROJA
                        <span><?php echo $pct_asmos; ?>%</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify; position: relative; z-index: 2;">
                * INFO: El Sector Elíseo (Azul) y el Sector Asmodiano (Rojo) mantienen una disputa activa por el control de la falla. La raza dominante de Dragones Ancestrales (Verde) reajusta sus tropas de forma autónoma en respuesta a las fluctuaciones de la torre central.
            </div>
        </div>
    </div>
</div>
<br /><br />
