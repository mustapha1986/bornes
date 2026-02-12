import React, { useState } from 'react';

type LoginFormProps = {
  onSubmit: (username: string, password: string) => void | Promise<void>;
  loading?: boolean;
  error?: string | null;
};

const LoginForm: React.FC<LoginFormProps> = ({ onSubmit, loading = false, error }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    onSubmit(username, password);
  };

  return (
    <form className="login-form" onSubmit={handleSubmit}>
      <label className="input-group">
        <span>Identifiant</span>
        <input
          className="input"
          type="text"
          autoComplete="username"
          placeholder="admin"
          value={username}
          onChange={(event) => setUsername(event.target.value)}
        />
      </label>
      <label className="input-group">
        <span>Mot de passe</span>
        <input
          className="input"
          type="password"
          autoComplete="current-password"
          placeholder="••••••••"
          value={password}
          onChange={(event) => setPassword(event.target.value)}
        />
      </label>
      {error && <div className="error">{error}</div>}
      <button className="button" type="submit" disabled={loading}>
        {loading ? 'Connexion...' : 'Se connecter'}
      </button>
    </form>
  );
};

export default LoginForm;
