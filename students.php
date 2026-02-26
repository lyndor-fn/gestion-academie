<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/auth.php';
require_login();
require_once __DIR__.'/includes/layout.php';

$levels = getLevels();
$classes = getClassesByLevel();
$class = null;
$students = [];

if(isset($_GET['class_id'])) {
    $class = getClassById((int)$_GET['class_id']);
    if($class) $students = getStudentsByClass($class['id']);
}

// Traiter l'ajout d'étudiant
if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['matricule']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['class_id'])){
    if(!verify_csrf($_POST['csrf_token'] ?? '')){ die('CSRF token invalide'); }
    addStudent($_POST['matricule'], $_POST['firstname'], $_POST['lastname'], $_POST['class_id']);
    header('Location: students.php?class_id='.$_POST['class_id']);
    exit;
}

start_layout('Gestion des Étudiants', 'students');
?>

<!-- Alerte succès (si redirection) -->
<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success mb-4">
        <div class="alert-icon"><i class="bi bi-check-circle"></i></div>
        <div class="alert-content">
            <div class="alert-title">Succès</div>
            <div class="alert-message">L'étudiant a été enregistré avec succès.</div>
        </div>
        <button class="alert-close"><i class="bi bi-x"></i></button>
    </div>
<?php endif; ?>

<!-- Sélection de la classe -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-funnel"></i> Filtrer par Classe
        </h5>
    </div>
    <div class="card-body">
        <form method="get" class="d-flex gap-2 flex-wrap">
            <select name="class_id" class="form-control" style="max-width: 300px;" onchange="this.form.submit();">
                <option value="">Sélectionnez une classe...</option>
                <?php foreach($classes as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo isset($_GET['class_id']) && $_GET['class_id'] == $c['id'] ? 'selected' : ''; ?>>
                        <?php echo e($c['level_name'] . ' - ' . $c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<!-- Formulaire d'ajout d'étudiant -->
<?php if($class): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-person-plus"></i> Ajouter un Étudiant à <?php echo e($class['name']); ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="post" class="form-group-row">
                <div class="form-group">
                    <label class="form-label required">Matricule</label>
                    <input type="text" name="matricule" class="form-control" placeholder="Matricule" required>
                </div>
                <div class="form-group">
                    <label class="form-label required">Prénom</label>
                    <input type="text" name="firstname" class="form-control" placeholder="Prénom" required>
                </div>
                <div class="form-group">
                    <label class="form-label required">Nom</label>
                    <input type="text" name="lastname" class="form-control" placeholder="Nom" required>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus"></i> Inscrire l'Étudiant
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <?php if(!empty($students)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul"></i> Étudiants de <?php echo e($class['name']); ?> (<?php echo count($students); ?>)
                </h5>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom Complet</th>
                            <th>Moyenne</th>
                            <th>Statut</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $s): 
                            $avg = calculateStudentAverage($s['id']);
                            $status = studentStatus($s['id']);
                            $status_badge = $status === 'Admis' ? 'success' : ($status === 'Ajourné' ? 'warning' : 'danger');
                        ?>
                            <tr>
                                <td><strong><?php echo e($s['matricule']); ?></strong></td>
                                <td><?php echo e($s['firstname'] . ' ' . $s['lastname']); ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="flex: 1;">
                                            <div style="width: 100%; height: 6px; background: var(--border-color); border-radius: 3px;">
                                                <div style="width: <?php echo $avg !== null ? ($avg / 20) * 100 : 0; ?>%; height: 100%; background: <?php echo $avg === null ? 'transparent' : ($avg >= 10 ? 'var(--success-color)' : ($avg >= 5 ? 'var(--warning-color)' : 'var(--danger-color)')); ?>; border-radius: 3px; transition: width 0.3s;"></div>
                                            </div>
                                        </div>
                                        <span><?php echo $avg === null ? 'N/A' : number_format($avg, 2); ?>/20</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $status_badge; ?>"><?php echo $status; ?></span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="evaluations.php?matricule=<?php echo urlencode($s['matricule']); ?>" class="table-action-btn" title="Notes">
                                            <i class="bi bi-graph-up"></i>
                                        </a>
                                        <a href="student_action.php?id=<?php echo $s['id']; ?>" class="table-action-btn" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="student_action.php?id=<?php echo $s['id']; ?>&action=delete" class="table-action-btn delete" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Statistiques de la classe -->
            <div class="card-footer">
                <div class="grid-2" style="gap: var(--spacing-lg);">
                    <div>
                        <div style="font-weight: var(--font-weight-bold); color: var(--text-dark); margin-bottom: var(--spacing-sm);">
                            Meilleur Étudiant
                        </div>
                        <?php 
                            $best = bestStudentInClass($class['id']);
                            if($best): 
                        ?>
                            <div style="color: var(--text-muted); font-size: 0.95rem;">
                                <strong><?php echo e($best['firstname'] . ' ' . $best['lastname']); ?></strong>
                                <br>
                                <span class="badge badge-success">
                                    Moyenne: <?php echo number_format($best['avg_score'], 2); ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div style="color: var(--text-muted);">Aucune donnée disponible</div>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <div style="font-weight: var(--font-weight-bold); color: var(--text-dark); margin-bottom: var(--spacing-sm);">
                            Étudiants au-Dessus de la Moyenne
                        </div>
                        <?php 
                            $above_avg = studentsAboveClassAverage($class['id']);
                            if($above_avg): 
                        ?>
                            <div style="color: var(--text-muted); font-size: 0.95rem;">
                                <?php echo count($above_avg); ?> étudiant(s)
                                <div style="font-size: 0.85rem; color: #718096; margin-top: 5px;">
                                    <?php foreach($above_avg as $s): ?>
                                        <span class="badge badge-info" style="display: inline-block; margin: 2px;">
                                            <?php echo e(substr($s['firstname'], 0, 1) . '. ' . $s['lastname']); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="color: var(--text-muted);">Aucun étudiant au-dessus de la moyenne</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h5 class="empty-state-title">Aucun Étudiant</h5>
                <p class="empty-state-text">Cette classe n'a pas encore d'étudiants inscrits. Utilisez le formulaire ci-dessus pour ajouter des étudiants.</p>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-search"></i>
            </div>
            <h5 class="empty-state-title">Sélectionnez une Classe</h5>
            <p class="empty-state-text">Choisissez une classe dans le filtre ci-dessus pour afficher ses étudiants.</p>
        </div>
    </div>
<?php endif; ?>

<?php end_layout(); ?>

