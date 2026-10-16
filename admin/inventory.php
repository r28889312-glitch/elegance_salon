<?php
session_start();require_once '../config.php';

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
    <title>Elegance Salon - Inventory</title>
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
        .low-stock { color: #ffc107; font-weight: bold; }
        .out-stock { color: #dc3545; font-weight: bold; }
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
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
        <li class="nav-item active"><a class="nav-link" href="inventory.php"><i class="fas fa-fw fa-boxes"></i><span>Inventory</span></a></li>
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
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-boxes"></i> Inventory Management</h1>
                    <button class="btn btn-primary shadow-sm" onclick="openInventoryModal()"><i class="fas fa-plus"></i> Add Product</button>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Products</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>ID</th><th>Product Name</th><th>Category</th><th>Quantity</th><th>Unit Price</th><th>Status</th><th>Actions</th></tr></thead>
                                <tbody id="inventoryTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<div id="inventoryModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="inventoryModalTitle">Add Product</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="hidden" id="productId">
                <div class="form-group"><label>Product Name</label><input type="text" id="productName" class="form-control" required></div>
                <div class="form-group"><label>Category</label><select id="productCategory" class="form-control"><option>Hair Products</option><option>Nail Products</option><option>Facial Products</option><option>Makeup</option><option>Waxing</option></select></div>
                <div class="form-group"><label>Quantity</label><input type="number" id="productQuantity" class="form-control" required></div>
                <div class="form-group"><label>Unit Price ($)</label><input type="number" id="productPrice" class="form-control" step="0.01" required></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button class="btn btn-primary" onclick="saveProduct()">Save</button></div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    let inventory = JSON.parse(localStorage.getItem('inventory')) || [
        { id: 1, name: 'Professional Shampoo', category: 'Hair Products', quantity: 25, price: 15.99 },
        { id: 2, name: 'Hair Color - Brown', category: 'Hair Products', quantity: 8, price: 12.50 },
        { id: 3, name: 'Nail Polish Kit', category: 'Nail Products', quantity: 15, price: 25.00 }
    ];
    
    function loadInventory() {
        let tbody = document.getElementById('inventoryTable');
        tbody.innerHTML = '';
        inventory.forEach((p, index) => {
            let status = p.quantity <= 5 ? '<span class="low-stock">Low Stock!</span>' : (p.quantity <= 0 ? '<span class="out-stock">Out of Stock!</span>' : '<span class="text-success">In Stock</span>');
            tbody.innerHTML += `<tr>
                <td>${index + 1}</td>
                <td>${p.name}</td>
                <td>${p.category}</td>
                <td>${p.quantity}</div></td>
                <td>$${p.price}</div></td>
                <td>${status}</div></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewProduct(${p.id})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-warning" onclick="editProduct(${p.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        localStorage.setItem('inventory', JSON.stringify(inventory));
    }
    
    function openInventoryModal() {
        document.getElementById('inventoryModalTitle').innerText = 'Add Product';
        document.getElementById('productId').value = '';
        document.getElementById('productName').value = '';
        document.getElementById('productCategory').value = 'Hair Products';
        document.getElementById('productQuantity').value = '';
        document.getElementById('productPrice').value = '';
        $('#inventoryModal').modal('show');
    }
    
    function saveProduct() {
        let id = document.getElementById('productId').value;
        let product = {
            id: id ? parseInt(id) : Date.now(),
            name: document.getElementById('productName').value,
            category: document.getElementById('productCategory').value,
            quantity: parseInt(document.getElementById('productQuantity').value),
            price: parseFloat(document.getElementById('productPrice').value)
        };
        
        if (id) {
            let index = inventory.findIndex(p => p.id == id);
            if (index !== -1) inventory[index] = product;
            alert('Product updated successfully!');
        } else {
            inventory.push(product);
            alert('Product added successfully!');
        }
        
        loadInventory();
        $('#inventoryModal').modal('hide');
    }
    
    function viewProduct(id) {
        let p = inventory.find(p => p.id === id);
        if (p) alert(`Product: ${p.name}\nCategory: ${p.category}\nQuantity: ${p.quantity}\nPrice: $${p.price}`);
    }
    
    function editProduct(id) {
        let p = inventory.find(p => p.id === id);
        if (p) {
            document.getElementById('inventoryModalTitle').innerText = 'Edit Product';
            document.getElementById('productId').value = p.id;
            document.getElementById('productName').value = p.name;
            document.getElementById('productCategory').value = p.category;
            document.getElementById('productQuantity').value = p.quantity;
            document.getElementById('productPrice').value = p.price;
            $('#inventoryModal').modal('show');
        }
    }
    
    function deleteProduct(id) {
        if (confirm('Delete this product?')) {
            inventory = inventory.filter(p => p.id !== id);
            loadInventory();
            alert('Product deleted successfully!');
        }
    }
    
    loadInventory();
</script>
</body>
</html>