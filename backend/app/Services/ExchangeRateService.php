<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExchangeRateService
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.exchange_rate.api_key');
        $this->apiUrl = config('services.exchange_rate.api_url');
    }

    /**
     * Get exchange rate between two currencies
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        // Return 1 if same currency
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        // Check cache first (cache for 5 minutes)
        $cacheKey = "exchange_rate_{$fromCurrency}_{$toCurrency}";
        
        return Cache::remember($cacheKey, 300, function() use ($fromCurrency, $toCurrency) {
            // Try to get from database first
            $rate = DB::table('exchange_rates')
                ->where('from_currency', $fromCurrency)
                ->where('to_currency', $toCurrency)
                ->where('valid_until', '>', now())
                ->value('rate');

            if ($rate) {
                return (float) $rate;
            }

            // Fetch from API
            return $this->fetchRateFromAPI($fromCurrency, $toCurrency);
        });
    }

    /**
     * Fetch rate from external API
     */
    private function fetchRateFromAPI($fromCurrency, $toCurrency)
    {
        try {
            // Using ExchangeRate-API (free tier)
            $response = Http::get("{$this->apiUrl}/{$this->apiKey}/pair/{$fromCurrency}/{$toCurrency}");

            if ($response->successful()) {
                $data = $response->json();
                $rate = $data['conversion_rate'] ?? null;

                if ($rate) {
                    // Save to database for caching
                    $this->saveRate($fromCurrency, $toCurrency, $rate);
                    return (float) $rate;
                }
            }

            // Fallback to mock rate if API fails
            return $this->getMockRate($fromCurrency, $toCurrency);
            
        } catch (\Exception $e) {
            \Log::error('Exchange rate API error: ' . $e->getMessage());
            return $this->getMockRate($fromCurrency, $toCurrency);
        }
    }

    /**
     * Save exchange rate to database
     */
    private function saveRate($fromCurrency, $toCurrency, $rate)
    {
        DB::table('exchange_rates')->updateOrInsert(
            [
                'from_currency' => $fromCurrency,
                'to_currency' => $toCurrency,
            ],
            [
                'rate' => $rate,
                'inverse_rate' => 1 / $rate,
                'valid_until' => now()->addHours(1),
                'source' => 'api',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    /**
     * Mock rates for development (when API is not available)
     */
    private function getMockRate($fromCurrency, $toCurrency)
    {
        $mockRates = [
            'USD_EUR' => 0.92,
            'USD_GBP' => 0.79,
            'USD_IQD' => 1310.00,
            'EUR_USD' => 1.09,
            'GBP_USD' => 1.27,
            'IQD_USD' => 0.00076,
        ];

        $key = "{$fromCurrency}_{$toCurrency}";
        return $mockRates[$key] ?? 1.0;
    }

    /**
     * Convert amount
     */
    public function convertAmount($amount, $fromCurrency, $toCurrency)
    {
        $rate = $this->getRate($fromCurrency, $toCurrency);
        return round($amount * $rate, 2);
    }

    /**
     * Get rate history (for charts)
     */
    public function getRateHistory($fromCurrency, $toCurrency, $days = 30)
    {
        $history = DB::table('exchange_rates')
            ->where('from_currency', $fromCurrency)
            ->where('to_currency', $toCurrency)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get();

        return $history;
    }
}

