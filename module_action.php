<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'edit';
if (!$id || !in_array($action, ['edit', 'delete'], true)) {
    header('Location: modules.php');
    exit;
}

$mod = getModuleById($id);
if (!$mod) {
    header('Location: modules.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');

    if ($action === 'delete') {
        deleteModule($id);
        header('Location: modules.php');
        exit;
    }

    updateModule($id, $_POST['code'], $_POST['name']);
    header('Location: modules.php');
    exit;
}
?>

<?php if ($action === 'delete'): ?>
<?php start_layout('Supprimer module', 'modules'); ?>
<div class="card border-danger shadow-sm">
  <div class="card-body">
    <div class="alert alert-danger mb-3" role="alert">
      <strong>Attention:</strong> Cette action est irréversible.
    </div>
    <h5 class="card-title mb-3">Supprimer le module</h5>
    <p class="mb-2"><strong>Code:</strong> <?= e($mod['code']) ?></p>
    <p class="mb-3"><strong>Nom:</strong> <?= e($mod['name']) ?></p>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <button type="submit" class="btn btn-danger">Confirmer</button>
      <a href="modules.php" class="btn btn-outline-secondary">Annuler</a>
    </form>
  </div>
</div>
<?php else: ?>
<?php start_layout('Modifier module', 'modules'); ?>
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">Modifier le module</h5>
    <form method="post" class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="<?= e($mod['code']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="name" class="form-control" value="<?= e($mod['name']) ?>" required>
      </div>
      <div class="col-12">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="modules.php" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php end_layout(); ?>
