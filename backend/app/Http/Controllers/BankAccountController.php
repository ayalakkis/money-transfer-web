<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $bankAccounts = $request->user()
            ->bankAccounts()
            ->where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bankAccounts
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_holder_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'routing_number' => 'nullable|string|max:50',
            'swift_code' => 'nullable|string|max:20',
            'iban' => 'nullable|string|max:50',
            'account_type' => 'nullable|in:checking,savings,business',
            'currency' => 'nullable|string|size:3',
            'country' => 'required|string|size:2',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->is_default) {
            $request->user()->bankAccounts()->update(['is_default' => false]);
        }

        $bankAccount = BankAccount::create($data);
        $this->initiateBankVerification($bankAccount);

        return response()->json([
            'success' => true,
            'data' => $bankAccount,
            'message' => 'Bank account added. Verification initiated.'
        ], 201);
    }

    public function verify(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount_1' => 'required|numeric|min:0.01|max:0.99',
            'amount_2' => 'required|numeric|min:0.01|max:0.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $bankAccount = $request->user()->bankAccounts()->find($id);

        if (!$bankAccount) {
            return response()->json(['success' => false, 'message' => 'Bank account not found'], 404);
        }

        if ($bankAccount->is_verified) {
            return response()->json(['success' => false, 'message' => 'Already verified'], 400);
        }

        if ($bankAccount->verification_attempts >= 3) {
            return response()->json(['success' => false, 'message' => 'Max attempts exceeded'], 400);
        }

        $amount1 = number_format($request->amount_1, 2, '.', '');
        $amount2 = number_format($request->amount_2, 2, '.', '');
        $deposit1 = number_format($bankAccount->micro_deposit_1, 2, '.', '');
        $deposit2 = number_format($bankAccount->micro_deposit_2, 2, '.', '');

        if ($amount1 === $deposit1 && $amount2 === $deposit2) {
            $bankAccount->update([
                'is_verified' => true,
                'verification_method' => 'micro_deposit',
                'verified_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $bankAccount,
                'message' => 'Bank account verified successfully'
            ]);
        }

        $bankAccount->increment('verification_attempts');

        return response()->json([
            'success' => false,
            'message' => 'Verification failed. Amounts do not match.',
            'attempts_remaining' => 3 - $bankAccount->verification_attempts
        ], 400);
    }

    private function initiateBankVerification(BankAccount $bankAccount)
    {
        $amount1 = rand(1, 99) / 100;
        $amount2 = rand(1, 99) / 100;

        $bankAccount->update([
            'micro_deposit_1' => $amount1,
            'micro_deposit_2' => $amount2,
            'verification_expires_at' => now()->addDays(3),
        ]);

        Log::info("Micro-deposits: {$amount1}, {$amount2} for account {$bankAccount->id}");
    }
}

