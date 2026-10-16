<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Cancel appointment
if (isset($_GET['cancel'])) {
    $cancel_id = $_GET['cancel'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND client_id = ?");
    $stmt->execute([$cancel_id, $client_id]);
    header("Location: my_appointments.php");
    exit();
}

// Get all appointments for this client
$appointmentsQuery = $pdo->prepare("
    SELECT a.*, s.service_name, s.price, u.full_name as stylist_name 
    FROM appointments a 
    LEFT JOIN services s ON a.service_id = s.id 
    LEFT JOIN users u ON a.stylist_id = u.id 
    WHERE a.client_id = ? 
    ORDER BY a.appointment_date DESC
");
$appointmentsQuery->execute([$client_id]);
$myAppointments = $appointmentsQuery->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Appointments - Salone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        body { background: #fdfcfb !important; color: #333 !important; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .badge-pending { background: rgba(255, 193, 7, 0.15); color: #b7950b; border: 1px solid #ffc107; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; font-weight: 600; }
        .badge-confirmed { background: rgba(23, 162, 184, 0.15); color: #117a8b; border: 1px solid #17a2b8; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; font-weight: 600; }
        .badge-completed { background: rgba(40, 167, 69, 0.15); color: #1e7e34; border: 1px solid #28a745; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; font-weight: 600; }
        .badge-cancelled { background: rgba(220, 53, 69, 0.15); color: #bd2130; border: 1px solid #dc3545; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; font-weight: 600; }
        .table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.03); }
        .table th { background-color: #bfa15f !important; color: #fff !important; border: none; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        .btn-gold { background-color: #bfa15f !important; border: none !important; color: #fff !important; border-radius: 6px !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding: 8px 16px; transition: background 0.3s; }
        .btn-gold:hover { background-color: #a88d4e !important; color: #fff; }
    </style>
</head>
<body>

<!-- Navbar Start -->
<div class="container-fluid bg-light sticky-top p-0">
    <nav class="navbar navbar-expand-lg navbar-light p-0">
        <a href="index.php" class="navbar-brand py-4 px-5 me-0" style="background-color: #bfa15f; color: #fff;">
            <h1 class="mb-0 text-white"><i class="bi bi-scissors"></i>Salone</h1>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="service.php" class="nav-item nav-link">Service</a>
                <a href="price.php" class="nav-item nav-link">Price</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu bg-light mt-2">
                        <a href="gallery.php" class="dropdown-item">Photo Gallery</a>
                        <a href="blog.php" class="dropdown-item">Beauty Blog</a>
                        <a href="team.php" class="dropdown-item">Our Team</a>
                        <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
                <a href="appointment.php" class="nav-item nav-link">Book Appointment</a>
                <a href="my_appointments.php" class="nav-item nav-link active">My Appointments</a>
                <a href="logout.php" class="nav-item nav-link">Logout</a>
            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Content Section -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h3 style="font-family: Georgia, serif; color: #222; font-weight: 700;">Your Booking History</h3>
        <p class="text-muted small">Yahan aap apni tamam appointments ki list dekh sakte hain</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Service</th>
                    <th>Stylist</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($myAppointments)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No appointments found. <a href="appointment.php">Book now</a></td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach($myAppointments as $a): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo date('d M Y', strtotime($a['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                        <td><?php echo htmlspecialchars($a['service_name'] ?? 'Custom Service'); ?></td>
                        <td><?php echo htmlspecialchars($a['stylist_name'] ?? 'Not Assigned'); ?></td>
                        <td>$<?php echo number_format($a['price'] ?? 0, 2); ?></td>
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
                        </td>
                        <td>
                            <?php if($a['status'] == 'pending' || $a['status'] == 'confirmed'): ?>
                                <a href="edit_appointment.php?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-warning text-white mb-1">Edit</a>
                                <a href="?cancel=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Kya aap waqai is appointment ko cancel karna chahte hain?')">Cancel</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="text-center mt-4">
        <a href="appointment.php" class="btn btn-gold">Book New Appointment</a>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Back to Home</a>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>