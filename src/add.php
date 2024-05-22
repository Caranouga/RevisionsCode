<?php

require __DIR__ . '/includes/header.php';

if(!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

if(!empty($_POST)){
    $errors = [];

    if(!isset($_POST['date'])){
        $errors['date'] = 'Vous devez rentrer une date';
    }

    if(!isset($_POST['time'])){
        $errors['time'] = 'Vous devez rentrer une heure';
    }

    if(!isset($_POST['signalisation'])){
        $errors['signalisation'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['signalisation_e'])){
        $errors['signalisation_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['stationnement'])){
        $errors['stationnement'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['stationnement_e'])){
        $errors['stationnement_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['feux'])){
        $errors['feux'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['feux_e'])){
        $errors['feux_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['vehicule'])){
        $errors['vehicule'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['vehicule_e'])){
        $errors['vehicule_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['depassement'])){
        $errors['depassement'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['depassement_e'])){
        $errors['depassement_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['orientation'])){
        $errors['orientation'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['orientation_e'])){
        $errors['orientation_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['priorites'])){
        $errors['priorites'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['priorites_e'])){
        $errors['priorites_e'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['conducteur'])){
        $errors['conducteur'] = 'Vous devez rentrer un nombre';
    }

    if(!isset($_POST['conducteur_e'])){
        $errors['conducteur_e'] = 'Vous devez rentrer un nombre';
    }

    if(empty($errors)){
        if($_POST['signalisation'] < $_POST['signalisation_e']){
            $errors['signalisation'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['stationnement'] < $_POST['stationnement_e']){
            $errors['stationnement'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['feux'] < $_POST['feux_e']){
            $errors['feux'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['vehicule'] < $_POST['vehicule_e']){
            $errors['vehicule'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['depassement'] < $_POST['depassement_e']){
            $errors['depassement'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['orientation'] < $_POST['orientation_e']){
            $errors['orientation'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['priorites'] < $_POST['priorites_e']){
            $errors['priorites'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['conducteur'] < $_POST['conducteur_e']){
            $errors['conducteur'] = 'Le nombre total ne peut pas être inférieur au nombre d\'erreurs';
        }

        if($_POST['signalisation'] + $_POST['stationnement'] + $_POST['feux'] + $_POST['vehicule'] + $_POST['depassement'] + $_POST['orientation'] + $_POST['priorites'] + $_POST['conducteur'] > 40){
            $errors['total'] = 'Le total ne peut pas être supérieur à 40';
        }

        if($_POST['signalisation_e'] + $_POST['stationnement_e'] + $_POST['feux_e'] + $_POST['vehicule_e'] + $_POST['depassement_e'] + $_POST['orientation_e'] + $_POST['priorites_e'] + $_POST['conducteur_e'] > 40){
            $errors['total'] = 'Le total ne peut pas être supérieur à 40';
        }

        if(empty($errors)){
            $req = $pdo->prepare('SELECT data_ids FROM users WHERE id = ?');
            $req->execute([$_SESSION['auth']->id]);
            $data_id = $req->fetch()->data_ids;

            $req = $pdo->prepare('INSERT INTO datas SET datetime = ?, signalisation = ?, stationnement = ?, feux = ?, vehicule = ?, depassement = ?, orientation = ?, priorites = ?, conducteur = ?');
            // Create a datime based on date and time
            $datetime = $_POST['date'] . ' ' . $_POST['time'];
            $signalisation = [
                'total' => $_POST['signalisation'],
                'erreurs' => $_POST['signalisation_e']
            ];
            $stationnement = [
                'total' => $_POST['stationnement'],
                'erreurs' => $_POST['stationnement_e']
            ];
            $feux = [
                'total' => $_POST['feux'],
                'erreurs' => $_POST['feux_e']
            ];
            $vehicule = [
                'total' => $_POST['vehicule'],
                'erreurs' => $_POST['vehicule_e']
            ];
            $depassement = [
                'total' => $_POST['depassement'],
                'erreurs' => $_POST['depassement_e']
            ];
            $orientation = [
                'total' => $_POST['orientation'],
                'erreurs' => $_POST['orientation_e']
            ];
            $priorites = [
                'total' => $_POST['priorites'],
                'erreurs' => $_POST['priorites_e']
            ];
            $conducteur = [
                'total' => $_POST['conducteur'],
                'erreurs' => $_POST['conducteur_e']
            ];
            $req->execute([$datetime, json_encode($signalisation), json_encode($stationnement), json_encode($feux), json_encode($vehicule), json_encode($depassement), json_encode($orientation), json_encode($priorites), json_encode($conducteur)]);
            $insertedId = $pdo->lastInsertId();

            // Check if data_id is null
            if($data_id == null){
                $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
                $req->execute([$insertedId, $_SESSION['auth']->id]);
            } else {
                $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
                $req->execute([$data_id . ',' . $insertedId, $_SESSION['auth']->id]);
            }

            header('Location: list.php');
            exit();
        }
    }


    // $filename = __DIR__ . '/data/' . time() . '.json';

    // $data = [
    //     'date' => $_POST['date'],
    //     'time' => $_POST['time'],
    //     'signalisation' => [
    //         'total' => $_POST['signalisation'],
    //         'erreurs' => $_POST['signalisation_e']
    //     ],
    //     'stationnement' => [
    //         'total' => $_POST['stationnement'],
    //         'erreurs' => $_POST['stationnement_e']
    //     ],
    //     'feux' => [
    //         'total' => $_POST['feux'],
    //         'erreurs' => $_POST['feux_e']
    //     ],
    //     'vehicule' => [
    //         'total' => $_POST['vehicule'],
    //         'erreurs' => $_POST['vehicule_e']
    //     ],
    //     'depassement' => [
    //         'total' => $_POST['depassement'],
    //         'erreurs' => $_POST['depassement_e']
    //     ],
    //     'orientation' => [
    //         'total' => $_POST['orientation'],
    //         'erreurs' => $_POST['orientation_e']
    //     ],
    //     'priorites' => [
    //         'total' => $_POST['priorites'],
    //         'erreurs' => $_POST['priorites_e']
    //     ],
    //     'conducteur' => [
    //         'total' => $_POST['conducteur'],
    //         'erreurs' => $_POST['conducteur_e']
    //     ]
    // ];

    // file_put_contents($filename, json_encode($data));
}

?>

<h1>Entrer mes données</h1>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

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