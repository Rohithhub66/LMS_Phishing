<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch all employees with their trainings and statuses
$query = "
    SELECT u.id AS user_id, u.name AS employee_name, u.email,
           t.title AS training_title, ta.due_date, ta.completed_at, ta.mandatory
    FROM users u
    LEFT JOIN training_assignments ta ON u.id = ta.user_id
    LEFT JOIN trainings t ON ta.training_id = t.id
    WHERE u.role = 'employee'
    ORDER BY u.name, ta.due_date
";

$stmt = $pdo->query($query);
$rows = $stmt->fetchAll();

// Organize data by employee
$employees = [];

foreach ($rows as $row) {
    $uid = $row['user_id'];

    if (!isset($employees[$uid])) {
        $employees[$uid] = [
            'name' => $row['employee_name'],
            'email' => $row['email'],
            'trainings' => [],
        ];
    }

    if ($row['training_title']) {
        $employees[$uid]['trainings'][] = [
            'title' => $row['training_title'],
            'due_date' => $row['due_date'],
            'completed_at' => $row['completed_at'],
            'mandatory' => $row['mandatory'],
        ];
    }
}

// Function to check compliance per employee
function isCompliant($trainings) {
    foreach ($trainings as $tr) {
        if ($tr['mandatory']) {
            // If not completed or completed after due date, not compliant
            if (!$tr['completed_at'] || (strtotime($tr['completed_at']) > strtotime($tr['due_date']))) {
                return false;
            }
        }
    }
    return true;
}
?>

<h2>Employee Training Compliance Overview</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Email</th>
            <th>Total Trainings Assigned</th>
            <th>Compliant?</th>
            <th>Pending / Overdue Trainings</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $employee): ?>
            <?php
                $total = count($employee['trainings']);
                $compliant = isCompliant($employee['trainings']);
                $pendingDetails = [];

                if (!$compliant) {
                    foreach ($employee['trainings'] as $tr) {
                        if ($tr['mandatory']) {
                            if (!$tr['completed_at']) {
                                // Pending or overdue?
                                if (strtotime($tr['due_date']) < time()) {
                                    $pendingDetails[] = $tr['title'] . " (Overdue since {$tr['due_date']})";
                                } else {
                                    $pendingDetails[] = $tr['title'] . " (Due by {$tr['due_date']})";
                                }
                            } else if (strtotime($tr['completed_at']) > strtotime($tr['due_date'])) {
                                $pendingDetails[] = $tr['title'] . " (Completed late on {$tr['completed_at']})";
                            }
                        }
                    }
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($employee['name']) ?></td>
                <td><?= htmlspecialchars($employee['email']) ?></td>
                <td><?= $total ?></td>
                <td><?= $compliant ? "<span style='color:green'>Yes</span>" : "<span style='color:red'>No</span>" ?></td>
                <td>
                    <?php 
                    if ($compliant) {
                        echo "-";
                    } else {
                        echo "<ul>";
                        foreach ($pendingDetails as $detail) {
                            echo "<li>" . htmlspecialchars($detail) . "</li>";
                        }
                        echo "</ul>";
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="dashboard.php">Back to Dashboard</a></p>
