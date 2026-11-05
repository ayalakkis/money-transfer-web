# Money Transfer Platform

A full-stack platform for comparing and managing international money transfers. The backend is a Laravel REST API with role-based access; the frontend is a React single-page app built on the Sneat dashboard design.

## Overview

The system serves three kinds of users:

- **Users** search and compare transfer services, manage bank accounts and payment methods, and track transfers.
- **Agents** process cash payouts and manage their location and working hours.
- **Admins** approve agents, manage exchange rates and promotions, and view reports.

## Tech stack

| Layer | Technology |
|---|---|
| Backend | Laravel (PHP), Laravel Sanctum token authentication |
| Database | MySQL (9 migrations, 14 tables) |
| Frontend | React, Tailwind CSS, Sneat dashboard design |

## Features

- Multi-role authentication (user, agent, admin) with role-based middleware
- Transfer service search, filterable by destination country, fees, exchange rate, transfer speed, payout method and promotions
- Quote calculation and exchange rate management
- Bank accounts with micro-deposit verification, and multiple payment methods (cards, e-wallets, mobile money)
- Agent directory with latitude/longitude, a map endpoint and nearby-agent search
- Admin dashboard: agent approval, exchange rates, promotions and reports
- Rate limiting and CORS configuration

## Project status

- **Backend:** API implemented (6 controllers, role middleware, exchange rate service, seeder).
- **Frontend:** 4 of 10 pages implemented (home, login, register, dashboard). The remaining pages (send money, transactions, bank accounts, payment methods, beneficiaries, profile) are scaffolded.
- The frontend runs in **demo mode** with mock data by default, so it works without the backend.

## Getting started

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# set database credentials and API keys in .env
mysql -u root -p -e "CREATE DATABASE money_transfer;"
php artisan migrate --seed
php artisan serve
```

The API is served at `http://localhost:8000`. The seeder creates demo accounts for each role.

### Frontend

```bash
cd frontend
npm install
npm run dev
```

The app is served at `http://localhost:3000`.

### Connecting the frontend to the backend

The frontend starts in demo mode. To use the live API, start the backend, set `MOCK_MODE` to `false` in `frontend/src/stores/authStore.js`, and restart the dev server.

## API overview

Public:

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api/health` | Health check |
| GET | `/api/transfer-services/search` | Search and filter transfer services |
| POST | `/api/transfer-services/calculate-quote` | Calculate a transfer quote |
| GET | `/api/transfer-services/currencies` | Supported currencies |
| GET | `/api/agents/map` | Agents with coordinates for map display |
| GET | `/api/agents/nearby` | Location-based agent lookup |

Authenticated (user):

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/auth/register`, `/api/auth/login` | Authentication |
| GET, POST | `/api/bank-accounts` | List and add bank accounts |
| POST | `/api/bank-accounts/{id}/verify` | Verify an account with micro-deposits |
| GET, POST | `/api/payment-methods` | Manage payment methods |
| GET, POST | `/api/transactions` | List and create transfers |
| GET | `/api/beneficiaries` | List recipients |

Agent and admin routes cover payout processing, agent profiles, agent approval, exchange rates, promotions and reports. The full reference is in [`backend/API_DOCUMENTATION.md`](backend/API_DOCUMENTATION.md).

## Documentation

- [Backend setup and features](backend/README.md)
- [API reference](backend/API_DOCUMENTATION.md)
- [Frontend setup](frontend/README.md)

## Roadmap

- Implement the remaining frontend pages and connect them to the API
- Add automated tests for the API and validation rules
