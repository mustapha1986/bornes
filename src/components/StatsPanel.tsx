import React from 'react';
import { Stats } from '../services/api';

type StatsPanelProps = {
  stats: Stats | null;
  loading?: boolean;
};

const StatsPanel: React.FC<StatsPanelProps> = ({ stats, loading = false }) => {
  const items = [
    { label: 'Total', value: stats?.total ?? 0, tone: 'total' },
    { label: 'Disponibles', value: stats?.disponible ?? 0, tone: 'available' },
    { label: 'En charge', value: stats?.en_charge ?? 0, tone: 'charging' },
    { label: 'En panne', value: stats?.en_panne ?? 0, tone: 'offline' },
  ];

  return (
    <section className="stats-panel">
      <div className="stats-header">
        <h3>Statistiques</h3>
        <span className="stats-status">{loading ? 'Sync' : 'Live'}</span>
      </div>
      <div className="stats-list">
        {items.map((item) => (
          <div key={item.label} className={`stats-item stats-item--${item.tone}`}>
            <span>{item.label}</span>
            <strong>{item.value}</strong>
          </div>
        ))}
      </div>
    </section>
  );
};

export default StatsPanel;
