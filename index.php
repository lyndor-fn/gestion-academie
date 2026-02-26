<?php
require_once __DIR__.'/includes/functions.php';
require_once __DIR__.'/includes/auth.php';
require_login();
require_once __DIR__.'/includes/layout.php';

$stats = stats();

start_layout('Tableau de bord', 'dashboard');
?>

<div class="alert alert-info mb-4">
    <div class="alert-content">
        <div class="alert-title">Bienvenue sur le système de gestion académique</div>
    </div>
</div>

<!-- <div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-label">Niveaux</div>
        <div class="stat-value"><?= (int)$stats['nb_levels'] ?></div>
        <div class="stat-meta">Structures pédagogiques</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Classes</div>
        <div class="stat-value"><?= (int)$stats['nb_classes'] ?></div>
        <div class="stat-meta">Groupes actifs</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Étudiants</div>
        <div class="stat-value"><?= (int)$stats['nb_students'] ?></div>
        <div class="stat-meta">Inscrits</div>
    </div>
</div> -->

<?php end_layout(); ?>

