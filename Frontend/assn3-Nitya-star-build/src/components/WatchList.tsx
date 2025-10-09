// importing
import { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import MovieCard from './MovieCard';
import SearchBar from './SearchBar';
import type { Movie } from '../types/types';
import '../styles/WatchList.css'

// declaring the component WatchList
const WatchList = () => {
  const { api_key } = useAuth();
  const [watchList, setWatchList] = useState<Movie[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [filteredMovies, setFilteredMovies] = useState<Movie[]>([]);
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('asc');
  const [selectedRating, setSelectedRating] = useState<{ [key: number]: number }>({});

  // fetching the movies in the WatchList from the api
  const fetchWatchList = () => {
    if (!api_key) return;
    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/towatchlist/entries`, {
      headers: {
        "Content-Type": "application/json",
        "X-API-KEY": api_key,
      }
    })
      .then(res => res.json())
      .then((data) => {
        setWatchList(data);
      })
      .catch(console.error);
  };
  useEffect(fetchWatchList, [api_key]);

  // handles a search when a searchitem is used to search for a particular movie
  const handleSearch = (query: string) => {
    setSearchQuery(query);
    const lower = query.toLowerCase();
    const filtered = watchList.filter(movie =>
      movie.title.toLowerCase().includes(lower)
    );
    setFilteredMovies(filtered);
  };

  // toggles the sort order button's results by sorting in ascending or descending order
  const toggleSortOrder = () => {
    setSortOrder(prev => (prev === 'asc' ? 'desc' : 'asc'));
  };

  // handling any change in the priority using priority button
  const handlePriorityChange = (movieId: number, newPriority: number) => {
    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/towatchlist/entries/${movieId}/priority`, {
      method: 'PATCH',
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "X-API-KEY": api_key ?? '',
      },
      body: `priority=${newPriority}`
    })
      .then(res => res.json())
      .then(() => {
        fetchWatchList();
      })
      .catch(console.error);
  };

  // adds a movie to completedwatchlist and removes it from towatchlist using MarkAsWatched button
  const handleMarkAsWatched = (movie: Movie) => {
    const rating = selectedRating[movie.id];
    if (!rating) {
      alert("Please select a rating before marking as watched.");
      return;
    }
    const today = new Date().toISOString().split("T")[0]; // "YYYY-MM-DD"
    const body = new URLSearchParams();
    body.append("movie_id", movie.id.toString());
    body.append("rating", movie.rating?.toString() ?? '0');
    body.append("date_first_watched", today);
    body.append("date_last_watched", today);

    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/completedwatchlist/entries`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "X-API-KEY": api_key ?? "",
      },
      body: body.toString(),
    })
      .then(() =>
        fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/towatchlist/entries/${movie.id}`, {
          method: "DELETE",
          headers: {
            "X-API-KEY": api_key ?? "",
          },
        })
      )
      .then(() => fetchWatchList())
      .catch(console.error);
  };

  // sorting the movies according to the priority
  const sortedMovies = (searchQuery ? filteredMovies : watchList).sort((a, b) => {
    const priorityA = parseInt((a.priority ?? '0').toString(), 10);
    const priorityB = parseInt((b.priority ?? '0').toString(), 10);
    return sortOrder === 'asc' ? priorityA - priorityB : priorityB - priorityA;
  });

  // handles removing from towatchlist if wanted
  const handleRemoveFromWatchList = (movieId: number) => {
    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/towatchlist/entries/${movieId}`, {
      method: "DELETE",
      headers: {
        "X-API-KEY": api_key ?? "",
      },
    })
      .then(() => fetchWatchList())
      .catch(console.error);
  };

  // returns SearchBar component, onClick button, MovieCard component, priority option, rating option, MarkAsWatched option and RemoveFromWatchList option
  return (
    <div>
      <h2>My Watch List</h2>
      <SearchBar onSearch={handleSearch} />
      <button onClick={toggleSortOrder}>
        Sort by Priority ({sortOrder === 'asc' ? 'Descending' : 'Ascending'})
      </button>
      {watchList.length == 0 ? (
        <p>No movies in your watch list.</p>
      ) : (
        <div className="movie-grid">
          {sortedMovies.map((movie: any) => (
            <div>
              <MovieCard key={movie.id} movie={movie} />
              <label>Priority: </label>
              <select
                value={movie.priority}
                onChange={(e) => handlePriorityChange(movie.id, Number(e.target.value))}
              >
                {[...Array(10)].map((_, i) => (
                  <option key={i + 1} value={i + 1}>{i + 1}</option>
                ))}
              </select>
              <select
                value={selectedRating[movie.id] ?? ""}
                onChange={(e) =>
                  setSelectedRating({
                    ...selectedRating,
                    [movie.id]: parseInt(e.target.value),
                  })
                }
              >
                <option value="">Select rating</option>
                {[...Array(10)].map((_, i) => (
                  <option key={i + 1} value={i + 1}>{i + 1}</option>
                ))}
              </select>
              <button onClick={() => handleMarkAsWatched(movie)}>
                Mark as Watched
              </button>
              <button onClick={() => handleRemoveFromWatchList(movie.id)}>
                Remove from Watchlist
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

// exporting
export default WatchList;
