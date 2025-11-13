<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use App\Models\Transaction;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin dashboard statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('user_type', 'user')->count(),
            'total_agents' => Agent::count(),
            'pending_agents' => Agent::where('status', 'pending')->count(),
            'total_transactions' => Transaction::count(),
            'today_transactions' => Transaction::whereDate('created_at', today())->count(),
            'total_volume' => Transaction::completed()->sum('send_amount'),
            'today_volume' => Transaction::whereDate('created_at', today())->sum('send_amount'),
            'active_promotions' => Promotion::where('is_active', true)->count(),
            'pending_kyc' => User::where('kyc_status', 'pending')->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Get pending agents
     */
    public function pendingAgents()
    {
        $agents = Agent::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $agents]);
    }

    /**
     * Approve agent
     */
    public function approveAgent(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent not found'], 404);
        }

        $agent->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);

        $agent->user->update(['is_approved' => true]);

        // TODO: Send email notification to agent

        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Agent approved successfully'
        ]);
    }

    /**
     * Reject agent
     */
    public function rejectAgent(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent not found'], 404);
        }

        $agent->update(['status' => 'rejected']);
        $agent->user->update(['is_approved' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Agent rejected'
        ]);
    }

    /**
     * Get all transactions (admin view)
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['user', 'beneficiary', 'agent']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->has('is_suspicious')) {
            $query->where('is_suspicious', true);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        return response()->json(['success' => true, 'data' => $transactions]);
    }

    /**
     * Update exchange rate (manual override)
     */
    public function updateExchangeRate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::table('exchange_rates')->updateOrInsert(
            [
                'from_currency' => $request->from_currency,
                'to_currency' => $request->to_currency,
            ],
            [
                'rate' => $request->rate,
                'inverse_rate' => 1 / $request->rate,
                'source' => 'manual',
                'valid_until' => now()->addDays(7),
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Exchange rate updated'
        ]);
    }

    /**
     * Create promotion
     */
    public function createPromotion(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => 'required|string|unique:promotions,code',
            'title' => 'required|string',
            'type' => 'required|in:percentage,fixed,free_transfer',
            'discount_value' => 'required_if:type,fixed|nullable|numeric',
            'discount_percentage' => 'required_if:type,percentage|nullable|numeric',
            'min_transfer_amount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'valid_until' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $promotion = Promotion::create($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $promotion,
            'message' => 'Promotion created successfully'
        ], 201);
    }

    /**
     * Get reports
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year

        $dateFrom = match($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $data = [
            'transactions' => Transaction::where('created_at', '>=', $dateFrom)->count(),
            'volume' => Transaction::where('created_at', '>=', $dateFrom)->sum('send_amount'),
            'fees_collected' => Transaction::where('created_at', '>=', $dateFrom)->sum('total_fee'),
            'new_users' => User::where('created_at', '>=', $dateFrom)->count(),
            'top_corridors' => Transaction::select('from_currency', 'to_currency', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $dateFrom)
                ->groupBy('from_currency', 'to_currency')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
}

