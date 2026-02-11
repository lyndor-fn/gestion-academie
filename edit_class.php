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

$cls = getClassById($id);
if(!$cls){ 
  header('Location: classes.php'); 
  exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');
    updateClass($id, $_POST['level_id'], $_POST['name'], $_POST['code']);
    header('Location: classes.php'); 
    exit;
}

$levels = getLevels();
?>

<?php start_layout('Modifier classe', 'classes'); ?>

<!-- Edit Form Card -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">
      <i class="bi bi-pencil-square"></i> Modification de 
      <strong><?= e($cls['name']) ?></strong>
    </h5>

    <form method="post" class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Niveau</label>
        <select name="level_id" class="form-select" required>
          <option value="">-- Sélectionnez un niveau --</option>
          <?php foreach($levels as $l): ?>
            <option value="<?= $l['id'] ?>" <?= ($l['id']==$cls['level_id'] ? 'selected' : '') ?>>
              <?= e($l['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Nom de la classe</label>
        <input type="text" name="name" class="form-control" value="<?= e($cls['name']) ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="<?= e($cls['code']) ?>" required>
      </div>

      <div class="col-12">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Enregistrer les modifications
        </button>
        <a href="classes.php" class="btn btn-outline-secondary">
          <i class="bi bi-x-circle"></i> Annuler
        </a>
      </div>
    </form>
  </div>
</div>

<?php end_layout(); ?>
