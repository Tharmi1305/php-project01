<?php
include_once 'includes/header.php';
?>

<section class="form-section">
    <div class="form-container">
        <h2>Login</h2>
        
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo '<div class="error">Please fill in all fields!</div>';
            } else if ($_GET["error"] == "wronglogin") {
                echo '<div class="error">Incorrect username/email or password!</div>';
            } else if ($_GET["error"] == "stmtfailed") {
                echo '<div class="error">Something went wrong. Please try again!</div>';
            }
        }
        ?>
        
        <form action="includes/login.inc.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="uid">Username or Email:</label>
                <input type="text" name="uid" placeholder="Enter username or email" required autocomplete="new-username">
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" name="pwd" placeholder="Enter your password" required autocomplete="new-password">
            </div>
            <button type="submit" name="submit" class="btn">Login</button>
            
            <div class="form-footer">
                <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            </div>
        </form>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>