<?php
require_once '../includes/library.php';
require_once './index.php';
$pdo = connectdb();

// function for calculating and getting statistics for a particular user
function get_user_stats($pdo, $user_id)
{
    // getting the number of movies in towatchlist of a particular user
    $stats = [];
    $stmt = $pdo->prepare("SELECT COUNT(*) AS towatch_count FROM toWatchList WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['towatch'] = $stmt->fetch(PDO::FETCH_ASSOC)['towatch_count'];
    // getting the number of movies in completedwatchlist of a particular user
    $stmt = $pdo->prepare("SELECT COUNT(*) AS completed_count FROM completedWatchList WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['completed'] = $stmt->fetch(PDO::FETCH_ASSOC)['completed_count'];
    // getting the average rating for all movies in completedwatchlist for a particular user_id
    $stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating FROM completedWatchList WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['avg_rating'] = round($stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'] ?? 0, 2);
    // getting the number of movies watched by a particular user after 2025-01-01
    $stmt = $pdo->prepare("SELECT COUNT(*) AS watched_in_2025 FROM completedWatchList WHERE date_first_watched > '2025-01-01' AND user_id = ?");
    $stmt->execute([$user_id]);
    $stats['watched_in_2025'] = $stmt->fetch(PDO::FETCH_ASSOC)['watched_in_2025'];
    json_response(200, $stats);
}

// getting the user_id using the provided apikey
function get_user_id_from_api_key($pdo)
{
    $headers = getallheaders();
    $api_key = $headers['X-API-Key'] ?? '';
    // checking if apikey provided or not
    if (!$api_key) {
        json_response(401, ['error' => 'API key is missing']);
    }
    $stmt = $pdo->prepare("SELECT id FROM users WHERE api_key = ?");
    $stmt->execute([$api_key]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // checking if apikey is valid or not
    if (!$user) {
        json_response(401, ['error' => 'Invalid API key']);
    }
    return $user['id'];
}
