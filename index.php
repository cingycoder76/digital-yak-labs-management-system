<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php'); // Redirect to login page
    exit();
}

// Redirect admin users to the admin dashboard
if ($_SESSION['role'] === 'admin') {
    header('Location: manage-admin/dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="text-gray-700 mb-6">
                You are logged in as a <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>.
            </p>

            <!-- Links based on user role -->
            <?php if ($_SESSION['role'] === 'user'): ?>
                <a href="manage-users/dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to User Dashboard</a>
            <?php endif; ?>

            <a href="auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-4">Logout</a>
        </div>
    </div>
</body>
</html>
