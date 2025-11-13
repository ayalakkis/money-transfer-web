<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\TransferServiceController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PromotionController;

/*
|--------------------------------------------------------------------------
| API Routes - Money Transfer Application
|--------------------------------------------------------------------------
| Multi-role API with User, Agent, and Admin endpoints
*/

// Public routes
Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()]));

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/agent', [AuthController::class, 'registerAgent']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Transfer Services (Public - for searching before registration)
Route::prefix('transfer-services')->group(function () {
    Route::get('/search', [TransferServiceController::class, 'search']);
    Route::post('/calculate-quote', [TransferServiceController::class, 'calculateQuote']);
    Route::get('/currencies', [TransferServiceController::class, 'getCurrencies']);
    Route::get('/currencies/popular', [TransferServiceController::class, 'getPopularCurrencies']);
    Route::get('/countries', [TransferServiceController::class, 'getCountries']);
});

// Agents Map (Public - for viewing agent locations)
Route::get('/agents/nearby', [AgentController::class, 'nearbyAgents']);
Route::get('/agents/map', [AgentController::class, 'allAgents']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Common routes (all authenticated users)
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ========================================
    // USER ROUTES
    // ========================================
    Route::middleware('role:user|admin')->group(function () {
        
        // Bank Accounts
        Route::prefix('bank-accounts')->group(function () {
            Route::get('/', [BankAccountController::class, 'index']);
            Route::post('/', [BankAccountController::class, 'store']);
            Route::post('/{id}/verify', [BankAccountController::class, 'verify']);
            Route::put('/{id}', [BankAccountController::class, 'update']);
            Route::delete('/{id}', [BankAccountController::class, 'destroy']);
        });

        // Payment Methods
        Route::prefix('payment-methods')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'index']);
            Route::post('/card', [PaymentMethodController::class, 'storeCard']);
            Route::post('/ewallet', [PaymentMethodController::class, 'storeEWallet']);
            Route::post('/mobile-money', [PaymentMethodController::class, 'storeMobileMoney']);
            Route::put('/{id}', [PaymentMethodController::class, 'update']);
            Route::delete('/{id}', [PaymentMethodController::class, 'destroy']);
        });

        // Beneficiaries
        Route::prefix('beneficiaries')->group(function () {
            Route::get('/', [BeneficiaryController::class, 'index']);
            Route::post('/', [BeneficiaryController::class, 'store']);
            Route::put('/{id}', [BeneficiaryController::class, 'update']);
            Route::delete('/{id}', [BeneficiaryController::class, 'destroy']);
        });

        // Transactions
        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::post('/', [TransactionController::class, 'store']);
            Route::get('/{id}', [TransactionController::class, 'show']);
            Route::get('/{id}/track', [TransactionController::class, 'track']);
            Route::post('/{id}/cancel', [TransactionController::class, 'cancel']);
            Route::get('/statistics/summary', [TransactionController::class, 'statistics']);
        });

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
        
        // Promotions
        Route::post('/promotions/apply', [PromotionController::class, 'apply']);
    });

    // ========================================
    // AGENT ROUTES
    // ========================================
    Route::middleware('role:agent|admin')->prefix('agent')->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard']);
        Route::get('/transactions', [AgentController::class, 'transactions']);
        Route::post('/transactions/{id}/process', [AgentController::class, 'processPayout']);
        Route::put('/profile', [AgentController::class, 'updateProfile']);
        Route::get('/commissions', [AgentController::class, 'commissions']);
    });

    // ========================================
    // ADMIN ROUTES
    // ========================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        
        // Users Management
        Route::get('/users', [AdminController::class, 'users']);
        Route::put('/users/{id}/activate', [AdminController::class, 'activateUser']);
        Route::put('/users/{id}/deactivate', [AdminController::class, 'deactivateUser']);
        
        // Agents Management
        Route::get('/agents/pending', [AdminController::class, 'pendingAgents']);
        Route::post('/agents/{id}/approve', [AdminController::class, 'approveAgent']);
        Route::post('/agents/{id}/reject', [AdminController::class, 'rejectAgent']);
        
        // Transactions Management
        Route::get('/transactions', [AdminController::class, 'transactions']);
        Route::get('/transactions/suspicious', [AdminController::class, 'suspiciousTransactions']);
        
        // Exchange Rates
        Route::put('/exchange-rates', [AdminController::class, 'updateExchangeRate']);
        Route::get('/exchange-rates/sync', [AdminController::class, 'syncExchangeRates']);
        
        // Promotions
        Route::post('/promotions', [AdminController::class, 'createPromotion']);
        Route::put('/promotions/{id}', [AdminController::class, 'updatePromotion']);
        Route::delete('/promotions/{id}', [AdminController::class, 'deletePromotion']);
        
        // Reports
        Route::get('/reports', [AdminController::class, 'reports']);
        Route::get('/reports/export', [AdminController::class, 'exportReports']);
    });
});
