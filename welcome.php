<?php
session_start();

if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit();
}

include_once 'includes/header.php';
?>

<div class="form-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    
    <div class="user-info" style="text-align: center;">
        <p><strong>User ID:</strong> <?php echo $_SESSION["userid"]; ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION["useruid"]); ?></p>
        <p>Welcome to your dashboard!</p>
        
        <div style="margin-top: 30px;">
            <a href="index.php" class="btn" style="display: inline-block; width: auto; margin: 5px;">Go to Home</a>
            <a href="includes/logout.inc.php" class="btn" style="display: inline-block; width: auto; margin: 5px; background: #dc3545;">Logout</a>
        </div>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>