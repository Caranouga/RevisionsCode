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
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php

            $req = $pdo->prepare('SELECT data_ids FROM users WHERE id = ?');
            $req->execute([$_SESSION['auth']->id]);
            $data_ids = $req->fetch()->data_ids;
            $data_ids = explode(',', $data_ids);

            $datas = [];

            foreach ($data_ids as $data_id){
                $req = $pdo->prepare('SELECT * FROM datas WHERE id = ?');
                $req->execute([$data_id]);
                $data = $req->fetch();
                $datas_ = [];
                foreach ($data as $key => $value) {
                    if($key == "id" || $key == "datetime"){
                        $datas_[$key] = $value;
                    } else {
                        $datas_[$key] = json_decode($value, true);
                    }
                }

                $date = $datas_['datetime'];
                $date = explode(' ', $date);
                $datas_['date'] = $date[0];
                $datas_['time'] = $date[1];
                unset($datas_['datatime']);

                $datas[] = $datas_;
            }

            foreach ($datas as $data):
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
            <td><a href="delete.php?id=<?= $data['id'] ?>" class="btn btn-danger">Supprimer</a></td>
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

foreach ($datas as $data){
    $date = new DateTime($data['date'] . ' ' . $data['time']);
    $now = new DateTime();
    $interval = $now->diff($date);
    $env = parse_ini_file(__DIR__ . '/.env');
    if($interval->d > $env['MAX_DAYS']){
        continue;
    }

    $errors['signalisation']['donnees'] += $data['signalisation']['erreurs'] / $data['signalisation']['total'];
    $errors['stationnement']['donnees'] += $data['stationnement']['erreurs'] / $data['stationnement']['total'];
    $errors['feux']['donnees'] += $data['feux']['erreurs'] / $data['feux']['total'];
    $errors['vehicule']['donnees'] += $data['vehicule']['erreurs'] / $data['vehicule']['total'];
    $errors['depassement']['donnees'] += $data['depassement']['erreurs'] / $data['depassement']['total'];
    $errors['orientation']['donnees'] += $data['orientation']['erreurs'] / $data['orientation']['total'];
    $errors['priorites']['donnees'] += $data['priorites']['erreurs'] / $data['priorites']['total'];
    $errors['conducteur']['donnees'] += $data['conducteur']['erreurs'] / $data['conducteur']['total'];
}

arsort($errors);

for($i = 0; $i < 3; $i++){
    $key = key($errors);
    echo '<li>' . $errors[$key]['titre'] . ' (' . number_format($errors[$key]['donnees'] / count($datas) * 100, 2, '.', '') . '% d\'erreurs)</li>';
    next($errors);
}
?>
</ul>

<h2>Graphiques</h2>
<h3>Evolutions des erreurs</h3>

<?php

function drawChartTotal($id, $title, $datas){
    echo 'var data = google.visualization.arrayToDataTable([';
    echo "['Date/Heure', '" . $title . " (erreurs)', '" . $title . " (total)'],";
    foreach ($datas as $data) {
        echo "['" . $data['date'] . ' ' . $data['time'] . "', " . $data[$id]['erreurs'] . ", " . $data[$id]['total'] . "],";
    }

    echo ']);';
    echo 'var options = {';
    echo "title: '" . $title . "',";
    echo "hAxis: {title: 'Date/Heure',  titleTextStyle: {color: '#333'}},";
    echo 'vAxis: {minValue: 0},';
    echo "legend: { position: 'bottom' },";
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
            drawChartTotal('signalisation', 'Signalisation', $datas);
            drawChartTotal('stationnement', 'Arrêt/Stationnement', $datas);
            drawChartTotal('feux', 'Feux/Intempéries', $datas);
            drawChartTotal('vehicule', 'Véhicule', $datas);
            drawChartTotal('depassement', 'Dépassement', $datas);
            drawChartTotal('orientation', 'Orientation', $datas);
            drawChartTotal('priorites', 'Priorités', $datas);
            drawChartTotal('conducteur', 'Conducteur', $datas);
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

<h3>Taux d'erreurs</h3>

<?php

function drawChartPercentage($id, $title, $datas){
    echo 'var data = google.visualization.arrayToDataTable([';
    echo "['Date/Heure', 'Taux d\'erreurs'],";
    foreach ($datas as $data) {
        echo "['" . $data['date'] . ' ' . $data['time'] . "', " . number_format($data[$id]['erreurs'] / $data[$id]['total'] * 100, 2, '.', '') . "],";
    }
    echo ']);';
    echo 'var options = {';
    echo "title: '" . $title . "',";
    echo "curveType: 'function',";
    echo "legend: { position: 'bottom' },";
    echo "vAxis: {minValue: 0, maxValue: 100, format: '#\'%\''},";
    echo '};';
    echo 'var chart = new google.visualization.AreaChart(document.getElementById("' . $id . '_taux"));';
    echo 'chart.draw(data, options);';
}

?>

<script>
    google.charts.setOnLoadCallback(drawChartsPercentage);

    function drawChartsPercentage() {
        <?php
            drawChartPercentage('signalisation', 'Signalisation', $datas);
            drawChartPercentage('stationnement', 'Arrêt/Stationnement', $datas);
            drawChartPercentage('feux', 'Feux/Intempéries', $datas);
            drawChartPercentage('vehicule', 'Véhicule', $datas);
            drawChartPercentage('depassement', 'Dépassement', $datas);
            drawChartPercentage('orientation', 'Orientation', $datas);
            drawChartPercentage('priorites', 'Priorités', $datas);
            drawChartPercentage('conducteur', 'Conducteur', $datas);
        ?>
    }
</script>

<div id="signalisation_taux" style="height: 300px;"></div>
<div id="stationnement_taux" style="height: 300px;"></div>
<div id="feux_taux" style="height: 300px;"></div>
<div id="vehicule_taux" style="height: 300px;"></div>
<div id="depassement_taux" style="height: 300px;"></div>
<div id="orientation_taux" style="height: 300px;"></div>
<div id="priorites_taux" style="height: 300px;"></div>
<div id="conducteur_taux" style="height: 300px;"></div>



<?php require __DIR__ . '/includes/footer.php'; ?>