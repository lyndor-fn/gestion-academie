<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/auth.php';
require_login();
require_once __DIR__.'/layout.php';

start_layout('Accueil', 'dashboard');
?>

<div class="alert alert-info mb-4">
    <div class="alert-icon"><i class="bi bi-info-circle"></i></div>
    <div class="alert-content">
        <div class="alert-title">Bienvenue sur le système de gestion académique</div>
        <div class="alert-message">
            Utilisez le menu de navigation pour gérer les différents aspects de votre institution : niveaux, classes, étudiants, modules et évaluations.
        </div>
    </div>
    <button class="alert-close"><i class="bi bi-x"></i></button>
</div>

<div class="grid-2 mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-diagram-3"></i> Niveaux de Formation
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Gérez les différents niveaux d'études de votre institution (L1, L2, L3, etc.)</p>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="levels.php" class="btn btn-primary btn-sm flex-grow-1">Gérer les Niveaux</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-building"></i> Classes
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Organisez les étudiants en classes et gérez leurs affectations.</p>
        </div>
        <div class="card-footer">
            <a href="classes.php" class="btn btn-primary btn-sm w-100">Gérer les Classes</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-people"></i> Étudiants
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Enregistrez et gérez les informations des étudiants inscrits.</p>
        </div>
        <div class="card-footer">
            <a href="students.php" class="btn btn-primary btn-sm w-100">Gérer les Étudiants</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-book"></i> Modules
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Créez et gérez les modules d'enseignement et leurs assignations.</p>
        </div>
        <div class="card-footer">
            <a href="modules.php" class="btn btn-primary btn-sm w-100">Gérer les Modules</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-graph-up"></i> Évaluations
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Enregistrez les notes et évaluations des étudiants.</p>
        </div>
        <div class="card-footer">
            <a href="evaluations.php" class="btn btn-primary btn-sm w-100">Gérer les Évaluations</a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-file-pdf"></i> Bulletins
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Générez les bulletins de notes en format PDF.</p>
        </div>
        <div class="card-footer">
            <a href="generate_bulletin.php" class="btn btn-primary btn-sm w-100">Générer les Bulletins</a>
        </div>
    </div>
</div>

<?php end_layout(); ?>
