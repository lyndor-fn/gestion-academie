<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/layout.php';
require_once __DIR__.'/auth.php';
require_login();

$matricule = $_GET['matricule'] ?? null;
if(!$matricule) { 
  header('Location: index.php'); 
  exit; 
}

$student = getStudentByMatricule($matricule);
if(!$student) { 
  header('Location: students.php'); 
  exit; 
}

$evals = getEvaluationsByStudent($student['id']);
$avg = calculateStudentAverage($student['id']);

// Try using FPDF if available
if(file_exists(__DIR__.'/vendor/autoload.php')) require __DIR__.'/vendor/autoload.php';

// Check if PDF export is requested
if(isset($_GET['format']) && $_GET['format'] === 'pdf' && class_exists('FPDF')){
    $pdf = new FPDF(); 
    $pdf->AddPage(); 
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'Bulletin de notes',0,1,'C');
    
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Matricule: '.$student['matricule'],0,1);
    $pdf->Cell(0,8,'Nom: '.$student['firstname'].' '.$student['lastname'],0,1);
    $pdf->Cell(0,8,'Classe: '.$student['class_name'],0,1);
    $pdf->Ln(4);
    
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80,8,'Module',1); 
    $pdf->Cell(30,8,'Type',1); 
    $pdf->Cell(30,8,'Score',1); 
    $pdf->Ln();
    
    $pdf->SetFont('Arial','',10);
    foreach($evals as $e){ 
      $pdf->Cell(80,8,substr($e['module_code'].' - '.$e['module_name'], 0, 30),1); 
      $pdf->Cell(30,8,$e['type'],1); 
      $pdf->Cell(30,8,$e['score'],1); 
      $pdf->Ln(); 
    }
    
    $pdf->Ln(4); 
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,'Moyenne générale: '.($avg===null?'N/A':number_format($avg,2)).'/20',0,1);
    
    $pdf->Output('D', 'Bulletin_'.$student['matricule'].'.pdf');
    exit;
}
?>

<?php start_layout('Bulletin de notes', 'bulletin'); ?>

<!-- Student Info Card -->
<div class="card mb-4 shadow-sm">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <h5 class="card-title mb-3"><i class="bi bi-person-vcard"></i> Informations étudiant</h5>
        <p class="mb-2">
          <strong>Matricule:</strong> <span class="badge bg-secondary"><?= e($student['matricule']) ?></span>
        </p>
        <p class="mb-2">
          <strong>Nom:</strong> <?= e($student['firstname'].' '.$student['lastname']) ?>
        </p>
        <p class="mb-0">
          <strong>Classe:</strong> <?= e($student['class_name'] ?? 'N/A') ?>
        </p>
      </div>
      <div class="col-md-6 text-md-end">
        <div class="stat-card">
          <div class="stat-card-value text-primary">
            <?= ($avg === null) ? 'N/A' : number_format($avg, 2) ?>
          </div>
          <div class="stat-card-label">Moyenne générale</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Evaluations Table -->
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3"><i class="bi bi-table"></i> Détail des évaluations</h5>
    
    <?php if(empty($evals)): ?>
      <div class="empty-state text-center py-5">
        <i class="bi bi-inbox"></i>
        <p class="mt-2 text-muted">Aucune évaluation enregistrée pour cet étudiant</p>
      </div>
    <?php else: ?>
      <div class="table-wrapper">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th><i class="bi bi-book"></i> Module</th>
              <th width="80">Type</th>
              <th width="80">Score</th>
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
                  <?php
                    $score = floatval($ev['score']);
                    $score_class = $score >= 14 ? 'success' : ($score >= 10 ? 'info' : 'warning');
                  ?>
                  <strong class="text-<?= $score_class ?>"><?= e($ev['score']) ?>/20</strong>
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
  <div class="card-footer bg-light d-flex justify-content-between align-items-center">
    <small class="text-muted">Total: <?= count($evals) ?> évaluation(s)</small>
    <div>
      <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-printer"></i> Imprimer
      </button>
      <?php if(class_exists('FPDF')): ?>
        <a href="?matricule=<?= urlencode($matricule) ?>&format=pdf" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-file-pdf"></i> Télécharger PDF
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php end_layout(); ?>
