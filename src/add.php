<?php

require __DIR__ . '/includes/header.php';

if(!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

// TODO: Ajouter les vérifs
if(!empty($_POST)){
    $filename = __DIR__ . '/data/' . time() . '.json';

    $data = [
        'date' => $_POST['date'],
        'time' => $_POST['time'],
        'signalisation' => [
            'total' => $_POST['signalisation'],
            'erreurs' => $_POST['signalisation_e']
        ],
        'stationnement' => [
            'total' => $_POST['stationnement'],
            'erreurs' => $_POST['stationnement_e']
        ],
        'feux' => [
            'total' => $_POST['feux'],
            'erreurs' => $_POST['feux_e']
        ],
        'vehicule' => [
            'total' => $_POST['vehicule'],
            'erreurs' => $_POST['vehicule_e']
        ],
        'depassement' => [
            'total' => $_POST['depassement'],
            'erreurs' => $_POST['depassement_e']
        ],
        'orientation' => [
            'total' => $_POST['orientation'],
            'erreurs' => $_POST['orientation_e']
        ],
        'priorites' => [
            'total' => $_POST['priorites'],
            'erreurs' => $_POST['priorites_e']
        ],
        'conducteur' => [
            'total' => $_POST['conducteur'],
            'erreurs' => $_POST['conducteur_e']
        ]
    ];

    file_put_contents($filename, json_encode($data));
}

?>

<h1>Entrer mes données</h1>

<form action="" method="post">
    <div class="form-group">
        <label for="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="form-group">
        <label for="time">Heure</label>
        <input type="time" class="form-control" id="time" name="time" value="<?= date('H:i') ?>" required>
    </div>
    <div class="form-group">
        <label for="signalisation">Signalisation (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="signalisation" name="signalisation" required>
    </div>
    <div class="form-group">
        <label for="signalisation_e">Signalisation (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="signalisation_e" name="signalisation_e" required>
    </div>
    <div class="form-group">
        <label for="stationnement">Arrêt/Stationnement (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="stationnement" name="stationnement" required>
    </div>
    <div class="form-group">
        <label for="stationnement_e">Arrêt/Stationnement (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="stationnement_e" name="stationnement_e" required>
    </div>
    <div class="form-group">
        <label for="feux">Feux/Intempéries (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="feux" name="feux" required>
    </div>
    <div class="form-group">
        <label for="feux_e">Feux/Intempéries (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="feux_e" name="feux_e" required>
    </div>
    <div class="form-group">
        <label for="vehicule">Véhicule (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="vehicule" name="vehicule" required>
    </div>
    <div class="form-group">
        <label for="vehicule_e">Véhicule (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="vehicule_e" name="vehicule_e" required>
    </div>
    <div class="form-group">
        <label for="depassement">Dépassement (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="depassement" name="depassement" required>
    </div>
    <div class="form-group">
        <label for="depassement_e">Dépassement (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="depassement_e" name="depassement_e" required>
    </div>
    <div class="form-group">
        <label for="orientation">Orientation (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="orientation" name="orientation" required>
    </div>
    <div class="form-group">
        <label for="orientation_e">Orientation (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="orientation_e" name="orientation_e" required>
    </div>
    <div class="form-group">
        <label for="priorites">Priorités (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="priorites" name="priorites" required>
    </div>
    <div class="form-group">
        <label for="priorites_e">Priorités (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="priorites_e" name="priorites_e" required>
    </div>
    <div class="form-group">
        <label for="conducteur">Conducteur (total)</label>
        <input type="number" min="0" max="40" class="form-control" id="conducteur" name="conducteur" required>
    </div>
    <div class="form-group">
        <label for="conducteur_e">Conducteur (erreurs)</label>
        <input type="number" min="0" max="40" class="form-control" id="conducteur_e" name="conducteur_e" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>