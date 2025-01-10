<?php
session_start();
require '../config/db.php'; // Include database connection

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../auth/login.php');
    exit();
}

$error = '';
$success = '';

// Fetch employees for the dropdown
try {
    $stmt = $pdo->query("SELECT id, name FROM employees");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error fetching employees: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $task_name = trim($_POST['task_name']);
    $task_description = trim($_POST['task_description']);
    $deadline = $_POST['deadline'];

    // Validate inputs
    if (empty($employee_id) || empty($task_name) || empty($deadline)) {
        $error = 'All fields are required.';
    } else {
        try {
            // Insert the task into the database
            $query = "INSERT INTO tasks (employee_id, task_name, task_description, deadline) 
                      VALUES (:employee_id, :task_name, :task_description, :deadline)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'employee_id' => $employee_id,
                'task_name' => $task_name,
                'task_description' => $task_description,
                'deadline' => $deadline
            ]);

            $success = 'Task assigned successfully!';
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
    <title>Upload Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Assign Task</h1>

            <!-- Display error or success messages -->
            <?php if (!empty($error)): ?>
                <p class="text-red-500"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif (!empty($success)): ?>
                <p class="text-green-500"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <!-- Form to assign a task -->
            <form action="upload-task.php" method="POST">
                <div class="mb-4">
                    <label for="employee_id" class="block text-gray-700">Assign to Employee</label>
                    <select id="employee_id" name="employee_id" class="w-full p-2 border rounded" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>">
                                <?php echo htmlspecialchars($employee['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="task_name" class="block text-gray-700">Task Name</label>
                    <input type="text" id="task_name" name="task_name" class="w-full p-2 border rounded" placeholder="Enter task name" required>
                </div>
                <div class="mb-4">
                    <label for="task_description" class="block text-gray-700">Task Description</label>
                    <textarea id="task_description" name="task_description" class="w-full p-2 border rounded" placeholder="Enter task description"></textarea>
                </div>
                <div class="mb-4">
                    <label for="deadline" class="block text-gray-700">Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="w-full p-2 border rounded" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Assign Task</button>
            </form>

            <a href="dashboard.php" class="text-blue-500 mt-4 inline-block hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
