Mini-projet PHP L2 GL 2026 - Gestion académique

Installation rapide:

1. Copier le dossier `exam` dans le dossier htdocs de XAMPP (ex: `C:/xampp/htdocs/exam`).
2. Créer une base MySQL (ex: `exam_db`) et exécuter le script `init.sql` pour créer les tables.
3. Modifier les paramètres de connexion dans `config.php` (hôte, nom de la base, utilisateur, mot de passe).
4. Installer FPDF pour les bulletins PDF (fortement recommandé):

	- Si vous avez Composer installé, exécutez dans le répertoire `exam`:

```bash
composer install
```

	- Ou manuellement téléchargez `fpdf.php` et placez-le dans le dossier `exam`.

5. Ouvrir dans le navigateur: `http://localhost/exam`.

Ce dépôt fournit une base fonctionnelle couvrant les exigences demandées: gestion des niveaux, classes, étudiants, modules, évaluations, calcul des moyennes, génération de bulletin (FPDF optionnel) et tableau de bord.
