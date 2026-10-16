<?php

require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_role'] ) || $_SESSION['user_role'] != 'receptionist') {
    header("Location: ../login.php");
    exit();
}
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'receptionist') {
    header("Location: ../login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book'])) {
    $stmt = $pdo->prepare("INSERT INTO appointments (client_id, service_id, stylist_id, appointment_date, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$_POST['client_id'], $_POST['service_id'], $_POST['stylist_id'], $_POST['appointment_date']]);
    header("Location: receptionist.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_client'])) {
    $stmt = $pdo->prepare("INSERT INTO clients (name, phone, email) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email']]);
    header("Location: receptionist.php");
    exit();
}

if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: receptionist.php");
    exit();
}

if(isset($_GET['delete_client'])) {
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$_GET['delete_client']]);
    header("Location: receptionist.php");
    exit();
}

$clients = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
$services = $pdo->query("SELECT * FROM services WHERE is_active = 1")->fetchAll();
$stylists = $pdo->query("SELECT id, full_name FROM users WHERE role = 'stylist' AND is_active = 1")->fetchAll();
$appointments = $pdo->query("SELECT a.*, c.name as client_name, s.service_name, s.price, u.full_name as stylist_name 
    FROM appointments a 
    LEFT JOIN clients c ON a.client_id=c.id 
    LEFT JOIN services s ON a.service_id=s.id 
    LEFT JOIN users u ON a.stylist_id=u.id 
    ORDER BY a.appointment_date DESC, a.id DESC")->fetchAll();

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
    <title>Receptionist Panel - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 12px !important; box-shadow: 0 2px 10px rgba(0,0,0,0.3); }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; }
        .text-gray-800, .text-gray-900, .card-body, .card-header h6 { color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; }
        .btn-primary:hover { background: #5a67d8 !important; transform: translateY(-2px); }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .status-pending { background: #ffc10720; color: #ffc107; border: 1px solid #ffc107; }
        .status-confirmed { background: #17a2b820; color: #17a2b8; border: 1px solid #17a2b8; }
        .status-completed { background: #28a74520; color: #28a745; border: 1px solid #28a745; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="receptionist.php">
            <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
            <div class="sidebar-brand-text mx-2">Elegance Salon</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active"><a class="nav-link" href="receptionist.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('appointments')"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('clients')"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('services')"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a></li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?> (Receptionist)</span>
                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="container-fluid">
                <!-- Dashboard View -->
                <div id="dashboardView">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Receptionist Dashboard</h1>
                        <div>
                            <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#bookModal"><i class="fas fa-plus"></i> New Appointment</button>
                            <button class="btn btn-success shadow-sm ml-2" data-toggle="modal" data-target="#clientModal"><i class="fas fa-user-plus"></i> Add Client</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Appointments</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($appointments); ?></div></div><div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div></div></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Clients</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($clients); ?></div></div><div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div></div></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Appointments</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $todayCount; ?></div></div><div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div></div></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Stylists</div><div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($stylists); ?></div></div><div class="col-auto"><i class="fas fa-user-md fa-2x text-gray-300"></i></div></div></div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-check"></i> Recent Appointments</h6>
                            <a href="#" onclick="showSection('appointments')" class="btn btn-primary btn-sm">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th>Date</th><th>Client</th><th>Service</th><th>Stylist</th><th>Status</th></tr></thead>
                                    <tbody>
                                    <?php foreach(array_slice($appointments, 0, 5) as $a): ?>
                                    <tr>
                                        <td><?php echo $a['appointment_date']; ?></td>
                                        <td><?php echo htmlspecialchars($a['client_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($a['service_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($a['stylist_name'] ?? ''); ?></td>
                                        <td><span class="status-badge status-<?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments View -->
                <div id="appointmentsView" style="display:none;">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> All Appointments</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th>ID</th><th>Date</th><th>Client</th><th>Service</th><th>Stylist</th><th>Status</th><th>Action</th></tr></thead>
                                    <tbody>
                                    <?php foreach($appointments as $a): ?>
                                    <tr>
                                        <td><?php echo $a['id']; ?></td>
                                        <td><?php echo $a['appointment_date']; ?></td>
                                        <td><?php echo htmlspecialchars($a['client_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($a['service_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($a['stylist_name'] ?? ''); ?></td>
                                        <td><span class="status-badge status-<?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
                                        <td><a href="?delete=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clients View -->
                <div id="clientsView" style="display:none;">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> All Clients</h6>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#clientModal">+ Add Client</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Action</th></tr></thead>
                                    <tbody>
                                    <?php foreach($clients as $c): ?>
                                    <tr>
                                        <td><?php echo $c['id']; ?></td>
                                        <td><?php echo htmlspecialchars($c['name']); ?></td>
                                        <td><?php echo htmlspecialchars($c['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                                        <td><a href="?delete_client=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services View -->
                <div id="servicesView" style="display:none;">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-cut"></i> Our Services (USD)</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead><tr><th>ID</th><th>Service Name</th><th>Price (USD)</th><th>Duration</th></tr></thead>
                                    <tbody>
                                    <?php foreach($services as $s): ?>
                                    <tr>
                                        <td><?php echo $s['id']; ?></td>
                                        <td><?php echo htmlspecialchars($s['service_name']); ?></td>
                                        <td><strong class="text-success">$<?php echo number_format($s['price'], 2); ?></strong></td>
                                        <td><?php echo $s['duration']; ?> min</td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2026</div></div></footer>
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal"><div class="modal-dialog"><div class="modal-content bg-dark text-white">
    <div class="modal-header"><h5><i class="fas fa-calendar-plus"></i> Book Appointment</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
    <form method="POST"><div class="modal-body">
        <label>Select Client</label>
        <select name="client_id" class="form-control mb-3" required>
            <option value="">-- Select Client --</option>
            <?php foreach($clients as $c): ?>
            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?> (<?php echo htmlspecialchars($c['phone']); ?>)</option>
            <?php endforeach; ?>
        </select>
        <label>Select Service</label>
        <select name="service_id" class="form-control mb-3" required>
            <option value="">-- Select Service --</option>
            <?php foreach($services as $s): ?>
            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['service_name']); ?> ($<?php echo number_format($s['price'], 2); ?>)</option>
            <?php endforeach; ?>
        </select>
        <label>Select Stylist</label>
        <select name="stylist_id" class="form-control mb-3" required>
            <option value="">-- Select Stylist --</option>
            <?php foreach($stylists as $st): ?>
            <option value="<?php echo $st['id']; ?>"><?php echo htmlspecialchars($st['full_name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Appointment Date</label>
        <input type="date" name="appointment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
    </div><div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="book" class="btn btn-primary"><i class="fas fa-check"></i> Book Appointment</button></div></form>
</div></div></div>

<!-- Client Modal -->
<div class="modal fade" id="clientModal"><div class="modal-dialog"><div class="modal-content bg-dark text-white">
    <div class="modal-header"><h5><i class="fas fa-user-plus"></i> Add New Client</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
    <form method="POST"><div class="modal-body">
        <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
        <input type="text" name="phone" class="form-control mb-3" placeholder="Phone Number" required>
        <input type="email" name="email" class="form-control mb-3" placeholder="Email Address">
    </div><div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="add_client" class="btn btn-success"><i class="fas fa-save"></i> Add Client</button></div></form>
</div></div></div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script>
function showSection(section) {
    document.getElementById('dashboardView').style.display = 'none';
    document.getElementById('appointmentsView').style.display = 'none';
    document.getElementById('clientsView').style.display = 'none';
    document.getElementById('servicesView').style.display = 'none';
    if(section === 'appointments') document.getElementById('appointmentsView').style.display = 'block';
    else if(section === 'clients') document.getElementById('clientsView').style.display = 'block';
    else if(section === 'services') document.getElementById('servicesView').style.display = 'block';
    else document.getElementById('dashboardView').style.display = 'block';
}
</script>
</body>
</html>