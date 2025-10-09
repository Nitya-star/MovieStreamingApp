<?php
// starting session and setting connection with the database
session_start();
require_once './includes/library.php';
$pdo = connectdb();

// initializing variables and errors arrray
$username = $password = '';
$errors = [];

// storing user input into variables and getting data from the database and checking the credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['api_key'] = $user['api_key'];
    // redirecting to view-account.php page
    header('Location: view-account.php');
    exit;
  } else {
    $errors[] = 'Invalid credentials';
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="./styles/main.css">
</head>

<body>
  <h2>Login</h2>
  <div id="error-box">
    <?php if ($errors) foreach ($errors as $e) echo "<p>$e</p>"; ?>
  </div>
  <form method="post">
    <p>Username: <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"></p>
    <p>Password: <input type="password" name="password"></p>
    <button type="submit">Login</button>
  </form>
  <a href="create-account.php">Don't have an account? Create one</a>
</body>

</html>