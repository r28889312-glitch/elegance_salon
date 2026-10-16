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
    <title>Elegance Salon - Services</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card, .modal-content { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; box-shadow: 0 5px 20px rgba(0,0,0,0.3); }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; border-radius: 15px 15px 0 0 !important; }
        .text-gray-800, .text-gray-900, .h5, .h3, .card-body, .card-header h6, label { color: #fff !important; }
        .bg-white, .topbar { background: #1e1e1e !important; }
        .navbar-nav .nav-link { color: #fff !important; }
        .form-control { background: #2a2a2a !important; border: 1px solid #333 !important; color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        footer { background: #1e1e1e !important; }
        footer .copyright { color: #aaa !important; }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { color: #fff !important; background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; }
        .btn-primary:hover { background: #5a67d8 !important; transform: translateY(-2px); }
        .btn-sm { padding: 5px 10px !important; margin: 2px; border-radius: 6px; }
        .modal-content { border-radius: 15px !important; }
        .close { color: #fff !important; }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php"><div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div><div class="sidebar-brand-text mx-2">Elegance Salon</div></a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="appointments.php"><i class="fas fa-fw fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff</span></a></li>
        <li class="nav-item active"><a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto"><li class="nav-item dropdown no-arrow"><a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"><span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin User</span><img class="img-profile rounded-circle" src="img/undraw_profile.svg" width="32"></a></li></ul>
            </nav>
            
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cut"></i> Services Management</h1>
                    <button class="btn btn-primary shadow-sm" onclick="openServiceModal()"><i class="fas fa-plus"></i> Add Service</button>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Services</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>ID</th><th>Service Name</th><th>Duration</th><th>Price</th><th>Category</th><th>Actions</th></tr></thead>
                                <tbody id="servicesTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<div id="serviceModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="serviceModalTitle">Add Service</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="hidden" id="serviceId">
                <div class="form-group"><label>Service Name</label><input type="text" id="serviceName" class="form-control" required></div>
                <div class="form-group"><label>Duration (minutes)</label><input type="number" id="serviceDuration" class="form-control" required></div>
                <div class="form-group"><label>Price ($)</label><input type="number" id="servicePrice" class="form-control" step="0.01" required></div>
                <div class="form-group"><label>Category</label><select id="serviceCategory" class="form-control"><option>Hair</option><option>Nails</option><option>Facial</option><option>Makeup</option><option>Waxing</option></select></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button class="btn btn-primary" onclick="saveService()">Save</button></div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    let services = JSON.parse(localStorage.getItem('services')) || [
        { id: 1, name: 'Haircut - Women', duration: 60, price: 50, category: 'Hair' },
        { id: 2, name: 'Haircut - Men', duration: 30, price: 35, category: 'Hair' },
        { id: 3, name: 'Manicure', duration: 45, price: 40, category: 'Nails' },
        { id: 4, name: 'Pedicure', duration: 60, price: 55, category: 'Nails' },
        { id: 5, name: 'Facial - Basic', duration: 45, price: 60, category: 'Facial' },
        { id: 6, name: 'Facial - Premium', duration: 75, price: 95, category: 'Facial' }
    ];
    
    function loadServices() {
        let tbody = document.getElementById('servicesTable');
        tbody.innerHTML = '';
        services.forEach((s, index) => {
            tbody.innerHTML += `<tr>
                <td>${index + 1}</td>
                <td>${s.name}</td>
                <td>${s.duration} min</div></td>
                <td>$${s.price}</td>
                <td>${s.category}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewService(${s.id})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-warning" onclick="editService(${s.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteService(${s.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        localStorage.setItem('services', JSON.stringify(services));
    }
    
    function openServiceModal() {
        document.getElementById('serviceModalTitle').innerText = 'Add Service';
        document.getElementById('serviceId').value = '';
        document.getElementById('serviceName').value = '';
        document.getElementById('serviceDuration').value = '';
        document.getElementById('servicePrice').value = '';
        document.getElementById('serviceCategory').value = 'Hair';
        $('#serviceModal').modal('show');
    }
    
    function saveService() {
        let id = document.getElementById('serviceId').value;
        let service = {
            id: id ? parseInt(id) : Date.now(),
            name: document.getElementById('serviceName').value,
            duration: parseInt(document.getElementById('serviceDuration').value),
            price: parseFloat(document.getElementById('servicePrice').value),
            category: document.getElementById('serviceCategory').value
        };
        
        if (id) {
            let index = services.findIndex(s => s.id == id);
            if (index !== -1) services[index] = service;
            alert('Service updated successfully!');
        } else {
            services.push(service);
            alert('Service added successfully!');
        }
        
        loadServices();
        $('#serviceModal').modal('hide');
    }
    
    function viewService(id) {
        let s = services.find(s => s.id === id);
        if (s) alert(`Service: ${s.name}\nDuration: ${s.duration} min\nPrice: $${s.price}\nCategory: ${s.category}`);
    }
    
    function editService(id) {
        let s = services.find(s => s.id === id);
        if (s) {
            document.getElementById('serviceModalTitle').innerText = 'Edit Service';
            document.getElementById('serviceId').value = s.id;
            document.getElementById('serviceName').value = s.name;
            document.getElementById('serviceDuration').value = s.duration;
            document.getElementById('servicePrice').value = s.price;
            document.getElementById('serviceCategory').value = s.category;
            $('#serviceModal').modal('show');
        }
    }
    
    function deleteService(id) {
        if (confirm('Delete this service?')) {
            services = services.filter(s => s.id !== id);
            loadServices();
            alert('Service deleted successfully!');
        }
    }
    
    loadServices();
</script>
</body>
</html>