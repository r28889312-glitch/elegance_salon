<?php
// includes/sidebar.php
$current_file = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
        <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
        <div class="sidebar-brand-text mx-2">Elegance Salon</div>
    </a>
    <hr class="sidebar-divider my-0">
    
    <li class="nav-item <?php echo ($current_file == 'admin.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="admin.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    
    <li class="nav-item <?php echo ($current_file == 'appointments.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="appointments.php"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'clients.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'staff.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'services.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'inventory.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="inventory.php"><i class="fas fa-fw fa-boxes"></i><span>Inventory</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'payments.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="payments.php"><i class="fas fa-fw fa-money-bill"></i><span>Payments</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'reports.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="reports.php"><i class="fas fa-fw fa-chart-line"></i><span>Reports</span></a>
    </li>
    
    <li class="nav-item <?php echo ($current_file == 'feedback.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="feedback.php"><i class="fas fa-fw fa-comment-dots"></i><span>Feedback</span></a>
    </li>
    
    <hr class="sidebar-divider">
    
    <li class="nav-item <?php echo ($current_file == 'contact.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="contact.php"><i class="fas fa-fw fa-envelope"></i><span>Contact</span></a>
    </li>
    
    <hr class="sidebar-divider">
    
    <li class="nav-item">
        <a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a>
    </li>
    
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>