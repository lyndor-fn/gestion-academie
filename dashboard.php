<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/auth.php';
require_login();
require_once __DIR__.'/layout.php';

$s = stats();

// Récupérer les statistiques supplémentaires
$studentStatus = [];
$cats = ['Admis' => 0, 'Ajourné' => 0, 'Exclus' => 0];

foreach($pdo->query('SELECT id FROM students') as $st) {
    $status = studentStatus($st['id']);
    if(isset($cats[$status])) $cats[$status]++;
}

// Répartition par niveau
$levelStats = $pdo->query('SELECT l.id, l.name as level, COUNT(s.id) as nb FROM students s 
                          JOIN classes c ON s.class_id=c.id 
                          JOIN levels l ON c.level_id=l.id 
                          GROUP BY l.id, l.name')->fetchAll(PDO::FETCH_ASSOC);

start_layout('Dashboard', 'dashboard');
?>

<!-- Cartes Statistiques -->
<div class="grid-4 mb-4">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-value"><?php echo $s['nb_students']; ?></div>
        <div class="stat-label">Étudiants Total</div>
        <div class="stat-change positive">
            <i class="bi bi-arrow-up"></i> Inscrits
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-building"></i>
        </div>
        <div class="stat-value"><?php echo $s['nb_classes']; ?></div>
        <div class="stat-label">Classes Actives</div>
        <div class="stat-change positive">
            Fonctionnelles
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-diagram-3"></i>
        </div>
        <div class="stat-value"><?php echo $s['nb_levels']; ?></div>
        <div class="stat-label">Niveaux</div>
        <div class="stat-change positive">
            Configurés
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-book"></i>
        </div>
        <div class="stat-value"><?php 
            $moduleCount = $pdo->query('SELECT COUNT(*) FROM modules')->fetchColumn();
            echo $moduleCount;
        ?></div>
        <div class="stat-label">Modules</div>
        <div class="stat-change positive">
            Disponibles
        </div>
    </div>
</div>

<!-- Ligne 2: Répartition et Catégories -->
<div class="grid-2 mb-4">
    <!-- Répartition par Niveau -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Répartition par Niveau</h5>
        </div>
        <div class="card-body">
            <div class="list-group list-group-flush">
                <?php foreach($levelStats as $level): ?>
                    <div class="list-group-item d-flex justify-between align-items-center">
                        <div>
                            <h6 class="mb-1"><?php echo e($level['level']); ?></h6>
                            <small class="text-muted"><?php echo $level['nb']; ?> étudiant(s)</small>
                        </div>
                        <div class="d-flex align-items-center gap-md">
                            <div style="width: 100px; height: 8px; background: rgba(30, 136, 229, 0.2); border-radius: 4px;">
                                <div style="width: <?php echo ($level['nb'] / max(1, $s['nb_students'])) * 100; ?>%; height: 100%; background: #1e88e5; border-radius: 4px; transition: width 0.3s;"></div>
                            </div>
                            <span class="badge badge-primary"><?php echo round(($level['nb'] / max(1, $s['nb_students'])) * 100); ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Statuts des Étudiants -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Statuts des Étudiants</h5>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                <!-- Admis -->
                <div class="d-flex justify-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-md">
                        <div class="stat-icon success" style="width: 40px; height: 40px; margin: 0;">
                            <i class="bi bi-check-circle" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <div class="text-bold">Admis</div>
                            <small class="text-muted">Réussis</small>
                        </div>
                    </div>
                    <div>
                        <div class="text-bold" style="color: var(--success-color);"><?php echo $cats['Admis']; ?></div>
                        <small class="text-muted"><?php echo round(($cats['Admis'] / max(1, $s['nb_students'])) * 100); ?>%</small>
                    </div>
                </div>
                
                <!-- Ajournés -->
                <div class="d-flex justify-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-md">
                        <div class="stat-icon warning" style="width: 40px; height: 40px; margin: 0;">
                            <i class="bi bi-exclamation-circle" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <div class="text-bold">Ajournés</div>
                            <small class="text-muted">À réviser</small>
                        </div>
                    </div>
                    <div>
                        <div class="text-bold" style="color: var(--warning-color);"><?php echo $cats['Ajourné']; ?></div>
                        <small class="text-muted"><?php echo round(($cats['Ajourné'] / max(1, $s['nb_students'])) * 100); ?>%</small>
                    </div>
                </div>
                
                <!-- Exclus -->
                <div class="d-flex justify-between align-items-center">
                    <div class="d-flex align-items-center gap-md">
                        <div class="stat-icon danger" style="width: 40px; height: 40px; margin: 0;">
                            <i class="bi bi-x-circle" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <div class="text-bold">Exclus</div>
                            <small class="text-muted">Non admis</small>
                        </div>
                    </div>
                    <div>
                        <div class="text-bold" style="color: var(--danger-color);"><?php echo $cats['Exclus']; ?></div>
                        <small class="text-muted"><?php echo round(($cats['Exclus'] / max(1, $s['nb_students'])) * 100); ?>%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Rapides -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Actions Rapides</h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-md flex-wrap">
            <a href="students.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Ajouter un Étudiant
            </a>
            <a href="levels.php" class="btn btn-secondary">
                <i class="bi bi-plus-circle"></i> Configurer les Niveaux
            </a>
            <a href="classes.php" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> Créer une Classe
            </a>
            <a href="generate_bulletin.php" class="btn btn-light">
                <i class="bi bi-file-pdf"></i> Générer des Bulletins
            </a>
        </div>
    </div>
</div>

<?php end_layout(); ?>
