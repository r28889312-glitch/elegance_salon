<?php
session_start();
require_once '../config.php';


if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
header("Location: ../login.php");
    exit();
}



$totalClients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$totalAppointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$totalStaff = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('receptionist', 'stylist')")->fetchColumn();
$todayAppointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE()")->fetchColumn();


$recentApps = $pdo->query("
    SELECT a.*, c.name as client_name, s.service_name 
    FROM appointments a 
    LEFT JOIN clients c ON a.client_id = c.id 
    LEFT JOIN services s ON a.service_id = s.id 
    ORDER BY a.id DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; box-shadow: 0 5px 20px rgba(0,0,0,0.3); }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; border-radius: 15px 15px 0 0 !important; }
        .text-gray-800, .text-gray-900, .h5, .h3, .card-body, .card-header h6 { color: #fff !important; }
        .text-gray-300 { color: #aaa !important; }
        .bg-white, .topbar { background: #1e1e1e !important; }
        .navbar-nav .nav-link { color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        footer { background: #1e1e1e !important; }
        footer .copyright { color: #aaa !important; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { color: #fff !important; background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; }
        .btn-primary:hover { background: #5a67d8 !important; transform: translateY(-2px); }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; }
        .status-pending { background: #ffc10720; color: #ffc107; border: 1px solid #ffc107; }
        .status-confirmed { background: #17a2b820; color: #17a2b8; border: 1px solid #17a2b8; }
        .status-completed { background: #28a74520; color: #28a745; border: 1px solid #28a745; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    
    <!-- Sidebar - SAME AS APPOINTMENTS PAGE -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
            <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
            <div class="sidebar-brand-text mx-2">Elegance Salon</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active"><a class="nav-link" href="admin.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider">
      
        <li class="nav-item"><a class="nav-link" href="appointments.php"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
          <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff </span></a></li>
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php"><i class="fas fa-fw fa-boxes"></i><span>Inventory</span></a></li>
        <li class="nav-item"><a class="nav-link" href="payments.php"><i class="fas fa-fw fa-money-bill"></i><span>Payments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-fw fa-chart-line"></i><span>Reports</span></a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="fas fa-fw fa-comment-dots"></i><span>Feedback</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-fw fa-envelope"></i><span>Contact</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a></li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg" width="32">
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    <a href="reports.php" class="btn btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>
                
                <!-- Stats Cards -->
                <div class="row">
                    <!-- Total Clients -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Clients</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalClients; ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Appointments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Appointments</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalAppointments; ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Today's Appointments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Appointments</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todayAppointments; ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Staff Members -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Staff Members</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalStaff; ?></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-md fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Appointments Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-check"></i> Recent Appointments</h6>
                        <a href="appointments.php" class="btn btn-primary btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($recentApps)): ?>
                                        <tr><td colspan="4" class="text-center">No appointments found</td></tr>
                                    <?php else: ?>
                                        <?php foreach($recentApps as $app): ?>
                                        <tr>
                                            <td><?php echo $app['appointment_date']; ?></td>
                                            <td><?php echo $app['client_name']; ?></td>
                                            <td><?php echo $app['service_name']; ?></td>
                                            <td><span class="status-badge status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Contact & Feedback -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-envelope"></i> Contact Us</h6></div>
                            <div class="card-body">
                                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> support@elegancesalon.com</p>
                                <p><i class="fas fa-phone"></i> <strong>Phone:</strong> +91 98765 43210</p>
                                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> Mumbai, India</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-comment"></i> Submit Feedback</h6></div>
                            <div class="card-body">
                                <form id="feedbackForm">
                                    <input type="text" class="form-control mb-2" id="feedbackName" placeholder="Your Name">
                                    <textarea class="form-control mb-2" id="feedbackMessage" rows="2" placeholder="Your Feedback..."></textarea>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Submit Feedback</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div>
            </div>
        </footer>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    $('#feedbackForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#feedbackName').val();
        var message = $('#feedbackMessage').val();
        
        if(name && message) {
            $.post('api/add_feedback.php', {name: name, message: message}, function(res) {
                alert('Thank you for your feedback!');
                $('#feedbackName').val('');
                $('#feedbackMessage').val('');
            });
        } else {
            alert('Please fill all fields');
        }
    });
</script>
</body>
</html>