<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = trim($_POST['email']);
$password = trim($_POST['password']);


    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    echo "<pre>";
    var_dump($email);
    var_dump($password);
    var_dump($user);
    echo "</pre>";

    if ($user && $password === $user['password']) {
        $_SESSION['user'] = $user;

        if ($user['must_reset']) {
            header("Location: reset_password.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<h2>LMS Login</h2>
<form method="post">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
<p style="color:red"><?php echo $error; ?></p>
<p><a href="forgot_password.php">Forgot Password?</a></p>