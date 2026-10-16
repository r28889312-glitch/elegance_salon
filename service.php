<?php
require_once 'config.php';

// Fetch services from database
$services = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id")->fetchAll();

// Fetch testimonials
$testimonials = $pdo->query("SELECT * FROM feedback WHERE rating >= 4 ORDER BY id DESC LIMIT 4")->fetchAll();

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

    <!-- Hero Start -->
    <div class="container-fluid bg-light page-header py-5 mb-5">
        <div class="container text-center py-5">
            <h1 class="display-1 animated slideInLeft">Service</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center animated slideInLeft mb-0">
                    <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-primary" href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hero End -->





    <!-- Service Start - DYNAMIC FROM DATABASE -->
    <div class="container-fluid service py-5">
        <div class="container">
            <div class="text-center wow fadeIn" data-wow-delay="0.1s">
                <h1 class="font-dancing-script text-primary">Our Services</h1>
                <h1 class="mb-5">Explore Our Services</h1>
            </div>
            <div class="row g-4 g-md-0 text-center">
                <?php 
                $serviceIndex = 0;
                foreach($services as $s): 
                    $serviceIndex++;
                    $delay = 0.1 + ($serviceIndex * 0.2);
                    
                    // Map category to image
                    $serviceImg = 'haircut.png';
                    $category = strtolower($s['category']);
                    if(strpos($category, 'nail') !== false) $serviceImg = 'manicure.png';
                    elseif(strpos($category, 'makeup') !== false) $serviceImg = 'makeup.png';
                    elseif(strpos($category, 'massage') !== false) $serviceImg = 'massage.png';
                    elseif(strpos($category, 'skin') !== false || strpos($category, 'facial') !== false) $serviceImg = 'skin-care.png';
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-item h-100 p-4 border-bottom border-end wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                        <img class="img-fluid" src="img/<?php echo $serviceImg; ?>" alt="">
                        <h3 class="mb-3"><?php echo htmlspecialchars($s['service_name']); ?></h3>
                        <p class="mb-3"><?php echo substr(htmlspecialchars($s['description']), 0, 120); ?>...</p>
                        <p class="text-primary fw-bold">$<?php echo number_format($s['price'], 2); ?> | <?php echo $s['duration']; ?> mins</p>
                        <a class="btn btn-sm btn-primary text-uppercase" href="appointment.php?service=<?php echo $s['id']; ?>">Book Now <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Testimonial Start - DYNAMIC FROM DATABASE -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center wow fadeIn" data-wow-delay="0.2s">
                <h1 class="font-dancing-script text-primary">Testimonial</h1>
                <h1 class="mb-5">What Clients Say!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeIn" data-wow-delay="0.3s">
                <?php foreach($testimonials as $t): ?>
                <div class="text-center bg-light p-4">
                    <i class="fa fa-quote-left fa-3x mb-3"></i>
                    <p>"<?php echo substr(htmlspecialchars($t['message']), 0, 100); ?>..."</p>
                    <img class="img-fluid mx-auto border p-1 mb-3" src="img/testimonial-1.jpg" alt="">
                    <h4 class="mb-1"><?php echo htmlspecialchars($t['name']); ?></h4>
                    <span>Happy Client</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <div class="container-fluid footer position-relative bg-dark text-white-50 py-5 mt-5 wow fadeIn" data-wow-delay="0.2s">
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
                            <a class="btn btn-link" href="login.php">Login</a>
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