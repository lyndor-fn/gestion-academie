<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'edit';
if (!$id || !in_array($action, ['edit', 'delete'], true)) {
    header('Location: classes.php');
    exit;
}

$cls = getClassById($id);
if (!$cls) {
    header('Location: classes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');

    if ($action === 'delete') {
        deleteClass($id);
        header('Location: classes.php');
        exit;
    }

    updateClass($id, $_POST['level_id'], $_POST['name'], $_POST['code']);
    header('Location: classes.php');
    exit;
}

if ($action === 'edit') {
    $levels = getLevels();
}
?>

<?php if ($action === 'delete'): ?>
<?php start_layout('Supprimer classe', 'classes'); ?>
<div class="card border-danger shadow-sm">
  <div class="card-body">
    <div class="alert alert-danger mb-3" role="alert">
      <strong>Attention:</strong> Cette action est irréversible.
    </div>
    <h5 class="card-title mb-3">Supprimer la classe</h5>
    <p class="mb-2"><strong>Classe:</strong> <?= e($cls['name']) ?></p>
    <p class="mb-3"><strong>Code:</strong> <?= e($cls['code']) ?></p>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <button type="submit" class="btn btn-danger">Confirmer</button>
      <a href="classes.php" class="btn btn-outline-secondary">Annuler</a>
    </form>
  </div>
</div>
<?php else: ?>
<?php start_layout('Modifier classe', 'classes'); ?>
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">Modifier la classe</h5>
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
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="<?= e($cls['name']) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="<?= e($cls['code']) ?>" required>
      </div>
      <div class="col-12">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="classes.php" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php end_layout(); ?>
