<?php
// started a session and set a connection with database
session_start();
require_once './includes/library.php';
$pdo = connectdb();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// retrieved data from database using the query
$stmt = $pdo->prepare("SELECT username, email, api_key, api_date FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();

// if user takes regenerating apikey option, it is regenerated and updated in the database
if (isset($_POST['regenerate'])) {
    $newKey = bin2hex(random_bytes(16));
    $newDate = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("UPDATE users SET api_key = ?, api_date = ? WHERE username = ?");
    $stmt->execute([$newKey, $newDate, $_SESSION['username']]);
    $_SESSION['api_key'] = $newKey;
    header("Location: view-account.php");
    exit;
}

// if user wants to logout, user is logged out and sent back to create-account.php page
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: create-account.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Account</title>
</head>

<body>
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>API Key: <?= htmlspecialchars($user['api_key']) ?></p>
    <p>API Date: <?= htmlspecialchars($user['api_date']) ?></p>
    <form method="post">
        <button name="regenerate">Regenerate API Key</button>
    </form>
    <form method="post">
        <button name="logout">Logout</button>
    </form>
</body>

</html>