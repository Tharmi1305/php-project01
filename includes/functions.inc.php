<?php
// ======================== SIGNUP FUNCTIONS ========================

// Check if any input is empty
function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) {
    $result = false;
    if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    }
    return $result;
}

// Check if username contains only letters and numbers
function invalidUid($username) {
    $result = false;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }
    return $result;
}

// Check if email is valid
function invalidEmail($email) {
    $result = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    return $result;
}

// Check if passwords match
function pwdMatch($pwd, $pwdRepeat) {
    $result = false;
    if ($pwd !== $pwdRepeat) {
        $result = true;
    }
    return $result;
}

// Check if username OR email already exists - FIXED VERSION
function uidExists($conn, $username, $email) {
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // If statement fails, assume user doesn't exist
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    // Check if we got any rows
    if (mysqli_num_rows($result) > 0) {
        // User exists (either username or email matches)
        mysqli_stmt_close($stmt);
        return true;
    } else {
        // User doesn't exist
        mysqli_stmt_close($stmt);
        return false;
    }
}

// Create new user
function createUser($conn, $name, $email, $username, $pwd) {
    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("location: ../signup.php?error=none");
    exit();
}

// ======================== LOGIN FUNCTIONS ========================

// Check if login inputs are empty
function emptyInputLogin($username, $pwd) {
    $result = false;
    if (empty($username) || empty($pwd)) {
        $result = true;
    }
    return $result;
}

// Login user
function loginUser($conn, $username, $pwd) {
    // First, check if user exists by username or email
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../login.php?error=stmtfailed");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // User found, verify password
        $pwdHashed = $row["usersPwd"];
        $checkPwd = password_verify($pwd, $pwdHashed);
        
        if ($checkPwd === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        } else if ($checkPwd === true) {
            // Password correct, start session
            session_start();
            $_SESSION["userid"] = $row["usersId"];
            $_SESSION["useruid"] = $row["usersUid"];
            $_SESSION["username"] = $row["usersName"];
            
            header("location: ../index.php");
            exit();
        }
    } else {
        // User not found
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    
    mysqli_stmt_close($stmt);
}
?>