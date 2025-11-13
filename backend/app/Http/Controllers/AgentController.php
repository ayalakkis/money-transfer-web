<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    /**
     * Get agent dashboard statistics
     */
    public function dashboard(Request $request)
    {
        $agent = $request->user()->agent;

        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Not an agent'], 403);
        }

        $stats = [
            'total_transactions' => $agent->total_transactions,
            'total_volume' => $agent->total_volume,
            'rating' => $agent->rating,
            'review_count' => $agent->review_count,
            'today_transactions' => Transaction::where('agent_id', $agent->id)
                ->whereDate('created_at', today())
                ->count(),
            'today_volume' => Transaction::where('agent_id', $agent->id)
                ->whereDate('created_at', today())
                ->sum('send_amount'),
            'pending_transactions' => Transaction::where('agent_id', $agent->id)
                ->whereIn('status', ['ready_for_pickup', 'processing'])
                ->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Get agent's transactions
     */
    public function transactions(Request $request)
    {
        $agent = $request->user()->agent;

        $transactions = Transaction::where('agent_id', $agent->id)
            ->with(['user', 'beneficiary'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $transactions]);
    }

    /**
     * Process payout (agent marks as completed)
     */
    public function processPayout(Request $request, $id)
    {
        $agent = $request->user()->agent;
        $transaction = Transaction::find($id);

        if (!$transaction || $transaction->agent_id !== $agent->id) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        if ($transaction->status !== 'ready_for_pickup') {
            return response()->json(['success' => false, 'message' => 'Transaction not ready for pickup'], 400);
        }

        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update agent stats
        $agent->increment('total_transactions');
        $agent->increment('total_volume', $transaction->send_amount);

        return response()->json([
            'success' => true,
            'data' => $transaction,
            'message' => 'Payout completed successfully'
        ]);
    }

    /**
     * Update agent profile
     */
    public function updateProfile(Request $request)
    {
        $agent = $request->user()->agent;

        $validator = \Validator::make($request->all(), [
            'business_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'working_hours' => 'sometimes|array',
            'is_open_now' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $agent->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Get nearby agents (for map)
     */
    public function nearbyAgents(Request $request)
    {
        $latitude = $request->get('lat');
        $longitude = $request->get('lng');
        $radius = $request->get('radius', 10); // km

        if (!$latitude || !$longitude) {
            return response()->json(['success' => false, 'message' => 'Location required'], 400);
        }

        $agents = Agent::active()
            ->nearby($latitude, $longitude, $radius)
            ->with('user')
            ->get();

        return response()->json(['success' => true, 'data' => $agents]);
    }

    /**
     * Get all agents for map
     */
    public function allAgents(Request $request)
    {
        $agents = Agent::active()
            ->select(['id', 'business_name', 'address', 'city', 'country', 'latitude', 'longitude', 'rating', 'working_hours'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return response()->json(['success' => true, 'data' => $agents]);
    }
}

