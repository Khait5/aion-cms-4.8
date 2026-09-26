?>
<div class="page-header-block rankings"></div>
<br /><br />

<?php
try {
    // PHP 8.0: Uso de Null Coalescing (??) para evitar errores si 'submodule' no está definido
    $rankingType = $_GET['submodule'] ?? 'abyss';
    $rankingServer = 'siel';

    $rankingData = loadCacheFile('rankings.abyss.' . $rankingServer . '.cache');
    
    if (!$rankingData) {
        throw new Exception("There was a problem loading the ranking data, please try again later.");
    }

    $result = rankingCacheToArray($rankingData);
?>
    <!-- Menú de Rankings -->
    <div class="rankings-selection">
        <a href="<?= module_url('rankings/abyss/', true) ?>" class="active">Abyss</a> / 
        <a href="<?= module_url('rankings/glory/', true) ?>">Glory Points</a> / 
        <a href="<?= module_url('rankings/kills/', true) ?>">Kills</a> / 
        <a href="<?= module_url('rankings/legions/', true) ?>">Legions</a> / 
        <a href="<?= module_url('rankings/votes/', true) ?>">Votes</a>
    </div>

    <!-- ABYSS RANKING -->
    <table class="rankings-table" cellspacing="0">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Level</th>
                <th>Abyss Points</th>
                <th>Race</th>
                <th>Class</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($result as $row) {
                // PHP 8.0: Forzamos (int) para prevenir ValueErrors en funciones como expToLevel o number_format si el valor es null
                $name = htmlspecialchars($row[1] ?? '', ENT_QUOTES, 'UTF-8');
                $level = expToLevel((int) ($row[2] ?? 0));
                $abyssPoints = number_format((int) ($row[6] ?? 0));
                $raceImg = getRaceImg($row[3] ?? '');
                $classImg = getClassImg($row[4] ?? '');
                $genderImg = getGenderImg($row[5] ?? '');
            ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $name ?></td>
                    <td><?= $level ?></td>
                    <td><?= $abyssPoints ?></td>
                    <td><?= $raceImg ?></td>
                    <td><?= $classImg ?></td>
                    <td><?= $genderImg ?></td>
                </tr>
            <?php
                $i++;
            }
            ?>
        </tbody>
    </table>

<?php
} catch (Exception $ex) {
    message($ex->getMessage(), 'error');
}
?>
