<?php

require __DIR__ . '/includes/header.php';

if(!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<h1>Mes données</h1>

<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Signalisation</th>
            <th>Arrêt/Stationnement</th>
            <th>Feux/Intempéries</th>
            <th>Véhicule</th>
            <th>Dépassement</th>
            <th>Orientation</th>
            <th>Priorités</th>
            <th>Conducteur</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $files = glob(__DIR__ . '/data/*.json');

            foreach ($files as $file):
                $content = file_get_contents($file);
                $data = json_decode($content, true);
        ?>
        <tr>
            <td><?= $data['date'] ?></td>
            <td><?= $data['time'] ?></td>
            <td><?= $data['signalisation']['total'] ?>/<?= $data['signalisation']['erreurs'] ?> (<?= number_format(($data['signalisation']['total'] - $data['signalisation']['erreurs']) / $data['signalisation']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['stationnement']['total'] ?>/<?= $data['stationnement']['erreurs'] ?> (<?= number_format(($data['stationnement']['total'] - $data['stationnement']['erreurs']) / $data['stationnement']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['feux']['total'] ?>/<?= $data['feux']['erreurs'] ?> (<?= number_format(($data['feux']['total'] - $data['feux']['erreurs']) / $data['feux']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['vehicule']['total'] ?>/<?= $data['vehicule']['erreurs'] ?> (<?= number_format(($data['vehicule']['total'] - $data['vehicule']['erreurs']) / $data['vehicule']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['depassement']['total'] ?>/<?= $data['depassement']['erreurs'] ?> (<?= number_format(($data['depassement']['total'] - $data['depassement']['erreurs']) / $data['depassement']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['orientation']['total'] ?>/<?= $data['orientation']['erreurs'] ?> (<?= number_format(($data['orientation']['total'] - $data['orientation']['erreurs']) / $data['orientation']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['priorites']['total'] ?>/<?= $data['priorites']['erreurs'] ?> (<?= number_format(($data['priorites']['total'] - $data['priorites']['erreurs']) / $data['priorites']['total'] * 100, 2, '.', '') ?>%)</td>
            <td><?= $data['conducteur']['total'] ?>/<?= $data['conducteur']['erreurs'] ?> (<?= number_format(($data['conducteur']['total'] - $data['conducteur']['erreurs']) / $data['conducteur']['total'] * 100, 2, '.', '') ?>%)</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p>Il faut travailler les points suivants :</p>
<ul>

<?php

$errors = [
    'signalisation' => [
        'donnees' => 0,
        'titre' => 'Signalisation'
    ],
    'stationnement' => [
        'donnees' => 0,
        'titre' => 'Arrêt/Stationnement'
    ],
    'feux' => [
        'donnees' => 0,
        'titre' => 'Feux/Intempéries'
    ],
    'vehicule' => [
        'donnees' => 0,
        'titre' => 'Véhicule'
    ],
    'depassement' => [
        'donnees' => 0,
        'titre' => 'Dépassement'
    ],
    'orientation' => [
        'donnees' => 0,
        'titre' => 'Orientation'
    ],
    'priorites' => [
        'donnees' => 0,
        'titre' => 'Priorités'
    ],
    'conducteur' => [
        'donnees' => 0,
        'titre' => 'Conducteur'
    ]
];

foreach ($files as $file):
    $content = file_get_contents($file);
    $data = json_decode($content, true);

    $errors['signalisation']['donnees'] += $data['signalisation']['erreurs'] / $data['signalisation']['total'];
    $errors['stationnement']['donnees'] += $data['stationnement']['erreurs'] / $data['stationnement']['total'];
    $errors['feux']['donnees'] += $data['feux']['erreurs'] / $data['feux']['total'];
    $errors['vehicule']['donnees'] += $data['vehicule']['erreurs'] / $data['vehicule']['total'];
    $errors['depassement']['donnees'] += $data['depassement']['erreurs'] / $data['depassement']['total'];
    $errors['orientation']['donnees'] += $data['orientation']['erreurs'] / $data['orientation']['total'];
    $errors['priorites']['donnees'] += $data['priorites']['erreurs'] / $data['priorites']['total'];
    $errors['conducteur']['donnees'] += $data['conducteur']['erreurs'] / $data['conducteur']['total'];
endforeach;

arsort($errors);

for($i = 0; $i < 3; $i++):
    $key = key($errors);
    echo '<li>' . $errors[$key]['titre'] . ' (' . number_format($errors[$key]['donnees'] / count($files) * 100, 2, '.', '') . '% d\'erreurs)</li>';
    next($errors);
endfor;
?>
</ul>

<h2>Graphiques</h2>

<?php

function drawChart($id, $title, $files){
    echo 'var data = google.visualization.arrayToDataTable([';
    echo "['Date/Heure', '" . $title . "'],";
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $data = json_decode($content, true);

        echo "['" . $data['date'] . ' ' . $data['time'] . "', " . $data[$id]['erreurs'] . "],";
    }
    echo ']);';
    echo 'var options = {';
    echo "title: '" . $title . "',";
    echo "hAxis: {title: 'Date/Heure',  titleTextStyle: {color: '#333'}},";
    echo 'vAxis: {minValue: 0}';
    echo '};';
    echo 'var chart = new google.visualization.AreaChart(document.getElementById("' . $id . '"));';
    echo 'chart.draw(data, options);';
}

?>

<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        <?php
            $files = glob(__DIR__ . '/data/*.json');

            drawChart('signalisation', 'Signalisation', $files);
            drawChart('stationnement', 'Arrêt/Stationnement', $files);
            drawChart('feux', 'Feux/Intempéries', $files);
            drawChart('vehicule', 'Véhicule', $files);
            drawChart('depassement', 'Dépassement', $files);
            drawChart('orientation', 'Orientation', $files);
            drawChart('priorites', 'Priorités', $files);
            drawChart('conducteur', 'Conducteur', $files);
        ?>
    }
</script>

<div id="signalisation" style="height: 300px;"></div>
<div id="stationnement" style="height: 300px;"></div>
<div id="feux" style="height: 300px;"></div>
<div id="vehicule" style="height: 300px;"></div>
<div id="depassement" style="height: 300px;"></div>
<div id="orientation" style="height: 300px;"></div>
<div id="priorites" style="height: 300px;"></div>
<div id="conducteur" style="height: 300px;"></div>

<?php require __DIR__ . '/includes/footer.php'; ?>