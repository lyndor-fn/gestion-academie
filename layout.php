<?php
/**
 * Layout Principal
 * Inclure ce fichier au début de chaque page
 */

if(!function_exists('start_layout')) {
    function start_layout($page_title = 'Gestion Académique', $current_page = 'dashboard') {
        $user = $_SESSION['user'] ?? 'Administrateur';
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title; ?> - Gestion Académique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper" id="appWrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <div class="sidebar-logo-text">Gestion</div>
            </div>
            
            <nav class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="index.php" class="sidebar-nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-speedometer2"></i></span>
                        <span class="sidebar-nav-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="levels.php" class="sidebar-nav-link <?php echo $current_page === 'levels' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-diagram-3"></i></span>
                        <span class="sidebar-nav-text">Niveaux</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="classes.php" class="sidebar-nav-link <?php echo $current_page === 'classes' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-building"></i></span>
                        <span class="sidebar-nav-text">Classes</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="students.php" class="sidebar-nav-link <?php echo $current_page === 'students' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-people"></i></span>
                        <span class="sidebar-nav-text">Étudiants</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="modules.php" class="sidebar-nav-link <?php echo $current_page === 'modules' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-book"></i></span>
                        <span class="sidebar-nav-text">Modules</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="evaluations.php" class="sidebar-nav-link <?php echo $current_page === 'evaluations' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-graph-up"></i></span>
                        <span class="sidebar-nav-text">Évaluations</span>
                    </a>
                </li>
                
                <li class="sidebar-nav-item">
                    <a href="generate_bulletin.php" class="sidebar-nav-link <?php echo $current_page === 'bulletin' ? 'active' : ''; ?>">
                        <span class="sidebar-nav-icon"><i class="bi bi-file-pdf"></i></span>
                        <span class="sidebar-nav-text">Bulletins</span>
                    </a>
                </li>
                
                <hr style="border-color: rgba(255,255,255,0.1); margin: var(--spacing-lg) 0;">
                
                <li class="sidebar-nav-item">
                    <a href="logout.php" class="sidebar-nav-link">
                        <span class="sidebar-nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span class="sidebar-nav-text">Déconnexion</span>
                    </a>
                </li>
            </nav>
        </aside>
        
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- TOPBAR -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="topbar-title"><?php echo $page_title; ?></div>
                </div>
                
                <div class="topbar-right">
                    <button class="btn btn-light btn-sm">
                        <i class="bi bi-bell"></i>
                        <span class="badge badge-danger">3</span>
                    </button>
                    
                    <div class="topbar-user">
                        <div class="topbar-avatar"><?php echo strtoupper(substr($user, 0, 1)); ?></div>
                        <div>
                            <div class="topbar-user-name"><?php echo htmlspecialchars($user); ?></div>
                            <div class="topbar-user-role">Administrateur</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- PAGE CONTENT -->
            <main class="page-content">
        <?php
    }
}

if(!function_exists('end_layout')) {
    function end_layout() {
        ?>
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const wrapper = document.getElementById('appWrapper');
            
            if (window.innerWidth < 480) {
                wrapper.classList.toggle('mobile-menu-open');
                sidebar.classList.toggle('show');
            } else {
                wrapper.classList.toggle('sidebar-collapsed');
            }
        });
        
        // Fermer le menu mobile au clic sur un lien
        document.querySelectorAll('.sidebar-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 480) {
                    document.getElementById('appWrapper').classList.remove('mobile-menu-open');
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
        
        // Fermer le menu au clic sur le backdrop
        document.getElementById('appWrapper').addEventListener('click', function(e) {
            if (window.innerWidth < 480 && e.target === this) {
                this.classList.remove('mobile-menu-open');
                document.getElementById('sidebar').classList.remove('show');
            }
        });
        
        // Animation des alertes
        document.querySelectorAll('.alert-close').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.alert').style.animation = 'slideIn 0.3s ease-in-out reverse';
                setTimeout(() => {
                    this.closest('.alert').remove();
                }, 300);
            });
        });
    </script>
</body>
</html>
        <?php
    }
}
?>
