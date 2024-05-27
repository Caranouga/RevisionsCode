<?php

require __DIR__ . '/includes/header.php';

$env = parse_ini_file(__DIR__ . '/.env');

print("<br/>");
print("<br/>");


if($env['ENABLE_CONVERTER'] != 1) {
    echo 'Conversion désactivée';
    exit();
}

if(!empty($_POST)){
    // Check if the user inputted a file name
    if(!empty($_POST['file'])) {
        $files = glob('data/' . $_POST['file']);
    }else{
        $files = glob('data/*.json');
    }

    if(empty($_POST['user_id'])) {
        echo 'Aucun utilisateur sélectionné';
        exit();
    }

    if(empty($files)) {
        echo 'Aucun fichier à convertir';
        exit();
    }

    foreach($files as $file) {
        $data = json_decode(file_get_contents($file), true);

        $req = $pdo->prepare('SELECT data_ids FROM users WHERE id = ?');
        $req->execute([$_POST['user_id']]);
        $data_id = $req->fetch()->data_ids;

        $req = $pdo->prepare('INSERT INTO datas SET datetime = ?, signalisation = ?, stationnement = ?, feux = ?, vehicule = ?, depassement = ?, orientation = ?, priorites = ?, conducteur = ?');
        $datetime = $data['date'] . ' ' . $data['time'];
        $req->execute([$datetime, json_encode($data['signalisation']), json_encode($data['stationnement']), json_encode($data['feux']), json_encode($data['vehicule']), json_encode($data['depassement']), json_encode($data['orientation']), json_encode($data['priorites']), json_encode($data['conducteur'])]);
        $insertedId = $pdo->lastInsertId();

        if($data_id == null){
            $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
            $req->execute([$insertedId, $_SESSION['auth']->id]);
        } else {
            $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
            $req->execute([$data_id . ',' . $insertedId, $_SESSION['auth']->id]);
        }
    }

    echo 'Conversion terminée';
    exit();
}

?>

<input type="text" id="file" placeholder="datas/**********************.json"/>
<input type="int" id="user_id" placeholder="user id" required/>
<input type="button" id="convert" value="Convertir" />
<p>Ne mettez pas de fichier si vous voulez convertir tous les fichiers</p>
<p>Cet outil n'est pas garantis sans bug, veuillez vérifier les données après conversion</p>

<?php require __DIR__ . '/includes/footer.php'; ?>