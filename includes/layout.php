<?php
if (!function_exists('start_layout')) {
    function start_layout($page_title = 'Gestion Académique', $current_page = '')
    {
        $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $asset_base = $base_path === '' || $base_path === '.' ? '' : $base_path;
        $user_name = htmlspecialchars($_SESSION['user'] ?? 'Administrateur');
        $initials = strtoupper(substr($user_name, 0, 1));
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Gestion Académique</title>
    <link href="<?= $asset_base ?>/assets/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper" id="appWrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">GA</div>
                <div class="sidebar-logo-text">Gestion Académique</div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'dashboard' ? 'active' : '' ?>" href="index.php">
                        <span class="sidebar-nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'levels' ? 'active' : '' ?>" href="levels.php">
                        <span class="sidebar-nav-text">Niveaux</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'classes' ? 'active' : '' ?>" href="classes.php">
                        <span class="sidebar-nav-text">Classes</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'students' ? 'active' : '' ?>" href="students.php">
                        <span class="sidebar-nav-text">Étudiants</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'modules' ? 'active' : '' ?>" href="modules.php">
                        <span class="sidebar-nav-text">Modules</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link <?= $current_page === 'evaluations' ? 'active' : '' ?>" href="evaluations.php">
                        <span class="sidebar-nav-text">Évaluations</span>
                    </a>
                </li>
            </ul>
        </aside>

        <div class="main-content">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" id="sidebarToggle" type="button">☰</button>
                    <div class="topbar-title"><?= $page_title ?></div>
                </div>
                <div class="topbar-right">
                    <div class="topbar-user">
                        <div class="topbar-avatar"><?= $initials ?></div>
                        <div>
                            <div class="topbar-user-name"><?= $user_name ?></div>
                            <div class="topbar-user-role">Administrateur</div>
                        </div>
                    </div>
                    <a class="btn btn-light btn-sm" href="logout.php">Déconnexion</a>
                </div>
            </header>

            <main class="page-content">
        <?php
    }
}

if (!function_exists('end_layout')) {
    function end_layout()
    {
        ?>
            </main>
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var wrapper = document.getElementById('appWrapper');
            var toggle = document.getElementById('sidebarToggle');

            if (!sidebar || !wrapper || !toggle) return;

            toggle.addEventListener('click', function () {
                if (window.innerWidth <= 480) {
                    sidebar.classList.toggle('show');
                    wrapper.classList.toggle('mobile-menu-open');
                    return;
                }
                wrapper.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('collapsed');
            });

            wrapper.addEventListener('click', function (event) {
                if (window.innerWidth > 480) return;
                if (!sidebar.classList.contains('show')) return;
                if (event.target.closest('.sidebar')) return;
                sidebar.classList.remove('show');
                wrapper.classList.remove('mobile-menu-open');
            });
        })();
    </script>
</body>
</html>
        <?php
    }
}
?>
