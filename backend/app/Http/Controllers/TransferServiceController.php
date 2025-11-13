<?php

namespace App\Http\Controllers;

use App\Models\TransferService;
use App\Models\Currency;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class TransferServiceController extends Controller
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Search for transfer services with filters
     */
    public function search(Request $request)
    {
        $query = TransferService::active();

        // Filter by destination country (REQUIRED)
        if ($request->has('to_country')) {
            $query->where('to_country', $request->to_country);
        }

        // Filter by source country
        if ($request->has('from_country')) {
            $query->where('from_country', $request->from_country);
        }

        // Filter by payout method
        if ($request->has('payout_method')) {
            $payoutMethod = $request->payout_method;
            $query->where($payoutMethod, true); // bank_deposit, cash_pickup, mobile_wallet
        }

        // Filter by transfer speed
        if ($request->has('speed')) {
            $query->where('speed', $request->speed);
        }

        // Filter by max fee
        if ($request->has('max_fee')) {
            $query->where('fixed_fee', '<=', $request->max_fee);
        }

        // Filter by currency
        if ($request->has('from_currency')) {
            $query->where('from_currency', $request->from_currency);
        }
        if ($request->has('to_currency')) {
            $query->where('to_currency', $request->to_currency);
        }

        // Only show promoted
        if ($request->has('promoted') && $request->promoted) {
            $query->where('is_promoted', true)
                  ->where(function($q) {
                      $q->whereNull('promotion_valid_until')
                        ->orWhere('promotion_valid_until', '>=', now());
                  });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'popularity');
        switch ($sortBy) {
            case 'fee_low':
                $query->orderBy('fixed_fee', 'asc');
                break;
            case 'fee_high':
                $query->orderBy('fixed_fee', 'desc');
                break;
            case 'speed':
                $query->orderByRaw("FIELD(speed, 'instant', 'same_day', 'next_day', '2-3_days', '3-5_days')");
                break;
            case 'popularity':
            default:
                $query->orderBy('popularity_score', 'desc');
                break;
        }

        $services = $query->paginate(20);

        // Add calculated fees for each service if amount provided
        if ($request->has('amount')) {
            $amount = $request->amount;
            $services->getCollection()->transform(function($service) use ($amount) {
                $service->calculated_fee = $service->calculateFee($amount);
                $service->total_cost = $amount + $service->calculated_fee;
                return $service;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $services,
            'filters_applied' => $request->all()
        ]);
    }

    /**
     * Get available currencies
     */
    public function getCurrencies()
    {
        $currencies = Currency::active()
            ->orderBy('is_popular', 'desc')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $currencies
        ]);
    }

    /**
     * Get popular currencies
     */
    public function getPopularCurrencies()
    {
        $currencies = Currency::active()
            ->popular()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $currencies
        ]);
    }

    /**
     * Calculate transfer quote
     */
    public function calculateQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'send_amount' => 'required|numeric|min:1',
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
            'payout_method' => 'nullable|in:bank_deposit,cash_pickup,mobile_wallet',
            'speed' => 'nullable|in:instant,same_day,next_day,2-3_days,3-5_days',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get exchange rate
        $exchangeRate = $this->exchangeRateService->getRate(
            $request->from_currency,
            $request->to_currency
        );

        // Find matching transfer service
        $service = TransferService::active()
            ->where('from_currency', $request->from_currency)
            ->where('to_currency', $request->to_currency)
            ->when($request->payout_method, function($query) use ($request) {
                $query->where($request->payout_method, true);
            })
            ->when($request->speed, function($query) use ($request) {
                $query->where('speed', $request->speed);
            })
            ->orderBy('popularity_score', 'desc')
            ->first();

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'No service available for this route'
            ], 404);
        }

        // Calculate fees
        $fee = $service->calculateFee($request->send_amount);
        $totalAmount = $request->send_amount + $fee;
        $receiveAmount = $request->send_amount * $exchangeRate;

        return response()->json([
            'success' => true,
            'data' => [
                'send_amount' => $request->send_amount,
                'send_currency' => $request->from_currency,
                'receive_amount' => round($receiveAmount, 2),
                'receive_currency' => $request->to_currency,
                'exchange_rate' => $exchangeRate,
                'transfer_fee' => $fee,
                'total_amount' => $totalAmount,
                'transfer_speed' => $service->speed,
                'estimated_arrival' => $service->estimated_time,
                'service' => $service,
            ]
        ]);
    }

    /**
     * Get supported countries
     */
    public function getCountries()
    {
        $countries = TransferService::active()
            ->select('to_country')
            ->distinct()
            ->get()
            ->pluck('to_country');

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}

