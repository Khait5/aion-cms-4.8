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
$tiempo_actualizacion = $semilla_tiempo * 1800;
$fecha_actualizacion = date('d-m-Y H:i', $tiempo_actualizacion);

mt_srand($semilla_tiempo);

// 2. PORCENTAJES GLOBALES (Para la barra superior)
$pct_elyos = mt_rand(28, 33);
$pct_asmos = mt_rand(28, 33);
$pct_balaur = 100 - ($pct_elyos + $pct_asmos);

// 3. GENERADOR DE ZONAS (Opacidad ajustada a 0.8)
function getZoneData($pct_elyos, $pct_asmo, $top, $left, $width = 25) {
    $roll = mt_rand(1, 100);
    if ($roll <= $pct_elyos) return ['faction' => 'elyos', 'color' => 'rgba(0, 191, 255, 0.8)', 'top' => $top, 'left' => $left, 'w' => $width];
    if ($roll <= ($pct_elyos + $pct_asmo)) return ['faction' => 'asmo', 'color' => 'rgba(255, 50, 50, 0.8)', 'top' => $top, 'left' => $left, 'w' => $width];
    return ['faction' => 'balaur', 'color' => 'rgba(46, 204, 113, 0.8)', 'top' => $top, 'left' => $left, 'w' => $width];
}

$zones = [
    'll' => getZoneData(45, 45, 75, 30),
    'lc' => getZoneData(40, 40, 80, 50),
    'lr' => getZoneData(45, 45, 75, 70),
    'ul' => getZoneData(20, 20, 25, 25),
    'uc' => getZoneData(15, 15, 22, 50),
    'ur' => getZoneData(20, 20, 25, 75),
    'core' => getZoneData(5, 5, 50, 50, 35)
];

// 4. ALGORITMO DE ATAQUE TÁCTICO
$attacks = [];
$attacked_zones = [];
$zone_keys = array_keys($zones);
$shuffled_keys = [];

while(count($zone_keys) > 0) {
    $idx = mt_rand(0, count($zone_keys) - 1);
    $shuffled_keys[] = $zone_keys[$idx];
    array_splice($zone_keys, $idx, 1);
}

foreach ($shuffled_keys as $attacker_key) {
    if (in_array($attacker_key, $attacked_zones)) continue;

    if (mt_rand(1, 100) <= 60) {
        $valid_targets = [];
        foreach ($zones as $target_key => $target_data) {
            if ($target_key == $attacker_key) continue; 
            if (in_array($target_key, $attacked_zones)) continue; 
            if ($zones[$attacker_key]['faction'] == $target_data['faction']) continue; 
            $valid_targets[] = $target_key;
        }

        if (count($valid_targets) > 0) {
            $target = $valid_targets[mt_rand(0, count($valid_targets) - 1)];
            $attacked_zones[] = $target; 
            
            $attacks[] = [
                'from' => $zones[$attacker_key],
                'to' => $zones[$target],
                'from_key' => $attacker_key,
                'to_key' => $target
            ];
        }
    }
}

// 5. GENERADOR DE TEXTO DE BATALLA POR ZONAS Y EVENTOS ALEATORIOS
$faction_tags = [
    'elyos' => '<span class="stat-elyos">Los Elyos</span>',
    'asmo' => '<span class="stat-asmo">Los Asmodianos</span>',
    'balaur' => '<span class="stat-balaur">Los Balaur</span>'
];

$zone_names = [
    'll' => 'Suroeste Inferior', 'lc' => 'Sur Inferior', 'lr' => 'Sureste Inferior',
    'ul' => 'Noroeste Superior', 'uc' => 'Norte Superior', 'ur' => 'Noreste Superior', 'core' => 'Núcleo Central'
];

