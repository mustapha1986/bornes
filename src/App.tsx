import React, { useCallback, useEffect, useState } from 'react';
import './styles/main.scss';
import Grid from './components/Grid';
import LoginForm from './components/LoginForm';
import StatsPanel from './components/StatsPanel';
import { fetchStations, fetchStats, login, Station, Stats } from './services/api';

const App: React.FC = () => {
  const [token, setToken] = useState<string | null>(() => localStorage.getItem('authToken'));
  const [stations, setStations] = useState<Station[]>([]);
  const [stats, setStats] = useState<Stats | null>(null);
  const [authLoading, setAuthLoading] = useState(false);
  const [authError, setAuthError] = useState<string | null>(null);
  const [dataLoading, setDataLoading] = useState(false);
  const [lastUpdated, setLastUpdated] = useState<Date | null>(null);

  const handleLogin = async (username: string, password: string) => {
    setAuthLoading(true);
    setAuthError(null);
    try {
      const result = await login(username, password);
      setToken(result.token);
      localStorage.setItem('authToken', result.token);
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Erreur de connexion.';
      setAuthError(message);
    } finally {
      setAuthLoading(false);
    }
  };

  const handleLogout = () => {
    setToken(null);
    setStations([]);
    setStats(null);
    localStorage.removeItem('authToken');
  };

  const loadDashboard = useCallback(async () => {
    if (!token) {
      return;
    }
    setDataLoading(true);
    try {
      const [stationsData, statsData] = await Promise.all([
        fetchStations(token),
        fetchStats(token),
      ]);
      setStations(stationsData);
      setStats(statsData);
      setLastUpdated(new Date());
    } catch (error) {
      console.error(error);
    } finally {
      setDataLoading(false);
    }
  }, [token]);

  useEffect(() => {
    if (token) {
      loadDashboard();
    }
  }, [token, loadDashboard]);

  if (!token) {
    return (
      <div className="auth-shell">
        <div className="login-card">
          <div className="brand brand--stack">
            <span className="brand__glow" />
            <div>
              <p className="brand__eyebrow">Gireve</p>
              <h1>City Grid</h1>
            </div>
          </div>
          <p className="login-subtitle">Supervision temps réel des bornes de recharge.</p>
          <LoginForm onSubmit={handleLogin} loading={authLoading} error={authError} />
        </div>
      </div>
    );
  }

  const updatedLabel = lastUpdated
    ? `Dernière mise à jour ${lastUpdated.toLocaleTimeString('fr-FR')}`
    : 'Aucune mise à jour';

  return (
    <div className="app-shell">
      <aside className="sidebar">
        <div className="brand">
          <span className="brand__glow" />
          <div>
            <p className="brand__eyebrow">Gireve</p>
            <h1>City Grid</h1>
          </div>
        </div>
        <StatsPanel stats={stats} loading={dataLoading} />
        <div className="sidebar-actions">
          <button className="button button--ghost" onClick={loadDashboard} disabled={dataLoading}>
            Rafraîchir
          </button>
          <button className="button button--ghost" onClick={handleLogout}>
            Déconnexion
          </button>
        </div>
      </aside>
      <main className="main-area">
        <header className="main-header">
          <div>
            <h2>Réseau de bornes</h2>
            <p className="meta-text">{updatedLabel}</p>
          </div>
          <div className="status-pill">Mercure prêt</div>
        </header>
        <Grid stations={stations} />
      </main>
    </div>
  );
};

export default App;
