#!/usr/bin/env bash
# Configuration checklist et guide d'installation du Design System

## ============================================
## GESTION ACADÉMIQUE - SYSTÈME DE DESIGN v1.0
## ============================================

## 📦 FICHIERS CRÉÉS / MODIFIÉS

### ✅ Création de Fichiers

1. **assets/css/styles.css** (1700+ lignes)
   - Système de variables CSS (couleurs, espacements, typo)
   - Composants: cartes, boutons, badges, alertes, tables, formulaires
   - Layout: sidebar, topbar, responsive
   - Animations et transitions fluides
   - Support mode sombre

2. **layout.php** (Template Principal)
   - Sidebar fixe avec navigation
   - Topbar sticky avec profil utilisateur
   - Functions PHP: start_layout(), end_layout()
   - JavaScript pour toggle menu mobile
   - Responsive (mobile < 480px)

3. **components-example.html** (Galerie Interactive)
   - Tous les composants avec exemples visuels
   - Code HTML copiable pour chaque composant
   - Section pour: stat-cards, boutons, badges, alertes, tables, formulaires, etc.

4. **quickstart.html** (Guide Rapide)
   - Patterns CSS les plus courants
   - Code snippets pour développement rapide
   - Explications des classes utilitaires

5. **DESIGN_SYSTEM.md** (Documentation Technique)
   - Guide complet du système de design
   - Palette de couleurs
   - Architecture layout
   - Composants détaillés
   - Responsive design
   - Bonnes pratiques

6. **README_DESIGN.md** (Guide Utilisateur)
   - Résumé des modifications
   - Comment intégrer le layout dans les pages
   - Exemples d'utilisation
   - Personnalisation

### 🔄 Fichiers Modifiés

1. **index.php**
   - Ancien: HTML simple minimaliste
   - Nouveau: Layout moderne avec sidebar
   - Grille 2x3 de cartes de bienvenue
   - Icônes Bootstrap Icons

2. **dashboard.php**
   - Ancien: Tableau simple avec statistiques basiques
   - Nouveau: Layout complet avec:
     - 4 cartes statistiques (grid-4)
     - Graphique répartition par niveau
     - Statuts des étudiants
     - Actions rapides avec boutons stylisés

3. **students.php** (Exemple Page CRUD)
   - Ancien: HTML basique sans CSS personnalisé
   - Nouveau: Pattern CRUD moderne avec:
     - Filtre par classe
     - Formulaire d'ajout élégant
     - Table avec barres de progression
     - Badges de statut
     - Actions avec icônes
     - Statistiques de classe
     - État vide

## 🎯 STRUCTURE DES FICHIERS

```
/L2/exam/
├── assets/
│   └── css/
│       └── styles.css              ← NOUVEAU
├── layout.php                      ← NOUVEAU
├── components-example.html         ← NOUVEAU
├── quickstart.html                 ← NOUVEAU
├── DESIGN_SYSTEM.md               ← NOUVEAU
├── README_DESIGN.md               ← NOUVEAU
├── index.php                       ← MODIFIÉ
├── dashboard.php                   ← MODIFIÉ
├── students.php                    ← MODIFIÉ
├── auth.php                        ← Existant (restauré)
├── login.php                       ← Existant
├── levels.php                      ← À mettre à jour
├── classes.php                     ← À mettre à jour
├── modules.php                     ← À mettre à jour
├── evaluations.php                 ← À mettre à jour
├── generate_bulletin.php           ← À mettre à jour
└── [autres fichiers]
```

## 🎨 PALETTE ACTUELLE

| Couleur | Valeur | Usage |
|---------|--------|-------|
| Primaire | #2c3e7f | Navigation, actions principales |
| Secondaire | #1e88e5 | Boutons, accents |
| Succès | #43a047 | Validations, états positifs |
| Avertissement | #fb8500 | Alertes, attention |
| Danger | #e53935 | Erreurs, suppression |
| Info | #0288d1 | Informations |
| Fond clair | #f5f7fa | Arrière-plan page |
| Fond blanc | #ffffff | Cartes, conteneurs |
| Texte | #2d3748 | Texte principal |
| Muted | #718096 | Texte secondaire |
| Bordure | #e2e8f0 | Séparations |

## 📱 BREAKPOINTS RESPONSIFS

- **Desktop (1000px+)**: Sidebar 280px visible, layout complet
- **Tablet (768-999px)**: Ajustements d'espacement
- **Mobile (480-767px)**: Sidebar cachée, menu toggle
- **Petit (<480px)**: Sidebar mobile overlay, layout adapté

## 🚀 COMMENT UTILISER

### 1. Ajouter le Layout à une Page

```php
<?php
require_once __DIR__.'/functions.php';
require_once __DIR__.'/auth.php';
require_login();
require_once __DIR__.'/layout.php';

// Démarrer la page
start_layout('Titre de la Page', 'page_key');
?>

<!-- Contenu de la page ici -->

<?php end_layout(); ?>
```

**Keys disponibles:** dashboard, levels, classes, students, modules, evaluations, bulletin

### 2. Ajouter une Carte Statistique

```html
<div class="stat-card">
    <div class="stat-icon primary">
        <i class="bi bi-people-fill"></i>
    </div>
    <div class="stat-value">250</div>
    <div class="stat-label">Étudiants</div>
    <div class="stat-change positive">+12 ce mois</div>
</div>
```

