<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$user = $_SESSION['user'] ?? [];
// Accept either ['first_name','last_name'] or ['name'] or fallback to username or Admin
if (isset($user['first_name']) && isset($user['last_name'])) {
    $userName = trim($user['first_name'].' '.$user['last_name']);
} elseif (isset($user['name'])) {
    $userName = $user['name'];
} elseif (isset($user['username'])) {
    $userName = $user['username'];
} else {
    $userName = 'Admin';
}
?>
<!-- Main Banner -->
<div class="banner-main">
    <span class="banner-title">Learning Management System</span>
    <span class="banner-user">
        <span class="user-details">
            👤 <?php echo htmlspecialchars($userName); ?>
            <a href="profile.php" class="profile-link" style="margin-left:8px; color:#232946; background:transparent; font-size:0.92em; text-decoration:underline;">Profile</a>
        </span>
        <a class="logout-link" href="logout.php">Logout</a>
    </span>
</div>
<!-- Navigation Bar -->
<nav class="nav-secondary">
    <div class="nav-secondary-inner">
        <?php if (isset($page_breadcrumb) && is_array($page_breadcrumb) && count($page_breadcrumb)): ?>
            <?php foreach ($page_breadcrumb as $i => $crumb): ?>
                <?php if (!empty($crumb['href'])): ?>
                    <a href="<?= htmlspecialchars($crumb['href']) ?>"><?= htmlspecialchars($crumb['text']) ?></a>
                <?php else: ?>
                    <span><?= htmlspecialchars($crumb['text']) ?></span>
                <?php endif; ?>
                <?php if ($i < count($page_breadcrumb) - 1): ?>
                    <span class="breadcrumb-sep">&gt;</span>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <span>Dashboard</span>
        <?php endif; ?>
    </div>
</nav>