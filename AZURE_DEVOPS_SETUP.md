# Azure DevOps Setup Guide

This guide will help you set up Azure DevOps for the Money Transfer Application project.

---

## Project Requirements

According to the project guidelines:

✅ Every team should create a project under Azure DevOps using only the UA emails  
✅ The instructor should be added to the created project with full privilege  
✅ Create sprints on Azure DevOps and add tickets to them  
✅ Tickets need to be updated every day to show the status of work (To do, Doing, Done)  
✅ Code is to be pushed whenever a feature is finished  
✅ The use of branches is recommended  

---

## Step 1: Create Azure DevOps Organization

1. Go to https://dev.azure.com
2. Sign in with your **UA email** (University of Anbar)
3. Click **"Create new organization"**
4. Name: `MoneyTransferApp` or similar
5. Region: Choose closest region
6. Click **"Continue"**

---

## Step 2: Create Project

1. Click **"+ New project"**
2. **Project name**: `Money Transfer Application`
3. **Visibility**: Private
4. **Work item process**: Agile (recommended) or Scrum
5. **Version control**: Git
6. Click **"Create"**

---

## Step 3: Add Team Members

1. Go to **Project Settings** (bottom left)
2. Click **"Teams"**
3. Select your team
4. Click **"Add"**
5. Enter team member UA emails:
   - Add all team members (2-4 students)
   - **Important**: Add instructor email with **Project Administrator** role

---

## Step 4: Set Up Repository

1. Go to **Repos** → **Files**
2. Initialize repository with `.gitignore` and `README.md`
3. Or clone empty repo:

```bash
git clone https://your-org@dev.azure.com/your-org/Money%20Transfer%20Application/_git/MoneyTransferApp
cd MoneyTransferApp

# Copy your backend files here
cp -r ../WebProg2Project/backend .
cp -r ../WebProg2Project/*.md .

git add .
git commit -m "Initial commit: Laravel backend with financial features"
git push origin main
```

---

## Step 5: Create Branches

### Branch Strategy:

```
main (production)
│
├── develop (integration)
│   ├── feature/bank-accounts
│   ├── feature/payment-methods
│   ├── feature/transactions
│   ├── feature/authentication
│   ├── feature/beneficiaries
│   └── feature/admin-panel
```

### Create Branches:

1. Go to **Repos** → **Branches**
2. Click **"New branch"**
3. Create `develop` branch from `main`
4. Create feature branches from `develop`

### Branch Protection:

1. Go to **Repos** → **Branches**
2. Click **"..."** next to `main` branch
3. Click **"Branch policies"**
4. Enable:
   - ✅ Require a minimum number of reviewers (at least 1)
   - ✅ Check for linked work items
   - ✅ Build validation (optional)

---

## Step 6: Create Sprints

### Sprint Structure (Example - 2 week sprints):

1. Go to **Boards** → **Sprints**
2. Click **"New Sprint"**

**Sprint 1: Project Setup & Financial Features (Week 1-2)**
- Database design and migrations
- Authentication setup
- Bank accounts CRUD
- Bank account verification

**Sprint 2: Payment Methods (Week 3-4)**
- Payment methods CRUD
- Stripe integration
- Card management
- E-wallet support

**Sprint 3: Transactions (Week 5-6)**
- Transaction creation
- Payment processing
- Transaction history
- Fee calculation

**Sprint 4: Additional Features (Week 7-8)**
- Beneficiary management
- User profile
- Email notifications
- Testing and bug fixes

---

## Step 7: Create Work Items (Tickets)

### For Financial Setup Features (Aya):

#### Epic: Financial Setup
```
Title: Financial Setup Module
Description: Implement bank account and payment method management
Assigned to: Aya
Sprint: Sprint 1
```

#### User Stories:

1. **Bank Account Management**
   ```
   Title: As a user, I want to add multiple bank accounts
   Description: 
   - Add bank account with all required fields
   - Support multiple account types (checking, savings, business)
   - Support international formats (SWIFT, IBAN, Routing)
   
   Acceptance Criteria:
   - User can add new bank account
   - Validation on all required fields
   - Support US and international formats
   
   Assigned to: Aya
   Sprint: Sprint 1
   Story Points: 5
   ```

2. **Bank Account Verification**
   ```
   Title: As a user, I want to verify my bank account
   Description:
   - Implement micro-deposit verification
   - Email notification on micro-deposits
   - Verification attempt tracking
   
   Acceptance Criteria:
   - System sends two micro-deposits
   - User can verify with correct amounts
   - Maximum 3 verification attempts
   - Email notification sent
   
   Assigned to: Aya
   Sprint: Sprint 1
   Story Points: 8
   ```

3. **Payment Method - Cards**
   ```
   Title: As a user, I want to add credit/debit cards
   Description:
   - Integrate with Stripe
   - Securely store card information
   - Display masked card numbers
   - Support multiple cards
   
   Acceptance Criteria:
   - Stripe integration working
   - Cards saved securely (PCI compliant)
   - Card validation (expiry, etc.)
   - Default card selection
   
   Assigned to: Aya
   Sprint: Sprint 2
   Story Points: 8
   ```

