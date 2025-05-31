<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
    <div class="container">
        <button class="btn btn-link text-dark text-decoration-none me-3" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fw-bold" href="http://localhost:8888/skill_swap/HomePage/home.php">SkillSwap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo 'http://localhost:8888/skill_swap/HomePage/home.php'; ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8888/skill_swap/MarketPlace/marketplace.php">Marketplace</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="user-name"><?php echo $_SESSION['auth_user']['username']; ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="<?php echo 'http://localhost:8888/skill_swap/HomePage/home.php'; ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo 'http://localhost:8888/skill_swap/Register/logout.php'; ?>" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>