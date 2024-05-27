<?php

require __DIR__ . '/includes/header.php';

if(isset($_SESSION['auth'])) {
    header('Location: list.php');
    exit();
}

if(!empty($_POST)){
    $errors = [];

    if(!isset($_POST['pseudo']) || !isset($_POST['password'])){
        $errors['pseudo'] = "Vous devez rentrer un pseudo et un mot de passe";
    } else {
        $req = $pdo->prepare('SELECT * FROM users WHERE pseudo = ?');
        $req->execute([$_POST['pseudo']]);
        $user = $req->fetch();

        if($user && password_verify($_POST['password'] . $user->salt, $user->password)){
            $_SESSION['auth'] = $user;

            header('Location: list.php');
            exit();
        } else {
            $errors['pseudo'] = 'Mauvais pseudo ou mot de passe';
        }
    }
}

?>

<h1>Connexion</h1>

<form action="" method="post">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>