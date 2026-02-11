<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/auth.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
    if(attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')){
        header('Location: index.php'); exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}
?><!doctype html>
<html><head><meta charset="utf-8"><title>Login</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4"><div class="container col-md-4">
  <h3>Connexion</h3>
  <?php if(!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-2"><input name="username" class="form-control" placeholder="Utilisateur"></div>
    <div class="mb-2"><input name="password" type="password" class="form-control" placeholder="Mot de passe"></div>
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <button class="btn btn-primary">Se connecter</button>
  </form>
</div></body></html>