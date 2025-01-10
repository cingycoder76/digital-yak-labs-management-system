<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO employee_attendance (employee_id, attendance_date, status)
            VALUES (:employee_id, :attendance_date, :status)
            ON DUPLICATE KEY UPDATE status = :status
        ");
        $stmt->execute([
            ':employee_id' => $employee_id,
            ':attendance_date' => $attendance_date,
            ':status' => $status
        ]);
        echo "Attendance marked successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch employees for dropdown
$employees = $pdo->query("SELECT id, name FROM employees")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
</head>
<body>
    <h1>Mark Attendance</h1>
    <form method="POST">
        <label for="employee_id">Employee:</label>
        <select name="employee_id" id="employee_id" required>
            <option value="">Select Employee</option>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['id'] ?>"><?= $employee['name'] ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="attendance_date">Date:</label>
        <input type="date" name="attendance_date" id="attendance_date" required><br><br>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Leave">Leave</option>
        </select><br><br>

        <button type="submit">Mark Attendance</button>
    </form>
</body>
</html>
