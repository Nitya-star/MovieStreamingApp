// importing
import { useState } from 'react';
import { Link, useNavigate } from 'react-router';
import type { FormEvent } from 'react';
import type { LoginFormProps } from '../types/types';
import { useAuth } from '../context/AuthContext';
import '../styles/LoginForm.css'

// declaring the component LoginForm
const LoginForm = ({ onLogin }: LoginFormProps) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { setApiKey } = useAuth();
  const navigate = useNavigate();

  // handles when a user submits credentials and try to login checking if valid credentials or not
  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError('');

    try {
      const res = await fetch(`https://loki.trentu.ca/~nityashah/3430/assn/assn2-Nitya-star/api/users/session`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: username,
          password: password
        }),
      });
      if (!res.ok) {
        throw new Error('Login failed');
      }
      const data = await res.json();
      if (data.api_key) {
        onLogin(data.api_key);
        setApiKey(data.api_key);
        navigate('/movies');
      } else {
        setError('Invalid login credentials');
      }
    } catch (err) {
      setError('Login error: ' + (err as Error).message);
    }
  };

  // returns basic login container including username and password
  return (
    <div className='login-container'>
      <h2>Login</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Username</label>
          <input
            type="text"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Password</label>
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        {error && <p>{error}</p>}
        <button type="submit">Login</button>
      </form>
      <p>
        Not registered? <Link to="/register">Create an account</Link>
      </p>
    </div>
  );
};

// exporting
export default LoginForm;
