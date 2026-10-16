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
    <title>Elegance Salon - Feedback</title>
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
        .modal-content { border-radius: 15px !important; }
        .close { color: #fff !important; }
        .rating-star { color: #ffc107; font-size: 18px; cursor: pointer; }
        .rating-star:hover { transform: scale(1.1); }
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
        <li class="nav-item active"><a class="nav-link" href="feedback.php"><i class="fas fa-fw fa-comment-dots"></i><span>Feedback</span></a></li>
        <hr class="sidebar-divider">
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-fw fa-envelope"></i><span>Contact</span></a></li>
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
                    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-comment-dots"></i> Feedback Management</h1>
                    <button class="btn btn-primary shadow-sm" onclick="openAddFeedbackModal()"><i class="fas fa-plus"></i> Add Feedback</button>
                </div>
                
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Feedback</div><div class="h5 mb-0 font-weight-bold text-gray-800" id="totalFeedback">0</div></div><div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div></div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-success text-uppercase mb-1">Average Rating</div><div class="h5 mb-0 font-weight-bold text-gray-800" id="avgRating">0</div></div><div class="col-auto"><i class="fas fa-star fa-2x text-gray-300"></i></div></div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-info text-uppercase mb-1">5 Star Reviews</div><div class="h5 mb-0 font-weight-bold text-gray-800" id="fiveStarCount">0</div></div><div class="col-auto"><i class="fas fa-star fa-2x text-gray-300"></i></div></div></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body"><div class="row no-gutters align-items-center"><div class="col mr-2"><div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Reply</div><div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingReply">0</div></div><div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div></div></div>
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">All Feedback</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>#</th><th>Name</th><th>Rating</th><th>Feedback</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                                <tbody id="feedbackList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white"><div class="container my-auto"><div class="copyright text-center my-auto">Copyright &copy; Elegance Salon 2024</div></div></footer>
    </div>
</div>

<!-- Add/Edit Feedback Modal -->
<div id="feedbackModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Feedback</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="feedbackName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="feedbackEmail" class="form-control">
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <div id="ratingStars">
                        <i class="fas fa-star rating-star" data-rating="1"></i>
                        <i class="fas fa-star rating-star" data-rating="2"></i>
                        <i class="fas fa-star rating-star" data-rating="3"></i>
                        <i class="fas fa-star rating-star" data-rating="4"></i>
                        <i class="fas fa-star rating-star" data-rating="5"></i>
                    </div>
                    <input type="hidden" id="feedbackRating" value="5">
                </div>
                <div class="form-group">
                    <label>Feedback Message</label>
                    <textarea id="feedbackMessage" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Reply (Optional)</label>
                    <textarea id="feedbackReply" class="form-control" rows="2" placeholder="Reply to this feedback..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="saveFeedback()">Save Feedback</button>
            </div>
        </div>
    </div>
</div>

<!-- View Reply Modal -->
<div id="replyModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedback Reply</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="replyContent"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
    let feedbacks = JSON.parse(localStorage.getItem('elegance_feedback')) || [
        { id: 1, name: 'Emma Wilson', email: 'emma@email.com', rating: 5, message: 'Great service! Very professional staff.', reply: '', date: '2024-01-15' },
        { id: 2, name: 'James Brown', email: 'james@email.com', rating: 4, message: 'Good experience, will come again.', reply: 'Thank you for your feedback!', date: '2024-01-14' },
        { id: 3, name: 'Sophia Davis', email: 'sophia@email.com', rating: 5, message: 'Amazing facial treatment!', reply: '', date: '2024-01-13' }
    ];
    
    // Rating stars functionality
    $(document).ready(function() {
        $('.rating-star').click(function() {
            let rating = $(this).data('rating');
            $('#feedbackRating').val(rating);
            $('.rating-star').css('color', '#ffc107');
            $('.rating-star').each(function() {
                if($(this).data('rating') > rating) $(this).css('color', '#555');
            });
        });
    });
    
    function loadFeedback() {
        const tbody = document.getElementById('feedbackList');
        tbody.innerHTML = '';
        let totalRating = 0, fiveStar = 0, pending = 0;
        
        feedbacks.forEach((f, i) => {
            totalRating += f.rating;
            if(f.rating === 5) fiveStar++;
            if(!f.reply) pending++;
            
            let stars = '';
            for(let s=1; s<=5; s++) stars += `<i class="fas fa-star" style="color: ${s <= f.rating ? '#ffc107' : '#555'}"></i>`;
            
            tbody.innerHTML += `<tr>
                <td>${i+1}</td>
                <td>${f.name}<br><small>${f.email}</small></td>
                <td>${stars} (${f.rating})</div></td>
                <td>${f.message.substring(0,50)}${f.message.length > 50 ? '...' : ''}</td>
                <td>${f.date}</td>
                <td>${f.reply ? '<span class="badge badge-success">Replied</span>' : '<span class="badge badge-warning">Pending</span>'}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewReply(${f.id})"><i class="fas fa-reply"></i> Reply</button>
                    <button class="btn btn-sm btn-warning" onclick="editFeedback(${f.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteFeedback(${f.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        });
        
        document.getElementById('totalFeedback').innerText = feedbacks.length;
        document.getElementById('avgRating').innerText = feedbacks.length ? (totalRating / feedbacks.length).toFixed(1) : 0;
        document.getElementById('fiveStarCount').innerText = fiveStar;
        document.getElementById('pendingReply').innerText = pending;
    }
    
    function openAddFeedbackModal() {
        document.getElementById('modalTitle').innerText = 'Add Feedback';
        document.getElementById('editId').value = '';
        document.getElementById('feedbackName').value = '';
        document.getElementById('feedbackEmail').value = '';
        document.getElementById('feedbackMessage').value = '';
        document.getElementById('feedbackReply').value = '';
        $('#feedbackRating').val(5);
        $('.rating-star').css('color', '#ffc107');
        $('#feedbackModal').modal('show');
    }
    
    function saveFeedback() {
        let id = document.getElementById('editId').value;
        let feedback = {
            id: id ? parseInt(id) : Date.now(),
            name: document.getElementById('feedbackName').value,
            email: document.getElementById('feedbackEmail').value,
            rating: parseInt(document.getElementById('feedbackRating').value),
            message: document.getElementById('feedbackMessage').value,
            reply: document.getElementById('feedbackReply').value,
            date: new Date().toISOString().split('T')[0]
        };
        
        if(!feedback.name || !feedback.message) {
            alert('Please fill name and message!');
            return;
        }
        
        if(id) {
            let index = feedbacks.findIndex(f => f.id == id);
            if(index !== -1) feedbacks[index] = feedback;
            alert('Feedback updated!');
        } else {
            feedbacks.push(feedback);
            alert('Feedback added!');
        }
        
        localStorage.setItem('elegance_feedback', JSON.stringify(feedbacks));
        loadFeedback();
        $('#feedbackModal').modal('hide');
    }
    
    function viewReply(id) {
        let f = feedbacks.find(f => f.id === id);
        if(f) {
            let stars = '';
            for(let s=1; s<=5; s++) stars += `<i class="fas fa-star" style="color: ${s <= f.rating ? '#ffc107' : '#555'}"></i>`;
            document.getElementById('replyContent').innerHTML = `
                <p><strong>From:</strong> ${f.name} (${f.email})</p>
                <p><strong>Rating:</strong> ${stars}</p>
                <p><strong>Message:</strong> ${f.message}</p>
                <p><strong>Date:</strong> ${f.date}</p>
                <hr>
                <p><strong>Reply:</strong> ${f.reply || 'No reply yet'}</p>
                ${!f.reply ? `<button class="btn btn-primary btn-sm mt-2" onclick="editFeedback(${f.id})">Add Reply</button>` : ''}
            `;
            $('#replyModal').modal('show');
        }
    }
    
    function editFeedback(id) {
        let f = feedbacks.find(f => f.id === id);
        if(f) {
            document.getElementById('modalTitle').innerText = 'Edit Feedback';
            document.getElementById('editId').value = f.id;
            document.getElementById('feedbackName').value = f.name;
            document.getElementById('feedbackEmail').value = f.email;
            document.getElementById('feedbackMessage').value = f.message;
            document.getElementById('feedbackReply').value = f.reply || '';
            $('#feedbackRating').val(f.rating);
            $('.rating-star').css('color', '#555');
            for(let i=1; i<=f.rating; i++) $('.rating-star[data-rating="'+i+'"]').css('color', '#ffc107');
            $('#feedbackModal').modal('show');
        }
    }
    
    function deleteFeedback(id) {
        if(confirm('Delete this feedback?')) {
            feedbacks = feedbacks.filter(f => f.id !== id);
            localStorage.setItem('elegance_feedback', JSON.stringify(feedbacks));
            loadFeedback();
            alert('Feedback deleted!');
        }
    }
    
    loadFeedback();
</script>
</body>
</html>