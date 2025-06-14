<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$user = $_SESSION['user'] ?? [];
$userName = isset($user['name']) ? $user['name'] : (isset($user['first_name']) ? $user['first_name'].' '.$user['last_name'] : 'Admin');
?>
<!-- ...rest of banner... -->
<span class="banner-user">
    <span class="user-details">
        👤 <?php echo htmlspecialchars($userName); ?>
        <a href="profile.php" class="profile-link" style="margin-left:8px; color:#232946; background:transparent; font-size:0.92em; text-decoration:underline;">Profile</a>
    </span>
    <a class="logout-link" href="logout.php">Logout</a>
</span>
<!-- ...rest of banner/nav... -->