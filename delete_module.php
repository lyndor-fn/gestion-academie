<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

$id = $_GET['id'] ?? null; 
if(!$id) { 
  header('Location: modules.php'); 
  exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');
    deleteModule($id);
    header('Location: modules.php'); 
    exit;
}

$mod = getModuleById($id);
if(!$mod){ 
  header('Location: modules.php'); 
  exit; 
}
?>

<?php start_layout('Supprimer module', 'modules'); ?>

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
      <i class="bi bi-trash"></i> Supprimer le module
    </h5>

    <div class="mb-4 p-3 bg-light rounded">
      <p class="mb-2">
        <strong>Code:</strong> <span class="badge bg-secondary"><?= e($mod['code']) ?></span>
      </p>
      <p class="mb-0">
        <strong>Module:</strong> <?= e($mod['name']) ?>
      </p>
    </div>

    <p class="text-muted mb-4">
      Êtes-vous certain de vouloir supprimer ce module? Toutes les données associées seront perdues.
    </p>

    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Confirmer la suppression
      </button>
      <a href="modules.php" class="btn btn-outline-secondary">
        <i class="bi bi-x-circle"></i> Annuler
      </a>
    </form>
  </div>
</div>

<?php end_layout(); ?>
