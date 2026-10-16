<?php
require_once 'config.php';

// Blog posts data (can be moved to database later)
$blogPosts = [
    [
        'date' => 'Jan 15, 2024',
        'category' => 'Hair Salon',
        'title' => 'How to Extend The Life of Your Haircolor',
        'description' => 'Learn professional tips and tricks to make your hair color last longer and stay vibrant. From proper washing techniques to product recommendations.',
        'image' => 'blog-1.jpg',
        'link' => '#'
    ],
    [
        'date' => 'Jan 10, 2024',
        'category' => 'Beauty Tips',
        'title' => 'Hottest Hairstyles and Haircuts in 2024',
        'description' => 'Discover the latest trending hairstyles and cuts for this season. From classic bobs to modern layers, find your perfect look.',
        'image' => 'blog-2.jpg',
        'link' => '#'
    ],
    [
        'date' => 'Jan 05, 2024',
        'category' => 'Skin Care',
        'title' => 'Essential Skincare Routine for Glowing Skin',
        'description' => 'Follow this simple daily skincare routine to achieve healthy, glowing skin. Tips for all skin types included.',
        'image' => 'blog-1.jpg',
        'link' => '#'
    ],
    [
        'date' => 'Dec 28, 2023',
        'category' => 'Nail Art',
        'title' => 'Latest Nail Art Trends You Need to Try',
        'description' => 'From minimalist designs to bold patterns, explore the hottest nail art trends that are taking over social media.',
        'image' => 'blog-2.jpg',
        'link' => '#'
    ]
];

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
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="service.php" class="nav-link">Service</a></li>
                <li class="nav-item"><a href="price.php" class="nav-link">Price</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <ul class="dropdown-menu bg-light mt-2">
                        <li><a href="gallery.php" class="dropdown-item">Photo Gallery</a></li>
                        <li><a href="blog.php" class="dropdown-item">Beauty Blog</a></li>
                        <li><a href="team.php" class="dropdown-item">Our Team</a></li>
                        <li><a href="testimonial.php" class="dropdown-item">Testimonial</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="appointment.php" class="nav-link">Book Appointment</a></li>
                <li class="nav-item"><a href="login.php" class="nav-link">Login</a></li>
                <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
</div>
<!-- Navbar End -->

    <!-- Hero Start -->
    <div class="container-fluid bg-light page-header py-5">
        <div class="container text-center py-5">
            <h1 class="display-1 animated slideInLeft">Blog</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center animated slideInLeft mb-0">
                    <li class="breadcrumb-item"><a class="text-primary" href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-primary" href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hero End -->


    

    <!-- Blog Start - DYNAMIC -->
    <div class="container-fluid blog p-0">
        <div class="row g-0">
            <?php 
            $blogIndex = 0;
            foreach($blogPosts as $post): 
                $blogIndex++;
                $position = ($blogIndex % 2 == 1) ? 'left' : 'right';
                $delay = ($blogIndex == 1) ? 0.1 : (($blogIndex == 2) ? 0.3 : (($blogIndex == 3) ? 0.5 : 0.7));
            ?>
            <?php if($position == 'left'): ?>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                <div class="h-100 d-flex flex-column justify-content-center bg-primary py-5 px-4">
                    <p class="mb-2"><i class="fa fa-calendar-alt text-dark me-1"></i><?php echo $post['date']; ?> | <i
                            class="fa fa-folder-open text-dark me-1"></i><?php echo $post['category']; ?></p>
                    <h3 class="mb-3"><?php echo $post['title']; ?></h3>
                    <p><?php echo $post['description']; ?></p>
                    <a class="btn btn-dark align-self-start text-uppercase" href="<?php echo $post['link']; ?>">Read More <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                <div class="h-100">
                    <img class="img-fluid w-100 h-100" src="img/<?php echo $post['image']; ?>" alt="" style="object-fit: cover;">
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                <div class="h-100">
                    <img class="img-fluid w-100 h-100" src="img/<?php echo $post['image']; ?>" alt="" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="<?php echo $delay; ?>s">
                <div class="h-100 d-flex flex-column justify-content-center bg-primary py-5 px-4">
                    <p class="mb-2"><i class="fa fa-calendar-alt text-dark me-1"></i><?php echo $post['date']; ?> | <i
                            class="fa fa-folder-open text-dark me-1"></i><?php echo $post['category']; ?></p>
                    <h3 class="mb-3"><?php echo $post['title']; ?></h3>
                    <p><?php echo $post['description']; ?></p>
                    <a class="btn btn-dark align-self-start text-uppercase" href="<?php echo $post['link']; ?>">Read More <i
                            class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
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