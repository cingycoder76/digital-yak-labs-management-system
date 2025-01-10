<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-6">Employee Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="task-given.php" class="block bg-blue-500 text-white text-center p-4 rounded-lg hover:bg-blue-600">
                    View Assigned Tasks
                </a>
                <a href="../auth/logout.php" class="block bg-red-500 text-white text-center p-4 rounded-lg hover:bg-red-600">
                    Logout
                </a>
            </div>
        </div>
    </div>
</body>
</html>
