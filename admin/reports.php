<?php
session_start();
   require_once '../config.php';

if($_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Elegance Salon - Reports</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; box-shadow: 0 5px 20px rgba(0,0,0,0.3); }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; border-radius: 15px 15px 0 0 !important; }
        .text-gray-800, .text-gray-900, .h5, .h3, .card-body, .card-header h6, label { color: #fff !important; }
        .bg-white, .topbar { background: #1e1e1e !important; }
        .navbar-nav .nav-link { color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        footer { background: #1e1e1e !important; }
        footer .copyright { color: #aaa !important; }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { color: #fff !important; background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; padding: 8px 20px; }
        .btn-primary:hover { background: #5a67d8 !important; transform: translateY(-2px); }
        .btn-sm { padding: 5px 10px !important; margin: 2px; border-radius: 6px; }
        .chart-box { background: #2a2a2a; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        .progress { background: #333; border-radius: 10px; height: 25px; }
        .progress-bar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; }
        .stat-number { font-size: 32px; font-weight: bold; color: #667eea; }
        .stat-label { color: #aaa; font-size: 14px; }
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
        <li class="nav-item"><a class="nav-link" href="appointments.php"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff</span></a></li>
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php"><i class="fas fa-fw fa-boxes"></i><span>Inventory</span></a></li>
        <li class="nav-item"><a class="nav-link" href="payments.php"><i class="fas fa-fw fa-money-bill"></i><span>Payments</span></a></li>
        <li class="nav-item active"><a class="nav-link" href="reports.php"><i class="fas fa-fw fa-chart-line"></i><span>Reports</span></a></li>
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
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-line"></i> Reports & Analytics</h1>
                    <button class="btn btn-primary shadow-sm" onclick="exportReport()"><i class="fas fa-download"></i> Export Report</button>
                </div>
                
                <!-- Overview Stats -->
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalRevenue">$0</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Appointments</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalAppointments">0</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Clients</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalClients">0</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Completion Rate</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="completionRate">0%</div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-percent fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Popular Services</h6></div>
                            <div class="card-body" id="popularServicesList"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Staff Performance</h6></div>
                            <div class="card-body" id="staffPerformanceList"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Trends -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Monthly Trends</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>Month</th><th>Appointments</th><th>Revenue</th><th>Growth</th></tr></thead>
                                <tbody id="monthlyTrends"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>Date</th><th>Activity</th><th>User</th></tr></thead>
                                <tbody id="recentActivity"></tbody>
                            </table>
                        </div>
                    </div>
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

    let appointments = JSON.parse(localStorage.getItem('elegance_appointments')) || [];
    let clients = JSON.parse(localStorage.getItem('elegance_clients')) || [];
    let services = JSON.parse(localStorage.getItem('elegance_services')) || [];
    let staff = JSON.parse(localStorage.getItem('elegance_staff')) || [];
    
  
    const servicePrices = {
        'Haircut - Women': 50, 'Haircut - Men': 35, 'Manicure': 40,
        'Pedicure': 55, 'Facial - Basic': 60, 'Facial - Premium': 95, 'Hair Coloring': 120
    };
    
    function loadReports() {
        // Calculate total revenue
        let totalRevenue = 0;
        appointments.forEach(apt => {
            let price = servicePrices[apt.service] || 50;
            if(apt.status === 'completed') totalRevenue += price;
        });
        document.getElementById('totalRevenue').innerText = '$' + totalRevenue;
        document.getElementById('totalAppointments').innerText = appointments.length;
        document.getElementById('totalClients').innerText = clients.length;
        
        let completedCount = appointments.filter(apt => apt.status === 'completed').length;
        let completionRate = appointments.length > 0 ? Math.round((completedCount / appointments.length) * 100) : 0;
        document.getElementById('completionRate').innerText = completionRate + '%';
        
        // Popular Services
        let serviceCount = {};
        appointments.forEach(apt => { serviceCount[apt.service] = (serviceCount[apt.service] || 0) + 1; });
        let sortedServices = Object.entries(serviceCount).sort((a,b) => b[1] - a[1]).slice(0,5);
        let popularHtml = '';
        sortedServices.forEach(([name, count]) => {
            let percentage = appointments.length > 0 ? Math.round((count / appointments.length) * 100) : 0;
            popularHtml += `<div class="mb-3"><div class="d-flex justify-content-between"><span>${name}</span><span>${count} bookings</span></div><div class="progress"><div class="progress-bar" style="width: ${percentage}%">${percentage}%</div></div></div>`;
        });
        document.getElementById('popularServicesList').innerHTML = popularHtml || '<p class="text-center">No data available</p>';
        
        // Staff Performance
        let staffCount = {};
        appointments.forEach(apt => { staffCount[apt.stylist] = (staffCount[apt.stylist] || 0) + 1; });
        let staffHtml = '';
        Object.entries(staffCount).forEach(([name, count]) => {
            let percentage = appointments.length > 0 ? Math.round((count / appointments.length) * 100) : 0;
            staffHtml += `<div class="mb-3"><div class="d-flex justify-content-between"><span>${name}</span><span>${count} appointments</span></div><div class="progress"><div class="progress-bar" style="width: ${percentage}%">${percentage}%</div></div></div>`;
        });
        document.getElementById('staffPerformanceList').innerHTML = staffHtml || '<p class="text-center">No data available</p>';
        
     
        let monthlyData = {};
        appointments.forEach(apt => {
            let month = apt.date.substring(0,7);
            if(!monthlyData[month]) monthlyData[month] = { count: 0, revenue: 0 };
            monthlyData[month].count++;
            let price = servicePrices[apt.service] || 50;
            if(apt.status === 'completed') monthlyData[month].revenue += price;
        });
        
        let months = Object.keys(monthlyData).sort().slice(-6).reverse();
        let trendsHtml = '';
        let prevRevenue = 0;
        months.forEach(month => {
            let data = monthlyData[month];
            let growth = prevRevenue ? ((data.revenue - prevRevenue) / prevRevenue * 100).toFixed(1) : 0;
            let growthClass = growth >= 0 ? 'text-success' : 'text-danger';
            trendsHtml += `<tr><td>${month}</td><td>${data.count}</td><td>$${data.revenue}</td><td class="${growthClass}">${growth > 0 ? '+' : ''}${growth}%</td></tr>`;
            prevRevenue = data.revenue;
        });
        document.getElementById('monthlyTrends').innerHTML = trendsHtml || '<tr><td colspan="4" class="text-center">No data available</td></tr>';
        
        // Recent Activity
        let recentActivity = [];
        appointments.slice(-5).reverse().forEach(apt => {
            recentActivity.push(`<tr><td>${apt.date}</td><td>Appointment booked - ${apt.service} with ${apt.stylist}</td><td>${apt.clientName}</td></tr>`);
        });
        document.getElementById('recentActivity').innerHTML = recentActivity.join('') || '<tr><td colspan="3" class="text-center">No recent activity</td></tr>';
    }
    
    function exportReport() {
        let reportData = {
            totalRevenue: document.getElementById('totalRevenue').innerText,
            totalAppointments: document.getElementById('totalAppointments').innerText,
            totalClients: document.getElementById('totalClients').innerText,
            completionRate: document.getElementById('completionRate').innerText,
            appointments: appointments,
            clients: clients,
            generated: new Date().toISOString()
        };
        let dataStr = JSON.stringify(reportData, null, 2);
        let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
        let exportFileDefaultName = 'elegance-salon-report.json';
        let linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
        alert('Report exported successfully!');
    }
    
    loadReports();
    
    // Refresh when data changes
    window.addEventListener('storage', function() { loadReports(); });
</script>
</body>
</html>