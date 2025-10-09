// type Movie
export interface Movie {
  id: number;
  title: string;
  poster?: string;
  vote_average?: number;
  genre?: string;
  priority?: number | string;
  date_last_watched? : Date;
  rating? : number;
}

// type MovieCardProps
export interface MovieCardProps {
  movie: Movie;
  onQuickAdd?: () => void;
}

// type LoginFormProps
export interface LoginFormProps {
  onLogin: (apiKey: string) => void;
}
