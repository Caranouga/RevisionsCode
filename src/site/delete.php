<?php

require __DIR__ . '/includes/header.php';

if(!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $req = $pdo->prepare('SELECT id, data_ids FROM users');
    $req->execute();
    $data = $req->fetchAll();

    foreach($data as $value) {
        $ids = explode(',', $value->data_ids);

        if(in_array($id, $ids)) {
            $key = array_search($id, $ids);
            unset($ids[$key]);
            $ids = implode(',', $ids);
            $req = $pdo->prepare('UPDATE users SET data_ids = ? WHERE id = ?');
            $req->execute([$ids, $value->id]);
        }
    }
    
    $req = $pdo->prepare('DELETE FROM datas WHERE id = ?');
    $req->execute([$id]);

    header('Location: list.php');
}