### 3. Ajouter une Table

```html
<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr><th>Colonne</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Données</td>
                    <td>
                        <div class="table-actions">
                            <button class="table-action-btn"><i class="bi bi-eye"></i></button>
                            <button class="table-action-btn"><i class="bi bi-pencil"></i></button>
                            <button class="table-action-btn delete"><i class="bi bi-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

### 4. Ajouter un Formulaire

```html
<div class="form-group">
    <label class="form-label required">Nom</label>
    <input type="text" class="form-control" required>
</div>

<div class="form-group-row">
    <div class="form-group">
        <label class="form-label">Champ 1</label>
        <input type="text" class="form-control">
    </div>
    <div class="form-group">
        <label class="form-label">Champ 2</label>
        <input type="text" class="form-control">
    </div>
</div>
```

## 🔗 URLS DE TESTS

- **Login**: http://localhost/L2/exam/login.php
- **Index**: http://localhost/L2/exam/index.php
- **Dashboard**: http://localhost/L2/exam/dashboard.php
- **Composants**: http://localhost/L2/exam/components-example.html
- **Quickstart**: http://localhost/L2/exam/quickstart.html

**Identifiants**: admin / admin123

## ✨ FONCTIONNALITÉS PRINCIPALES

✅ Sidebar avec navigation
✅ Topbar avec profil utilisateur
✅ Layout responsive (mobile-first)
✅ 20+ composants CSS
✅ Design moderne et professionnel
✅ Mode sombre préparé
✅ Animation fluides (non-lourdes)
✅ Icônes Bootstrap Icons
✅ Documentation complète
✅ Galerie interactive des composants

## 📚 DOCUMENTATION

### Pour Apprendre

1. **DESIGN_SYSTEM.md** - Documentation technique complète
2. **components-example.html** - Galerie interactive (ouvrir dans navigateur)
3. **quickstart.html** - Guide rapide des patterns

### Pour Développer

1. **styles.css** - Code source du CSS avec commentaires
2. **layout.php** - Structure HTML du layout
3. **students.php** - Exemple complet de page CRUD

## 🎯 PAGES À METTRE À JOUR

Les fichiers suivants peuvent être améliorés avec le nouveau design:

- [ ] levels.php
- [ ] classes.php
- [ ] modules.php
- [ ] evaluations.php
- [ ] edit_student.php
- [ ] edit_class.php
- [ ] edit_module.php
- [ ] delete_*.php
- [ ] generate_bulletin.php

### Pattern recommandé pour chaque page

```php
<?php
start_layout('Titre', 'page_key');
?>

<!-- Alerte succès (si applicable) -->
<div class="alert alert-success mb-4">...</div>

<!-- Filtre/Recherche (si applicable) -->
<div class="card mb-4">...</div>

<!-- Formulaire d'ajout (si applicable) -->
<div class="card mb-4">...</div>

<!-- Tableau des données -->
<div class="card">...</div>

<?php end_layout(); ?>
```

## 🛠️ PERSONNALISATION

### Changer la Palette de Couleurs

Modifier `:root` dans `styles.css`:

```css
:root {
  --primary-color: #votre-couleur;
  --secondary-color: #votre-couleur;
  --success-color: #votre-couleur;
  /* ... */
}
```

### Changer l'Espacement

Les variables d'espacement sont dans `:root`:

```css
--spacing-md: 1rem;    /* Modifier la valeur */
```

### Changer le Logo

Dans `layout.php`:

```html
<div class="sidebar-logo-icon">
    <i class="bi bi-mortarboard"></i>  <!-- Changer l'icône -->
</div>
<div class="sidebar-logo-text">Academy</div>  <!-- Changer le texte -->
```

## 📊 STATISTIQUES

- **CSS**: 1700+ lignes, 30+ composants
- **JavaScript**: Minimal (sidebar toggle, alertes)
- **Bootstrap**: 5.3.0
- **Bootstrap Icons**: 2000+ icônes disponibles
- **Support**: Chrome, Firefox, Safari, Edge (modernes)
- **Performance**: Aucune animation lourde, transitions opt-out

## ✅ CHECKLIST FINALISATION

- [x] CSS personnalisé complet
- [x] Layout principal fonctionnel
- [x] Dashboard redessiné
- [x] Page CRUD exemple (students.php)
- [x] Composants réutilisables
- [x] Design responsive
- [x] Documentation technique
- [x] Guide utilisateur
- [x] Galerie interactive
- [x] Guide rapide

## 📞 NOTES

- Tous les fichiers incluent des commentaires CSS détaillés
- La documentation est complète dans DESIGN_SYSTEM.md
- Exemples visuels disponibles dans components-example.html
- Support du mode sombre via CSS media queries
- Compatible avec tous les modules existants

## 🎓 Pour l'Équipe de Développement

1. Consulter `components-example.html` pour voir les composants en action
2. Utiliser `quickstart.html` pour les patterns courants
3. Consulter `DESIGN_SYSTEM.md` pour la documentation technique
4. Adapter `layout.php` pour nouvelles pages
5. Maintenir la cohérence de `styles.css` pour les nouvelles features

---

**Version**: 1.0  
**Date**: 11 février 2026  
**Status**: ✅ Complet et Fonctionnel
