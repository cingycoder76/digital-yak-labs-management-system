<?php
session_start();
require '../config/db.php'; // Include the database connection

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../auth/login.php'); // Redirect to login if not admin
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_name = trim($_POST['department_name']);
    $description = trim($_POST['description']);

    // Validate inputs
    if (empty($department_name)) {
        $error = 'Department name is required.';
    } else {
        try {
            // Insert the department into the database
            $query = "INSERT INTO departments (department_name, description) VALUES (:department_name, :description)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'department_name' => $department_name,
                'description' => $description
            ]);

            $success = 'Department added successfully!';
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Upload Department</h1>

            <!-- Display error or success messages -->
            <?php if (!empty($error)): ?>
                <p class="text-red-500"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif (!empty($success)): ?>
                <p class="text-green-500"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <!-- Form to upload department -->
            <form action="upload-department.php" method="POST">
                <div class="mb-4">
                    <label for="department_name" class="block text-gray-700">Department Name</label>
                    <input type="text" id="department_name" name="department_name" class="w-full p-2 border rounded" placeholder="Enter department name" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded" placeholder="Enter a brief description (optional)"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
            </form>

            <a href="dashboard.php" class="text-blue-500 mt-4 inline-block hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
