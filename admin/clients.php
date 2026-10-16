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
    <title>Elegance Salon - Clients</title>
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
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; padding: 8px 20px; }
        .btn-primary:hover { background: #5a67d8 !important; transform: translateY(-2px); }
        .btn-sm { padding: 5px 10px !important; margin: 2px; border-radius: 6px; }
        .modal-content { border-radius: 15px !important; }
        .close { color: #fff !important; }
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
        <li class="nav-item active"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
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
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users"></i> Clients Management</h1>
                    <button class="btn btn-primary shadow-sm" onclick="openClientModal()"><i class="fas fa-plus"></i> Add Client</button>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Clients</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Total Visits</th><th>Actions</th></tr>
                                </thead>
                                <tbody id="clientsTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<!-- Client Modal -->
<div id="clientModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalTitle">Add Client</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="clientId">
                <div class="form-group"><label>Name</label><input type="text" id="clientName" class="form-control" required></div>
                <div class="form-group"><label>Email</label><input type="email" id="clientEmail" class="form-control"></div>
                <div class="form-group"><label>Phone</label><input type="text" id="clientPhone" class="form-control" required></div>
                <div class="form-group"><label>Address</label><textarea id="clientAddress" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="saveClient()">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    let clients = JSON.parse(localStorage.getItem('clients')) || [
        { id: 1, name: 'Emma Wilson', email: 'emma@email.com', phone: '+91 98765 43210', address: 'Mumbai', visits: 5 },
        { id: 2, name: 'James Brown', email: 'james@email.com', phone: '+91 98765 43211', address: 'Mumbai', visits: 3 },
        { id: 3, name: 'Sophia Davis', email: 'sophia@email.com', phone: '+91 98765 43212', address: 'Mumbai', visits: 8 }
    ];
    
    function loadClients() {
        let tbody = document.getElementById('clientsTable');
        tbody.innerHTML = '';
        clients.forEach((c, index) => {
            tbody.innerHTML += `<tr>
                <td>${index + 1}</td>
                <td>${c.name}</td>
                <td>${c.email}</td>
                <td>${c.phone}</td>
                <td>${c.address}</td>
                <td>${c.visits}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewClient(${c.id})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-warning" onclick="editClient(${c.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteClient(${c.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        localStorage.setItem('clients', JSON.stringify(clients));
    }
    
    function openClientModal() {
        document.getElementById('clientModalTitle').innerText = 'Add Client';
        document.getElementById('clientId').value = '';
        document.getElementById('clientName').value = '';
        document.getElementById('clientEmail').value = '';
        document.getElementById('clientPhone').value = '';
        document.getElementById('clientAddress').value = '';
        $('#clientModal').modal('show');
    }
    
    function saveClient() {
        let id = document.getElementById('clientId').value;
        let client = {
            id: id ? parseInt(id) : Date.now(),
            name: document.getElementById('clientName').value,
            email: document.getElementById('clientEmail').value,
            phone: document.getElementById('clientPhone').value,
            address: document.getElementById('clientAddress').value,
            visits: 0
        };
        
        if (id) {
            let index = clients.findIndex(c => c.id == id);
            if (index !== -1) clients[index] = client;
            alert('Client updated successfully!');
        } else {
            clients.push(client);
            alert('Client added successfully!');
        }
        
        loadClients();
        $('#clientModal').modal('hide');
    }
    
    function viewClient(id) {
        let c = clients.find(c => c.id === id);
        if (c) {
            alert(`Name: ${c.name}\nEmail: ${c.email}\nPhone: ${c.phone}\nAddress: ${c.address}\nTotal Visits: ${c.visits}`);
        }
    }
    
    function editClient(id) {
        let c = clients.find(c => c.id === id);
        if (c) {
            document.getElementById('clientModalTitle').innerText = 'Edit Client';
            document.getElementById('clientId').value = c.id;
            document.getElementById('clientName').value = c.name;
            document.getElementById('clientEmail').value = c.email;
            document.getElementById('clientPhone').value = c.phone;
            document.getElementById('clientAddress').value = c.address;
            $('#clientModal').modal('show');
        }
    }
    
    function deleteClient(id) {
        if (confirm('Are you sure you want to delete this client?')) {
            clients = clients.filter(c => c.id !== id);
            loadClients();
            alert('Client deleted successfully!');
        }
    }
    
    loadClients();
</script>
</body>
</html>