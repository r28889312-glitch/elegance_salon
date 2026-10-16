<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$message = '';
$error = '';

// Update profile
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("UPDATE clients SET name = ?, phone = ?, email = ? WHERE id = ?");
    if($stmt->execute([$name, $phone, $email, $client_id])) {
        $_SESSION['client_name'] = $name;
        $_SESSION['client_email'] = $email;
        $message = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile!";
    }
}

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$customer = $stmt->fetch();

$contactPhone = '+91 98765 43210';
$contactEmail = 'info@elegancesalon.com';
$contactAddress = '123, Beauty Street, Mumbai, India';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Profile - Elegance Salon</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Playfair+Display:wght@500&family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- Spinner -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<!-- Navbar Start -->
<div class="container-fluid bg-light sticky-top p-0">
    <nav class="navbar navbar-expand-lg navbar-light p-0">
        <a href="index.php" class="navbar-brand bg-primary py-4 px-5 me-0">
            <h1 class="mb-0"><i class="bi bi-scissors"></i>Salone</h1>
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
                <?php if(isset($_SESSION['client_id'])): ?>

                    <a href="profile.php" class="nav-item nav-link active">My Profile</a>
                    <a href="logout.php" class="nav-item nav-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-item nav-link">Login</a>
                    <a href="register.php" class="nav-item nav-link">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->

<!-- Hero Start -->
<div class="container-fluid bg-light page-header py-5 mb-5">
    <div class="container text-center py-5">
        <h1 class="display-1 animated slideInLeft">My Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center animated slideInLeft mb-0">
                <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Profile</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Hero End -->

<!-- Profile Section -->
<div class="container py-5">
    <div class="text-center mb-5 wow fadeIn" data-wow-delay="0.1s">
        <h1 class="font-dancing-script text-primary">My Account</h1>
        <h1 class="mb-4">Manage Your Profile</h1>
    </div>
    
    <?php if($message): ?>
        <div class="alert alert-success wow fadeIn"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger wow fadeIn"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="row g-5">
        <div class="col-lg-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="bg-light text-center p-5">
                <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                <h3><?php echo htmlspecialchars($customer['name']); ?></h3>
                <p class="text-muted"><?php echo htmlspecialchars($customer['email']); ?></p>
                <p class="text-muted"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($customer['phone'] ?? 'Not provided'); ?></p>
                <hr>
                <p><small>Member since: <?php echo date('d M Y', strtotime($customer['created_at'] ?? 'now')); ?></small></p>
            </div>
        </div>
        
        <div class="col-lg-7 wow fadeIn" data-wow-delay="0.3s">
            <div class="bg-light p-5">
                <h3 class="mb-4">Edit Profile</h3>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($customer['phone']); ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4 wow fadeIn" data-wow-delay="0.4s">
        <a href="appointment.php" class="btn btn-primary">Book Appointment</a>
        <a href="my_appointments.php" class="btn btn-outline-secondary ms-2">My Appointments</a>
        <a href="logout.php" class="btn btn-outline-danger ms-2">Logout</a>
    </div>
</div>

<!-- Footer Start -->
<div class="container-fluid footer position-relative bg-dark text-white-50 py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6 pe-lg-5">
                <a href="index.php" class="navbar-brand">
                    <h1 class="display-5 text-primary mb-0"><i class="bi bi-scissors"></i>Salone</h1>
                </a>
                <p>Experience luxury and relaxation at Elegance Salon. We offer premium beauty services tailored just for you.</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-2"></i><?php echo $contactAddress; ?></p>
                <p class="mb-2"><i class="fa fa-phone-alt me-2"></i><?php echo $contactPhone; ?></p>
                <p><i class="fa fa-envelope me-2"></i><?php echo $contactEmail; ?></p>
                <div class="d-flex justify-content-start mt-4">
                    <a class="btn btn-sm-square btn-primary me-3" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-sm-square btn-primary me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-sm-square btn-primary me-3" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-sm-square btn-primary me-3" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <h5 class="text-primary mb-4">Quick Links</h5>
                        <a class="btn btn-link" href="about.php">About Us</a>
                        <a class="btn btn-link" href="contact.php">Contact Us</a>
                        <a class="btn btn-link" href="service.php">Our Services</a>
                        <a class="btn btn-link" href="appointment.php">Book Appointment</a>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="text-primary mb-4">My Account</h5>
                        <a class="btn btn-link" href="my_appointments.php">My Appointments</a>
                        <a class="btn btn-link" href="profile.php">My Profile</a>
                        <a class="btn btn-link" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid bg-dark text-white border-top border-secondary py-4 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="border-bottom" href="#">Elegance Salon</a>, All Right Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end">
                Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>.
                Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </div>
</div>

<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>