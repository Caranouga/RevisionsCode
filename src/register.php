<?php

require __DIR__ . '/includes/header.php';

if(isset($_SESSION['auth'])) {
    header('Location: list.php');
    exit();
}

if(!empty($_POST)){
    $errors = [];

    if(empty($_POST['pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['pseudo'])){
        $errors['pseudo'] = "Votre pseudo n'est pas valide (alphanumérique)";
    } else {
        $req = $pdo->prepare('SELECT id FROM users WHERE pseudo = ?');
        $req->execute([$_POST['pseudo']]);
        $user = $req->fetch();

        if($user){
            $errors['pseudo'] = 'Ce pseudo est déjà pris';
        }
    }

    if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
        $errors['password'] = "Vous devez rentrer un mot de passe valide";
    }

    if(empty($errors)){
        $req = $pdo->prepare('INSERT INTO users SET pseudo = ?, password = ?, salt = ?');
        $salt = sha1(uniqid());
        $password = password_hash($_POST['password'] . $salt, PASSWORD_BCRYPT); // On crypte le mot de passe (avec un salt différent pour chaque utilisateur
        $req->execute([$_POST['pseudo'], $password, $salt]);
        $lastId = $pdo->lastInsertId();

        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$lastId]);
        $user = $req->fetch();

        $_SESSION['auth'] = $user;

        header('Location: list.php');
        exit();
    }
}

?>

<h1>Créer un compte</h1>

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
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password_confirm">Confirmer le mot de passe</label>
        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
</form>