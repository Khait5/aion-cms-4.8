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

mt_srand($semilla_tiempo); // Fijar la semilla para que no cambie al recargar

// 2. PORCENTAJES GLOBALES (Para la barra superior)
$pct_elyos = mt_rand(28, 33);
$pct_asmos = mt_rand(28, 33);
$pct_balaur = 100 - ($pct_elyos + $pct_asmos);

// 3. GENERADOR DE ZONAS (Guardando Facción, Color y Coordenadas)
function getZoneData($pct_elyos, $pct_asmo, $top, $left, $width = 25) {
    $roll = mt_rand(1, 100);
    if ($roll <= $pct_elyos) return ['faction' => 'elyos', 'color' => 'rgba(0, 191, 255, 0.7)', 'top' => $top, 'left' => $left, 'w' => $width];
    if ($roll <= ($pct_elyos + $pct_asmo)) return ['faction' => 'asmo', 'color' => 'rgba(255, 50, 50, 0.7)', 'top' => $top, 'left' => $left, 'w' => $width];
    return ['faction' => 'balaur', 'color' => 'rgba(46, 204, 113, 0.7)', 'top' => $top, 'left' => $left, 'w' => $width];
}

$zones = [
    'll' => getZoneData(45, 45, 75, 30), // Lower Left
    'lc' => getZoneData(40, 40, 80, 50), // Lower Center
    'lr' => getZoneData(45, 45, 75, 70), // Lower Right
    'ul' => getZoneData(20, 20, 25, 25), // Upper Left
    'uc' => getZoneData(15, 15, 22, 50), // Upper Center
    'ur' => getZoneData(20, 20, 25, 75), // Upper Right
    'core' => getZoneData(5, 5, 50, 50, 35) // Core (Centro, es más grande: 35%)
];

// 4. ALGORITMO DE ATAQUE TÁCTICO TIPO RADAR
$attacks = [];
$attacked_zones = [];

// Desordenar las zonas de forma aleatoria atada a la semilla para elegir quién ataca primero
$zone_keys = array_keys($zones);
$shuffled_keys = [];
while(count($zone_keys) > 0) {
    $idx = mt_rand(0, count($zone_keys) - 1);
    $shuffled_keys[] = $zone_keys[$idx];
    array_splice($zone_keys, $idx, 1);
}

foreach ($shuffled_keys as $attacker_key) {
    // Regla: Si esta zona está siendo atacada, tiene que defenderse (no puede atacar)
    if (in_array($attacker_key, $attacked_zones)) continue;

    // Regla: 60% de probabilidad de que decidan lanzar un ataque
    if (mt_rand(1, 100) <= 60) {
        $valid_targets = [];
        
        // Buscar zonas válidas para atacar
        foreach ($zones as $target_key => $target_data) {
            if ($target_key == $attacker_key) continue; // No atacarse a sí mismo
            if (in_array($target_key, $attacked_zones)) continue; // No atacar a alguien ya asediado
            if ($zones[$attacker_key]['faction'] == $target_data['faction']) continue; // Fuego amigo anulado

            $valid_targets[] = $target_key;
        }

        // Si hay objetivos viables, elegir uno al azar y lanzar el ataque
        if (count($valid_targets) > 0) {
            $target = $valid_targets[mt_rand(0, count($valid_targets) - 1)];
            $attacked_zones[] = $target; // Marcar como asediada
            
            $attacks[] = [
                'from' => $zones[$attacker_key],
                'to' => $zones[$target]
            ];
        }
    }
}

// =====================================================================
// RESTAURAR ALEATORIEDAD EN VIVO (Para el radar de entidades numéricas)
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
    filter: blur(8px);
    animation: pulse 3s infinite alternate;
    mix-blend-mode: screen;
}
@keyframes pulse {
    0% { opacity: 0.6; transform: translate(-50%, -50%) scale(0.9); }
    100% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
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
    filter: drop-shadow(0px 0px 3px rgba(0,0,0,0.8));
}

.map-legend { display: flex; justify-content: center; gap: 20px; margin-top: 15px; font-size: 12px; }
.legend-item { display: flex; align-items: center; gap: 5px; color: #fff; }
.legend-color { width: 12px; height: 12px; border-radius: 50%; }

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
                
                <!-- GENERAR NODOS DE CALOR DESDE PHP -->
                <?php foreach ($zones as $key => $zone): ?>
                <div class="control-node" style="top: <?php echo $zone['top']; ?>%; left: <?php echo $zone['left']; ?>%; width: <?php echo $zone['w']; ?>%; height: <?php echo $zone['w']; ?>%; background: radial-gradient(circle, <?php echo $zone['color']; ?> 0%, transparent 70%);"></div>
                <?php endforeach; ?>
                
                <!-- CAPA SVG PARA LAS FLECHAS DE ATAQUE TÁCTICAS -->
                <svg class="attack-vectors">
                    <defs>
                        <!-- Puntas de flecha por facción -->
                        <marker id="arrow-elyos" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#00bfff" /></marker>
                        <marker id="arrow-asmo" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#ff4d4d" /></marker>
                        <marker id="arrow-balaur" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="5" markerHeight="5" orient="auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#2ecc71" /></marker>
                    </defs>
                    
                    <?php foreach ($attacks as $attack): 
                        // Asignar color y marcador correcto según el atacante
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
            
            <div class="last-update">
                > ACTUALIZACIÓN SATELITAL: <span><?php echo $fecha_actualizacion; ?></span>
            </div>
            
            <div style="margin-top: 15px; font-size: 11px; color: #555; text-align: justify;">
                * INFO: Actualización estratégica cada 30 minutos. Las flechas animadas indican las rutas de asalto confirmadas por el escáner. Una fortaleza asediada desvía todas sus tropas a tareas defensivas.
            </div>
        </div>
    </div>
</div>
<br /><br />
