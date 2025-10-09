import { Route, Routes } from 'react-router-dom';
import LoginForm from './components/LoginForm';
import MovieList from './components/MovieCatalogue'
import MovieDetail from './components/MovieDetail';
import WatchList from './components/WatchList';
import CompletedList from './components/CompletedList';

function App() {
  // Handling the login of a user
  const handleLogin = (apiKey: string) => {
    console.log('Logged in! API Key:', apiKey);
  };
  
  // Using components according to the path
  return (
      <Routes>
        <Route path="/" element={<LoginForm onLogin={handleLogin} />} />
        <Route path="/movies" element={<MovieList />} />
        <Route path="/movies/:id" element={<MovieDetail />} />
        <Route path="/towatchlist/entries" element={<WatchList /> }/>
        <Route path="/completedwatchlist/entries" element={<CompletedList />} />
      </Routes>
  );
}

export default App;
