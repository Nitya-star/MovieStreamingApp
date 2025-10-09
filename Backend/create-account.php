<?php
// starting session and setting connection with database
session_start();
require_once './includes/library.php';
$pdo = connectdb();

// initialize variables and errors array
$username = $email = $password = '';
$errors = [];

// storing user data into variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validating user input
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email';
    }
    if (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    // checking if account already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $errors['userexists'] = 'Username or email already exists';
    }

    // inserting the new user and the data into the database
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $apiKey = bin2hex(random_bytes(16));
        $apiDate = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, api_key, api_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed, $apiKey, $apiDate]);

        // redirecting to view-account.php page
        $_SESSION['username'] = $username;
        header('Location: view-account.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
    <h2>Create Account</h2>
    <div id="error-box">
        <?php if ($errors) foreach ($errors as $e) echo "<p>$e</p>"; ?>
    </div>
    <form method="post">
        <p>Username: <input type="text" name="username" value="<?= $username ?>"></p>
        <p>Email: <input type="email" name="email" value="<?= $email ?>"></p>
        <p>Password: <input type="password" name="password"></p>
        <button type="submit">Create Account</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</body>

</html>