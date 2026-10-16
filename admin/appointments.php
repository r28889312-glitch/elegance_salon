<?php

require_once '../config.php';

if($_SESSION['user_role'] != 'admin') {
  header("Location: ../login.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_appointment'])) {
    $stmt = $pdo->prepare("INSERT INTO appointments (client_id, service_id, stylist_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['client_id'], $_POST['service_id'], $_POST['stylist_id'], $_POST['appointment_date'], $_POST['appointment_time'], $_POST['status']]);
    header("Location: appointments.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
    $stmt = $pdo->prepare("UPDATE appointments SET client_id = ?, service_id = ?, stylist_id = ?, appointment_date = ?, appointment_time = ?, status = ? WHERE id = ?");
    $stmt->execute([$_POST['client_id'], $_POST['service_id'], $_POST['stylist_id'], $_POST['appointment_date'], $_POST['appointment_time'], $_POST['status'], $_POST['appointment_id']]);
    header("Location: appointments.php");
    exit();
}


if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: appointments.php");
    exit();
}


$clients = $pdo->query("SELECT * FROM clients ORDER BY name")->fetchAll();
$services = $pdo->query("SELECT * FROM services WHERE is_active = 1")->fetchAll();
$stylists = $pdo->query("SELECT * FROM users WHERE role = 'stylist' AND is_active = 1")->fetchAll();


$appointments = $pdo->query("
    SELECT a.*, c.name as client_name, s.service_name, s.price, u.full_name as stylist_name 
    FROM appointments a 
    LEFT JOIN clients c ON a.client_id = c.id 
    LEFT JOIN services s ON a.service_id = s.id 
    LEFT JOIN users u ON a.stylist_id = u.id 
    ORDER BY a.appointment_date ASC, a.id ASC
")->fetchAll();

$todayCount = 0;
foreach($appointments as $a) {
    if($a['appointment_date'] == date('Y-m-d')) $todayCount++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Appointments - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
   <style>
   
    body { background: #0f0f0f !important; }
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
    .card { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; }
    .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; }
    .text-gray-800, .text-gray-900, .h5, .h3, .card-body, .card-header h6, label { color: #fff !important; }
    .table { color: #fff !important; }
    .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
    .sidebar .nav-link { color: #ddd !important; }
    .sidebar .nav-link:hover { background: #667eea20 !important; border-radius: 10px; }
    .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; }
    .nav-item.active { background: #667eea; border-radius: 10px; }
    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
    .status-pending { background: #ffc10720; color: #ffc107; border: 1px solid #ffc107; }
    .status-confirmed { background: #17a2b820; color: #17a2b8; border: 1px solid #17a2b8; }
    .status-completed { background: #28a74520; color: #28a745; border: 1px solid #28a745; }
    .status-cancelled { background: #dc354520; color: #dc3545; border: 1px solid #dc3545; }


    .bg-white, .topbar, .navbar {
        background: #1e1e1e !important;
    }
    
    .navbar-nav .nav-link {
        color: #fff !important;
    }
    
    footer, .sticky-footer {
        background: #1e1e1e !important;
    }
    
    footer .copyright {
        color: #aaa !important;
    }
</style>
</head>
<body id="page-top">

<div id="wrapper">
    
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
            <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
            <div class="sidebar-brand-text mx-2">Elegance Salon</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item active"><a class="nav-link" href="appointments.php"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff</span></a></li>
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
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user_name']; ?></span>
                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calendar-check"></i> Appointments Management</h1>
                    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addAppointmentModal"><i class="fas fa-plus"></i> New Appointment</button>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-primary shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Appointments</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($appointments); ?></div></div><div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-gray-300"></i></div></div></div></div></div>
                    <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Appointments</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todayCount; ?></div></div><div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div></div></div></div></div>
                    <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count(array_filter($appointments, fn($a) => $a['status'] == 'completed')); ?></div></div><div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div></div></div></div></div>
                    <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-warning shadow h-100 py-2"><div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count(array_filter($appointments, fn($a) => $a['status'] == 'pending')); ?></div></div><div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div></div></div></div></div>
                </div>

                <!-- Appointments Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> All Appointments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th>ID</th><th>Date</th><th>Time</th><th>Client</th><th>Service</th><th>Stylist</th><th>Price</th><th>Status</th><th>Actions</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($appointments as $a): ?>
                                    <tr>
                                        <td><?php echo $a['id']; ?></td>
                                        <td><?php echo $a['appointment_date']; ?></td>
                                        <td><?php echo $a['appointment_time'] ?? '--:--'; ?></td>
                                        <td><?php echo $a['client_name']; ?></td>
                                        <td><?php echo $a['service_name']; ?></td>
                                        <td><?php echo $a['stylist_name']; ?></td>
                                        <td>$<?php echo number_format($a['price'], 2); ?></td>
                                        <td><span class="status-badge status-<?php echo $a['status']; ?>"><?php echo ucfirst($a['status']); ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAppointmentModal" onclick="editAppointment(<?php echo htmlspecialchars(json_encode($a)); ?>)"><i class="fas fa-edit"></i></button>
                                            <a href="?delete=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this appointment?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="copyright text-center">Copyright &copy; Elegance Salon 2024</div></footer>
    </div>
</div>

<!-- Add Appointment Modal -->
<div class="modal fade" id="addAppointmentModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header bg-primary"><h5>Book Appointment</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST">
                <div class="modal-body">
                    <select name="client_id" class="form-control mb-2" required>
                        <option value="">Select Client</option>
                        <?php foreach($clients as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?> (<?php echo $c['phone']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <select name="service_id" class="form-control mb-2" required>
                        <option value="">Select Service</option>
                        <?php foreach($services as $s): ?>
                        <option value="<?php echo $s['id']; ?>"><?php echo $s['service_name']; ?> - $<?php echo $s['price']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="stylist_id" class="form-control mb-2" required>
                        <option value="">Select Stylist</option>
                        <?php foreach($stylists as $st): ?>
                        <option value="<?php echo $st['id']; ?>"><?php echo $st['full_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="appointment_date" class="form-control mb-2" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="time" name="appointment_time" class="form-control mb-2" value="10:00">
                    <select name="status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="add_appointment" class="btn btn-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header bg-warning"><h5>Edit Appointment</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="edit_id">
                    <select name="client_id" id="edit_client_id" class="form-control mb-2" required>
                        <?php foreach($clients as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="service_id" id="edit_service_id" class="form-control mb-2" required>
                        <?php foreach($services as $s): ?>
                        <option value="<?php echo $s['id']; ?>"><?php echo $s['service_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="stylist_id" id="edit_stylist_id" class="form-control mb-2" required>
                        <?php foreach($stylists as $st): ?>
                        <option value="<?php echo $st['id']; ?>"><?php echo $st['full_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="appointment_date" id="edit_date" class="form-control mb-2" required>
                    <input type="time" name="appointment_time" id="edit_time" class="form-control mb-2">
                    <select name="status" id="edit_status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="update_appointment" class="btn btn-primary">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
function editAppointment(app) {
    document.getElementById('edit_id').value = app.id;
    document.getElementById('edit_client_id').value = app.client_id;
    document.getElementById('edit_service_id').value = app.service_id;
    document.getElementById('edit_stylist_id').value = app.stylist_id;
    document.getElementById('edit_date').value = app.appointment_date;
    document.getElementById('edit_time').value = app.appointment_time || '10:00';
    document.getElementById('edit_status').value = app.status;
}
</script>
</body>
</html>