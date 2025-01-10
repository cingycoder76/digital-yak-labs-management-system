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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_name = trim($_POST['employee_name']);
    $employee_email = trim($_POST['employee_email']);
    $employee_location = trim($_POST['employee_location']);
    $employee_number = trim($_POST['employee_number']);
    $employee_password = trim($_POST['employee_password']);

    // Validate inputs
    if (empty($employee_name) || empty($employee_email) || empty($employee_location) || empty($employee_number) || empty($employee_password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($employee_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        try {
            // Check if the employee already exists
            $stmt = $pdo->prepare("SELECT id FROM employees WHERE email = :email");
            $stmt->execute(['email' => $employee_email]);
            if ($stmt->rowCount() > 0) {
                $error = 'An employee with this email already exists.';
            } else {
                // Insert employee into the database
                $hashed_password = password_hash($employee_password, PASSWORD_BCRYPT);
                $query = "INSERT INTO employees (name, email, location, number, password) 
                          VALUES (:name, :email, :location, :number, :password)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'name' => $employee_name,
                    'email' => $employee_email,
                    'location' => $employee_location,
                    'number' => $employee_number,
                    'password' => $hashed_password
                ]);

                $success = 'Employee added successfully!';
            }
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
    <title>Upload Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold mb-6">Add Employee</h1>

            <!-- Display error or success messages -->
            <?php if (!empty($error)): ?>
                <p class="text-red-500"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif (!empty($success)): ?>
                <p class="text-green-500"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <!-- Form to add employee -->
            <form action="upload-employee.php" method="POST">
                <div class="mb-4">
                    <label for="employee_name" class="block text-gray-700">Employee Name</label>
                    <input type="text" id="employee_name" name="employee_name" class="w-full p-2 border rounded" placeholder="Enter employee name" required>
                </div>
                <div class="mb-4">
                    <label for="employee_email" class="block text-gray-700">Employee Email</label>
                    <input type="email" id="employee_email" name="employee_email" class="w-full p-2 border rounded" placeholder="Enter employee email" required>
                </div>
                <div class="mb-4">
                    <label for="employee_location" class="block text-gray-700">Location</label>
                    <input type="text" id="employee_location" name="employee_location" class="w-full p-2 border rounded" placeholder="Enter location" required>
                </div>
                <div class="mb-4">
                    <label for="employee_number" class="block text-gray-700">Contact Number</label>
                    <input type="text" id="employee_number" name="employee_number" class="w-full p-2 border rounded" placeholder="Enter contact number" required>
                </div>
                <div class="mb-4">
                    <label for="employee_password" class="block text-gray-700">Password</label>
                    <input type="password" id="employee_password" name="employee_password" class="w-full p-2 border rounded" placeholder="Enter password" required>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Employee</button>
            </form>

            <a href="dashboard.php" class="text-blue-500 mt-4 inline-block hover:underline">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
