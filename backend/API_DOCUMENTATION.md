# 🚀 Money Transfer API - Complete Documentation

## Base URL
```
http://localhost:8000/api
```

---

## 🔐 Authentication

All protected endpoints require Bearer token:
```
Authorization: Bearer {token}
```

---

## 📡 API Endpoints

### Authentication

#### Register User
```http
POST /api/auth/register

Body:
{
  "first_name": "Aya",
  "last_name": "Ahmed",
  "email": "aya@example.com",
  "password": "Password@123",
  "password_confirmation": "Password@123",
  "phone": "+9647701234567",
  "country": "IQ"
}

Response:
{
  "success": true,
  "data": {
    "user": {...},
    "token": "..."
  }
}
```

#### Register Agent
```http
POST /api/auth/register/agent

Body:
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "agent@example.com",
  "password": "Password@123",
  "password_confirmation": "Password@123",
  "phone": "+9647701234567",
  "business_name": "Baghdad Exchange",
  "address": "Al-Mansour, Baghdad",
  "city": "Baghdad",
  "country": "IQ",
  "latitude": 33.3152,
  "longitude": 44.3661,
  "supported_currencies": ["USD", "EUR", "IQD"]
}
```

#### Login
```http
POST /api/auth/login

Body:
{
  "email": "aya@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "data": {
    "user": {...},
    "token": "...",
    "role": "user"
  }
}
```

---

## 🔍 Transfer Services Search (KEY FEATURE)

#### Search Services
```http
GET /api/transfer-services/search?to_country=IQ&payout_method=cash_pickup&speed=same_day&amount=100

Query Parameters:
- to_country (required): Destination country code
- from_country: Source country  
- payout_method: bank_deposit, cash_pickup, mobile_wallet
- speed: instant, same_day, next_day, 2-3_days, 3-5_days
- max_fee: Maximum acceptable fee
- from_currency: Source currency
- to_currency: Destination currency
- promoted: true/false
- sort_by: fee_low, fee_high, speed, popularity

Response:
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "name": "US to Iraq - Same Day",
      "speed": "same_day",
      "estimated_time": "1-2 hours",
      "fixed_fee": 2.99,
      "percentage_fee": 0.0150,
      "calculated_fee": 4.49,
      "total_cost": 104.49,
      "payout_methods": {
        "bank_deposit": true,
        "cash_pickup": true,
        "mobile_wallet": false
      }
    }
  ]
}
```

#### Calculate Quote
```http
POST /api/transfer-services/calculate-quote

Body:
{
  "send_amount": 100,
  "from_currency": "USD",
  "to_currency": "IQD",
  "payout_method": "cash_pickup",
  "speed": "same_day"
}

Response:
{
  "success": true,
  "data": {
    "send_amount": 100.00,
    "send_currency": "USD",
    "receive_amount": 131000.00,
    "receive_currency": "IQD",
    "exchange_rate": 1310.00,
    "transfer_fee": 4.49,
    "total_amount": 104.49,
    "transfer_speed": "same_day",
    "estimated_arrival": "1-2 hours"
  }
}
```

#### Get Currencies
```http
GET /api/transfer-services/currencies

Response:
{
  "success": true,
  "data": [
    {
      "code": "USD",
      "name": "US Dollar",
      "symbol": "$",
      "flag_emoji": "🇺🇸",
      "is_popular": true
    }
  ]
}
```

---

## 🏦 Bank Accounts

```http
GET    /api/bank-accounts              # List all
POST   /api/bank-accounts              # Add new
POST   /api/bank-accounts/{id}/verify  # Verify
PUT    /api/bank-accounts/{id}         # Update
DELETE /api/bank-accounts/{id}         # Delete
```

---

## 💳 Payment Methods

```http
GET    /api/payment-methods                # List all
POST   /api/payment-methods/card          # Add card
POST   /api/payment-methods/ewallet       # Add e-wallet
POST   /api/payment-methods/mobile-money  # Add mobile money
PUT    /api/payment-methods/{id}          # Update
DELETE /api/payment-methods/{id}          # Delete
```

---

## 👥 Beneficiaries

```http
GET    /api/beneficiaries      # List all
POST   /api/beneficiaries      # Add new
PUT    /api/beneficiaries/{id} # Update
DELETE /api/beneficiaries/{id} # Delete
```

---

## 💸 Transactions

```http
GET    /api/transactions                 # List user's transactions
POST   /api/transactions                 # Create transfer
GET    /api/transactions/{id}            # Get details
GET    /api/transactions/{id}/track      # Track status
POST   /api/transactions/{id}/cancel     # Cancel
GET    /api/transactions/statistics/summary  # Get stats
```

---

## 🗺️ Agents (Map Features)

#### Get Nearby Agents
```http
GET /api/agents/nearby?lat=33.3152&lng=44.3661&radius=10

Response:
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "business_name": "Baghdad Exchange Center",
      "address": "Al-Mansour, Baghdad",
      "latitude": 33.3152,
      "longitude": 44.3661,
      "distance": 2.5,
      "rating": 4.8,
      "working_hours": {...},
      "is_open_now": true
    }
  ]
}
```

#### Get All Agents for Map
```http
GET /api/agents/map

Response: Array of all agents with coordinates
```

---

## 👨‍💼 Agent Dashboard (Protected)

```http
GET  /api/agent/dashboard       # Agent statistics
GET  /api/agent/transactions    # Agent's transactions
POST /api/agent/transactions/{id}/process  # Process payout
PUT  /api/agent/profile         # Update profile
```

---

## 🛡️ Admin Dashboard (Protected)

```http
GET  /api/admin/dashboard               # Platform statistics
GET  /api/admin/agents/pending          # Pending agents
POST /api/admin/agents/{id}/approve     # Approve agent
POST /api/admin/agents/{id}/reject      # Reject agent
GET  /api/admin/transactions            # All transactions
PUT  /api/admin/exchange-rates          # Update rates
POST /api/admin/promotions              # Create promotion
GET  /api/admin/reports                 # Generate reports
```

---

## 🏷️ Promotions

```http
POST /api/promotions/apply

Body:
{
  "code": "WELCOME10",
  "transaction_amount": 100.00
}

Response:
{
  "success": true,
  "data": {
    "discount_amount": 10.00,
    "final_amount": 90.00
  }
}
```

---

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "data": {...},
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {...}
}
```

---

## 🔒 Role-Based Access

| Endpoint | User | Agent | Admin |
|----------|------|-------|-------|
| /api/bank-accounts | ✅ | ✅ | ✅ |
| /api/transactions | ✅ | ✅ | ✅ |
| /api/agent/* | ❌ | ✅ | ✅ |
| /api/admin/* | ❌ | ❌ | ✅ |

---

## 🧪 Test Accounts

**Admin:**
- Email: admin@moneytransfer.com
- Password: Admin@123

**Agent:**
- Email: agent@example.com  
- Password: Agent@123

**User:**
- Email: aya@example.com
- Password: password123

---

**Complete API with 50+ endpoints ready! 🚀**

