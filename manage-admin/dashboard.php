<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Initialize counters
$total_employees = 0;
$tasks_assigned = 0;
$pending_approvals = 0;

try {
    // Fetch total employees
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM employees");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_employees = $result['total'];

    // Fetch total tasks assigned
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tasks");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $tasks_assigned = $result['total'];

    // Fetch total pending approvals (example logic: tasks with status 'pending')
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tasks WHERE status = 'pending'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pending_approvals = $result['total'];
} catch (PDOException $e) {
    die('Error fetching data: ' . $e->getMessage());
}

// Fetch the recent activity logs
$recent_activities = [];
try {
    $stmt = $pdo->query("SELECT description, created_at FROM activity_logs ORDER BY created_at DESC LIMIT 5");
    $recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching recent activities: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-1/4 h-screen bg-gray-800 text-white p-5">
            <h2 class="text-center text-2xl font-bold mb-5">Dashboard</h2>
            <nav class="space-y-3">
                <a href="dashboard.php" class="block px-4 py-2 rounded hover:bg-gray-700">Home</a>
                <a href="upload-employee.php" class="block px-4 py-2 rounded hover:bg-gray-700">Add Employee</a>
                <a href="list-employee.php" class="block px-4 py-2 rounded hover:bg-gray-700">List Employees</a>
                <a href="upload-task.php" class="block px-4 py-2 rounded hover:bg-gray-700">Assign Task</a>
                <a href="manage-users.php" class="block px-4 py-2 rounded hover:bg-gray-700">Manage Users</a>
                <a href="../auth/logout.php" class="block px-4 py-2 rounded hover:bg-red-700 text-red-300">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 p-5">
            <h1 class="text-3xl font-bold mb-5">Dashboard</h1>
            <div class="grid grid-cols-3 gap-4">
                <!-- Total Employees -->
                <div class="bg-blue-500 text-white p-5 rounded shadow-lg">
                    <h3 class="text-lg font-semibold">Total Employees</h3>
                    <p class="text-2xl font-bold"><?php echo $total_employees; ?></p>
                </div>
                <!-- Tasks Assigned -->
                <div class="bg-green-500 text-white p-5 rounded shadow-lg">
                    <h3 class="text-lg font-semibold">Tasks Assigned</h3>
                    <p class="text-2xl font-bold"><?php echo $tasks_assigned; ?></p>
                </div>
                <!-- Pending Approvals -->
                <div class="bg-yellow-500 text-white p-5 rounded shadow-lg">
                    <h3 class="text-lg font-semibold">Pending Approvals</h3>
                    <p class="text-2xl font-bold"><?php echo $pending_approvals; ?></p>
                </div>
            </div>

          <!-- Recent Activity Section -->
<div class="mt-10">
    <h2 class="text-xl font-bold mb-3">Recent Activity</h2>
    <div class="bg-white shadow-lg rounded p-5">
        <?php if (!empty($recent_activities)): ?>
            <ul class="list-disc pl-5 space-y-3">
                <?php foreach ($recent_activities as $activity): ?>
                    <li class="flex justify-between items-center">
                        <span><?php echo htmlspecialchars($activity['description']); ?></span>
                        <span class="text-gray-500 text-sm"><?php echo date('M d, Y h:i A', strtotime($activity['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">No recent activity found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
