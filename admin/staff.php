<?php
session_start();
require_once '../config.php';

// 🔒 SECURITY CHECK
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// 🔧 FIX - Define variables to avoid warnings
$success = '';
$error = '';

// Rest of your code...

// ADD STAFF
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    
    if($check->rowCount() > 0) {
        $error = "Username or email already exists!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, role, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
        if($stmt->execute([$username, $email, $password, $full_name, $phone, $role])) {
            $success = "Staff added successfully!";
            header("Location: staff.php");
            exit();
        }
    }
}

// UPDATE STAFF
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_staff'])) {
    $id = $_POST['staff_id'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, role = ?, is_active = ? WHERE id = ?");
    $stmt->execute([$full_name, $phone, $role, $is_active, $id]);
    $success = "Staff updated successfully!";
    header("Location: staff.php");
    exit();
}

// DELETE STAFF
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if($id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: staff.php");
        exit();
    } else {
        $error = "You cannot delete your own account!";
    }
}

// TOGGLE STATUS
if(isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: staff.php");
    exit();
}

// GET ALL STAFF
$staff = $pdo->query("SELECT * FROM users WHERE role IN ('receptionist', 'stylist') ORDER BY id ASC")->fetchAll();

$editStaff = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editStaff = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Staff Management - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 12px !important; }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; }
        .text-gray-800, .card-body, .card-header h6, label { color: #fff !important; }
        .table, .table th, .table td { color: #fff !important; border-color: #333 !important; }
        .form-control { background: #2a2a2a !important; border: 1px solid #333 !important; color: #fff !important; }
        .btn-primary { background: #667eea !important; border: none !important; }
        .role-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .role-receptionist { background: #17a2b820; color: #17a2b8; border: 1px solid #17a2b8; }
        .role-stylist { background: #28a74520; color: #28a745; border: 1px solid #28a745; }
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }

bg-white, .topbar, .navbar {
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
    

    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        
        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
            <div class="sidebar-brand-icon"><i class="fas fa-spa"></i></div>
            <div class="sidebar-brand-text mx-2">Elegance Salon</div>
        </a>
        
        <hr class="sidebar-divider my-0">
        
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <hr class="sidebar-divider">
        
        <!-- Appointments -->
        <li class="nav-item">
            <a class="nav-link" href="appointments.php">
                <i class="fas fa-fw fa-calendar-check"></i>
                <span>Appointments</span>
            </a>
        </li>
        
        <!-- Clients -->
        <li class="nav-item">
            <a class="nav-link" href="clients.php">
                <i class="fas fa-fw fa-users"></i>
                <span>Clients</span>
            </a>
        </li>
        
        <!-- Staff Management (ACTIVE) -->
        <li class="nav-item active">
            <a class="nav-link" href="staff.php">
                <i class="fas fa-fw fa-user-md"></i>
                <span>Staff</span>
            </a>
        </li>
        
        <!-- Services -->
        <li class="nav-item">
            <a class="nav-link" href="services.php">
                <i class="fas fa-fw fa-cut"></i>
                <span>Services</span>
            </a>
        </li>
        
        <!-- Inventory -->
        <li class="nav-item">
            <a class="nav-link" href="inventory.php">
                <i class="fas fa-fw fa-boxes"></i>
                <span>Inventory</span>
            </a>
        </li>
        
        <!-- Payments -->
        <li class="nav-item">
            <a class="nav-link" href="payments.php">
                <i class="fas fa-fw fa-money-bill"></i>
                <span>Payments</span>
            </a>
        </li>
        
        <!-- Reports -->
        <li class="nav-item">
            <a class="nav-link" href="reports.php">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Reports</span>
            </a>
        </li>
        
        <!-- Feedback -->
        <li class="nav-item">
            <a class="nav-link" href="feedback.php">
                <i class="fas fa-fw fa-comment-dots"></i>
                <span>Feedback</span>
            </a>
        </li>
        
        <hr class="sidebar-divider">
        
        <!-- Contact -->
        <li class="nav-item">
            <a class="nav-link" href="contact.php">
                <i class="fas fa-fw fa-envelope"></i>
                <span>Contact</span>
            </a>
        </li>
        
        <hr class="sidebar-divider d-none d-md-block">
             
        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
        
  <!-- Sidebar Toggle -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        

    </ul>
    <!-- ============================================ -->
    <!-- END SIDEBAR -->
    <!-- ============================================ -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['user_name']; ?> (Admin)</span>
                            <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-md"></i> Staff Management</h1>
                    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addStaffModal">
                        <i class="fas fa-plus"></i> Add New Staff
                    </button>
                </div>
                
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Stats -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card p-3 text-center">
                            <h6>Total Receptionists</h6>
                            <h2><?php 
                                $recCount = 0;
                                foreach($staff as $s) { if($s['role'] == 'receptionist') $recCount++; }
                                echo $recCount;
                            ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card p-3 text-center">
                            <h6>Total Stylists</h6>
                            <h2><?php 
                                $styCount = 0;
                                foreach($staff as $s) { if($s['role'] == 'stylist') $styCount++; }
                                echo $styCount;
                            ?></h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card p-3 text-center">
                            <h6>Total Staff</h6>
                            <h2><?php echo count($staff); ?></h2>
                        </div>
                    </div>
                </div>
                
                <!-- Staff Table -->
                <div class="card">
                    <div class="card-header">All Staff Members</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($staff as $s): ?>
                                    <tr>
                                        <td><?php echo $s['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($s['username']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($s['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                                        <td><?php echo $s['phone'] ?? '-'; ?></td>
                                        <td><span class="role-badge role-<?php echo $s['role']; ?>"><?php echo ucfirst($s['role']); ?></span></td>
                                        <td><?php echo $s['is_active'] ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Inactive</span>'; ?></td>
                                        <td>
                                            <a href="?edit=<?php echo $s['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <a href="?toggle=<?php echo $s['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-power-off"></i></a>
                                            <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($staff)): ?>
                                    <tr><td colspan="8" class="text-center">No staff found</td></tr>
                                    <?php endif; ?>
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

<!-- ADD MODAL -->
<div class="modal fade" id="addStaffModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header bg-primary"><h5><i class="fas fa-user-plus"></i> Add Staff</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" name="username" class="form-control mb-2" placeholder="Username *" required>
                    <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name *" required>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email *" required>
                    <input type="text" name="phone" class="form-control mb-2" placeholder="Phone">
                    <select name="role" class="form-control mb-2" required>
                        <option value="receptionist">📞 Receptionist</option>
                        <option value="stylist">✂️ Stylist</option>
                    </select>
                    <input type="password" name="password" class="form-control mb-2" placeholder="Password *" value="123456" required>
                    <small class="text-muted">Default password: 123456</small>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="add_staff" class="btn btn-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editStaffModal">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header bg-warning"><h5><i class="fas fa-user-edit"></i> Edit Staff</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="staff_id" id="edit_id">
                    <input type="text" name="full_name" id="edit_fullname" class="form-control mb-2" placeholder="Full Name" required>
                    <input type="text" name="phone" id="edit_phone" class="form-control mb-2" placeholder="Phone">
                    <select name="role" id="edit_role" class="form-control mb-2">
                        <option value="receptionist">📞 Receptionist</option>
                        <option value="stylist">✂️ Stylist</option>
                    </select>
                    <label class="mt-2"><input type="checkbox" name="is_active" id="edit_active" value="1"> Active Account</label>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="update_staff" class="btn btn-primary">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
<?php if($editStaff): ?>
$(document).ready(function() {
    $('#edit_id').val('<?php echo $editStaff['id']; ?>');
    $('#edit_fullname').val('<?php echo addslashes($editStaff['full_name']); ?>');
    $('#edit_phone').val('<?php echo $editStaff['phone']; ?>');
    $('#edit_role').val('<?php echo $editStaff['role']; ?>');
    $('#edit_active').prop('checked', <?php echo $editStaff['is_active'] ? 'true' : 'false'; ?>);
    $('#editStaffModal').modal('show');
});
<?php endif; ?>
</script>

</body>
</html>