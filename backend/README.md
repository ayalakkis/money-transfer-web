# 💰 Money Transfer Backend - Secure Laravel API

Complete, production-ready Laravel API with multi-role authentication and all features.

---

## ✅ Features Implemented

### Multi-Role System
- ✅ **User** - Send money, manage accounts
- ✅ **Agent** - Process payouts, cash-in/out
- ✅ **Admin** - Platform management

### Financial Features
- ✅ Multiple bank accounts with verification
- ✅ Payment methods (Cards, E-wallets, Mobile Money)
- ✅ Stripe integration
- ✅ Beneficiary management

### Transfer System
- ✅ **Transfer services search** with filters:
  - Destination country ⭐
  - Transfer fees ⭐
  - Exchange rates ⭐
  - Transfer speed ⭐
  - Payout method ⭐
  - Promotions ⭐
- ✅ Real-time exchange rates (API integration)
- ✅ Fee calculation engine
- ✅ Multi-currency support

### Agent Features
- ✅ Agent registration & approval
- ✅ Location management (lat/long for maps)
- ✅ Working hours
- ✅ Commission tracking
- ✅ Cash-in/out processing
- ✅ **Map integration** ready

### Admin Features
- ✅ Approve/reject agents
- ✅ Manage exchange rates
- ✅ Monitor all transactions
- ✅ Create promotions
- ✅ View reports
- ✅ User management

### Security
- ✅ Laravel Sanctum authentication
- ✅ Role-based access control (Spatie Permission)
- ✅ Password encryption
- ✅ Input validation
- ✅ KYC verification
- ✅ Rate limiting
- ✅ CORS configuration
- ✅ Audit trails

---

## 🚀 Quick Start

```bash
cd backend

# Install dependencies (includes Spatie Permission for roles)
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure .env file with:
# - Database credentials
# - Stripe keys
# - Exchange Rate API key (free: https://exchangerate-api.com)

# Create database
mysql -u root -p -e "CREATE DATABASE money_transfer;"

# Run migrations (14 tables)
php artisan migrate

# Seed roles, currencies, test users
php artisan db:seed

# Start server
php artisan serve
```

Visit: http://localhost:8000/api/health

---

## 📁 Database Schema (14 Tables)

1. **users** - Multi-role users with KYC
2. **agents** - Agent profiles with location
3. **bank_accounts** - User bank accounts
4. **payment_methods** - Cards, e-wallets, mobile money
5. **beneficiaries** - Transfer recipients
6. **currencies** - Supported currencies
7. **exchange_rates** - Real-time rates
8. **transfer_services** - Routes & fees
9. **transactions** - Money transfers
10. **transaction_status_history** - Status tracking
11. **payout_locations** - Cash pickup locations
12. **reviews** - Ratings
13. **notifications** - User notifications
14. **promotions** - Discount codes

Plus: Spatie Permission tables (roles, permissions, model_has_roles, etc.)

---

## 👥 Test Accounts

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@moneytransfer.com | Admin@123 |
| **Agent** | agent@example.com | Agent@123 |
| **User** | aya@example.com | password123 |

---

## 📡 API Endpoints (50+)

### Public
- Health check
- Transfer services search ⭐
- Calculate quote
- Get currencies
- Get agents map

### User (Protected)
- Bank accounts (CRUD + verify)
- Payment methods (CRUD)
- Beneficiaries (CRUD)
- Transactions (create, track, cancel)
- Reviews
- Promotions

### Agent (Protected)
- Dashboard stats
- Process payouts
- Update profile
- View commissions

### Admin (Protected)
- Approve agents
- Manage rates
- View all transactions
- Create promotions
- Generate reports

**See**: `API_DOCUMENTATION.md` for complete list

---

## 🔍 Transfer Services Search

**Key Feature** - Search with multiple filters:

```bash
GET /api/transfer-services/search?to_country=IQ&payout_method=cash_pickup&speed=same_day
```

Filters:
- ✅ Destination country (required)
- ✅ Payout method (bank, cash, mobile)
- ✅ Transfer speed (instant to 3-5 days)
- ✅ Max fee
- ✅ Currencies
- ✅ Promoted services
- ✅ Sort by (fee, speed, popularity)

---

## 🗺️ Agent Map Integration

Get agents with location:

```bash
# Nearby agents
GET /api/agents/nearby?lat=33.3152&lng=44.3661&radius=10

# All agents for map
GET /api/agents/map
```

Returns:
- Business name
- Address
- Coordinates (lat/long)
- Rating
- Working hours
- Distance (for nearby)

---

## 🛡️ Security Features

### Authentication
- Sanctum token-based auth
- Role-based permissions
- Token abilities per role
- Agent approval required

### Validation
- Request validation all endpoints
- Email verification
- KYC verification
- Document upload

### Protection
- Rate limiting (60 req/min)
- CORS configured
- SQL injection prevention
- XSS protection
- Soft deletes
- UUID keys

### Compliance
- Transaction risk assessment
- Suspicious activity flagging
- Audit trails
- KYC requirements

---

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_DATABASE=money_transfer
DB_USERNAME=root
DB_PASSWORD=your_password

# Stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# Exchange Rate API (Free: exchangerate-api.com)
EXCHANGE_RATE_API_KEY=your_api_key

# Frontend (CORS)
FRONTEND_URL=http://localhost:3000
```

---

## 📊 Database Relationships

```
User
├── Bank Accounts (1:N)
├── Payment Methods (1:N)
├── Beneficiaries (1:N)
├── Transactions (1:N)
├── Reviews (1:N)
└── Agent Profile (1:1) [if agent]

Agent
├── Payout Locations (1:N)
├── Transactions (1:N)
└── Reviews (1:N)

Transaction
├── User (Sender)
├── Beneficiary (Recipient)
├── Payment Method
├── Bank Account
├── Agent (if cash pickup)
├── Transfer Service
└── Status History (1:N)
```

---

## 🧪 Testing

```bash
# Health check
curl http://localhost:8000/api/health

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"aya@example.com","password":"password123"}'

# Search services
curl "http://localhost:8000/api/transfer-services/search?to_country=IQ&amount=100"
```

---

## 📚 Documentation

- **API_DOCUMENTATION.md** - Complete API reference
- **BACKEND_COMPLETE_GUIDE.md** - Features overview
- **README.md** - This file

---

## 🎯 What's Complete

| Feature | Status |
|---------|--------|
| Multi-role authentication | ✅ 100% |
| Bank accounts management | ✅ 100% |
| Payment methods | ✅ 100% |
| Transfer services search | ✅ 100% |
| Exchange rates | ✅ 100% |
| Agent system | ✅ 100% |
| Admin panel | ✅ 100% |
| Security features | ✅ 100% |
| Map integration | ✅ 100% |
| Database schema | ✅ 100% |
| API endpoints | ✅ 100% |

**Overall: 100% Complete** ✅

---

## 🚀 Connect to Frontend

1. Start backend: `php artisan serve`
2. In frontend, set `MOCK_MODE = false` in `authStore.js`
3. Update `.env` with `VITE_API_URL=http://localhost:8000/api`
4. Backend and frontend will work together!

---

**Production-ready, secure backend with all features! 🎉**
