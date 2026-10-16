<?php
require_once 'config.php';

$error = '';
$success = '';

// Variables to retain input values
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($password) && !empty($confirm)) {
        if ($password !== $confirm) {
            $error = "Passwords do not match!";
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email is already registered!";
            } else {
                // Hash password securely
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new client only when everything is correct
                $insertStmt = $pdo->prepare("INSERT INTO clients (name, email, phone, password) VALUES (?, ?, ?, ?)");
                if ($insertStmt->execute([$name, $email, $phone, $hashedPassword])) {
                    $success = "Registration successful! You can now login.";
                    // Clear fields after success
                    $name = $email = $phone = '';
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customer Registration - Salone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdfcfb !important; color: #333 !important; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { background: #ffffff !important; border: 1px solid #eaeaea !important; border-radius: 12px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-control { background: #f9f9f9 !important; border: 1px solid #ddd !important; color: #333 !important; border-radius: 6px !important; padding: 10px 15px; }
        .form-control:focus { background: #fff !important; border-color: #bfa15f !important; color: #333 !important; box-shadow: 0 0 0 0.2rem rgba(191, 161, 95, 0.15) !important; }
        .form-label { color: #444 !important; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-gold { background-color: #bfa15f !important; border: none !important; color: #fff !important; border-radius: 6px !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding: 10px; transition: background 0.3s; }
        .btn-gold:hover { background-color: #a88d4e !important; }
        h3 { font-family: Georgia, serif; color: #222; font-weight: 700; }
        a { color: #bfa15f !important; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-5 shadow-sm" style="width: 480px;">
        <div class="text-center mb-4">
            <h3 class="mb-1">Create Account</h3>
            <p class="text-muted small">Join Salone for exclusive bookings</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="John Doe" value="<?php echo htmlspecialchars($name); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" required placeholder="+92 300 1234567" value="<?php echo htmlspecialchars($phone); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-gold w-100 mt-2">Register</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">Already have an account? <a href="login.php">Login Here</a></small>
        </div>
    </div>
</body>
</html>