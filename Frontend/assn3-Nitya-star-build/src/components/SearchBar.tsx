// importing
import { useState } from 'react';

// declaring type SearchBarProps
type SearchBarProps = {
  onSearch: (query: string) => void;
  placeholder?: string;
};

// declaring the component SearchBar
const SearchBar = ({ onSearch, placeholder = "Search movies..." }: SearchBarProps) => {
  const [searchTerm, setSearchTerm] = useState("");

  // handles a change when something is searched
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value;
    setSearchTerm(value);
    onSearch(value);
  };

  return (
    <div>
    <p>Search a movie here:</p>
      <input
        type="text"
        value={searchTerm}
        onChange={handleChange}
        placeholder={placeholder}
      />
    </div>
  );
};

// exporting
export default SearchBar;
