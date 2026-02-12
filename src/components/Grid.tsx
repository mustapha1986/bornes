import React, { useMemo } from 'react';
import { Station } from '../services/api';

type GridProps = {
  stations: Station[];
  size?: number;
};

const Grid: React.FC<GridProps> = ({ stations, size = 12 }) => {
  const stationMap = useMemo(() => {
    return new Map(stations.map((station) => [`${station.x}-${station.y}`, station]));
  }, [stations]);

  const cells = Array.from({ length: size * size }, (_, index) => {
    const x = index % size;
    const y = Math.floor(index / size);
    const station = stationMap.get(`${x}-${y}`);
    const statusClass = station ? `station-cell--${station.status}` : 'station-cell--empty';
    const label = station?.id ? `#${station.id.slice(-4)}` : '';

    return (
      <div key={`${x}-${y}`} className={`station-cell ${statusClass}`}>
        <div className="station-cell__inner">
          {station && <span className="station-dot" />}
          <span>{label}</span>
        </div>
      </div>
    );
  });

  return (
    <div
      className="grid-board"
      style={{ gridTemplateColumns: `repeat(${size}, minmax(0, 1fr))` }}
    >
      {cells}
    </div>
  );
};

export default Grid;
