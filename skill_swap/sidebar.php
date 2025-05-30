<!-- Sidebar -->
<div class="sidebar" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
    <div class="sidebar-header">
        <h5 class="mb-0">Menu</h5>
        <button class="btn btn-link text-dark text-decoration-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="sidebar-content">
        <div class="user-info mb-4">
            <div class="d-flex align-items-start">
                <div class="user-avatar me-3">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
                <div>
                    <h6 class="mb-0 user-name">User Name</h6>
                    <a href="#" class="text-muted small text-decoration-none" data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a>
                </div>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo 'http://localhost:8888/skill_swap/HomePage/home.php'; ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marketplace.html">
                    <i class="bi bi-shop me-2"></i>
                    Marketplace
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Skill
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#findMentorModal">
                    <i class="bi bi-people me-2"></i>
                    Find Mentor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="bi bi-person me-2"></i>
                    Profile Settings
                </a>
            </li>
        </ul>
    </div>
</div>