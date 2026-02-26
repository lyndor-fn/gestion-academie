<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/layout.php';
require_once __DIR__.'/includes/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'edit';
if (!$id || !in_array($action, ['edit', 'delete'], true)) {
    header('Location: students.php');
    exit;
}

$st = getStudentById($id);
if (!$st) {
    header('Location: students.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');

    if ($action === 'delete') {
        deleteStudent($id);
        header('Location: students.php?class_id='.$st['class_id']);
        exit;
    }

    updateStudent($id, $_POST['matricule'], $_POST['firstname'], $_POST['lastname'], $_POST['class_id']);
    header('Location: students.php?class_id='.$_POST['class_id']);
    exit;
}

$classes = getClassesByLevel();
?>

<?php if ($action === 'delete'): ?>
<?php start_layout('Supprimer étudiant', 'students'); ?>
<div class="card border-danger shadow-sm">
  <div class="card-body">
    <div class="alert alert-danger mb-3" role="alert">
      <strong>Attention:</strong> Cette action est irréversible.
    </div>
    <h5 class="card-title mb-3">Supprimer l'étudiant</h5>
    <p class="mb-2"><strong>Matricule:</strong> <?= e($st['matricule']) ?></p>
    <p class="mb-3"><strong>Nom:</strong> <?= e($st['firstname'].' '.$st['lastname']) ?></p>
    <form method="post" class="d-flex gap-2">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <button type="submit" class="btn btn-danger">Confirmer</button>
      <a href="students.php?class_id=<?= e($st['class_id']) ?>" class="btn btn-outline-secondary">Annuler</a>
    </form>
  </div>
</div>
<?php else: ?>
<?php start_layout('Modifier étudiant', 'students'); ?>
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">Modifier l'étudiant</h5>
    <form method="post" class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Matricule</label>
        <input type="text" name="matricule" class="form-control" value="<?= e($st['matricule']) ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="firstname" class="form-control" value="<?= e($st['firstname']) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nom</label>
        <input type="text" name="lastname" class="form-control" value="<?= e($st['lastname']) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Classe</label>
        <select name="class_id" class="form-select" required>
          <option value="">-- Sélectionnez une classe --</option>
          <?php foreach($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($c['id']==$st['class_id'] ? 'selected' : '') ?>>
              <?= e($c['level_name'].' - '.$c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="students.php?class_id=<?= e($st['class_id']) ?>" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php end_layout(); ?>
