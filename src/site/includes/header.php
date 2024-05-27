<?php

require_once __DIR__ . '/bdd.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

function pre($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Caranouga">
        <title>Starter Template · Bootstrap v4.6</title>

        <link href="../assets/css/main.css" rel="stylesheet">
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="../" class="navbar-brand">Révisions Code</a>
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav">
                        <?php if(isset($_SESSION['auth'])) : ?>
                            <li><a href="add.php">Entrer mes données</a></li>
                            <li><a href="list.php">Voir mes données</a></li>
                            <li><a href="logout.php">Se déconnecter</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Se connecter</a></li>
                            <li><a href="register.php">S'inscrire</a></li>
                        <?php endif; ?>
                        <li><a href="https://passetoncode.fr" target="_blank">M'entrainer</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="page-header">
                <div class="row">