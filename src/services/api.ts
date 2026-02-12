const API_URL = import.meta.env.VITE_API_URL ?? 'http://localhost:8000';

const buildHeaders = (token?: string) => ({
  'Content-Type': 'application/json',
  ...(token ? { Authorization: `Bearer ${token}` } : {}),
});

export type StationStatus = 'disponible' | 'en_charge' | 'en_panne';

export type Station = {
  id: string;
  x: number;
  y: number;
  status: StationStatus;
  updatedAt: string;
};

export type Stats = {
  total: number;
  disponible: number;
  en_charge: number;
  en_panne: number;
};

export type AuthResponse = {
  token: string;
  user: { username: string };
};

export async function login(username: string, password: string): Promise<AuthResponse> {
  const response = await fetch(`${API_URL}/api/auth/login`, {
    method: 'POST',
    headers: buildHeaders(),
    body: JSON.stringify({ username, password }),
  });

  if (!response.ok) {
    const data = await response.json().catch(() => ({}));
    throw new Error(data.error ?? 'Erreur d9authentification.');
  }

  return response.json();
}

export async function fetchStations(token: string): Promise<Station[]> {
  const response = await fetch(`${API_URL}/api/stations`, {
    headers: buildHeaders(token),
  });

  if (!response.ok) {
    throw new Error('Impossible de charger les stations.');
  }

  const data = await response.json();
  return data.stations ?? [];
}

export async function fetchStats(token: string): Promise<Stats> {
  const response = await fetch(`${API_URL}/api/stats`, {
    headers: buildHeaders(token),
  });

  if (!response.ok) {
    throw new Error('Impossible de charger les statistiques.');
  }

  const data = await response.json();
  return data.stats ?? { total: 0, disponible: 0, en_charge: 0, en_panne: 0 };
}