$eventos_inactivos = [
    "realizan patrullajes de rutina sin detectar anomalías biológicas.",
    "detectan una extraña fluctuación de partículas y proceden a investigar las inmediaciones.",
    "descubren una veta de minerales de alta energía y establecen un perímetro de excavación.",
    "sufren fallos temporales en las comunicaciones debido a interferencias de la Torre.",
    "interceptan transmisiones codificadas de origen desconocido y mantienen alerta máxima.",
    "dedican el ciclo a reforzar las barreras estructurales de sus fortalezas tácticas.",
    "realizan maniobras logísticas de reabastecimiento y mantenimiento de equipos.",
    "reportan avistamientos de fauna mutada en la periferia, pero mantienen la posición.",
    "establecen una torre temporal de relé para intentar enlazar con la Red de Tierra.",
    "localizan ruinas subterráneas no cartografiadas y despliegan drones de reconocimiento."
];

$battle_log = [];

// Analizar cada zona individualmente
foreach ($zones as $z_key => $z_data) {
    $fac_name = $faction_tags[$z_data['faction']];
    $z_name = $zone_names[$z_key];
    
    // Comprobar si esta zona está atacando a alguien
    $is_attacking = false;
    $target_zone = '';
    $target_fac = '';
    foreach ($attacks as $atk) {
        if ($atk['from_key'] == $z_key) {
            $is_attacking = true;
            $target_zone = $zone_names[$atk['to_key']];
            $target_fac = $faction_tags[$atk['to']['faction']];
            break;
        }
    }
    
    // Comprobar si esta zona está siendo atacada
    $is_defending = false;
    $attacker_fac = '';
    foreach ($attacks as $atk) {
        if ($atk['to_key'] == $z_key) {
            $is_defending = true;
            $attacker_fac = $faction_tags[$atk['from']['faction']];
            break;
        }
    }
    
    // Generar el registro según el estado de la zona
    if ($is_attacking) {
        $battle_log[] = "> " . $fac_name . " del sector " . $z_name . " lanzan una ofensiva táctica contra " . $target_fac . " en el sector " . $target_zone . ".";
    } elseif ($is_defending) {
        $battle_log[] = "> " . $fac_name . " del sector " . $z_name . " se atrincheran y defienden la posición ante el asalto enemigo de " . $attacker_fac . ".";
    } else {
        $evento_random = $eventos_inactivos[mt_rand(0, count($eventos_inactivos) - 1)];
        $battle_log[] = "> " . $fac_name . " del sector " . $z_name . " " . $evento_random;
    }
}

// =====================================================================
// RESTAURAR ALEATORIEDAD EN VIVO (Para el radar numérico)
// =====================================================================
mt_srand(); 

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
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 12px; font-weight: bold;
    text-shadow: 1px 1px 2px #000; transition: width 0.5s ease-in-out;
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

.radar-sweep {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, rgba(0, 255, 204, 0) 0%, rgba(0, 255, 204, 0.1) 50%, rgba(0, 255, 204, 0) 100%);
    background-size: 100% 200%;
    animation: sweep 4s linear infinite;
    pointer-events: none; z-index: 10;
}
@keyframes sweep {
    0% { background-position: 0 -100%; }
    100% { background-position: 0 200%; }
}

.control-node {
    position: absolute;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    filter: blur(4px);
    animation: pulse 3s infinite alternate;
}
@keyframes pulse {
    0% { opacity: 0.7; transform: translate(-50%, -50%) scale(0.95); }
    100% { opacity: 1; transform: translate(-50%, -50%) scale(1.05); }
}

/* VECTORES DE ATAQUE ANIMADOS */
.attack-vectors {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: 5; pointer-events: none;
}
@keyframes dash-animation {
    to { stroke-dashoffset: -20; }
}
.attack-line {
    stroke-dasharray: 6, 6;
    animation: dash-animation 1s linear infinite;
    filter: drop-shadow(0px 0px 3px rgba(0,0,0,1));
}

.map-legend { display: flex; justify-content: center; gap: 20px; margin-top: 15px; font-size: 12px; }
.legend-item { display: flex; align-items: center; gap: 5px; color: #fff; }
.legend-color { width: 12px; height: 12px; border-radius: 50%; }

/* REPORTE DE BATALLA NARRATIVO */
.battle-report-box {
    margin-top: 25px;
    padding: 15px;
    background: rgba(5, 5, 5, 0.9);
    border: 1px solid #1f3a3d;
    border-radius: 3px;
    font-size: 13px;
    color: #88b3b3;
    line-height: 1.6;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.8);
}
.battle-report-title {
    color: #ff9933;
    font-weight: bold;
    margin-bottom: 10px;
    letter-spacing: 1px;
    border-bottom: 1px solid #333;
    padding-bottom: 5px;
}
.battle-entry { margin-bottom: 8px; }

