<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch training materials
$trainings = $pdo->query("SELECT * FROM trainings")->fetchAll();
// Fetch employees
$employees = $pdo->query("SELECT * FROM users WHERE role = 'employee'")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $training_id = $_POST['training_id'];
    $employee_id = $_POST['employee_id'];
    $mandatory = isset($_POST['mandatory']) ? 1 : 0;
    $due_date = $_POST['due_date'];  // new due date from form

    // Check if already assigned
    $stmt = $pdo->prepare("SELECT * FROM training_assignments WHERE training_id = ? AND user_id = ?");
    $stmt->execute([$training_id, $employee_id]);

    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO training_assignments (training_id, user_id, mandatory, assigned_at, due_date) VALUES (?, ?, ?, NOW(), ?)");
        $stmt->execute([$training_id, $employee_id, $mandatory, $due_date]);
        echo "Training assigned successfully.";
    } else {
        echo "Training already assigned to this employee.";
    }
}
?>

<h2>Assign Training</h2>
<form method="post">
    Select Training:<br>
    <select name="training_id" required>
        <?php foreach ($trainings as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Select Employee:<br>
    <select name="employee_id" required>
        <?php foreach ($employees as $e): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    Mandatory? <input type="checkbox" name="mandatory"><br><br>

    Due Date: <input type="date" name="due_date" required><br><br>

    <input type="submit" value="Assign Training">
</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>
