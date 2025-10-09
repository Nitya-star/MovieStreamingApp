// importing
import { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import MovieCard from './MovieCard';
import SearchBar from './SearchBar';
import type { Movie } from '../types/types';
import '../styles/MovieCatalogue.css';

// declaring CompletedList component
const CompletedList = () => {
  const { api_key } = useAuth();
  const [completedList, setCompletedList] = useState<Movie[]>([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [filteredMovies, setFilteredMovies] = useState<Movie[]>([]);
  const [selectedRating, setSelectedRating] = useState<{ [key: number]: number }>({});
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');

  // fetchCompletedList to fetch completedwatch entries from api
  const fetchCompletedList = () => {
    if (!api_key) return;
    fetch('https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/completedwatchlist/entries', {
      headers: {
        "Content-Type": "application/json",
        "X-API-KEY": api_key,
      }
    })
      .then(res => res.json())
      .then((data) => {
        setCompletedList(data);
      })
      .catch(console.error);
  };
  useEffect(fetchCompletedList, [api_key]);

  // handling a search checking if the search item is present in title or not and provides movies which contain
  const handleSearch = (query: string) => {
    setSearchQuery(query);
    const lower = query.toLowerCase();
    const filtered = completedList.filter(movie =>
      movie.title.toLowerCase().includes(lower)
    );
    setFilteredMovies(filtered);
  };

  // increments the number of times for a movie watched 
  const handleWatchedAgain = (movieId: number) => {
    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/completedwatchlist/entries/${movieId}/times-watched`, {
      method: "PATCH",
      headers: {
        "X-API-KEY": api_key ?? "",
      }
    })
      .then(() => fetchCompletedList()) // refresh the list after updating
      .catch(console.error);
  };

  // allows the user to add a rating or update the rating for a movie
  const handleRatingChange = (movieId: number, newRating: number) => {
    const body = new URLSearchParams();
    body.append("rating", newRating.toString());

    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/completedwatchlist/entries/${movieId}/rating`, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
        "X-API-KEY": api_key ?? "",
      },
      body: body.toString(),
    })
      .then(() => fetchCompletedList())
      .catch(console.error);
  };

  // toggling the sort order when that button is clicked
  const toggleSortOrder = () => {
    setSortOrder(prev => (prev === 'asc' ? 'desc' : 'asc'));
  };

  // sorting the movies according to the rating given by the user
  const sortedList = [...(searchQuery ? filteredMovies : completedList)].sort((a, b) => {
    const ratingA = a.rating ?? 0;
    const ratingB = b.rating ?? 0;
    return sortOrder === 'asc' ? ratingA - ratingB : ratingB - ratingA;
  });

  // including searchbar and moviecard components in return along with various other elements
  return (
    <div>
      <h2>My Completed Movies</h2>
      <SearchBar onSearch={handleSearch} />
      <button onClick={toggleSortOrder}>
        Sorted by your Rating ({sortOrder === 'asc' ? 'Lowest First' : 'Highest First'})
      </button>
      {completedList.length == 0 ? (
        <p>No completed movies yet.</p>
      ) : (
        <div className="movie-grid">
          {sortedList.map((movie: any) => (
            <div>
              <MovieCard key={movie.id} movie={movie} />
              <label>Rate this movie: </label>
              <select
                value={movie.rating ?? ''}
                onChange={(e) => {
                  const newRating = parseInt(e.target.value);
                  setSelectedRating({ ...selectedRating, [movie.id]: newRating });
                  handleRatingChange(movie.id, newRating);
                }}
              >
                <option value="">--</option>
                {[...Array(10)].map((_, i) => (
                  <option key={i + 1} value={i + 1}>{i + 1}</option>
                ))}
              </select>
              <button onClick={() => handleWatchedAgain(movie.id)}>
                Watched Again
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

// exporting
export default CompletedList;
