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
<link rel="stylesheet" href="css/header.css">
<!-- Main Banner -->
<div class="banner-main">
    <span class="banner-title">Learning Management System</span>
    <span class="banner-user">
        <div class="user-profile" tabindex="0">
            <div class="user-profile-inner">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M12 14c-5 0-7 2.5-7 4.5V21h14v-2.5c0-2-2-4.5-7-4.5z"/>
                </svg>
                <span><?= htmlspecialchars($userName) ?></span>
            </div>
            <div class="user-profile-dropdown">
                <a href="profile.php" class="profile-link">Profile</a>
                <a class="logout-link" href="logout.php">Logout</a>
            </div>
        </div>
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
<script>
    // Show/hide dropdown on hover/focus for .user-profile
    document.addEventListener('DOMContentLoaded', function() {
        const userProfile = document.querySelector('.user-profile');
        if (!userProfile) return;
        userProfile.addEventListener('mouseenter', () => {
            userProfile.classList.add('open');
        });
        userProfile.addEventListener('mouseleave', () => {
            userProfile.classList.remove('open');
        });
        userProfile.addEventListener('focusin', () => {
            userProfile.classList.add('open');
        });
        userProfile.addEventListener('focusout', () => {
            userProfile.classList.remove('open');
        });
    });
</script>
