<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if client is logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id        = $_SESSION['client_id'];
    $service_id       = $_POST['service_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';

    if (!empty($service_id) && !empty($appointment_date) && !empty($appointment_time)) {
        
        // Check if the slot is already booked for that date and time
        $checkStmt = $pdo->prepare("SELECT id FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
        $checkStmt->execute([$appointment_date, $appointment_time]);
        
        if ($checkStmt->fetch()) {
            $error = "Yeh time slot pehle se booked hai! Meherbani karke koi doosra time select karein.";
        } else {
            // Insert appointment if slot is available
            $insertStmt = $pdo->prepare("INSERT INTO appointments (client_id, service_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'pending')");
            
            if ($insertStmt->execute([$client_id, $service_id, $appointment_date, $appointment_time])) {
                $success = "Aapki appointment kamyabi ke sath book ho gayi hai!";
            } else {
                $error = "Kuch kharabi ho gayi hai, dobara koshish karein.";
            }
        }
    } else {
        $error = "Tamam fields lazmi fill karein.";
    }
}

// Fetch services for dropdown
$services = $pdo->query("SELECT * FROM services")->fetchAll();

// Define Available Salon Time Slots
$time_slots = [
    "10:00:00" => "10:00 AM",
    "11:00:00" => "11:00 AM",
    "12:00:00" => "12:00 PM",
    "13:00:00" => "01:00 PM",
    "14:00:00" => "02:00 PM",
    "15:00:00" => "03:00 PM",
    "16:00:00" => "04:00 PM",
    "17:00:00" => "05:00 PM",
    "18:00:00" => "06:00 PM",
    "19:00:00" => "07:00 PM",
    "20:00:00" => "08:00 PM",
    "21:00:00" => "09:00 PM"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Appointment - Salone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdfcfb !important; color: #333 !important; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { background: #ffffff !important; border: 1px solid #eaeaea !important; border-radius: 12px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-control, .form-select { background: #f9f9f9 !important; border: 1px solid #ddd !important; color: #333 !important; border-radius: 6px !important; padding: 10px 15px; }
        .form-control:focus, .form-select:focus { background: #fff !important; border-color: #bfa15f !important; color: #333 !important; box-shadow: 0 0 0 0.2rem rgba(191, 161, 95, 0.15) !important; }
        .form-label { color: #444 !important; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-gold { background-color: #bfa15f !important; border: none !important; color: #fff !important; border-radius: 6px !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding: 10px; transition: background 0.3s; }
        .btn-gold:hover { background-color: #a88d4e !important; }
        h3 { font-family: Georgia, serif; color: #222; font-weight: 700; }
        a { color: #bfa15f !important; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-5 shadow-sm" style="width: 500px;">
        <div class="text-center mb-4">
            <h3 class="mb-1">Book Appointment</h3>
            <p class="text-muted small">Apna pasandeeda waqt aur service select karein</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Select Service</label>
                <select name="service_id" class="form-select" required>
                    <option value="">-- Choose Service --</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>"><?php echo htmlspecialchars($service['service_name'] ?? $service['name'] ?? ''); ?> (<?php echo $service['price'] ?? ''; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Appointment Time Slot</label>
                <select name="appointment_time" class="form-select" required>
                    <option value="">-- Select Time Slot --</option>
                    <?php foreach ($time_slots as $val => $label): ?>
                        <option value="<?php echo $val; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold w-100 mt-2">Confirm Booking</button>
        </form>
        <div class="text-center mt-3">
            <small class="text-muted"><a href="index.php">Back to Home</a></small>
        </div>
    </div>
</body>
</html>