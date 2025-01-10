<?php
require '../config/db.php';

// Check if a date is selected; otherwise, fetch all attendance records
$attendance_date = $_GET['date'] ?? null;

if ($attendance_date) {
    // Fetch attendance for the selected date
    $stmt = $pdo->prepare("
        SELECT e.name AS employee_name, DATE(a.attendance_date) AS attendance_date, a.status
        FROM employee_attendance a
        JOIN employees e ON e.id = a.employee_id
        WHERE DATE(a.attendance_date) = :attendance_date
    ");
    $stmt->execute([':attendance_date' => $attendance_date]);
} else {
    // Fetch all attendance records
    $stmt = $pdo->query("
        SELECT e.name AS employee_name, DATE(a.attendance_date) AS attendance_date, a.status
        FROM employee_attendance a
        JOIN employees e ON e.id = a.employee_id
        ORDER BY a.attendance_date DESC
    ");
}

$attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-5xl w-full bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">View Attendance</h1>
        <form method="GET" class="flex items-center space-x-4 mb-6">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Select Date:</label>
                <input type="date" name="date" id="date" value="<?= $attendance_date ?>" 
                       class="mt-1 p-2 border rounded-md w-full focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <button type="submit" 
                        class="mt-6 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    View
                </button>
            </div>
        </form>

        <h2 class="text-xl font-semibold text-gray-700 mb-4">
            Attendance Records <?= $attendance_date ? "for $attendance_date" : "(All Dates)" ?>
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Employee Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($attendance_records)): ?>
                        <tr>
                            <td colspan="3" class="border border-gray-300 px-4 py-2 text-center">
                                No records found <?= $attendance_date ? "for $attendance_date" : "" ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($attendance_records as $record): ?>
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($record['employee_name']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($record['attendance_date']) ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($record['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
