// importing
import { useAuth } from '../context/AuthContext';
import { useState, useEffect } from 'react';
import MovieCard from './MovieCard';
import SearchBar from './SearchBar';
import type { Movie } from '../types/types';
import '../styles/MovieCatalogue.css';
import { Link } from 'react-router';

// declaring the component MovieList
const MovieList = () => {
  const { api_key } = useAuth();
  const [searchQuery, setSearchQuery] = useState("");
  const [filteredMovies, setFilteredMovies] = useState<Movie[]>([]);
  const [selectedGenre, setSelectedGenre] = useState("");

  // handles when a particular movie is searched or movie with a particular term
  const handleSearch = (query: string) => {
    setSearchQuery(query);
  };
  useEffect(() => {
    if (!api_key) return;
    const params = new URLSearchParams();
    if (searchQuery) params.append("title", searchQuery);
    if (selectedGenre) params.append("genre", selectedGenre);

    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/movies?${params.toString()}`, {
      headers: {
        'X-API-Key': api_key,
      }
    })
      .then(res => res.json())
      .then(data => {
        setFilteredMovies(data);
      });
  }, [searchQuery, selectedGenre, api_key]);

  // adding a movie to watchlist when quickadd button clicked 
  const handleQuickAdd = async (movieId: number) => {
    try {
      const formData = new URLSearchParams();
      formData.append('movie_id', movieId.toString());
      formData.append('priority', '5'); // default priority
      formData.append('notes', '');     // empty notes

      const response = await fetch(
        'https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/towatchlist/entries',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-API-Key': api_key ?? '',
          },
          body: formData.toString(),
        }
      );

      if (!response.ok) {
        const err = await response.json();
        console.error('Failed to add movie:', err);
        alert(`Error: ${err.error || 'Failed to add movie to watchlist.'}`);
      } else {
        alert('Movie added to your plan-to-watch list!');
      }
    } catch (error) {
      console.error('Error adding movie to watchlist:', error);
    }
  };

  // returns a navigation pane, searchbar, genre filter and moviecard component
  return (
    <div>
      <nav>
        <ul>
          <li><Link to="/movies">Movie Catalogue</Link></li>
          <li><Link to="/towatchlist/entries">Watchlist</Link></li>
          <li><Link to="/completedwatchlist/entries">Completed List</Link></li>
        </ul>
      </nav>
      <h1>All Movies</h1>
      <SearchBar onSearch={handleSearch} />
      <select onChange={e => setSelectedGenre(e.target.value)}>
        <option value="">All Genres</option>
        <option value="Romance">Romance</option>
        <option value="Comedy">Comedy</option>
        <option value="Action">Action</option>
        <option value="Adventure">Adventure</option>
      </select>
      <div className="movie-grid">
        {filteredMovies.map(movie => (
          <div>
            <MovieCard
              key={movie.id}
              movie={movie}
              onQuickAdd={() => handleQuickAdd(movie.id)}
            />
          </div>
        ))}
      </div>
    </div>
  );
};

// exporting
export default MovieList;
