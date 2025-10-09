<?php
require_once '../includes/library.php';
$pdo = connectdb();
require_once './index.php';

// function for getting all entries from completedwatchlist
function get_completed_entries($pdo)
{
    $user_id = get_user_id_from_api_key($pdo);
    // function for checking if min_rating is provided or not and if provided then filtering according to it
    if (isset($_GET['min_rating'])) {
        $min_rating = floatval($_GET['min_rating']);
        $stmt = $pdo->prepare("SELECT m.id, m.title, m.poster, m.vote_average, cw.rating, cw.times_watched FROM completedWatchList cw JOIN movies m ON cw.movie_id = m.id WHERE cw.user_id = ? AND cw.rating > ?");
        $stmt->execute([$user_id, $min_rating]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        // function for getting with filtering
        $sql = "SELECT m.id, m.title, m.poster, m.vote_average, cw.rating, cw.times_watched FROM completedWatchList cw JOIN movies m ON cw.movie_id = m.id WHERE cw.user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

// function for getting times watched for a particular movie_id and a user
function get_completed_times_watched($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    $stmt = $pdo->prepare("SELECT times_watched FROM completedWatchList WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    json_response(200, $stmt->fetch(PDO::FETCH_ASSOC));
}

// functiong for getting the rating for a particular movie_id and a user
function get_completed_rating($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    $stmt = $pdo->prepare("SELECT rating FROM completedWatchList WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    json_response(200, $stmt->fetch(PDO::FETCH_ASSOC));
}

// function for adding an entry to completedwatchlist
function add_completed_entry($pdo)
{
    $user_id = get_user_id_from_api_key($pdo);
    // checking if movie_id, rating, date_first_watched set or not
    if (!isset($_POST['movie_id'], $_POST['rating'], $_POST['date_first_watched'])) {
        json_response(400, ["error" => "Missing required fields"]);
    }
    $stmt = $pdo->prepare("INSERT INTO completedWatchList (user_id, movie_id, rating, notes, date_first_watched, date_last_watched, times_watched) VALUES (?, ?, ?, ?, ?, ?, 1)");
    $stmt->execute([$user_id, $_POST['movie_id'], $_POST['rating'], $_POST['notes'] ?? '', $_POST['date_first_watched'], $_POST['date_last_watched']]);
    recalculate_movie_rating_after_new($pdo, $_POST['movie_id'], $_POST['rating']);
    json_response(201, ["message" => "Entry added"]);
}

// function for updating the rating for an entry in completedwatchlist
function update_completed_rating($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    parse_str(file_get_contents("php://input"), $requestData);
    // checking if rating is provided or not
    if (!isset($requestData['rating'])) json_response(400, ["error" => "Missing rating"]);
    $stmt = $pdo->prepare("SELECT rating FROM completedWatchList WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    $old = $stmt->fetch(PDO::FETCH_ASSOC);
    // checking if entry exists or not
    if (!$old) json_response(404, ["error" => "Entry not found"]);
    $old_rating = $old['rating'];
    $stmt = $pdo->prepare("UPDATE completedWatchList SET rating = ? WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$requestData['rating'], $user_id, $movie_id]);
    recalculate_movie_rating_after_update($pdo, $movie_id, $old_rating, $requestData['rating']);
    json_response(200, ["message" => "Rating updated"]);
}

// function for incrementing times watched a particular movie by a particular user
function increment_completed_times_watched($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    $stmt = $pdo->prepare("UPDATE completedWatchList SET times_watched = times_watched + 1, date_last_watched = ? WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([date('Y-m-d'), $user_id, $movie_id]);
    json_response(200, ["message" => "Times watched incremented"]);
}

// function for deleting an entry from completedwatchlist for a particular movie
function delete_completed_entry($pdo, $movie_id)
{
    $user_id = get_user_id_from_api_key($pdo);
    $stmt = $pdo->prepare("DELETE FROM completedWatchList WHERE user_id = ? AND movie_id = ?");
    $stmt->execute([$user_id, $movie_id]);
    json_response(200, ["message" => "Entry deleted"]);
}

// function for getting the user_id using the provided apikey
function get_user_id_from_api_key($pdo)
{
    $api_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    // checking if apikey provided or not
    if (empty($api_key)) {
        json_response(401, ["error" => "Missing API key"]);
    }
    $stmt = $pdo->prepare("SELECT id FROM users WHERE api_key = ?");
    $stmt->execute([$api_key]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // checking if apikey is valid or not
    if (!$user) {
        json_response(401, ["error" => "Invalid API key"]);
    }
    return $user['id'];
}

// function for recalculating the rating for a movie after new entry added to completedwatchlist for that movie_id
function recalculate_movie_rating_after_new($pdo, $movie_id, $new_rating)
{
    $stmt = $pdo->prepare("SELECT vote_average, vote_count FROM movies WHERE id = ?");
    $stmt->execute([$movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    $old_avg = $movie['vote_average'] ?? 0;
    $old_count = $movie['vote_count'] ?? 0;
    $new_count = $old_count + 1;

    $new_avg = (($old_avg * $old_count) + $new_rating) / $new_count;

    $stmt = $pdo->prepare("UPDATE movies SET vote_average = ?, vote_count = ? WHERE id = ?");
    $stmt->execute([$new_avg, $new_count, $movie_id]);
}

// function for recalculating the rating for a movie after the rating for that movie has been updated in completedwatchlist
function recalculate_movie_rating_after_update($pdo, $movie_id, $old_rating, $new_rating)
{
    $stmt = $pdo->prepare("SELECT vote_average, vote_count FROM movies WHERE id = ?");
    $stmt->execute([$movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    $old_avg = $movie['vote_average'] ?? 0;
    $count = $movie['vote_count'] ?? 0;

    $new_avg = (($old_avg * $count) - $old_rating + $new_rating) / $count;

    $stmt = $pdo->prepare("UPDATE movies SET vote_average = ? WHERE id = ?");
    $stmt->execute([$new_avg, $movie_id]);
}
