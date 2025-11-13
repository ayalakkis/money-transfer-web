# Money Transfer Frontend - React Application

A modern, responsive React frontend for the Money Transfer Application, inspired by Wise's UI/UX.

---

## 🚀 Quick Start

### Prerequisites
- Node.js 18+ and npm
- Backend API running on `http://localhost:8000`

### Installation

```bash
# Navigate to frontend directory
cd frontend

# Install dependencies
npm install

# Create environment file
cp .env.example .env

# Edit .env with your API URL and Stripe key
# VITE_API_URL=http://localhost:8000/api
# VITE_STRIPE_PUBLIC_KEY=pk_test_...

# Start development server
npm run dev
```

Visit: **http://localhost:3000**

---

## ✅ What's Implemented

### Core Infrastructure (100%)
- ✅ React 18 + Vite
- ✅ React Router v6
- ✅ Tailwind CSS styling
- ✅ Axios API client
- ✅ React Query for data fetching
- ✅ Zustand for state management
- ✅ Toast notifications
- ✅ Responsive design (mobile-first)

### Layouts (100%)
- ✅ MainLayout - Public pages with header/footer
- ✅ AuthLayout - Login/Register pages
- ✅ DashboardLayout - Protected dashboard with sidebar

### Pages - Completed
- ✅ **HomePage** - Landing page with hero, features, CTA
- ✅ **LoginPage** - User authentication with social login UI
- ✅ **RegisterPage** - User registration form
- ✅ **DashboardPage** - Overview with stats and quick actions

### Pages - TODO (Placeholders Created)
- ⏳ **SendMoneyPage** - Main transfer feature (PRIORITY)
- ⏳ **TransactionsPage** - Transaction history with filters
- ⏳ **BankAccountsPage** - Manage bank accounts
- ⏳ **PaymentMethodsPage** - Manage cards & e-wallets
- ⏳ **BeneficiariesPage** - Manage recipients
- ⏳ **ProfilePage** - User profile settings

### Components
- ✅ Button - Reusable button component
- ✅ Input - Form input with validation
- ✅ Card - Container component

### API Integration
- ✅ Authentication API
- ✅ Bank Accounts API
- ✅ Payment Methods API
- ✅ Transactions API
- ✅ Beneficiaries API
- ✅ Interceptors (auth tokens, error handling)

---

## 📁 Project Structure

```
frontend/
├── public/              # Static files
├── src/
│   ├── components/      # Reusable components
│   │   └── ui/         # UI components (Button, Input, Card)
│   ├── layouts/        # Page layouts
│   │   ├── MainLayout.jsx       # Public pages
│   │   ├── AuthLayout.jsx       # Login/Register
│   │   └── DashboardLayout.jsx  # Dashboard
│   ├── pages/          # Page components
│   │   ├── HomePage.jsx
│   │   ├── auth/
│   │   │   ├── LoginPage.jsx
│   │   │   └── RegisterPage.jsx
│   │   └── dashboard/
│   │       ├── DashboardPage.jsx
│   │       ├── SendMoneyPage.jsx          # TODO
│   │       ├── TransactionsPage.jsx       # TODO
│   │       ├── BankAccountsPage.jsx       # TODO
│   │       ├── PaymentMethodsPage.jsx     # TODO
│   │       ├── BeneficiariesPage.jsx      # TODO
│   │       └── ProfilePage.jsx            # TODO
│   ├── stores/         # Zustand stores
│   │   └── authStore.js
│   ├── lib/            # Utilities
│   │   └── api.js      # Axios instance & API endpoints
│   ├── App.jsx         # Main app with routes
│   ├── main.jsx        # Entry point
│   └── index.css       # Global styles
├── .env.example        # Environment variables template
├── package.json        # Dependencies
├── vite.config.js      # Vite configuration
├── tailwind.config.js  # Tailwind configuration
└── README.md           # This file
```

---

## 🎨 Design System

