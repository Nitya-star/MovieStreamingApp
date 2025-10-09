# Testing - Nitya Shah

Please be sure to put all your testing-screenshots in this folder so they won't sync to Loki. You can assemble your testing document below. It only need to include well labelled screenshots of your testing (no code).

## Screenshots with results of requests having /movies endpoint

### Method - GET, Endpoint - /movies
![All movies(First 100)](../testing-screenshots/movies/Movies_1.png)

### Method - GET, Endpoint - /movies (Filter - genre)
![All movies of Fantasy genre](../testing-screenshots/movies/Movies_2.png)

### Method - GET, Endpoint - /movies (Filter - title)
![All movies having Harry Potter in title](../testing-screenshots/movies/Movies_3.png)

### Method - GET, Endpoint - /movies (Filter - genre & title)
![All movies having Romance genre and Star in its title](../testing-screenshots/movies/Movies_4.png)

### Method - GET, Endpoint - /movies/20
![The movie with movie_id = 20](../testing-screenshots/movies/Movies_5.png)

### Method - GET, Endpoint - /movies/20/rating
![The rating of movie with movie_id = 20](../testing-screenshots/movies/Movies_6.png)

## Screenshots with results of requests having /towatchlist endpoint

### Method - GET, Endpoint - /towatchlist/entries
![All movies in towatchlist of a particular user](../testing-screenshots/towatchlist/ToWatchList_1.png)

### Method - GET, Endpoint - /towatchlist/entries (Filter - priority)
![All movies in towatchlist of a particular user with priority = 3](../testing-screenshots/towatchlist/ToWatchList_2.png)

### Method - GET, Endpoint - /towatchlist/entries
![Invalid apikey in the header](../testing-screenshots/towatchlist/ToWatchList_3.png)

### Method - GET, Endpoint - /towatchlist/entries
![Missing apikey in the header](../testing-screenshots/towatchlist/ToWatchList_4.png)

### Method - POST, Endpoint - /towatchlist/entries
![Missing required fields in the request](../testing-screenshots/towatchlist/ToWatchList_5.png)

### Method - POST, Endpoint - /towatchlist/entries
![movie_id and priority not in numeric form](../testing-screenshots/towatchlist/ToWatchList_6.png)

### Method - POST, Endpoint - /towatchlist/entries
![priority not between 1 and 10](../testing-screenshots/towatchlist/ToWatchList_7.png)

### Method - POST, Endpoint - towatchlist/entries
![Movie with movie_id = 33 added with a priority = 7.5 and notes = Watch before last date to a particular user's towatchlist](../testing-screenshots/towatchlist/ToWatchList_8.png)

### Method - PUT, Endpoint - towatchlist/entries/20
![Updated an entry in towatchlist with movie_id = 20, priority = 2 and note = Watch within two weeks for a user](../testing-screenshots/towatchlist/ToWatchList_9.png)

### Method - PUT, Endpoint - towatchlist/entries/40
![Inserted an entry in towatchlist with movie_id = 40, priority = 5 and note = Watch today with entire family for a user](../testing-screenshots/towatchlist/ToWatchList_10.png)

### Method - PATCH, Endpoint - towatchlist/entries/40/priority
![Updated priority of an entry where movie_id = 40 for a particular user](../testing-screenshots/towatchlist/ToWatchList_11.png)

### Method - DELETE, Endpoint - towatchlist/entries/33
![Deleted an entry from towatchlist where movie_id = 33 for a particular user](../testing-screenshots/towatchlist/ToWatchList_12.png)

## Screenshots with results of requests having /completedwatchlist endpoint

### Method - GET, Endpoint - completedwatchlist/entries
![Retrieved all the movies in completedwatchlist for a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_1.png)

### Method - GET, Endpoint - completedwatchlist/entries/45/times-watched
![Retrieved times-watched a movie with movie_id = 45 by a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_2.png)

### Method - GET, Endpoint - completedwatchlist/entries/60/rating
![Retrieved rating for a movie with movie_id = 60 for a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_3.png)

### Method - POST, Endpoint - completedwatchlist/entries
![Added new entry to completedwatchlist with movie_id = 90, rating = 8.6 for a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_4.png)

### Method - PATCH, Endpoint - completedwatchlist/entries/30/rating
![Updated the priority = 5.0 for movie_id = 30 in completedwatchlist](../testing-screenshots/completedwatchlist/CompletedWatchList_5.png)

### Method - PATCH, Endpoint - completedwatchlist/entries/90/times-watched
![Incremented the timeswatched for movie_id = 90 in completedwatchlist for a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_5.png)

### Method - DELETE, Endpoint - completedwatchlist/entries/60
![Deleted an entry from completedwatchlist where movie_id = 60 for a particular user](../testing-screenshots/completedwatchlist/CompletedWatchList_6.png)

## Screenshot with results of request having /users endpoint

### Method - GET, Endpoint - users/2/stats
![Retrieved stats for a particular user](../testing-screenshots/users/Users_1.png)

## Screenshot with results of request having /users endpoint

### Method - POST, Endpoint - users/session
![Retrieved information for a particular user by taking username and password](../testing-screenshots/auth/Auth_1.png)