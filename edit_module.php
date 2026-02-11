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

$mod = getModuleById($id);
if(!$mod){ 
  header('Location: modules.php'); 
  exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');
    updateModule($id, $_POST['code'], $_POST['name']);
    header('Location: modules.php'); 
    exit;
}
?>

<?php start_layout('Modifier module', 'modules'); ?>

<!-- Edit Form Card -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">
      <i class="bi bi-pencil-square"></i> Modification de 
      <strong><?= e($mod['name']) ?></strong>
    </h5>

    <form method="post" class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Code du module</label>
        <input type="text" name="code" class="form-control" value="<?= e($mod['code']) ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Nom du module</label>
        <input type="text" name="name" class="form-control" value="<?= e($mod['name']) ?>" required>
      </div>

      <div class="col-12">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Enregistrer les modifications
        </button>
        <a href="modules.php" class="btn btn-outline-secondary">
          <i class="bi bi-x-circle"></i> Annuler
        </a>
      </div>
    </form>
  </div>
</div>

<?php end_layout(); ?>