### Colors
- **Primary**: Green (#22c55e) - Inspired by Wise
- **Secondary**: Gray tones
- **Success**: Green (#22c55e)
- **Error**: Red (#ef4444)
- **Warning**: Yellow (#eab308)

### Typography
- Font: Inter (Google Fonts)
- Sizes: text-sm, text-base, text-lg, text-xl, etc.

### Components
- Rounded corners: rounded-lg (8px)
- Shadows: shadow-sm, shadow-md
- Transitions: 200ms ease-in-out

---

## 🔌 API Integration

The frontend connects to your Laravel backend via `/lib/api.js`:

```javascript
// Example usage
import { bankAccountsAPI } from './lib/api'

// Fetch all bank accounts
const response = await bankAccountsAPI.getAll()

// Add new bank account
const newAccount = await bankAccountsAPI.create(data)
```

### API Endpoints Available:
- Auth: login, register, logout
- Bank Accounts: CRUD + verify
- Payment Methods: CRUD (cards & e-wallets)
- Transactions: CRUD + statistics
- Beneficiaries: CRUD

---

## 🚧 How to Complete Remaining Pages

### Priority 1: SendMoneyPage (Main Feature)

**File**: `src/pages/dashboard/SendMoneyPage.jsx`

**Steps to implement**:
1. Create multi-step form (4 steps)
2. Step 1: Amount input
   - From/To currency selection
   - Amount input
   - Show exchange rate
   - Calculate fees
3. Step 2: Beneficiary selection
   - List beneficiaries
   - Add new beneficiary inline
4. Step 3: Payment method
   - List saved methods
   - Add new card/e-wallet
5. Step 4: Review & Confirm
   - Show all details
   - Confirm button → create transaction
6. Success screen
   - Transaction ID
   - Track button
   - Send another

**Example structure**:
```jsx
const SendMoneyPage = () => {
  const [step, setStep] = useState(1)
  const [formData, setFormData] = useState({
    amount: '',
    from_currency: 'USD',
    to_currency: 'EUR',
    beneficiary_id: '',
    payment_method_id: '',
  })

  return (
    <div>
      {step === 1 && <AmountStep />}
      {step === 2 && <BeneficiaryStep />}
      {step === 3 && <PaymentStep />}
      {step === 4 && <ReviewStep />}
    </div>
  )
}
```

### Priority 2: BankAccountsPage

**File**: `src/pages/dashboard/BankAccountsPage.jsx`

**Features**:
- List all bank accounts (useQuery)
- Add new account button → modal/form
- Verify account (micro-deposits)
- Set default account
- Delete account
- Show verified/unverified status

**API calls**:
```javascript
const { data } = useQuery('bank-accounts', bankAccountsAPI.getAll)
const addMutation = useMutation(bankAccountsAPI.create)
const verifyMutation = useMutation((id, amounts) => 
  bankAccountsAPI.verify(id, amounts)
)
```

### Priority 3: PaymentMethodsPage

**File**: `src/pages/dashboard/PaymentMethodsPage.jsx`

**Features**:
- List cards and e-wallets
- Add card (Stripe Elements integration)
- Add e-wallet
- Set default
- Delete

**Stripe Integration**:
```bash
npm install @stripe/stripe-js @stripe/react-stripe-js
```

```jsx
import { loadStripe } from '@stripe/stripe-js'
import { Elements, CardElement } from '@stripe/react-stripe-js'

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY)

// Use Stripe Elements for card input
```

### Priority 4: TransactionsPage

**File**: `src/pages/dashboard/TransactionsPage.jsx`

**Features**:
- List transactions with pagination
- Filters: status, date range
- Search by amount/beneficiary
- Export to CSV/PDF
- View transaction details (modal)

### Priority 5: BeneficiariesPage & ProfilePage

Similar patterns to BankAccountsPage - CRUD operations with forms.

---

## 🎯 Features to Add

### Authentication Enhancements
- [ ] Social login (Google, Facebook, Apple) - OAuth integration
- [ ] Forgot password flow
- [ ] Email verification
- [ ] Two-factor authentication

### Dashboard Features
- [ ] Real-time notifications (WebSocket)
- [ ] Currency exchange rate chart
- [ ] Transaction history export
- [ ] Referral program
- [ ] Multi-language support

### Advanced Features
- [ ] Search functionality
- [ ] Dark mode toggle
- [ ] Progressive Web App (PWA)
- [ ] Offline support
- [ ] Push notifications

---

## 🧪 Testing

```bash
# Run tests (add tests first)
npm test

# Build for production
npm run build

# Preview production build
npm run preview
```

---

## 📱 Responsive Design

The app is fully responsive:
- **Mobile**: < 768px (mobile-first)
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

Test on different devices using browser DevTools!

---

## 🔐 Authentication Flow

1. User registers/logs in
2. JWT token stored in localStorage
3. Token added to all API requests (interceptor)
4. Protected routes check auth status
5. Expired token → redirect to login

**AuthStore** (`stores/authStore.js`):
```javascript
const { user, login, logout, isAuthenticated } = useAuthStore()
```

---

## 🚀 Deployment

### Build for Production

```bash
npm run build
```

Creates optimized build in `dist/` folder.

### Deploy Options

1. **Vercel** (Recommended)
   ```bash
   npm install -g vercel
   vercel
   ```

2. **Netlify**
   ```bash
   npm install -g netlify-cli
   netlify deploy
   ```

3. **GitHub Pages**
   - Configure `vite.config.js` base path
   - Push to GitHub
   - Enable Pages in settings

### Environment Variables

Set these in your hosting platform:
- `VITE_API_URL` - Your backend API URL
- `VITE_STRIPE_PUBLIC_KEY` - Stripe publishable key

---

## 📊 State Management

### Zustand Stores

**Auth Store** (`stores/authStore.js`):
- User data
- Login/logout
- Token management

**Add more stores as needed**:
```javascript
// stores/transactionStore.js
export const useTransactionStore = create((set) => ({
  transactions: [],
  addTransaction: (tx) => set((state) => ({
    transactions: [...state.transactions, tx]
  })),
}))
```

---

## 🎨 UI Components

### Button
```jsx
<Button variant="primary" size="md" loading={false}>
  Click Me
</Button>
```

**Variants**: primary, secondary, outline, danger, ghost
**Sizes**: sm, md, lg

### Input
```jsx
<Input
  label="Email"
  type="email"
  icon={<Mail />}
  error="Invalid email"
/>
```

### Card
```jsx
<Card title="Title" subtitle="Subtitle" action={<Button>Action</Button>}>
  Content
</Card>
```

---

## 🐛 Common Issues

### Port 3000 already in use
```bash
# Change port in vite.config.js
server: { port: 3001 }
```

### API CORS errors
- Ensure Laravel backend has CORS configured
- Check `backend/config/cors.php`

### Tailwind not working
```bash
npm run dev
# Restart dev server
```

### Stripe not loading
- Check `VITE_STRIPE_PUBLIC_KEY` in `.env`
- Verify key starts with `pk_test_` or `pk_live_`

---

## 📚 Resources

- [React Docs](https://react.dev)
- [Vite Guide](https://vitejs.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [React Router](https://reactrouter.com)
- [React Query](https://tanstack.com/query)
- [Stripe React](https://stripe.com/docs/stripe-js/react)

---

## 🎓 Learning Path

1. ✅ Understand project structure
2. ✅ Review completed pages
3. ⏳ Complete SendMoneyPage (priority)
4. ⏳ Add Stripe integration
5. ⏳ Complete remaining pages
6. ⏳ Add tests
7. ⏳ Deploy to production

---

## 👥 Team Collaboration

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/send-money-page

# Make changes
git add .
git commit -m "feat: implement send money page"

# Push to Azure DevOps
git push origin feature/send-money-page

# Create pull request
```

### Azure DevOps Integration
- Create tickets for each page
- Move tickets: To Do → Doing → Done
- Link commits to work items
- Daily updates required

---

## ✅ Checklist for Completion

### Must Have (MVP)
- [ ] Complete SendMoneyPage
- [ ] Complete BankAccountsPage
- [ ] Complete PaymentMethodsPage
- [ ] Complete TransactionsPage
- [ ] Add Stripe integration
- [ ] Test all features
- [ ] Mobile responsive

### Nice to Have
- [ ] Social login
- [ ] Real-time notifications
- [ ] Dark mode
- [ ] Multi-language
- [ ] PWA support

---

## 🎉 Summary

You now have:
- ✅ Complete React project structure
- ✅ Modern UI with Tailwind CSS
- ✅ Authentication system
- ✅ API integration
- ✅ Dashboard layout
- ✅ Main pages (landing, login, register, dashboard)
- ⏳ Placeholder pages ready to complete

**Next Steps**:
1. Run `npm install`
2. Start dev server
3. Complete SendMoneyPage (main feature)
4. Complete remaining pages
5. Test everything
6. Deploy!

---

**Happy Coding! 🚀**

For questions, refer to the parent README.md or backend documentation.

