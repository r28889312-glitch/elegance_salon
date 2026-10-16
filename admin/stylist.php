<?php
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching so hitting back after logout forces fresh login check
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Stylist access check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'stylist') {
    header("Location: ../login.php");
    exit();
}

$appointments = $pdo->prepare("SELECT a.*, c.name as client_name, c.phone as client_phone, s.service_name, s.price 
    FROM appointments a 
    LEFT JOIN clients c ON a.client_id=c.id 
    LEFT JOIN services s ON a.service_id=s.id 
    WHERE a.stylist_id = ? 
    ORDER BY a.appointment_date DESC");
$appointments->execute([$stylist_id]);
$myApps = $appointments->fetchAll();

$completed = 0;
$totalCommission = 0;
foreach($myApps as $a) {
    if($a['status'] == 'completed') {
        $completed++;
        $totalCommission += $a['price'] * 0.10;
    }
}
$pending = count($myApps) - $completed;
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Stylist Panel - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 12px !important; }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; }
        .text-gray-800, .text-gray-900, .card-body, .card-header h6 { color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; }
        .btn-success { background: #28a745 !important; border: none !important; }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .task-card { background: #2a2a2a; padding: 15px; border-radius: 12px; margin-bottom: 12px; border-left: 4px solid #667eea; }
        .task-time { color: #667eea; font-weight: bold; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .status-completed { background: #28a74520; color: #28a745; border: 1px solid #28a745; }
        .status-pending { background: #ffc10720; color: #ffc107; border: 1px solid #ffc107; }
        .commission-card { background: linear-gradient(135deg, #667eea20, #764ba220); border: 1px solid #667eea; border-radius: 12px; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="stylist.php">
            <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
            <div class="sidebar-brand-text mx-2">Elegance Salon</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active"><a class="nav-link" href="stylist.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>My Dashboard</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('schedule')"><i class="fas fa-fw fa-calendar-alt"></i><span>My Schedule</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('clients')"><i class="fas fa-fw fa-users"></i><span>My Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#" onclick="showSection('earnings')"><i class="fas fa-fw fa-chart-line"></i><span>My Earnings</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a></li>
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
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user_name']; ?> (Stylist)</span>
                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="container-fluid">
                <!-- Dashboard View -->
                <div id="dashboardView">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Stylist Dashboard</h1>
                        <span class="badge bg-primary p-2"><i class="fas fa-calendar"></i> <?php echo date('l, F j, Y'); ?></span>
                    </div>

                    <!-- Stats Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-primary shadow h-100 py-2"><div class="card-body"><div class="row no-gutters"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Appointments</div><div class="h5 mb-0 font-weight-bold"><?php echo count($myApps); ?></div></div><div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div></div></div></div></div>
                        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-success shadow h-100 py-2"><div class="card-body"><div class="row no-gutters"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div><div class="h5 mb-0 font-weight-bold"><?php echo $completed; ?></div></div><div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div></div></div></div></div>
                        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="row no-gutters"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pending</div><div class="h5 mb-0 font-weight-bold"><?php echo $pending; ?></div></div><div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div></div></div></div></div>
                        <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-warning shadow h-100 py-2"><div class="card-body"><div class="row no-gutters"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Commission</div><div class="h5 mb-0 font-weight-bold">$<?php echo number_format($totalCommission, 2); ?></div></div><div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div></div></div></div></div>
                    </div>

                    <!-- Today's Tasks -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks"></i> Today's Tasks</h6></div>
                        <div class="card-body">
                            <?php 
                            $todayTasks = array_filter($myApps, function($a) use ($today) { return $a['appointment_date'] == $today; });
                            if(empty($todayTasks)) echo '<p class="text-muted">No tasks today. Relax! 🎉</p>';
                            foreach($todayTasks as $t): ?>
                            <div class="task-card">
                                <div class="task-time"><?php echo $t['appointment_time'] ?? 'Flexible'; ?></div>
                                <div class="h5 mt-1"><?php echo $t['client_name']; ?></div>
                                <div><?php echo $t['service_name']; ?> - <strong>$<?php echo number_format($t['price'], 2); ?></strong></div>
                                <div class="text-muted small">📞 <?php echo $t['client_phone']; ?></div>
                                <?php if($t['status'] == 'completed'): ?>
                                    <span class="status-badge status-completed mt-2 d-inline-block">✓ Completed</span>
                                <?php else: ?>
                                    <a href="?complete=<?php echo $t['id']; ?>" class="btn btn-sm btn-success mt-2" onclick="return confirm('Mark this task as completed?')"><i class="fas fa-check"></i> Mark Complete</a>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Commission Card -->
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="commission-card p-3">
                                <div class="text-primary">Commission Tracker (10%)</div>
                                <h2 class="mb-0">$<?php echo number_format($totalCommission, 2); ?></h2>
                                <small>Total earned this month</small>
                                <div class="progress mt-2"><div class="progress-bar" style="width: <?php echo min(100, ($totalCommission/500)*100); ?>%"></div></div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card h-100"><div class="card-header"><h6 class="font-weight-bold text-primary">Earnings Progress</h6></div><div class="card-body"><div class="d-flex justify-content-between"><span><?php echo round(($completed/max(1,count($myApps)))*100); ?>% COMPLETED</span><span><?php echo $completed; ?>/<?php echo count($myApps); ?> Tasks</span></div><div class="progress mt-2"><div class="progress-bar" style="width: <?php echo ($completed/max(1,count($myApps)))*100; ?>%"></div></div></div></div>
                        </div>
                    </div>
                </div>

                <!-- Schedule View -->
                <div id="scheduleView" style="display:none;">
                    <div class="card shadow mb-4"><div class="card-header"><h6 class="font-weight-bold text-primary"><i class="fas fa-calendar-alt"></i> My Complete Schedule</h6></div><div class="card-body"><div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Date</th><th>Client</th><th>Service</th><th>Price (USD)</th><th>Status</th><th>Action</th></td></thead><tbody>
                    <?php foreach($myApps as $a): ?>
                    <tr>
                        <td><?php echo $a['appointment_date']; ?></div></div></td>
                        <td><?php echo $a['client_name']; ?></div></div></div></div></td>
                        <td><?php echo $a['service_name']; ?></div></div></div></div></div></div></td>
                        <td><strong>$<?php echo number_format($a['price'], 2); ?></strong></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $a['status']; ?></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php if($a['status'] != 'completed'): ?><a href="?complete=<?php echo $a['id']; ?>" class="btn btn-sm btn-success">Complete</a><?php else: ?>✓<?php endif; ?></div></div></div></div></div></div></div></div></div></div></div></div></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody></table></div></div></div>
                </div>

                <!-- Clients View -->
                <div id="clientsView" style="display:none;">
                    <div class="card shadow mb-4"><div class="card-header"><h6 class="font-weight-bold text-primary"><i class="fas fa-users"></i> My Regular Clients</h6></div><div class="card-body"><div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Client Name</th><th>Phone</th><th>Total Visits</th><th>Last Visit</th></tr></thead><tbody>
                    <?php
                    $clientsMap = [];
                    foreach($myApps as $a) {
                        if(!isset($clientsMap[$a['client_name']])) $clientsMap[$a['client_name']] = ['visits' => 0, 'lastVisit' => $a['appointment_date'], 'phone' => $a['client_phone']];
                        $clientsMap[$a['client_name']]['visits']++;
                        if($a['appointment_date'] > $clientsMap[$a['client_name']]['lastVisit']) $clientsMap[$a['client_name']]['lastVisit'] = $a['appointment_date'];
                    }
                    foreach($clientsMap as $name => $data): ?>
                    <tr>
                        <td><?php echo $name; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $data['phone']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $data['visits']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $data['lastVisit']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody></table></div></div></div>
                </div>

                <!-- Earnings View -->
                <div id="earningsView" style="display:none;">
                    <div class="card shadow mb-4"><div class="card-header"><h6 class="font-weight-bold text-primary"><i class="fas fa-chart-line"></i> Earnings Report (USD)</h6></div><div class="card-body">
                    <div class="row"><div class="col-md-6"><div class="commission-card p-3 text-center"><h4>Total Commission</h4><h2 class="text-success">$<?php echo number_format($totalCommission, 2); ?></h2><small>10% of total revenue</small></div></div>
                    <div class="col-md-6"><div class="card p-3 text-center"><h4>Total Revenue Generated</h4><h2 class="text-primary">$<?php echo number_format($totalCommission * 10, 2); ?></h2></div></div></div>
                    <div class="table-responsive mt-3"><table class="table table-bordered"><thead><tr><th>Date</th><th>Client</th><th>Service</th><th>Amount</th><th>Commission (10%)</th></tr></thead><tbody>
                    <?php foreach($myApps as $a): if($a['status'] == 'completed'): ?>
                    <tr>
                        <td><?php echo $a['appointment_date']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $a['client_name']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><?php echo $a['service_name']; ?></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><strong>$<?php echo number_format($a['price'], 2); ?></strong></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                        <td><strong class="text-success">$<?php echo number_format($a['price'] * 0.10, 2); ?></strong></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></div></td>
                    </tr>
                    <?php endif; endforeach; ?>
                    </tbody></table></div></div></div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script>
function showSection(section) {
    document.getElementById('dashboardView').style.display = 'none';
    document.getElementById('scheduleView').style.display = 'none';
    document.getElementById('clientsView').style.display = 'none';
    document.getElementById('earningsView').style.display = 'none';
    if(section === 'schedule') document.getElementById('scheduleView').style.display = 'block';
    else if(section === 'clients') document.getElementById('clientsView').style.display = 'block';
    else if(section === 'earnings') document.getElementById('earningsView').style.display = 'block';
    else document.getElementById('dashboardView').style.display = 'block';
}
</script>
</body>
</html>