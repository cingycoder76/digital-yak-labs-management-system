<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch all users
$users = [];
try {
    $stmt = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching users: ' . $e->getMessage());
}

// Handle delete user
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $_GET['delete_id']]);
        header('Location: manage-users.php');
        exit();
    } catch (PDOException $e) {
        die('Error deleting user: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
                <a href="manage-users.php" class="block px-4 py-2 rounded bg-gray-700">Manage Users</a>
                <a href="../auth/logout.php" class="block px-4 py-2 rounded hover:bg-red-700 text-red-300">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 p-5">
            <h1 class="text-3xl font-bold mb-5">Manage Users</h1>
            <div class="flex justify-end mb-4">
                <a href="add-user.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add User</a>
            </div>
            <div class="bg-white shadow-lg rounded p-5">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-3 text-left">ID</th>
                            <th class="border p-3 text-left">Username</th>
                            <th class="border p-3 text-left">Email</th>
                            <th class="border p-3 text-left">Created At</th>
                            <th class="border p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="border p-3"><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td class="border p-3"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="border p-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="border p-3"><?php echo date('M d, Y h:i A', strtotime($user['created_at'])); ?></td>
                                    <td class="border p-3 text-center">
                                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="text-blue-500 hover:underline">Edit</a> |
                                        <a href="?delete_id=<?php echo $user['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="border p-3 text-center text-gray-500">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
