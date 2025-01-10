<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch employees
try {
    $stmt = $pdo->query("SELECT name, email, location, number FROM employees ORDER BY name ASC");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching employees: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Employees</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Employee List</h1>

            <?php if (empty($employees)): ?>
                <p class="text-gray-500">No employees found.</p>
            <?php else: ?>
                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-200 p-2 text-left">Name</th>
                            <th class="border border-gray-200 p-2 text-left">Email</th>
                            <th class="border border-gray-200 p-2 text-left">Location</th>
                            <th class="border border-gray-200 p-2 text-left">Contact Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($employee['name']); ?></td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($employee['email']); ?></td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($employee['location']); ?></td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($employee['number']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="dashboard.php" class="text-blue-500 mt-4 inline-block hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
