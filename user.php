<?php
session_start();
require_once 'config.php';

// Check if customer is logged in
if(!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Get customer details
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$customer = $stmt->fetch();

// Get customer's appointments
$appointments = $pdo->prepare("
    SELECT a.*, s.service_name, s.price, s.duration, u.full_name as stylist_name 
    FROM appointments a 
    LEFT JOIN services s ON a.service_id = s.id 
    LEFT JOIN users u ON a.stylist_id = u.id 
    WHERE a.client_id = ? 
    ORDER BY a.appointment_date DESC
");
$appointments->execute([$client_id]);
$myAppointments = $appointments->fetchAll();

$totalAppointments = count($myAppointments);
$completedApps = count(array_filter($myAppointments, fn($a) => $a['status'] == 'completed'));
$pendingApps = count(array_filter($myAppointments, fn($a) => $a['status'] == 'pending'));
$upcomingApps = count(array_filter($myAppointments, fn($a) => $a['appointment_date'] >= date('Y-m-d') && $a['status'] != 'completed'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Dashboard - Elegance Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: linear-gradient(135deg, #667eea, #764ba2) !important; padding: 15px 0; }
        .navbar-brand, .nav-link { color: white !important; }
        .card { border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; }
        .stat-card { background: white; border-radius: 15px; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-card i { font-size: 40px; margin-bottom: 10px; }
        .table { background: white; border-radius: 15px; overflow: hidden; }
        .badge-pending { background: #ffc107; color: #333; padding: 5px 12px; border-radius: 20px; }
        .badge-confirmed { background: #17a2b8; color: white; padding: 5px 12px; border-radius: 20px; }
        .badge-completed { background: #28a745; color: white; padding: 5px 12px; border-radius: 20px; }
        .badge-cancelled { background: #dc3545; color: white; padding: 5px 12px; border-radius: 20px; }
        .sidebar-menu { background: white; border-radius: 15px; padding: 20px; }
        .sidebar-menu a { display: block; padding: 10px 15px; color: #333; text-decoration: none; border-radius: 10px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .sidebar-menu i { width: 25px; margin-right: 10px; }
        .welcome-text { font-size: 14px; color: #666; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-spa"></i> Elegance Salon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="appointment.php">Book Appointment</a></li>
                <li class="nav-item"><a class="nav-link active" href="customer_dashboard.php">My Account</a></li>
                <li class="nav-item"><a class="nav-link" href="customer_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="sidebar-menu">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-4x" style="color: #667eea;"></i>
                    <h5 class="mt-2"><?php echo htmlspecialchars($customer['name']); ?></h5>
                    <p class="welcome-text"><?php echo htmlspecialchars($customer['email']); ?></p>
                </div>
                <hr>
                <a href="customer_dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                
                           
                <a href="profile.php">
                    <i class="fas fa-user-edit"></i> Profile Settings
                </a>
                <hr>
                <a href="customer_logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Welcome Card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-greeting"></i> Welcome, <?php echo htmlspecialchars($customer['name']); ?>!</h4>
                </div>
                <div class="card-body">
                    <p>Here's an overview of your appointments and account activity.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check text-primary"></i>
                        <h3><?php echo $totalAppointments; ?></h3>
                        <p class="text-muted mb-0">Total Appointments</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-check-circle text-success"></i>
                        <h3><?php echo $completedApps; ?></h3>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-clock text-warning"></i>
                        <h3><?php echo $pendingApps; ?></h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <i class="fas fa-calendar-week text-info"></i>
                        <h3><?php echo $upcomingApps; ?></h3>
                        <p class="text-muted mb-0">Upcoming</p>
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Recent Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Service</th>
                                    <th>Stylist</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($myAppointments)): ?>
                                    <tr><td colspan="7" class="text-center">No appointments yet. <a href="appointment.php">Book your first appointment</a></td></tr>
                                <?php else: ?>
                                    <?php foreach(array_slice($myAppointments, 0, 5) as $a): ?>
                                        <tr>
                                            <td><?php echo date('d M Y', strtotime($a['appointment_date'])); ?></td>
                                            <td><?php echo $a['appointment_time'] ?? '--:--'; ?></div></td>
                                            <td><?php echo $a['service_name']; ?></div></td>
                                            <td><?php echo $a['stylist_name']; ?></div></td>
                                            <td>$<?php echo number_format($a['price'] ?? 0, 2); ?></div></td>
                                            <td>
                                                <?php if($a['status'] == 'pending'): ?>
                                                    <span class="badge-pending">Pending</span>
                                                <?php elseif($a['status'] == 'confirmed'): ?>
                                                    <span class="badge-confirmed">Confirmed</span>
                                                <?php elseif($a['status'] == 'completed'): ?>
                                                    <span class="badge-completed">Completed</span>
                                                <?php else: ?>
                                                    <span class="badge-cancelled">Cancelled</span>
                                                <?php endif; ?>
                                             </div>
                                            <td>
                                                <?php if($a['status'] == 'pending' || $a['status'] == 'confirmed'): ?>
                                                    <a href="cancel_appointment.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                             </div>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(count($myAppointments) > 5): ?>
                        <div class="text-center mt-3">
                            <a href="myappointments.php" class="btn btn-primary btn-sm">View All Appointments</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>