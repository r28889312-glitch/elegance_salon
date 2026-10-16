<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        
        // 1. Check in `users` table (Admin, Receptionist, Stylist)
        $stmt =$pdo->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user =$stmt->fetch();

        if ($user) {
            // Supports hashed password or direct string match during migration
            if (password_verify($password,$user['password']) || $password ===$user['password'] || md5($password) ===$user['password']) {
                session_regenerate_id(true);
                $_SESSION['user_id']   =$user['id'];
                $_SESSION['user_name'] =$user['full_name'];
                $_SESSION['user_role'] = strtolower($user['role']);

                switch ($_SESSION['user_role']) {
                    case 'admin':
                        header("Location: admin/admin.php");
                        exit();
                    case 'receptionist':
                        header("Location: admin/receptionist.php");
                        exit();
                    case 'stylist':
                        header("Location: admin/stylist.php");
                        exit();
                    default:
                        header("Location: index.php");
                        exit();
                }
            }
        } 
        
        // 2. Check in `clients` table
        $stmt =$pdo->prepare("SELECT id, name, email, password FROM clients WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $client =$stmt->fetch();

        if ($client) {
            if (password_verify($password,$client['password']) || $password ===$client['password'] || md5($password) ===$client['password']) {
                session_regenerate_id(true);
                $_SESSION['client_id']   = $client['id'];$_SESSION['client_name'] = $client['name'];$_SESSION['user_role']   = 'client';

                header("Location: index.php");
                exit();
            }
        }

        $error = "Invalid Email or Password!";
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - Elegance Salon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 400px; border-radius: 10px;">
        <h3 class="text-center mb-4 text-primary">Login</h3>
        
        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
        </form>

        <div class="text-center mt-3">
            <small>Don't have an account? <a href="register.php">Register Here</a></small>
        </div>
    </div>
</body>
</html>