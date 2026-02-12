# Charge Hero – City Builder ⚡

Mini‑ville électrique interactive (React + TypeScript, Symfony + Mercure, MongoDB) avec grille 40×20, bornes de recharge, états dynamiques et notifications temps réel.

## ✨ Fonctionnalités
- Grille 40×20 (placement, suppression, réparation)
- États des bornes : `disponible`, `en_charge`, `en_panne`
- Changements d’état dynamiques + statistiques globales en temps réel via **Mercure**
- UX responsive + animations légères
- Style avancé en **SCSS** (variables, mixins, partials)

---

## ✅ Pré‑requis
- Node.js 18+
- PHP 8.2+
- Composer
- Docker (pour MongoDB et Mercure)

---

## 🚀 Lancer le projet

### 1) MongoDB (Docker)
```bash
docker run -d --name charge-hero-mongo -p 27017:27017 mongo:7
```

### 2) Mercure Hub (Docker)
```bash
docker run -d --name charge-hero-mercure -p 3000:80 
  -e SERVER_NAME=:80 
  -e MERCURE_PUBLISHER_JWT_KEY=supersecret 
  -e MERCURE_SUBSCRIBER_JWT_KEY=supersecret 
  dunglas/mercure
```

### 3) Backend (Symfony)
```bash
cd backend
composer install
php -S localhost:8000 -t public
```

Lancer le simulateur (changements d’état auto) :
```bash
php bin/console app:simulate-stations
```

### 4) Frontend (React + Vite)
```bash
cd frontend
npm install
npm run dev
```

---

## ⚙️ Variables d’environnement

### backend/.env
```
MONGODB_URI=mongodb://127.0.0.1:27017
MONGODB_DB=charge_hero
MERCURE_URL=http://localhost:3000/.well-known/mercure
MERCURE_PUBLIC_URL=http://localhost:3000/.well-known/mercure
MERCURE_JWT_SECRET=supersecret
```

### frontend/.env
```
VITE_API_URL=http://localhost:8000
VITE_MERCURE_URL=http://localhost:3000/.well-known/mercure
```

---

## 🧱 Choix techniques
- **React + TypeScript** : itérations rapides, typage strict, composants réutilisables
- **Symfony + Mercure** : API REST + temps réel propre et scalable
- **MongoDB** : stockage flexible, rapide à prototyper
- **SCSS** : architecture modulaire (variables, mixins, partials)

---

## 🎨 Parti pris visuel
- Thème énergie futuriste / mobilité verte
- États visuels différenciés (halo, fumée, étincelles)
- Transitions douces + animations légères

---

## 🔧 Trade‑offs
- Simulation d’événements via commande CLI (au lieu d’un worker dédié)
- Pas de persistance avancée (indexation ou historiques)

---

## 🚧 Améliorations futures
- Mini‑map et zoom/pan natif mobile
- Profils utilisateurs / sauvegarde
- Statistiques avancées et analytics
- Mode « défis » (objectifs & score)

---

## 📁 Structure
```
backend/     # Symfony + API + Mercure
frontend/    # React + TS + SCSS
```