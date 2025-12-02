<?php
// Start output buffering to prevent header errors
ob_start();

if (isset($_POST["submit"])) {
    
    // Get form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["uid"]);
    $pwd = trim($_POST["pwd"]);
    $pwdRepeat = trim($_POST["pwdrepeat"]);
    
    // Include required files
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';
    
    // Error handling
    $hasError = false;
    
    // 1. Check empty inputs
    if (emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat)) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }
    
    // 2. Check invalid username
    if (invalidUid($username)) {
        header("location: ../signup.php?error=invaliduid");
        exit();
    }
    
    // 3. Check invalid email
    if (invalidEmail($email)) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }
    
    // 4. Check password match
    if (pwdMatch($pwd, $pwdRepeat)) {
        header("location: ../signup.php?error=passwordsdontmatch");
        exit();
    }
    
    // 5. Check if user already exists
    if (uidExists($conn, $username, $email)) {
        header("location: ../signup.php?error=usernametaken");
        exit();
    }
    
    // 6. If no errors, create user
    createUser($conn, $name, $email, $username, $pwd);
    
} else {
    // If form not submitted, redirect to signup
    header("location: ../signup.php");
    exit();
}

// Clean output buffer
ob_end_flush();
?>