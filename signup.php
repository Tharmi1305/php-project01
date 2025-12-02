<?php
include_once 'includes/header.php';
?>

<section class="form-section">
    <div class="form-container">
        <h2>Sign Up</h2>
        
        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo '<div class="error">Fill in all fields!</div>';
            } else if ($_GET["error"] == "invaliduid") {
                echo '<div class="error">Username can only contain letters and numbers!</div>';
            } else if ($_GET["error"] == "invalidemail") {
                echo '<div class="error">Invalid email address!</div>';
            } else if ($_GET["error"] == "passwordsdontmatch") {
                echo '<div class="error">Passwords don\'t match!</div>';
            } else if ($_GET["error"] == "stmtfailed") {
                echo '<div class="error">Database error, please try again!</div>';
            } else if ($_GET["error"] == "usernametaken") {
                echo '<div class="error">Username or email already taken!</div>';
            } else if ($_GET["error"] == "none") {
                echo '<div class="success">Registration successful! <a href="login.php">Login now</a></div>';
            }
        }
        ?>
        
        <form action="includes/signup.inc.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="uid">Username:</label>
                <input type="text" name="uid" placeholder="Choose a username" required>
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" name="pwd" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label for="pwdrepeat">Repeat Password:</label>
                <input type="password" name="pwdrepeat" placeholder="Repeat your password" required>
            </div>
            <button type="submit" name="submit" class="btn">Sign Up</button>
            
            <div class="form-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>