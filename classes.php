<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/auth.php';
require_login();

$levels = getLevels();
$filter_level_id = $_GET['level_id'] ?? null;

if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['name']) && !empty($_POST['level_id']) && !empty($_POST['code'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  addClass($_POST['level_id'], $_POST['name'], $_POST['code']);
  header('Location: classes.php'); 
  exit;
}

$classes = getClassesByLevel();
?>

<?php start_layout('Classes', 'classes'); ?>

<!-- Filter & Add Card -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-building"></i> Ajouter une classe</h5>
    <form method="post" class="row g-2">
      <div class="col-md-3">
        <select name="level_id" class="form-select form-select-sm" required>
          <option value="">-- Sélectionnez un niveau --</option>
          <?php foreach($levels as $l): ?>
            <option value="<?= $l['id'] ?>"><?= e($l['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nom de la classe" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="code" class="form-control form-control-sm" placeholder="Code" required>
      </div>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm w-100">
          <i class="bi bi-plus-circle"></i> Ajouter
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Classes by Level -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-table"></i> Classes par niveau</h5>
    
    <?php if(empty($classes)): ?>
      <div class="empty-state text-center py-5">
        <i class="bi bi-inbox"></i>
        <p class="mt-2 text-muted">Aucune classe enregistrée</p>
      </div>
    <?php else: ?>
      <div class="table-wrapper">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th><i class="bi bi-layers"></i> Niveau</th>
              <th><i class="bi bi-building"></i> Classe</th>
              <th>Code</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($classes as $c): ?>
              <tr>
                <td>
                  <span class="badge bg-info text-dark"><?= e($c['level_name']) ?></span>
                </td>
                <td>
                  <strong><?= e($c['name']) ?></strong>
                </td>
                <td>
                  <small class="text-muted"><?= e($c['code']) ?></small>
                </td>
                <td class="text-end">
                  <a href="students.php?class_id=<?= $c['id'] ?>" class="btn table-action-btn btn-sm btn-outline-primary" title="Voir les étudiants">
                    <i class="bi bi-people"></i>
                  </a>
                  <a href="modules.php?class_id=<?= $c['id'] ?>" class="btn table-action-btn btn-sm btn-outline-success" title="Voir les modules">
                    <i class="bi bi-book"></i>
                  </a>
                  <a href="class_action.php?id=<?= $c['id'] ?>" class="btn table-action-btn btn-sm btn-outline-warning" title="Modifier">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="class_action.php?id=<?= $c['id'] ?>&action=delete" class="btn table-action-btn btn-sm btn-outline-danger" title="Supprimer">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
  <div class="card-footer bg-light">
    <small class="text-muted">Total: <?= count($classes) ?> classe(s)</small>
  </div>
</div>

<?php end_layout(); ?>

