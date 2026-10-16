<?php
require_once 'config.php';

$services = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id LIMIT 6")->fetchAll();
$stylists = $pdo->query("SELECT * FROM users WHERE role = 'stylist' AND is_active = 1 LIMIT 4")->fetchAll();
$priceServices = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id LIMIT 6")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM feedback WHERE rating >= 4 ORDER BY id DESC LIMIT 4")->fetchAll();

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
    <!-- Hero Start -->
    <div class="container-fluid p-0 hero-header bg-light mb-5">
        <div class="container p-0">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 hero-header-text py-5">
                    <div class="py-5 px-3 ps-lg-0">
                        <h1 class="font-dancing-script text-primary animated slideInLeft">Welcome</h1>
                        <h1 class="display-1 mb-4 animated slideInLeft">Beauty Salon Fashion for Women</h1>
                        <div class="row g-4 animated slideInLeft">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-square btn btn-primary flex-shrink-0">
                                        <i class="fa fa-phone text-dark"></i>
                                    </div>
                                    <div class="px-3">
                                        <h5 class="text-primary mb-0">Call Us</h5>
                                        <p class="fs-5 text-dark mb-0"><?php echo $contactPhone; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-square btn btn-primary flex-shrink-0">
                                        <i class="fa fa-envelope text-dark"></i>
                                    </div>
                                    <div class="px-3">
                                        <h5 class="text-primary mb-0">Mail Us</h5>
                                        <p class="fs-5 text-dark mb-0"><?php echo $contactEmail; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="owl-carousel header-carousel animated fadeIn">
                        <img class="img-fluid" src="img/hero-slider-1.jpg" alt="">
                        <img class="img-fluid" src="img/hero-slider-2.jpg" alt="">
                        <img class="img-fluid" src="img/hero-slider-3.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->




    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.2s">
                    <img class="img-fluid mb-3" src="img/about.jpg" alt="">
                    <div class="d-flex align-items-center bg-light">
                        <div class="btn-square flex-shrink-0 bg-primary" style="width: 100px; height: 100px;">
                            <i class="fa fa-phone fa-2x text-dark"></i>
                        </div>
                        <div class="px-3">
                            <h3><?php echo $contactPhone; ?></h3>
                            <span>Call us direct 24/7 for get a free consultation</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="font-dancing-script text-primary">About Us</h1>
                    <h1 class="mb-5">Why People Choose Us!</h1>
                    <p class="mb-4">Elegance Salon is a premier beauty destination offering top-quality hair, makeup, nail, and skincare services. Our expert team is dedicated to making you look and feel your best.</p>
                    <div class="row g-3 mb-5">
                        <div class="col-sm-6">
                            <div class="bg-light text-center p-4">
                                <i class="fas fa-calendar-alt fa-4x text-primary"></i>
                                <h1 class="display-5" data-toggle="counter-up">25</h1>
                                <p class="text-dark text-uppercase mb-0">Years experience</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bg-light text-center p-4">
                                <i class="fas fa-users fa-4x text-primary"></i>
                                <h1 class="display-5" data-toggle="counter-up">999</h1>
                                <p class="text-dark text-uppercase mb-0">Happy Customers</p>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary text-uppercase px-5 py-3" href="about.php">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Service Start -->
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
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="service-item h-100 p-4 border-bottom border-end wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                        <img class="img-fluid" src="img/<?php echo $s['category'] == 'Hair' ? 'haircut.png' : ($s['category'] == 'Nails' ? 'manicure.png' : 'skin-care.png'); ?>" alt="">
                        <h3 class="mb-3"><?php echo htmlspecialchars($s['service_name']); ?></h3>
                        <p class="mb-3"><?php echo substr(htmlspecialchars($s['description']), 0, 100); ?>...</p>
                        <a class="btn btn-sm btn-primary text-uppercase" href="appointment.php?service=<?php echo $s['id']; ?>">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Pricing Start -->
    <div class="container-fluid price px-0 py-5">
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
                    ?>
                    <div class="price-item mb-3 wow fadeIn" data-wow-delay="0.<?php echo $priceIndex; ?>s">
                        <img class="img-fluid flex-shrink-0" src="img/price-<?php echo $priceIndex; ?>.jpg" alt="">
                        <div class="text-end px-4">
                            <h6 class="text-uppercase text-primary"><?php echo htmlspecialchars($ps['service_name']); ?></h6>
                            <h3 class="text-white font-work-sans mb-0">$<?php echo number_format($ps['price'], 2); ?></h3>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Pricing End -->

    <!-- Gallery Start -->
    <div class="container-fluid gallery py-5">
        <div class="container">
            <div class="text-center wow fadeIn" data-wow-delay="0.2s">
                <h1 class="font-dancing-script text-primary">Gallery</h1>
                <h1 class="mb-5">Explore Our Gallery</h1>
            </div>
            <div class="row g-0">
                <?php for($i = 1; $i <= 6; $i++): ?>
                <div class="col-md-<?php echo ($i == 1 || $i == 6) ? '6' : '3'; ?> wow fadeIn" data-wow-delay="0.<?php echo $i * 2; ?>s">
                    <div class="gallery-item h-100">
                        <img src="img/gallery-<?php echo $i; ?>.jpg" class="img-fluid w-100 h-100" alt="">
                        <div class="gallery-icon">
                            <a href="img/gallery-<?php echo $i; ?>.jpg" class="btn btn-primary btn-lg-square"
                                data-lightbox="Gallery-<?php echo $i; ?>"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <!-- Gallery End -->

    <!-- Team Start -->
    <div class="container-fluid overflow-hidden py-5">
        <div class="container">
            <div class="text-center wow fadeIn" data-wow-delay="0.2s">
                <h1 class="font-dancing-script text-primary">Team Members</h1>
                <h1 class="mb-5">Our Experienced Specialists</h1>
            </div>
            <div class="row g-4 team">
                <?php 
                $teamIndex = 0;
                foreach($stylists as $st): 
                    $teamIndex++;
                    $delay = 0.1 + ($teamIndex * 0.2);
                ?>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="img/team-<?php echo $teamIndex; ?>.jpg" alt="">
                        <div class="team-overlay">
                            <p class="text-primary mb-1">Beauty Specialist</p>
                            <h4><?php echo htmlspecialchars($st['full_name']); ?></h4>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-dark btn-sm-square border-2 me-3" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-dark btn-sm-square border-2 me-3" href="#">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-dark btn-sm-square border-2" href="#">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Testimonial Start -->
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
                    <p><?php echo substr(htmlspecialchars($t['message']), 0, 120); ?>...</p>
                    <img class="img-fluid mx-auto border p-1 mb-3" src="img/testimonial-1.jpg" alt="">
                    <h4 class="mb-1"><?php echo htmlspecialchars($t['name']); ?></h4>
                    <span>Happy Client</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

    <!-- Blog Start -->
    <div class="container-fluid blog p-0 mt-5">
        <div class="row g-0">
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                <div class="h-100 d-flex flex-column justify-content-center bg-primary py-5 px-4">
                    <p class="mb-2"><i class="fa fa-calendar-alt text-dark me-1"></i>Latest | <i class="fa fa-folder-open text-dark me-1"></i>Hair Salon</p>
                    <h3 class="mb-3">How to Extend The Life of Your Haircolor</h3>
                    <p>Tips and tricks to make your hair color last longer and look vibrant.</p>
                    <a class="btn btn-dark align-self-start text-uppercase" href="blog.php">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                <div class="h-100">
                    <img class="img-fluid w-100 h-100" src="img/blog-1.jpg" alt="" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                <div class="h-100 d-flex flex-column justify-content-center bg-primary py-5 px-4">
                    <p class="mb-2"><i class="fa fa-calendar-alt text-dark me-1"></i>Latest | <i class="fa fa-folder-open text-dark me-1"></i>Hair Salon</p>
                    <h3 class="mb-3">Hottest Hairstyles and Haircuts</h3>
                    <p>Discover the latest trending hairstyles and cuts for this season.</p>
                    <a class="btn btn-dark align-self-start text-uppercase" href="blog.php">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                <div class="h-100">
                    <img class="img-fluid w-100 h-100" src="img/blog-2.jpg" alt="" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
    <!-- Blog End -->

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
                            <a class="btn btn-link" href="login.php"> Login</a>
                        </div>
                        <div class="col-sm-12">
                            <h5 class="text-primary mb-4">Newsletter</h5>
                            <div class="position-relative w-100 mb-2">
                                <input class="form-control bg-secondary border-0 w-100 ps-4 pe-5" type="text" placeholder="Enter Your Email" style="height: 60px;">
                                <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-2 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
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