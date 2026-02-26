<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')){
        header('Location: index.php'); exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-header">
            <div class="login-title">Connexion</div>
            <div class="login-subtitle">Accédez à votre espace de gestion académique</div>
        </div>
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username">Utilisateur</label>
                <input id="username" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <button class="btn btn-primary w-100">Se connecter</button>
        </form>
        <div class="login-footer text-muted">Compte par défaut: admin / admin123</div>
    </div>
</body>
</html>
