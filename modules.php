<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['code']) && !empty($_POST['name'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  addModule($_POST['code'], $_POST['name']); 
  header('Location: modules.php'); 
  exit;
}

$class=null; 
if(isset($_GET['class_id'])) $class=getClassById((int)$_GET['class_id']);

if(isset($_POST['assign_module']) && !empty($_POST['module_id']) && !empty($_POST['class_id'])){ 
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ 
    die('CSRF token invalide'); 
  } 
  assignModuleToClass($_POST['class_id'], $_POST['module_id']); 
  header('Location: modules.php?class_id='.$_POST['class_id']); 
  exit; 
}

$modules = getModulesByClass($class['id'] ?? null);
$all_modules = $pdo->query('SELECT * FROM modules ORDER BY code')->fetchAll(PDO::FETCH_ASSOC);
?>

<?php start_layout('Modules', 'modules'); ?>

<!-- Add Module Card (Global) -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-book-half"></i> Créer un nouveau module</h5>
    <form method="post" class="row g-2">
      <div class="col-md-2">
        <input type="text" name="code" class="form-control form-control-sm" placeholder="Code" required>
      </div>
      <div class="col-md-6">
        <input type="text" name="name" class="form-control form-control-sm" placeholder="Nom du module" required>
      </div>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm w-100">
          <i class="bi bi-plus-circle"></i> Créer
        </button>
      </div>
    </form>
  </div>
</div>

<?php if($class): ?>
  <!-- Modules for Current Class -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">
        <i class="bi bi-table"></i> Modules de <strong><?= e($class['name']) ?></strong>
      </h5>
      
      <?php if(empty($modules)): ?>
        <div class="empty-state text-center py-5">
          <i class="bi bi-inbox"></i>
          <p class="mt-2 text-muted">Aucun module assigné à cette classe</p>
        </div>
      <?php else: ?>
        <div class="table-wrapper">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th><i class="bi bi-book"></i> Code</th>
                <th>Nom</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($modules as $m): ?>
                <tr>
                  <td>
                    <span class="badge bg-secondary"><?= e($m['code']) ?></span>
                  </td>
                  <td>
                    <strong><?= e($m['name']) ?></strong>
                  </td>
                  <td class="text-end">
                    <a href="edit_module.php?id=<?= $m['id'] ?>" class="btn table-action-btn btn-sm btn-outline-warning">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="delete_module.php?id=<?= $m['id'] ?>" class="btn table-action-btn btn-sm btn-outline-danger">
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
      <small class="text-muted">Total: <?= count($modules) ?> module(s)</small>
    </div>
  </div>

<?php else: ?>
  <!-- Assign Module to Class -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3"><i class="bi bi-link"></i> Assigner un module à une classe</h5>
      <form method="post" class="row g-2">
        <div class="col-md-4">
          <select name="class_id" class="form-select form-select-sm" required>
            <option value="">-- Sélectionnez une classe --</option>
            <?php foreach(getClassesByLevel() as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['level_name'] . ' - ' . $c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <select name="module_id" class="form-select form-select-sm" required>
            <option value="">-- Sélectionnez un module --</option>
            <?php foreach($all_modules as $m): ?>
              <option value="<?= $m['id'] ?>"><?= e($m['code'] . ' - ' . $m['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <div class="col-md-2">
          <button type="submit" name="assign_module" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-plus-circle"></i> Assigner
          </button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<?php end_layout(); ?>
