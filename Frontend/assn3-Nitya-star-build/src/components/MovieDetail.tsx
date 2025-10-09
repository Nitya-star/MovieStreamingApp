// importing
import { useParams } from 'react-router';
import { useEffect, useState } from 'react';
import { useAuth } from '../context/AuthContext';

// declaring the component MovieDetail which shows detail for a movie when it's title is clicked
const MovieDetail = () => {
  const { id } = useParams();
  const { api_key } = useAuth();
  const [movie, setMovie] = useState<any>(null);

  useEffect(() => {
    if (!api_key || !id) return;

    fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/movies/${id}`, {
      headers: {
        Authorization: `Bearer ${api_key}`
      }
    })
      .then(res => res.json())
      .then(data => setMovie(data))
      .catch(console.error);
  }, [id, api_key]);

  if (!movie) return <p>Loading movie details...</p>;

  // provides the tite, tagline, overview and budget for the details
  return (
    <div>
      <h2>{movie.title}</h2>
      <p>{movie.tagline}</p>
      <p>{movie.overview}</p>
      <p><strong>Budget:</strong> ${movie.budget}</p>
    </div>
  );
};

// exporting
export default MovieDetail;
