<?php
require_once 'config.php';

// Fetch services for pricing from database
$priceServices = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id LIMIT 6")->fetchAll();

// Contact info
$contactPhone = '+91 98765 43210';
$contactEmail = 'info@elegancesalon.com';
$contactAddress = '123, Beauty Street, Mumbai, India';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Salone - Beauty Salon Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Playfair+Display:wght@500&family=Work+Sans&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


<?php
session_start();
require_once '../backend/config.php';

if(!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Cancel appointment
if(isset($_GET['cancel'])) {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND client_id = ?");
    $stmt->execute([$_GET['cancel'], $client_id]);
    header("Location: my_appointments.php");
    exit();
}

// Get all appointments
$appointments = $pdo->prepare("
    SELECT a.*, s.service_name, s.price, u.full_name as stylist_name 
    FROM appointments a 
    LEFT JOIN services s ON a.service_id = s.id 
    LEFT JOIN users u ON a.stylist_id = u.id 
    WHERE a.client_id = ? 
    ORDER BY a.appointment_date DESC
");
$appointments->execute([$client_id]);
$myAppointments = $appointments->fetchAll();

$contactPhone = '+91 98765 43210';
$contactEmail = 'info@elegancesalon.com';
$contactAddress = '123, Beauty Street, Mumbai, India';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Appointments - Elegance Salon</title>
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
    <style>
        .badge-pending { background: #ffc10720; color: #ffc107; border: 1px solid #ffc107; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .badge-confirmed { background: #17a2b820; color: #17a2b8; border: 1px solid #17a2b8; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .badge-completed { background: #28a74520; color: #28a745; border: 1px solid #28a745; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
        .badge-cancelled { background: #dc354520; color: #dc3545; border: 1px solid #dc3545; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: inline-block; }
    </style>
</head>
<body>

<!-- Spinner -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<!-- Navbar Start - SAME AS ABOUT PAGE -->
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
                    <a href="my_appointments.php" class="nav-item nav-link active">My Appointments</a>
                    <a href="profile.php" class="nav-item nav-link">My Profile</a>
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

<!-- Hero Start - EXACT SAME AS ABOUT PAGE -->
<div class="container-fluid bg-light page-header py-5 mb-5">
    <div class="container text-center py-5">
        <h1 class="display-1 animated slideInLeft">My Appointments</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center animated slideInLeft mb-0">
                <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Appointments</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Hero End -->

<!-- Appointments Section -->
<div class="container py-5">
    <div class="text-center mb-5 wow fadeIn" data-wow-delay="0.1s">
        <h1 class="font-dancing-script text-primary">My Appointments</h1>
        <h1 class="mb-4">Your Booking History</h1>
    </div>
    
    <div class="table-responsive wow fadeIn" data-wow-delay="0.2s">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Stylist</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($myAppointments)): ?>
                    <tr><td colspan="8" class="text-center">No appointments found. <a href="appointment.php">Book now</a></td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach($myAppointments as $a): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo date('d M Y', strtotime($a['appointment_date'])); ?></td>
                        <td><?php echo $a['appointment_time'] ?? '--:--'; ?></td>
                        <td><?php echo $a['service_name']; ?></td>
                        <td><?php echo $a['stylist_name']; ?></td>
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
                                <a href="?cancel=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?')">Cancel</a>
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
    
    <div class="text-center mt-4 wow fadeIn" data-wow-delay="0.3s">
        <a href="appointment.php" class="btn btn-primary">Book New Appointment</a>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Back to Home</a>
    </div>
</div>


    <!-- Hero Start -->
    <div class="container-fluid bg-light page-header py-5">
        <div class="container text-center py-5">
            <h1 class="display-1 animated slideInLeft">Price</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center animated slideInLeft mb-0">
                    <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-primary" href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Price</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Pricing Start - DYNAMIC FROM DATABASE -->
    <div class="container-fluid price px-0">
        <div class="row g-0">
            <div class="col-md-6">
                <div class="d-flex align-items-center h-100 bg-primary p-5">
                    <div class="wow fadeIn" data-wow-delay="0.3s">
                        <h1 class="font-dancing-script text-white">Pricing</h1>
                        <h1 class="mb-0">Beauty Salon</h1>
                        <h1 class="display-1 text-uppercase mb-5" style="letter-spacing: 10px;">Pricing</h1>
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-6">
                                <div class="text-center bg-dark p-5">
                                    <h4 class="text-white">Enjoy</h4>
                                    <h1 class="display-1 font-work-sans text-white">20%</h1>
                                    <p class="fs-2 text-white mb-0">Off</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <p>Get 20% off on your first appointment. Book now and experience the best beauty services!</p>
                                <a class="btn btn-dark" href="appointment.php">Get 20% Off</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="h-100 bg-dark p-5">
                    <?php 
                    $priceIndex = 0;
                    foreach($priceServices as $ps): 
                        $priceIndex++;
                        $delay = 0.1 + ($priceIndex * 0.1);
                    ?>
                    <div class="price-item mb-3 wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                        <img class="img-fluid flex-shrink-0" src="img/price-<?php echo $priceIndex; ?>.jpg" alt="">
                        <div class="text-end px-4">
                            <h6 class="text-uppercase text-primary"><?php echo htmlspecialchars($ps['service_name']); ?></h6>
                            <h3 class="text-white font-work-sans mb-0">$<?php echo number_format($ps['price'], 2); ?></h3>
                            <small class="text-muted"><?php echo $ps['duration']; ?> mins</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Pricing End -->


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
                            <h5 class="text-primary mb-4">Popular Links</h5>
                            <a class="btn btn-link" href="team.php">Our Team</a>
                            <a class="btn btn-link" href="gallery.php">Gallery</a>
                            <a class="btn btn-link" href="price.php">Pricing</a>
                            <a class="btn btn-link" href="../backend/login.php"> Login</a>
                        </div>
                        <div class="col-sm-12">
                            <h5 class="text-primary mb-4">Newsletter</h5>
                            <div class="position-relative w-100 mb-2">
                                <input class="form-control bg-secondary border-0 w-100 ps-4 pe-5" type="text"
                                    placeholder="Enter Your Email" style="height: 60px;">
                                <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-2 me-2"><i
                                        class="fa fa-paper-plane text-primary fs-4"></i></button>
                            </div>
                            <p class="mb-0">Subscribe for latest offers and updates!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Copyright Start -->
    <div class="container-fluid bg-dark text-white border-top border-secondary py-4 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">Elegance Salon</a>, All Right Reserved.
              
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>