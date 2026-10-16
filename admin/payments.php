<?php
session_start();
   require_once '../config.php';

if($_SESSION['user_role'] != 'admin') {
   header("Location: ../login.php");
    exit();
}

   
// ========== ADD PAYMENT ==========
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    $invoice_no = 'INV' . date('Ymd') . rand(100, 999);
    $stmt = $pdo->prepare("INSERT INTO payments (invoice_no, client_id, amount, payment_method, payment_status, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$invoice_no, $_POST['client_id'], $_POST['amount'], $_POST['payment_method'], $_POST['payment_status'], $_POST['payment_date']]);
    header("Location: payments.php");
    exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $stmt = $pdo->prepare("UPDATE payments SET client_id = ?, amount = ?, payment_method = ?, payment_status = ?, payment_date = ? WHERE id = ?");
    $stmt->execute([$_POST['client_id'], $_POST['amount'], $_POST['payment_method'], $_POST['payment_status'], $_POST['payment_date'], $_POST['payment_id']]);
    header("Location: payments.php");
    exit();
}

// ========== DELETE PAYMENT ==========
if(isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: payments.php");
    exit();
}

$payments = $pdo->query("SELECT p.*, c.name as client_name FROM payments p LEFT JOIN clients c ON p.client_id = c.id ORDER BY p.id DESC")->fetchAll();
$clients = $pdo->query("SELECT * FROM clients ORDER BY name")->fetchAll();

// For edit modal
$editPayment = null;
if(isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editPayment = $stmt->fetch();
}

$totalRevenue = array_sum(array_map(fn($p) => $p['amount'], array_filter($payments, fn($p) => $p['payment_status'] == 'completed')));
$pendingCount = count(array_filter($payments, fn($p) => $p['payment_status'] == 'pending'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payments - Elegance Salon</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; }
        .card-header { background: #2a2a2a !important; border-bottom: 1px solid #333 !important; }
        .text-gray-800, .text-gray-900, .card-body, .card-header h6, label { color: #fff !important; }
        .table { color: #fff !important; }
        .table-bordered, .table-bordered th, .table-bordered td { border-color: #333 !important; }
        .sidebar .nav-link { color: #ddd !important; }
        .sidebar .nav-link:hover { background: #667eea20 !important; border-radius: 10px; }
        .btn-primary { background: #667eea !important; border: none !important; border-radius: 8px !important; }
        .nav-item.active { background: #667eea; border-radius: 10px; }
        .form-control { background: #2a2a2a !important; border: 1px solid #333 !important; color: #fff !important; }

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
        <a class="sidebar-brand" href="admin.php"><i class="fas fa-spa"></i> Elegance Salon</a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item"><a class="nav-link" href="admin.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-user-md"></i><span>Staff</span></a></li>
        <li class="nav-item"><a class="nav-link" href="appointments.php"><i class="fas fa-calendar-check"></i><span>Appointments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-cut"></i><span>Services</span></a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php"><i class="fas fa-boxes"></i><span>Inventory</span></a></li>
        <li class="nav-item active"><a class="nav-link" href="payments.php"><i class="fas fa-money-bill"></i><span>Payments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-chart-line"></i><span>Reports</span></a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="fas fa-comment-dots"></i><span>Feedback</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i><span>Contact</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
    </ul>

    <div id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                <ul class="navbar-nav ml-auto"><li class="nav-item dropdown no-arrow"><a class="nav-link" href="#"><span class="mr-2 d-none d-lg-inline">Admin User</span><img class="img-profile rounded-circle" src="img/undraw_profile.svg" width="32"></a></li></ul>
            </nav>
            
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-money-bill-wave"></i> Payments & Invoices</h1>
                    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addPaymentModal"><i class="fas fa-plus"></i> New Payment</button>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3"><div class="card p-3 text-center"><h6>Total Revenue</h6><h2 class="text-success">$<?php echo number_format($totalRevenue, 2); ?></h2></div></div>
                    <div class="col-md-4 mb-3"><div class="card p-3 text-center"><h6>Total Transactions</h6><h2><?php echo count($payments); ?></h2></div></div>
                    <div class="col-md-4 mb-3"><div class="card p-3 text-center"><h6>Pending Payments</h6><h2><?php echo $pendingCount; ?></h2></div></div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Payments</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($payments as $p): ?>
                                <tr>
                                    <td><?php echo $p['invoice_no']; ?></td>
                                    <td><?php echo $p['client_name']; ?></td>
                                    <td><strong>$<?php echo number_format($p['amount'], 2); ?></strong></td>
                                    <td><?php echo ucfirst($p['payment_method']); ?></td>
                                    <td><?php echo $p['payment_status'] == 'completed' ? '<span class="text-success">Paid</span>' : '<span class="text-warning">Pending</span>'; ?></td>
                                    <td><?php echo $p['payment_date']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPaymentModal" onclick="editPayment(<?php echo htmlspecialchars(json_encode($p)); ?>)"><i class="fas fa-edit"></i></a>
                                        <a href="?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            <tr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal">
    <div class="modal-dialog"><div class="modal-content bg-dark text-white">
        <div class="modal-header bg-success"><h5>Record Payment</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
        <form method="POST">
            <div class="modal-body">
                <select name="client_id" class="form-control mb-2" required>
                    <option value="">Select Client</option>
                    <?php foreach($clients as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?> (<?php echo $c['phone']; ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="amount" class="form-control mb-2" step="0.01" placeholder="Amount (USD)" required>
                <select name="payment_method" class="form-control mb-2">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="online">Online</option>
                </select>
                <select name="payment_status" class="form-control mb-2">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
                <input type="date" name="payment_date" class="form-control mb-2" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="add_payment" class="btn btn-success">Save</button></div>
        </form>
    </div></div>
</div>

<!-- Edit Payment Modal (NEW) -->
<div class="modal fade" id="editPaymentModal">
    <div class="modal-dialog"><div class="modal-content bg-dark text-white">
        <div class="modal-header bg-warning"><h5>Edit Payment</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
        <form method="POST">
            <div class="modal-body">
                <input type="hidden" name="payment_id" id="edit_id">
                <select name="client_id" id="edit_client_id" class="form-control mb-2" required>
                    <?php foreach($clients as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?> (<?php echo $c['phone']; ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="amount" id="edit_amount" class="form-control mb-2" step="0.01" placeholder="Amount (USD)" required>
                <select name="payment_method" id="edit_method" class="form-control mb-2">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="online">Online</option>
                </select>
                <select name="payment_status" id="edit_status" class="form-control mb-2">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
                <input type="date" name="payment_date" id="edit_date" class="form-control mb-2" required>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" name="update_payment" class="btn btn-primary">Save Changes</button></div>
        </form>
    </div></div>
</div>
     

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
function editPayment(payment) {
    document.getElementById('edit_id').value = payment.id;
    document.getElementById('edit_client_id').value = payment.client_id;
    document.getElementById('edit_amount').value = payment.amount;
    document.getElementById('edit_method').value = payment.payment_method;
    document.getElementById('edit_status').value = payment.payment_status;
    document.getElementById('edit_date').value = payment.payment_date;
}

<?php if($editPayment): ?>
$(document).ready(function() {
    editPayment(<?php echo json_encode($editPayment); ?>);
    $('#editPaymentModal').modal('show');
});
<?php endif; ?>
</script>
  <footer class="sticky-footer bg-white"><div class="copyright text-center">Copyright &copy; Elegance Salon 2024</div></footer>
</body>
</html>