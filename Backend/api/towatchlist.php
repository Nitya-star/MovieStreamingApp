<?php

require_once '../includes/library.php';
$pdo = connectdb();
require_once './index.php';

// function for retrieving all the movies in towatchlist
function get_towatch_entries($pdo)
{
    $user_id = get_user_id_from_api_key($pdo);

    // function for filtering movies in towatchlist according to priority
    if (isset($_GET['priority'])) {
        $priority = $_GET['priority'];
        $sql = "SELECT m.id, m.title, m.poster, m.vote_average tw.priority, tw.notes FROM toWatchList tw JOIN movies m ON tw.movie_id = m.id WHERE tw.user_id = ? AND tw.priority = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $priority]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        // retrieving without filtering
        $sql = "SELECT m.id, m.title, m.poster, m.vote_average, tw.priority, tw.notes FROM toWatchList tw JOIN movies m ON tw.movie_id = m.id WHERE tw.user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

// function for adding an entry to towatchlist
function add_towatch_entry($pdo)
{
    $user_id = get_user_id_from_api_key($pdo);
    // checking if movie_id and priority are provided or not
    if (!isset($_POST['movie_id'], $_POST['priority'])) {
        json_response(400, ["error" => "Missing required fields"]);
    }
    $movie_id = $_POST['movie_id'];
    $priority = $_POST['priority'];
    $notes = $_POST['notes'] ?? '';
    // checking if movie_id and priority are numeric or not
    if (!is_numeric($movie_id) || !is_numeric($priority)) {
        json_response(400, ["error" => "movie_id and priority must be numeric"]);
    }
    // checking if priority is between 1 and 10 or not
    if ($priority < 1 || $priority > 10) {
        json_response(400, ["error" => "Priority must be between 1 and 10"]);
    }
    $stmt = $pdo->prepare("INSERT INTO toWatchList (user_id, movie_id, priority, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $movie_id, $priority, $notes]);
    json_response(201, ["message" => "Entry added"]);
}

// function for replacing an existing entry in towatchlist having a particular movie_id
function replace_towatch_entry($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    parse_str(file_get_contents("php://input"), $requestData);
    // checking if priority value provided or not
    if (!isset($requestData['priority'])) {
        json_response(400, ["error" => "Missing required field: priority"]);
    }
    $priority = $requestData['priority'];
    $notes = $requestData['notes'] ?? '';
    // checking if priority is between 1 and 10 or not
    if (!is_numeric($priority) || $priority < 1 || $priority > 10) {
        json_response(400, ["error" => "Priority must be a number between 1 and 10"]);
    }
    $checkStmt = $pdo->prepare("SELECT id FROM toWatchList WHERE user_id = ? AND movie_id = ?");
    $checkStmt->execute([$user_id, $movie_id]);
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
    // updating an entry if it exists in towatchlist
    if ($existing) {
        $updateStmt = $pdo->prepare("UPDATE toWatchList SET priority = ?, notes = ? WHERE user_id = ? AND movie_id = ?");
        $updateStmt->execute([$priority, $notes, $user_id, $movie_id]);
        json_response(200, ["message" => "Entry updated"]);
    } else {
        // inserting an entry if it does not exist in towatchlist
        $insertStmt = $pdo->prepare("INSERT INTO toWatchList (user_id, movie_id, priority, notes) VALUES (?, ?, ?, ?)");
        $insertStmt->execute([$user_id, $movie_id, $priority, $notes]);
        json_response(201, ["message" => "Entry inserted"]);
    }
}

// function for updating priority of an entry in towatchlist for a particular user and movie_id
function update_towatch_priority($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    parse_str(file_get_contents("php://input"), $requestData);
    // checking if priority provided or not
    if (!isset($requestData['priority'])) {
        json_response(400, ["error" => "Missing required field: priority"]);
    }
    $priority = $requestData['priority'];
    if ($priority < 1 || $priority > 10) {
        // checking if priority is between 1 and 10 or not
        json_response(400, ["error" => "Priority must be between 1 and 10"]);
    }
    $stmt = $pdo->prepare("UPDATE toWatchList SET priority = ? WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$priority, $user_id, $movie_id]);
    json_response(200, ["message" => "Priority updated"]);
}

// function for deleting an entry from towatchlist for a particular user and movie_id
function delete_towatch_entry($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    $stmt = $pdo->prepare("DELETE FROM toWatchList WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    json_response(200, ["message" => "Entry deleted"]);
}

// function for getting user_id using the provided apikey
function get_user_id_from_api_key($pdo)
{
    $api_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if (empty($api_key)) {
        // checking if apikey provided or not
        json_response(401, ["error" => "Missing API key"]);
    }
    $stmt = $pdo->prepare("SELECT id FROM users WHERE api_key = ?");
    $stmt->execute([$api_key]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        // checking if apikey is valid or not
        json_response(401, ["error" => "Invalid API key"]);
    }
    return $user['id'];
}
