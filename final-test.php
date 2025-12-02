<?php
echo "<h1>FINAL TEST - Complete System Check</h1>";

// Test database connection
require_once 'includes/dbh.inc.php';
echo "<h3>1. Database Connection:</h3>";
if ($conn) {
    echo "✅ Connected to database: " . mysqli_get_host_info($conn) . "<br>";
    
    // Check users table
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    echo "Users table: " . (mysqli_num_rows($result) > 0 ? "✅ Exists" : "❌ Missing") . "<br>";
    
    // Show current users
    $result = mysqli_query($conn, "SELECT * FROM users");
    $userCount = mysqli_num_rows($result);
    echo "Total users: $userCount<br>";
    
    if ($userCount > 0) {
        echo "<h4>Current Users:</h4>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['usersId']}</td>";
            echo "<td>{$row['usersName']}</td>";
            echo "<td><strong>{$row['usersUid']}</strong></td>";
            echo "<td>{$row['usersEmail']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "❌ Database connection failed";
}

// Test functions
require_once 'includes/functions.inc.php';
echo "<h3>2. Testing Functions:</h3>";

// Test uidExists with different scenarios
echo "<h4>uidExists() Tests:</h4>";

// Add a test user if none exist
if ($userCount == 0) {
    $testPass = password_hash("123456", PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) 
                        VALUES ('Test User', 'test@test.com', 'testuser', '$testPass')");
    echo "Added test user: testuser / test@test.com<br>";
    $userCount = 1;
}

// Test cases
if ($userCount > 0) {
    // Get first user
    $result = mysqli_query($conn, "SELECT usersUid, usersEmail FROM users LIMIT 1");
    $firstUser = mysqli_fetch_assoc($result);
    $existingUser = $firstUser['usersUid'];
    $existingEmail = $firstUser['usersEmail'];
    
    echo "Existing user: $existingUser ($existingEmail)<br><br>";
    
    $tests = [
        ['user' => $existingUser, 'email' => 'new@test.com', 'shouldExist' => true, 'desc' => 'Existing username'],
        ['user' => 'newuser', 'email' => $existingEmail, 'shouldExist' => true, 'desc' => 'Existing email'],
        ['user' => $existingUser, 'email' => $existingEmail, 'shouldExist' => true, 'desc' => 'Both exist'],
        ['user' => 'brandnew', 'email' => 'brandnew@test.com', 'shouldExist' => false, 'desc' => 'Both new'],
    ];
    
    foreach ($tests as $test) {
        $exists = uidExists($conn, $test['user'], $test['email']);
        $passed = ($exists === $test['shouldExist']);
        
        echo "Test: {$test['desc']}<br>";
        echo "Input: user='{$test['user']}', email='{$test['email']}'<br>";
        echo "Result: " . ($exists ? 'EXISTS' : 'NOT EXISTS') . "<br>";
        echo "Expected: " . ($test['shouldExist'] ? 'SHOULD EXIST' : 'SHOULD NOT EXIST') . "<br>";
        echo "Status: " . ($passed ? '✅ PASS' : '❌ FAIL') . "<br><br>";
    }
}

// Test form simulation
echo "<h3>3. Test Registration Process:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testName = $_POST['name'];
    $testEmail = $_POST['email'];
    $testUser = $_POST['username'];
    $testPass = $_POST['password'];
    
    echo "<h4>Testing registration for:</h4>";
    echo "Name: $testName<br>";
    echo "Email: $testEmail<br>";
    echo "Username: $testUser<br>";
    
    // Run all checks
    $errors = [];
    
    if (emptyInputSignup($testName, $testEmail, $testUser, $testPass, $testPass)) {
        $errors[] = "Empty input";
    }
    
    if (invalidUid($testUser)) {
        $errors[] = "Invalid username (only letters/numbers)";
    }
    
    if (invalidEmail($testEmail)) {
        $errors[] = "Invalid email";
    }
    
    if (uidExists($conn, $testUser, $testEmail)) {
        $errors[] = "Username or email already taken";
    }
    
    if (empty($errors)) {
        echo "<p style='color: green; font-weight: bold;'>✅ ALL CHECKS PASSED - Can register!</p>";
        
        // Actually register if requested
        if (isset($_POST['doregister'])) {
            $hashed = password_hash($testPass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $testName, $testEmail, $testUser, $hashed);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<p style='color: green;'>✅ User registered successfully!</p>";
            } else {
                echo "<p style='color: red;'>❌ Registration failed: " . mysqli_error($conn) . "</p>";
            }
        }
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ ERRORS FOUND:</p>";
        foreach ($errors as $error) {
            echo "- $error<br>";
        }
    }
}
?>

<h4>Test Registration Form:</h4>
<form method="post">
    Name: <input type="text" name="name" value="John Doe" required><br>
    Email: <input type="email" name="email" value="john<?php echo rand(100,999); ?>@doe.com" required><br>
    Username: <input type="text" name="username" value="johndoe<?php echo rand(100,999); ?>" required><br>
    Password: <input type="password" name="password" value="123456" required><br>
    <input type="checkbox" name="doregister"> Actually register this user<br>
    <input type="submit" value="Test Registration">
</form>

<hr>
<p><a href="signup.php">Go to Real Signup Page</a></p>