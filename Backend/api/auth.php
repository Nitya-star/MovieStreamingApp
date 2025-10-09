<?php
require_once '../includes/library.php';
$pdo = connectdb();
require_once './index.php';

// function for getting and providing all the information of a user's account by asking for username and password first
function authenticate_user($pdo)
{
    $requestData = json_decode(file_get_contents("php://input"), true);
    $username = $requestData['username'] ?? '';
    $password = $requestData['password'] ?? '';
    // checking if username and password provided or not
    if (empty($username) || empty($password)) {
        json_response(400, ['error' => 'Username and password required']);
    }
    $stmt = $pdo->prepare("SELECT id, password_hash, api_key FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // checking if password matches or not
    if ($user && password_verify($password, $user['password_hash'])) {
        json_response(200, [
            'message' => 'Login successful',
            'api_key' => $user['api_key'],
            'user_id' => $user['id']
        ]);
    } else {
        json_response(401, ['error' => 'Invalid credentials']);
    }
}
