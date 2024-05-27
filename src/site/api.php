<?php
require __DIR__ . '/includes/bdd.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    
    $data = json_decode($input, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        // echo json_encode(array(
        //     'success' => true,
        //     'message' => $data
        // ));

        $req = $pdo->prepare('SELECT id FROM users WHERE api_key = ?');
        $req->execute([$data['api_key']]);
        $user = $req->fetch();

        if($user){
            $req = $pdo->prepare('SELECT data_ids FROM users WHERE id = ?');
            $req->execute([$user->id]);
            $data_id = $req->fetch()->data_ids;

            $req = $pdo->prepare('INSERT INTO datas SET datetime = ?, signalisation = ?, stationnement = ?, feux = ?, vehicule = ?, depassement = ?, orientation = ?, priorites = ?, conducteur = ?');
            // Create a datime based on date and time
            $datetime = $data['date'] . ' ' . $data['time'];
            $signalisation = [
                'total' => $data['datas']['signalisation']['total'],
                'erreurs' => $data['datas']['signalisation']['erreurs']
            ];
            $stationnement = [
                'total' => $data['datas']['stationnement']['total'],
                'erreurs' => $data['datas']['stationnement']['erreurs']
            ];
            $feux = [
                'total' => $data['datas']['feux']['total'],
                'erreurs' => $data['datas']['feux']['erreurs']
            ];
            $vehicule = [
                'total' => $data['datas']['vehicule']['total'],
                'erreurs' => $data['datas']['vehicule']['erreurs']
            ];
            $depassement = [
                'total' => $data['datas']['depassement']['total'],
                'erreurs' => $data['datas']['depassement']['erreurs']
            ];
            $orientation = [
                'total' => $data['datas']['orientation']['total'],
                'erreurs' => $data['datas']['orientation']['erreurs']
            ];
            $priorites = [
                'total' => $data['datas']['priorites']['total'],
                'erreurs' => $data['datas']['priorites']['erreurs']
            ];
            $conducteur = [
                'total' => $data['datas']['conducteur']['total'],
                'erreurs' => $data['datas']['conducteur']['erreurs']
            ];
            $req->execute([$datetime, json_encode($signalisation), json_encode($stationnement), json_encode($feux), json_encode($vehicule), json_encode($depassement), json_encode($orientation), json_encode($priorites), json_encode($conducteur)]);
            $insertedId = $pdo->lastInsertId();

            // Check if data_id is null
            if($data_id == null){
                $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
                $req->execute([$insertedId, $user->id]);
            } else {
                $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
                $req->execute([$data_id . ',' . $insertedId, $user->id]);
            }

            echo json_encode(array(
                'success' => true,
                'message' => 'Data inserted'
            ));

            exit();
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Invalid API key'
            ));
        }
    } else {
        // Respond with an error if the JSON is invalid
        echo json_encode(array(
            'success' => false,
            'message' => 'Invalid JSON\n'. $data
        ));
    }
} else {
    // Respond with an error if the request method is not POST
    echo json_encode(array(
        'success' => false,
        'message' => 'Invalid request method'
    ));
}
