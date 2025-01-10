<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch tasks assigned to the logged-in employee
try {
    $employee_id = $_SESSION['user_id'];
    $query = "SELECT task_name, task_description, deadline, status, created_at FROM tasks WHERE employee_id = :employee_id ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['employee_id' => $employee_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Your Assigned Tasks</h1>

            <?php if (empty($tasks)): ?>
                <p class="text-gray-500">No tasks assigned to you yet.</p>
            <?php else: ?>
                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-200 p-2 text-left">Task Name</th>
                            <th class="border border-gray-200 p-2 text-left">Description</th>
                            <th class="border border-gray-200 p-2 text-left">Deadline</th>
                            <th class="border border-gray-200 p-2 text-left">Status</th>
                            <th class="border border-gray-200 p-2 text-left">Assigned Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($task['task_name']); ?></td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($task['task_description']); ?></td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($task['deadline']); ?></td>
                                <td class="border border-gray-200 p-2 capitalize">
                                    <?php echo htmlspecialchars($task['status']); ?>
                                </td>
                                <td class="border border-gray-200 p-2"><?php echo htmlspecialchars($task['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="user.php" class="text-blue-500 mt-4 inline-block hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
