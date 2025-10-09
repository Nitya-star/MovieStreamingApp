<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-API-KEY");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../includes/library.php';
$pdo = connectdb();

// function for outputting data on the screen
function json_response($status, $data)
{
    http_response_code($status);
    header("Content-Type: application/json; charset = UTF-8");
    $response_data = json_encode($data);
    echo $response_data;
    exit;
}

// storing the url and the endpoint and defining the base for the url
$uri = parse_url($_SERVER['REQUEST_URI']);
define('__BASE__', '/~nityashah/3430/assn/assn2-Nitya-star/api');
$endpoint = str_replace(__BASE__, "", $uri["path"]);
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    // movies endpoints
    // case for getting list of all movies
    case $method === "GET" && $endpoint === "/movies":
        require_once 'movies.php';
        $query = "SELECT id, title, poster, vote_average FROM movies LIMIT 100";
        get_all_movies($pdo, $query);
        break;

    // case for getting all the movies with the provided movie_id
    case $method === 'GET' && preg_match("/^\/movies\/(\d+)$/", $endpoint, $matches):
        require_once 'movies.php';
        $id = $matches[1];
        get_movie_by_id($pdo, $id);
        break;

    // case for getting rating for a movie with provided movie_id
    case $method === 'GET' && preg_match('#^/movies/(\d+)/rating$#', $endpoint, $matches):
        require_once 'movies.php';
        get_movie_rating($pdo, $matches[1]);
        break;

    // towatchlist endpoints
    // case for getting all movies in towatchlist
    case $method === 'GET' && $endpoint === '/towatchlist/entries':
        require_once 'towatchlist.php';
        get_towatch_entries($pdo);
        break;

    // case for adding a new entry in towatchlist
    case $method === 'POST' && $endpoint === '/towatchlist/entries':
        require_once 'towatchlist.php';
        add_towatch_entry($pdo);
        break;

    // case for replacing an existing entry in towatchlist
    case $method === 'PUT' && preg_match('#^/towatchlist/entries/(\d+)$#', $endpoint, $matches):
        require_once 'towatchlist.php';
        replace_towatch_entry($pdo, $matches[1]);
        break;

    // case for updating priority for a particular user and movie_id
    case $method === 'PATCH' && preg_match('#^/towatchlist/entries/(\d+)/priority$#', $endpoint, $matches):
        require_once 'towatchlist.php';
        update_towatch_priority($pdo, $matches[1]);
        break;

    // case for deleting an entry from towatchlist for a particular movie
    case $method === 'DELETE' && preg_match('#^/towatchlist/entries/(\d+)$#', $endpoint, $matches):
        require_once 'towatchlist.php';
        delete_towatch_entry($pdo, $matches[1]);
        break;

    // completedwatchlist endpoints
    // case for getting list of all movies in completedwatchlist
    case $method === 'GET' && $endpoint === '/completedwatchlist/entries':
        require_once 'completedwatchlist.php';
        get_completed_entries($pdo);
        break;

    // case for getting timeswatched for a movie_id and user_id
    case $method === 'GET' && preg_match('#^/completedwatchlist/entries/(\d+)/times-watched$#', $endpoint, $matches):
        require_once 'completedwatchlist.php';
        get_completed_times_watched($pdo, $matches[1]);
        break;

    // case for getting the rating for a particular movie
    case $method === 'GET' && preg_match('#^/completedwatchlist/entries/(\d+)/rating$#', $endpoint, $matches):
        require_once 'completedwatchlist.php';
        get_completed_rating($pdo, $matches[1]);
        break;

    // case for adding an entry to completedwatchlist
    case $method === 'POST' && $endpoint === '/completedwatchlist/entries':
        require_once 'completedwatchlist.php';
        add_completed_entry($pdo);
        break;

    // case for updating an entry in completedwatchlist
    case $method === 'PATCH' && preg_match('#^/completedwatchlist/entries/(\d+)/rating$#', $endpoint, $matches):
        require_once 'completedwatchlist.php';
        update_completed_rating($pdo, $matches[1]);
        break;

    // case for incrementing the times a movie watched by a particular user
    case $method === 'PATCH' && preg_match('#^/completedwatchlist/entries/(\d+)/times-watched$#', $endpoint, $matches):
        require_once 'completedwatchlist.php';
        increment_completed_times_watched($pdo, $matches[1]);
        break;

    // case for deleting entry from completedwatchlist for a particular movie_id
    case $method === 'DELETE' && preg_match('#^/completedwatchlist/entries/(\d+)$#', $endpoint, $matches):
        require_once 'completedwatchlist.php';
        delete_completed_entry($pdo, $matches[1]);
        break;

    // users endpoint
    // case for getting statistics about a particular user
    case $method === 'GET' && preg_match('#^/users/(\d+)/stats$#', $endpoint, $matches):
        require_once 'users.php';
        get_user_stats($pdo, $matches[1]);
        break;

    // auth endpoint
    // case for getting information of a particular user's account
    case $method === 'POST' && $endpoint === '/users/session':
        require_once 'auth.php';
        authenticate_user($pdo);
        break;

    // Default: not found
    default:
        json_response(404, ["error" => "Endpoint not found"]);
}
