<?php
session_start();
require '../config/db.php'; // Include the database connection

$error = ''; // Variable to store errors
$success = ''; // Variable to store success messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username already exists
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = 'Username already exists.';
        } else {
            // Hash the password and insert into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute(['username' => $username, 'password' => $hashed_password]);

            if ($result) {
                $success = 'Registration successful. You can now login.';
                header('Refresh: 2; url=login.php'); // Redirect to login after 2 seconds
                exit();
            } else {
                $error = 'An error occurred. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

        <!-- Display error or success messages -->
        <?php if (!empty($error)): ?>
            <p class="text-red-500 text-center"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (!empty($success)): ?>
            <p class="text-green-500 text-center"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded" placeholder="Enter your username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded" placeholder="Enter your password" required>
            </div>
            <div class="mb-6">
                <label for="confirm_password" class="block text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
        </form>

        <p class="text-center mt-4 text-gray-600">
            Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
