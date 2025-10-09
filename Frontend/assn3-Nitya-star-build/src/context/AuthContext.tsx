import React, { createContext, useContext, useState } from "react";

// type AuthContextType
interface AuthContextType {
  api_key: string | null;
  setApiKey: (key: string) => void;
  clearApiKey: () => void;
}

// declaring AuthContext
const AuthContext = createContext<AuthContextType | undefined>(undefined);

// declaring AuthProvider to wrap App around with it
export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [api_key, setApiKeyState] = useState<string | null>(null);

  const setApiKey = (key: string) => setApiKeyState(key);
  const clearApiKey = () => setApiKeyState(null);

  return (
    <AuthContext.Provider value={{ api_key, setApiKey, clearApiKey }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
};
