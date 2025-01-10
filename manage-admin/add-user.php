<?php
session_start();
require '../config/db.php'; // Database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } else {
        // Check if the username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already exists.';
        } else {
            // Insert the user into the database
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                ]);
                header('Location: manage-users.php');
                exit();
            } catch (PDOException $e) {
                $errors[] = 'Error adding user: ' . $e->getMessage();
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
    <title>Add User</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10">
        <div class="max-w-md mx-auto bg-white shadow-lg rounded p-5">
            <h1 class="text-2xl font-bold mb-5">Add User</h1>

            <!-- Display Errors -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" name="username" id="username" class="w-full p-3 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-3 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-3 border rounded" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add User</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
