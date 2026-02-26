<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/auth.php';
require_login();

if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['name'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  addLevel($_POST['name']); 
  header('Location: levels.php'); 
  exit;
}

$levels = getLevels();
?>

<?php start_layout('Niveaux', 'levels'); ?>

<!-- Filter & Add Card -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-layers"></i> Ajouter un niveau</h5>
    <form method="post" class="row g-2">
      <div class="col-md-6">
        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nom du niveau" required>
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

<!-- Levels Table -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-table"></i> Liste des niveaux</h5>
    
    <?php if(empty($levels)): ?>
      <div class="empty-state text-center py-5">
        <i class="bi bi-inbox"></i>
        <p class="mt-2 text-muted">Aucun niveau enregistré</p>
      </div>
    <?php else: ?>
      <div class="table-wrapper">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th><i class="bi bi-layers"></i> Nom</th>
              <th>Classe</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($levels as $lvl): ?>
              <tr>
                <td>
                  <strong><?= e($lvl['name']) ?></strong>
                </td>
                <td>
                  <?php if(levelHasClasses($lvl['id'])): ?>
                    <span class="badge bg-success">Assignée</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">Aucune classe</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a href="classes.php?level_id=<?= $lvl['id'] ?>" class="btn table-action-btn btn-sm btn-outline-primary">
                    <i class="bi bi-building"></i> Voir classes
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
    <small class="text-muted">Total: <?= count($levels) ?> niveau(x)</small>
  </div>
</div>

<?php end_layout(); ?>

