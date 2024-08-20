<?php
session_start();
require_once 'includes/connection.php'; // Include your PDO database connection file

// Generate CSRF token if it does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validate CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // Update CSRF token for the next request
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);

    if (!$username || !$email || !$gender || !$birthday) {
        die('Invalid input data');
    }

    // Use PDO to update the user's information
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, gender = ?, birthday = ? WHERE id = ?");
    $stmt->execute([$username, $email, $gender, $birthday, $user_id]);

    $success_message = "Profile updated successfully!";
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Use PDO to retrieve the user's information
$stmt = $pdo->prepare("SELECT username, email, gender, birthday FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"">
    <link rel="icon" type="image/x-icon" href="assets/images/Logo_Transparent.png">
=======
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .edit-profile-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            color: #153448;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 0.9rem;
        }

        .alert.success {
            background-color: #e0f7fa;
            color: #00796b;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #153448;
            font-weight: bold;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            height: calc(2.5rem + 2px);
            box-sizing: border-box;
        }

        select {
            background-color: #fff;
            color: #495057;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn.primary {
            background-color: #153448;
            color: #fff;
            width: 48%;
        }

        .btn.primary:hover {
            background-color: #0e2330;
        }

        .btn.secondary {
            background-color: #e9ecef;
            color: #153448;
            width: 48%;
        }

        .btn.secondary:hover {
            background-color: #d3d6d8;
        }

        @media (max-width: 480px) {
            .edit-profile-container {
                padding: 1.5rem;
            }
            h2 {
                font-size: 1.3rem;
            }
            input[type="text"],
            input[type="email"],
            input[type="date"],
            select,
            .btn {
                font-size: 0.9rem;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

    </style>
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled>Select Gender</option>
                    <option value="male" <?php echo ($row['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($row['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo ($row['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($row['birthday']); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn primary">Update</button>
                <button type="button" class="btn secondary" onclick="window.history.back();">Back</button>
            </div>
        </form>
    </div>

</body>
</html>