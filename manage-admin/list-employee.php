<?php
session_start();
require '../config/db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch all employees
$employees = [];
try {
    $stmt = $pdo->query("SELECT id, name, email, location, number, created_at FROM employees ORDER BY created_at DESC");
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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Container -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-5">List of Employees</h1>
        <div class="bg-white shadow-lg rounded p-5">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Location</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Number</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Joined On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($employee['id']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($employee['name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($employee['email']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($employee['location']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($employee['number']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo date('M d, Y', strtotime($employee['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-gray-500">No employees found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
