// importing
import React from "react";
import type { MovieCardProps } from '../types/types'
import { Link } from 'react-router'
import '../styles/MovieCard.css'

// declaring the MovieCard component
const MovieCard: React.FC<MovieCardProps> = ({ movie, onQuickAdd }) => {
  // returns a container with image of poster, title and average vote for a movie
  return (
    <div className="movie-card">
      {
        <img
          src={movie.poster}
          alt={movie.title}
        />
      }
      <Link to={`/movies/${movie.id}`}><h3>{movie.title}</h3></Link>
      <p>Average Vote: {movie.vote_average}</p>
      {onQuickAdd && (
        <button onClick={onQuickAdd}>
          Quick Add
        </button>
      )}
    </div>
  );
};

// exporting
export default MovieCard;
