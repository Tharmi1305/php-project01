<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Blogs Website</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <div class="wrapper">
            <div class="logo">
                <a href="index.php">
                    <div style="color: white; font-size: 24px; font-weight: bold; letter-spacing: 1px;">BLOGS</div>
                </a>
            </div>
            <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="#">About Us</a></li>
    <li><a href="#">Find Blogs</a></li>
    <?php
    if (isset($_SESSION["userid"])) {
        // Show "Profile Page" as text, but link to profile.php
        echo '<li><a href="profile.php">Profile Page</a></li>';
        echo '<li><a href="includes/logout.inc.php">Logout</a></li>';
        // Username is shown in the welcome message instead
    } else {
        echo '<li><a href="signup.php">Sign up</a></li>';
        echo '<li><a href="login.php">Log in</a></li>';
    }
    ?>
</ul>
        </div>
    </nav>
    
    <!-- Welcome Message Section (ADDED THIS PART) -->
    <?php if (isset($_SESSION["userid"])): ?>
        <div class="welcome-message wrapper">
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION["useruid"]); ?>!</h2>
            <p>We're glad to have you here.</p>
        </div>
    <?php endif; ?>
    
    <div class="wrapper">