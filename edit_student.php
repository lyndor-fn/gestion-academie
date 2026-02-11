<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

$id = $_GET['id'] ?? null; 
if(!$id) { 
  header('Location: students.php'); 
  exit; 
}

$st = getStudentById($id);
if(!$st){ 
  header('Location: students.php'); 
  exit; 
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!verify_csrf($_POST['csrf_token'] ?? '')) die('CSRF token invalide');
    updateStudent($id, $_POST['matricule'], $_POST['firstname'], $_POST['lastname'], $_POST['class_id']);
    header('Location: students.php?class_id='.$_POST['class_id']); 
    exit;
}

$classes = getClassesByLevel();
?>

<?php start_layout('Modifier étudiant', 'students'); ?>

<!-- Edit Form Card -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-4">
      <i class="bi bi-pencil-square"></i> Modification de 
      <strong><?= e($st['firstname'].' '.$st['lastname']) ?></strong>
    </h5>

    <form method="post" class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Matricule</label>
        <input type="text" name="matricule" class="form-control" value="<?= e($st['matricule']) ?>" required>
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
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Enregistrer les modifications
        </button>
        <a href="students.php?class_id=<?= e($st['class_id']) ?>" class="btn btn-outline-secondary">
          <i class="bi bi-x-circle"></i> Annuler
        </a>
      </div>
    </form>
  </div>
</div>

<?php end_layout(); ?>
