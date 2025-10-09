<?php
require_once '../includes/library.php';
$pdo = connectdb();
require_once './index.php';

// function for retrieving all movies by limiting to 100
function get_all_movies($pdo, $query)
{

    // filtering by genre and title
    if (isset($_GET['title']) && isset($_GET['genre'])) {
        $title = ('%' . $_GET['title'] . '%');
        $genre = $_GET['genre'];
        $stmt = $pdo->prepare("SELECT m.id, m.title, m.poster, m.vote_average FROM movies m JOIN movie_genres mg ON m.id = mg.movie_id JOIN genres g ON mg.genre_id = g.id WHERE g.name = ? AND m.title LIKE ?");
        $stmt->execute([$genre, $title]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } else if (isset($_GET['title'])) {
        // filtering by title only
        $title = ('%' . $_GET['title'] . '%');
        $stmt = $pdo->prepare("SELECT id, title, poster, vote_average FROM movies WHERE title LIKE ?");
        $stmt->execute([$title]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } else if (isset($_GET['genre'])) {
        // filtering by genre only
        $genre = $_GET['genre'];
        $stmt = $pdo->prepare("SELECT m.id, m.title, m.poster, m.vote_average FROM movies m JOIN movie_genres mg ON m.id = mg.movie_id JOIN genres g ON mg.genre_id = g.id WHERE g.name = ?");
        $stmt->execute([$genre]);
        json_response(200, $stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        // all movies without filtering
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        json_response(200, $data);
    }
}

// function for getting a movie from list with a given movie_id
function get_movie_by_id($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT id, title, tagline, overview, budget FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($movie) json_response(200, $movie);
    else json_response(404, ["error" => "Movie not found"]);
}

// function for getting the rating for a movie from list with a given movie_id
function get_movie_rating($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT vote_average FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $rating = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rating) json_response(200, $rating);
    else json_response(404, ["error" => "Rating not found"]);
}
