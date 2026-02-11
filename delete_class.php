<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

$id = $_GET['id'] ?? null; 
if(!$id) { 
  header('Location: classes.php'); 
  exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');
    deleteClass($id);
    header('Location: classes.php'); 
    exit;
}

$cls = getClassById($id);
if(!$cls){ 
  header('Location: classes.php'); 
  exit; 
}
?>

<?php start_layout('Supprimer classe', 'classes'); ?>

<!-- Delete Confirmation Card -->
<div class="card border-danger shadow-sm">
  <div class="card-body">
    <div class="d-flex align-items-center mb-4">
      <div class="alert alert-danger mb-0 flex-grow-1" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        <strong> Attention!</strong> Cette action est irréversible.
      </div>
    </div>

    <h5 class="card-title mb-3">
      <i class="bi bi-trash"></i> Supprimer la classe
    </h5>

    <div class="mb-4 p-3 bg-light rounded">
      <p class="mb-2">
        <strong>Niveau:</strong> <?= e($cls['level_name']) ?>
      </p>
      <p class="mb-0">
        <strong>Classe:</strong> <?= e($cls['name']) ?> <span class="badge bg-secondary"><?= e($cls['code']) ?></span>
      </p>
    </div>

    <p class="text-muted mb-4">
      Êtes-vous certain de vouloir supprimer cette classe? Toutes les données associées seront perdues.
    </p>

    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Confirmer la suppression
      </button>
      <a href="classes.php" class="btn btn-outline-secondary">
        <i class="bi bi-x-circle"></i> Annuler
      </a>
    </form>
  </div>
</div>

<?php end_layout(); ?>
