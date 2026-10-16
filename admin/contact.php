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
    <title>Elegance Salon - Contact</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #0f0f0f !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .card { background: #1e1e1e !important; border: none !important; border-radius: 15px !important; box-shadow: 0 5px 20px rgba(0,0,0,0.3); }
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
        .contact-icon { font-size: 40px; color: #667eea; margin-bottom: 15px; }
        .contact-card { text-align: center; padding: 20px; transition: all 0.3s; }
        .contact-card:hover { transform: translateY(-5px); background: #2a2a2a; border-radius: 15px; }
        .social-icon { font-size: 24px; margin: 0 10px; color: #aaa; transition: all 0.3s; cursor: pointer; }
        .social-icon:hover { color: #667eea; transform: translateY(-3px); }
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
        <li class="nav-item"><a class="nav-link" href="clients.php"><i class="fas fa-fw fa-users"></i><span>Clients</span></a></li>
        <li class="nav-item"><a class="nav-link" href="staff.php"><i class="fas fa-fw fa-user-md"></i><span>Staff</span></a></li>
        <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-fw fa-cut"></i><span>Services</span></a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php"><i class="fas fa-fw fa-boxes"></i><span>Inventory</span></a></li>
        <li class="nav-item"><a class="nav-link" href="payments.php"><i class="fas fa-fw fa-money-bill"></i><span>Payments</span></a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php"><i class="fas fa-fw fa-chart-line"></i><span>Reports</span></a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="fas fa-fw fa-comment-dots"></i><span>Feedback</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item active"><a class="nav-link" href="contact.php"><i class="fas fa-fw fa-envelope"></i><span>Contact</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a></li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Topbar -->
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
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-envelope"></i> Contact Information</h1>
                    <button class="btn btn-primary shadow-sm" onclick="editContactInfo()"><i class="fas fa-edit"></i> Edit Info</button>
                </div>
                
                <!-- Contact Cards -->
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-body contact-card">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <h5 class="font-weight-bold">Address</h5>
                                <p id="addressText" class="mb-0">123, Beauty Street, Andheri East, Mumbai - 400069</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-body contact-card">
                                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                                <h5 class="font-weight-bold">Phone</h5>
                                <p id="phoneText" class="mb-0">+91 98765 43210</p>
                                <p id="phoneAltText" class="mb-0">+91 98765 43211</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-body contact-card">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <h5 class="font-weight-bold">Email</h5>
                                <p id="emailText" class="mb-0">support@elegancesalon.com</p>
                                <p id="emailAltText" class="mb-0">info@elegancesalon.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Working Hours -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clock"></i> Working Hours</h6></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Monday - Friday:</strong> <span id="weekdayHours">9:00 AM - 8:00 PM</span></p>
                                <p><strong>Saturday:</strong> <span id="saturdayHours">10:00 AM - 6:00 PM</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Sunday:</strong> <span id="sundayHours">10:00 AM - 4:00 PM</span></p>
                                <p><strong>Holidays:</strong> <span id="holidayHours">Closed</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media & Map -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-share-alt"></i> Follow Us</h6></div>
                            <div class="card-body text-center">
                                <div class="social-icon" onclick="socialMedia('facebook')"><i class="fab fa-facebook-f"></i></div>
                                <div class="social-icon" onclick="socialMedia('instagram')"><i class="fab fa-instagram"></i></div>
                                <div class="social-icon" onclick="socialMedia('twitter')"><i class="fab fa-twitter"></i></div>
                                <div class="social-icon" onclick="socialMedia('youtube')"><i class="fab fa-youtube"></i></div>
                                <div class="mt-3">
                                    <p class="mb-0">Follow us on social media for updates and offers!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-map"></i> Location</h6></div>
                            <div class="card-body">
                                <div id="mapPlaceholder" style="background: #2a2a2a; height: 150px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <p class="text-center mb-0"><i class="fas fa-map-marked-alt fa-3x mb-2" style="color: #667eea;"></i><br>Map View - Andheri East, Mumbai</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-paper-plane"></i> Send us a Message</h6></div>
                    <div class="card-body">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label>Your Name</label><input type="text" id="contactName" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label>Your Email</label><input type="email" id="contactEmail" class="form-control" required></div>
                                </div>
                            </div>
                            <div class="form-group"><label>Subject</label><input type="text" id="contactSubject" class="form-control" required></div>
                            <div class="form-group"><label>Message</label><textarea id="contactMessage" class="form-control" rows="3" required></textarea></div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
                
                <!-- Messages Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-inbox"></i> Received Messages</h6>
                        <button class="btn btn-sm btn-danger" onclick="clearAllMessages()">Clear All</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
                                <tbody id="messagesList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<!-- Edit Contact Modal -->
<div id="editContactModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contact Information</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label>Address</label><textarea id="editAddress" class="form-control" rows="2"></textarea></div>
                <div class="form-group"><label>Phone (Primary)</label><input type="text" id="editPhone" class="form-control"></div>
                <div class="form-group"><label>Phone (Secondary)</label><input type="text" id="editPhoneAlt" class="form-control"></div>
                <div class="form-group"><label>Email (Primary)</label><input type="email" id="editEmail" class="form-control"></div>
                <div class="form-group"><label>Email (Secondary)</label><input type="email" id="editEmailAlt" class="form-control"></div>
                <div class="form-group"><label>Weekday Hours</label><input type="text" id="editWeekdayHours" class="form-control"></div>
                <div class="form-group"><label>Saturday Hours</label><input type="text" id="editSaturdayHours" class="form-control"></div>
                <div class="form-group"><label>Sunday Hours</label><input type="text" id="editSundayHours" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="saveContactInfo()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    // Contact info from localStorage
    let contactInfo = JSON.parse(localStorage.getItem('elegance_contact')) || {
        address: '123, Beauty Street, Andheri East, Mumbai - 400069',
        phone: '+91 98765 43210',
        phoneAlt: '+91 98765 43211',
        email: 'support@elegancesalon.com',
        emailAlt: 'info@elegancesalon.com',
        weekdayHours: '9:00 AM - 8:00 PM',
        saturdayHours: '10:00 AM - 6:00 PM',
        sundayHours: '10:00 AM - 4:00 PM'
    };
    
    let messages = JSON.parse(localStorage.getItem('elegance_contact_messages')) || [];
    
    function loadContactInfo() {
        document.getElementById('addressText').innerText = contactInfo.address;
        document.getElementById('phoneText').innerText = contactInfo.phone;
        document.getElementById('phoneAltText').innerText = contactInfo.phoneAlt;
        document.getElementById('emailText').innerText = contactInfo.email;
        document.getElementById('emailAltText').innerText = contactInfo.emailAlt;
        document.getElementById('weekdayHours').innerText = contactInfo.weekdayHours;
        document.getElementById('saturdayHours').innerText = contactInfo.saturdayHours;
        document.getElementById('sundayHours').innerText = contactInfo.sundayHours;
    }
    
    function editContactInfo() {
        document.getElementById('editAddress').value = contactInfo.address;
        document.getElementById('editPhone').value = contactInfo.phone;
        document.getElementById('editPhoneAlt').value = contactInfo.phoneAlt;
        document.getElementById('editEmail').value = contactInfo.email;
        document.getElementById('editEmailAlt').value = contactInfo.emailAlt;
        document.getElementById('editWeekdayHours').value = contactInfo.weekdayHours;
        document.getElementById('editSaturdayHours').value = contactInfo.saturdayHours;
        document.getElementById('editSundayHours').value = contactInfo.sundayHours;
        $('#editContactModal').modal('show');
    }
    
    function saveContactInfo() {
        contactInfo = {
            address: document.getElementById('editAddress').value,
            phone: document.getElementById('editPhone').value,
            phoneAlt: document.getElementById('editPhoneAlt').value,
            email: document.getElementById('editEmail').value,
            emailAlt: document.getElementById('editEmailAlt').value,
            weekdayHours: document.getElementById('editWeekdayHours').value,
            saturdayHours: document.getElementById('editSaturdayHours').value,
            sundayHours: document.getElementById('editSundayHours').value
        };
        localStorage.setItem('elegance_contact', JSON.stringify(contactInfo));
        loadContactInfo();
        $('#editContactModal').modal('hide');
        alert('Contact information updated!');
    }
    
    function loadMessages() {
        const tbody = document.getElementById('messagesList');
        tbody.innerHTML = '';
        messages.slice().reverse().forEach((msg, i) => {
            tbody.innerHTML += `<tr>
                <td>${i+1}</td>
                <td>${msg.name}</td>
                <td>${msg.email}</td>
                <td>${msg.subject}</td>
                <td>${msg.message.substring(0,50)}${msg.message.length > 50 ? '...' : ''}</td>
                <td>${msg.date}</td>
                <td><button class="btn btn-sm btn-danger" onclick="deleteMessage(${msg.id})"><i class="fas fa-trash"></i></button></td>
            </tr>`;
        });
    }
    
    function deleteMessage(id) {
        if(confirm('Delete this message?')) {
            messages = messages.filter(m => m.id !== id);
            localStorage.setItem('elegance_contact_messages', JSON.stringify(messages));
            loadMessages();
            alert('Message deleted!');
        }
    }
    
    function clearAllMessages() {
        if(confirm('Delete all messages?')) {
            messages = [];
            localStorage.setItem('elegance_contact_messages', JSON.stringify(messages));
            loadMessages();
            alert('All messages cleared!');
        }
    }
    
    function socialMedia(platform) {
        let urls = {
            facebook: 'https://facebook.com/elegancesalon',
            instagram: 'https://instagram.com/elegancesalon',
            twitter: 'https://twitter.com/elegancesalon',
            youtube: 'https://youtube.com/elegancesalon'
        };
        window.open(urls[platform], '_blank');
        alert(`Opening ${platform} page...`);
    }
    
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        let message = {
            id: Date.now(),
            name: document.getElementById('contactName').value,
            email: document.getElementById('contactEmail').value,
            subject: document.getElementById('contactSubject').value,
            message: document.getElementById('contactMessage').value,
            date: new Date().toISOString().split('T')[0]
        };
        messages.push(message);
        localStorage.setItem('elegance_contact_messages', JSON.stringify(messages));
        loadMessages();
        alert('Message sent successfully!');
        $('#contactForm')[0].reset();
    });
    
    loadContactInfo();
    loadMessages();
</script>
</body>
</html>