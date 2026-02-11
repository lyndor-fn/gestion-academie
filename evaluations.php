<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

// Add evaluation by matricule or by student_id
if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['matricule']) && !empty($_POST['module_code']) && isset($_POST['score'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  $s = getStudentByMatricule($_POST['matricule']); 
  $m = getModuleByCode($_POST['module_code']);
  if($s && $m) { 
    addEvaluation($s['id'],$m['id'], $_POST['type'] ?? 'DEVOIR', $_POST['score'], $_POST['date'] ?? null); 
  }
  header('Location: evaluations.php?matricule='.$_POST['matricule']); 
  exit;
}

// Update or delete via matricule + module_code
if(isset($_POST['update']) && !empty($_POST['matricule']) && !empty($_POST['module_code'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  updateEvaluationByMatriculeModule($_POST['matricule'], $_POST['module_code'], $_POST['type'], $_POST['score']); 
  header('Location: evaluations.php?matricule='.$_POST['matricule']); 
  exit;
}

if(isset($_POST['delete']) && !empty($_POST['matricule']) && !empty($_POST['module_code'])){
  if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
  deleteEvaluationByMatriculeModule($_POST['matricule'], $_POST['module_code']); 
  header('Location: evaluations.php?matricule='.$_POST['matricule']); 
  exit;
}

$student=null; 
if(isset($_GET['matricule'])) $student=getStudentByMatricule($_GET['matricule']);
?>

<?php start_layout('Évaluations', 'evaluations'); ?>

<!-- Add Evaluation Card -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-graph-up"></i> Enregistrer une évaluation</h5>
    <form method="post" class="row g-2">
      <div class="col-md-2">
        <input type="text" name="matricule" class="form-control form-control-sm" placeholder="Matricule" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="module_code" class="form-control form-control-sm" placeholder="Code module" required>
      </div>
      <div class="col-md-1">
        <select name="type" class="form-select form-select-sm">
          <option>DEVOIR</option>
          <option>EXAM</option>
          <option>TP</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" name="score" class="form-control form-control-sm" placeholder="Score" step="0.01" required>
      </div>
      <div class="col-md-2">
        <input type="date" name="date" class="form-control form-control-sm">
      </div>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary btn-sm w-100">
          <i class="bi bi-plus-circle"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Update/Delete Evaluation Card -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-pencil-square"></i> Modifier / Supprimer une évaluation</h5>
    <form method="post" class="row g-2">
      <div class="col-md-2">
        <input type="text" name="matricule" class="form-control form-control-sm" placeholder="Matricule" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="module_code" class="form-control form-control-sm" placeholder="Code module" required>
      </div>
      <div class="col-md-1">
        <select name="type" class="form-select form-select-sm">
          <option>DEVOIR</option>
          <option>EXAM</option>
          <option>TP</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" name="score" class="form-control form-control-sm" placeholder="Score" step="0.01" required>
      </div>
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="col-md-2">
        <button type="submit" name="update" class="btn btn-warning btn-sm w-100">
          <i class="bi bi-pencil"></i> Modifier
        </button>
      </div>
      <div class="col-md-2">
        <button type="submit" name="delete" class="btn btn-danger btn-sm w-100">
          <i class="bi bi-trash"></i> Supprimer
        </button>
      </div>
    </form>
  </div>
</div>

<?php if($student): 
  $evals = getEvaluationsByStudent($student['id']); 
  $avg = calculateStudentAverage($student['id']);
?>
  <!-- Student Evaluations History -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">
          <i class="bi bi-table"></i> Historique de 
          <strong><?= e($student['firstname'].' '.$student['lastname']) ?></strong>
        </h5>
        <div>
          <span class="badge bg-info"><?= $student['matricule'] ?></span>
          <?php if($avg !== null): ?>
            <span class="badge bg-success">Moyenne: <?= number_format($avg, 2) ?></span>
          <?php else: ?>
            <span class="badge bg-secondary">Pas d'évaluations</span>
          <?php endif; ?>
        </div>
      </div>

      <?php if(empty($evals)): ?>
        <div class="empty-state text-center py-5">
          <i class="bi bi-inbox"></i>
          <p class="mt-2 text-muted">Aucune évaluation enregistrée</p>
        </div>
      <?php else: ?>
        <div class="table-wrapper">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th><i class="bi bi-book"></i> Module</th>
                <th width="100">Type</th>
                <th width="100">Score</th>
                <th width="100">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($evals as $ev): ?>
                <tr>
                  <td>
                    <small class="text-muted"><?= e($ev['module_code']) ?></small><br>
                    <strong><?= e($ev['module_name']) ?></strong>
                  </td>
                  <td>
                    <span class="badge bg-secondary"><?= e($ev['type']) ?></span>
                  </td>
                  <td>
                    <strong class="text-primary"><?= e($ev['score']) ?>/20</strong>
                  </td>
                  <td>
                    <small class="text-muted"><?= e($ev['date_eval']) ?></small>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
    <div class="card-footer bg-light">
      <small class="text-muted">Total: <?= count($evals) ?> évaluation(s) | Moyenne: <?= ($avg === null) ? 'N/A' : number_format($avg, 2) ?></small>
    </div>
  </div>

<?php endif; ?>

<?php end_layout(); ?>