4. **Payment Method - E-wallets**
   ```
   Title: As a user, I want to add e-wallet payment methods
   Description:
   - Support PayPal, Venmo, etc.
   - Store wallet credentials
   - Manage multiple wallets
   
   Acceptance Criteria:
   - User can add wallet provider
   - Email or phone required
   - Multiple wallets supported
   
   Assigned to: Aya
   Sprint: Sprint 2
   Story Points: 5
   ```

5. **Transaction Processing**
   ```
   Title: As a user, I want to make payments using saved methods
   Description:
   - Create transactions using saved payment methods
   - Process payments via Stripe
   - Calculate and display fees
   - Track transaction status
   
   Acceptance Criteria:
   - Payment processing works
   - Fees calculated correctly
   - Status tracking implemented
   - Error handling for failed payments
   
   Assigned to: Aya
   Sprint: Sprint 3
   Story Points: 13
   ```

#### Tasks (breakdown of User Stories):

Under each User Story, create tasks:

**Example for "Add Bank Account" story:**
- [ ] Create database migration
- [ ] Create BankAccount model
- [ ] Create BankAccountController
- [ ] Implement validation rules
- [ ] Create API routes
- [ ] Write unit tests
- [ ] Update API documentation

---

## Step 8: Daily Workflow

### Every day, update your tickets:

1. Go to **Boards** → **Sprints**
2. Move cards through columns:
   - **To Do** → **Doing** → **Done**
3. Update task status
4. Add comments on progress
5. Link commits to work items

### Linking Commits to Work Items:

```bash
# In your commit messages, reference the work item ID:
git commit -m "Implement bank account verification #123"

# Multiple work items:
git commit -m "Add Stripe integration #124 #125"

# Close work item:
git commit -m "Fix validation bug, closes #126"
```

---

## Step 9: Code Push Strategy

### Feature Development:

```bash
# 1. Create feature branch
git checkout develop
git pull origin develop
git checkout -b feature/bank-accounts

# 2. Work on feature
# ... make changes ...

# 3. Commit regularly
git add .
git commit -m "Implement bank account model #123"
git push origin feature/bank-accounts

# 4. Create Pull Request when done
# Go to Azure DevOps → Repos → Pull Requests
# Create PR from feature/bank-accounts to develop
```

### Pull Request Process:

1. Go to **Repos** → **Pull Requests**
2. Click **"New pull request"**
3. Source: `feature/bank-accounts`
4. Target: `develop`
5. Title: "Feature: Bank Accounts Management"
6. Description: Describe changes and link work items
7. Add reviewers (team members)
8. Submit for review

### After Review Approval:

```bash
# Merge to develop
# Then periodically merge develop to main

git checkout develop
git pull origin develop
git checkout main
git pull origin main
git merge develop
git push origin main
```

---

## Step 10: Sprint Board View

### Columns Setup:

1. **New** - Newly created items
2. **To Do** - Ready to start
3. **Doing** - In progress
4. **Review** - In code review
5. **Testing** - Being tested
6. **Done** - Completed

### Daily Standup:

Track progress using the sprint board:
- What did I do yesterday?
- What will I do today?
- Any blockers?

---

## Step 11: Project Dashboard

### Create Dashboard:

1. Go to **Overview** → **Dashboards**
2. Click **"+ New Dashboard"**
3. Add widgets:
   - Sprint burndown
   - Work items assigned to me
   - Recent commits
   - Pull request status
   - Build status (if CI/CD set up)

---

## Example Sprint Planning for Financial Setup

### Sprint 1 (Week 1-2): Foundation

**Week 1:**
- [ ] Project setup and repository structure
- [ ] Database schema design
- [ ] Run migrations
- [ ] Authentication setup
- [ ] Bank account model and controller

**Week 2:**
- [ ] Bank account CRUD API
- [ ] Bank account verification logic
- [ ] Testing and bug fixes
- [ ] API documentation

**Daily Updates:**
- Monday: "Started bank account model #123 - Status: Doing"
- Tuesday: "Completed bank account CRUD #123 - Status: Done"
- Wednesday: "Started verification logic #124 - Status: Doing"
- And so on...

---

## Grading Criteria Checklist

✅ Azure DevOps project created with UA emails  
✅ Instructor added with full privileges  
✅ Sprints created and organized  
✅ Work items (tickets) created with proper details  
✅ Daily ticket updates (To Do → Doing → Done)  
✅ Regular code pushes (not just at the end)  
✅ Branch strategy implemented  
✅ Pull requests with code reviews  
✅ Linked commits to work items  
✅ Project dashboard set up  

---

## Tips for Success

1. **Commit Often**: Commit at least once per day
2. **Update Tickets Daily**: Move cards and add comments
3. **Write Good Commit Messages**: Reference work item IDs
4. **Use Pull Requests**: Don't push directly to main/develop
5. **Code Reviews**: Review each other's code
6. **Documentation**: Keep documentation updated
7. **Communication**: Use Azure DevOps comments for discussions
8. **Backup**: Keep local backups of your work

---

## Common Mistakes to Avoid

❌ Pushing all code at the end  
❌ Not updating tickets daily  
❌ Not linking commits to work items  
❌ Working directly on main branch  
❌ No code reviews  
❌ Poor commit messages  
❌ Not adding instructor to project  

---

## Resources

- Azure DevOps Documentation: https://docs.microsoft.com/azure/devops
- Git Documentation: https://git-scm.com/doc
- Azure DevOps Video Tutorials: https://www.youtube.com/c/AzureDevOps

---

Good luck with your project! 🚀