.last-update { text-align: center; color: #669999; font-size: 13px; margin-top: 20px; letter-spacing: 1px; }
.last-update span { color: #00ffcc; font-weight: bold; }
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
            
            <div class="control-bar">
                <?php if($pct_elyos > 0): ?><div class="bar-segment elyos-bar" style="width: <?php echo $pct_elyos; ?>%;">Elyos <?php echo $pct_elyos; ?>%</div><?php endif; ?>
                <?php if($pct_balaur > 0): ?><div class="bar-segment balaur-bar" style="width: <?php echo $pct_balaur; ?>%;">Balaur <?php echo $pct_balaur; ?>%</div><?php endif; ?>
                <?php if($pct_asmos > 0): ?><div class="bar-segment asmo-bar" style="width: <?php echo $pct_asmos; ?>%;">Asmodian <?php echo $pct_asmos; ?>%</div><?php endif; ?>
            </div>
            
            <div class="interactive-map">
                <div class="radar-sweep"></div>
                
                <?php foreach ($zones as $key => $zone): ?>
                <div class="control-node" style="top: <?php echo $zone['top']; ?>%; left: <?php echo $zone['left']; ?>%; width: <?php echo $zone['w']; ?>%; height: <?php echo $zone['w']; ?>%; background: radial-gradient(circle, <?php echo $zone['color']; ?> 30%, transparent 80%);"></div>
                <?php endforeach; ?>
                
                <svg class="attack-vectors">
                    <defs>
                        <marker id="arrow-elyos" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#00bfff" /></marker>
                        <marker id="arrow-asmo" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#ff4d4d" /></marker>
                        <marker id="arrow-balaur" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#2ecc71" /></marker>
                    </defs>
                    
                    <?php foreach ($attacks as $attack): 
                        $color = '#fff'; $marker = '';
                        if ($attack['from']['faction'] == 'elyos') { $color = '#00bfff'; $marker = 'url(#arrow-elyos)'; }
                        if ($attack['from']['faction'] == 'asmo') { $color = '#ff4d4d'; $marker = 'url(#arrow-asmo)'; }
                        if ($attack['from']['faction'] == 'balaur') { $color = '#2ecc71'; $marker = 'url(#arrow-balaur)'; }
                    ?>
                    <line class="attack-line" 
                          x1="<?php echo $attack['from']['left']; ?>%" y1="<?php echo $attack['from']['top']; ?>%" 
                          x2="<?php echo $attack['to']['left']; ?>%" y2="<?php echo $attack['to']['top']; ?>%" 
                          stroke="<?php echo $color; ?>" stroke-width="2" marker-end="<?php echo $marker; ?>" />
                    <?php endforeach; ?>
                </svg>
            </div>

            <div class="map-legend">
                <div class="legend-item"><div class="legend-color" style="background: rgba(0, 191, 255, 0.8); box-shadow: 0 0 5px #00bfff;"></div> Sector Elíseo</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(255, 50, 50, 0.8); box-shadow: 0 0 5px #ff4d4d;"></div> Sector Asmodiano</div>
                <div class="legend-item"><div class="legend-color" style="background: rgba(46, 204, 113, 0.8); box-shadow: 0 0 5px #2ecc71;"></div> Anomalía Balaur</div>
            </div>

            <div class="battle-report-box">
                <div class="battle-report-title">BITÁCORA DE ZONAS (ESCANEO DE RED)</div>
                <?php foreach ($battle_log as $log_entry): ?>
                    <div class="battle-entry"><?php echo $log_entry; ?></div>
                <?php endforeach; ?>
            </div>
            
            <div class="last-update">
                > ACTUALIZACIÓN SATELITAL: <span><?php echo $fecha_actualizacion; ?></span>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify;">
                * INFO: Actualización estratégica cada 30 minutos. El reporte clasifica las hostilidades y los eventos de supervivencia de cada punto clave en la falla.
            </div>
        </div>
    </div>
</div>
<br /><br />
