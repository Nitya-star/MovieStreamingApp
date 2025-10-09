<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie API Documentation</title>
</head>

<body>
  <h1>Welcome to the Movie Review API</h1>
  <p>This is the back-end API for a movie review and tracking application.</p>
  <h2>Endpoints</h2>
  <ul>
    <li><strong>GET /movies/</strong> - List basic info for all movies</li>
    <li><strong>GET /movies/{id}</strong> - Get full info for a particular movie</li>
    <li><strong>GET /movies/{id}/rating</strong> - Get rating for a particular movie</li>
    <li><strong>GET /towatchlist/entries</strong> - List your to-watch movies</li>
    <li><strong>POST /towatchlist/entries</strong> - Add movie to your to-watch list</li>
    <li><strong>PUT /towatchlist/entries/{id}</strong> - Replace a movie in to-watch list</li>
    <li><strong>PATCH /towatchlist/entries/{id}/priority</strong> - Change priority in to-watch list</li>
    <li><strong>DELETE /towatchlist/entries/{id}</strong> - Remove movie from to-watch list</li>
    <li><strong>GET /completedwatchlist/entries</strong> - List completed movies</li>
    <li><strong>GET /completedwatchlist/entries/{id}/times-watched</strong> - Get times a movie watched</li>
    <li><strong>GET /completedwatchlist/entries/{id}/rating</strong> - Get your rating for a movie</li>
    <li><strong>POST /completedwatchlist/entries</strong> - Add a completed-watching movie</li>
    <li><strong>PATCH /completedwatchlist/entries/{id}/rating</strong> - Update rating for a movie</li>
    <li><strong>PATCH /completedwatchlist/entries/{id}/times-watched</strong> - Increment the times a particular movie watched</li>
    <li><strong>DELETE /completedwatchlist/entries/{id}</strong> - Remove movie from completed-watch list</li>
    <li><strong>GET /users/{id}/stats</strong> - Get user watch stats</li>
    <li><strong>POST /users/session</strong> - Login and get API key</li>
  </ul>
</body>

</html>