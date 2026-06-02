<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Advertisement;

class FrontController extends Controller
{
    private string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = rtrim(config('services.main_api.url'), '/');
    }

 

    public function home()
{
    $today = Carbon::today('Asia/Kolkata');
    $yesterday = Carbon::yesterday('Asia/Kolkata');

    $todayResponse = Http::timeout(10)->get($this->apiBaseUrl . '/api/games-results', [
        'date' => $today->format('Y-m-d'),
    ]);

    $yesterdayResponse = Http::timeout(10)->get($this->apiBaseUrl . '/api/games-results', [
        'date' => $yesterday->format('Y-m-d'),
    ]);

    $todayGames = $todayResponse->successful()
        ? collect($todayResponse->json('games', []))
        : collect();

    $yesterdayGames = $yesterdayResponse->successful()
        ? collect($yesterdayResponse->json('games', []))->keyBy('slug')
        : collect();

    $games = $todayGames->map(function ($game) use ($yesterdayGames) {
        $yesterdayGame = $yesterdayGames->get($game['slug']);

        $todayResult = $game['result'] ?? [];
        $yesterdayResult = $yesterdayGame['result'] ?? [];


        
        return (object) [
            'id'          => $game['id'] ?? null,
            'name'        => $game['name'] ?? '',
            'slug'        => $game['slug'] ?? '',
            'result_time' => $game['result_time'] ?? '',
            'sort_order'  => $game['sort_order'] ?? 0,

            'todayResult' => (object) [
                'id'           => $todayResult['id'] ?? null,
                'result_date'  => $todayResult['result_date'] ?? null,
                'result'       => $todayResult['result'] ?? null,
                'status'       => $todayResult['status'] ?? 'waiting',
                'show_minutes' => $todayResult['show_minutes'] ?? 10,
                'updated_at'   => $todayResult['updated_at'] ?? null,
                'is_live'      => $todayResult['is_live'] ?? false,
            ],

            'yesterdayResult' => (object) [
                'id'           => $yesterdayResult['id'] ?? null,
                'result_date'  => $yesterdayResult['result_date'] ?? null,
                'result'       => $yesterdayResult['result'] ?? null,
                'status'       => $yesterdayResult['status'] ?? 'waiting',
            ],

            'latestResult' => (object) [
                'result' => $todayResult['result'] ?? null,
                'status' => $todayResult['status'] ?? 'waiting',
            ],
        ];
    })->values();

    // Chart ke liye original game order chahiye, live/top sorting nahi
    $chartGames = $games->sortBy('sort_order')->values();

    $startDate = $today->copy()->startOfMonth();
    $endDate = $today->copy()->endOfMonth();
    $dates = CarbonPeriod::create($startDate, $endDate);

    $monthlyResults = collect();

    foreach ($dates as $date) {
        $response = Http::timeout(10)->get($this->apiBaseUrl . '/api/games-results', [
            'date' => $date->format('Y-m-d'),
        ]);

        if ($response->successful()) {
            $monthlyResults->put(
                $date->format('Y-m-d'),
                collect($response->json('games', []))->map(function ($game) {
                    $result = $game['result'] ?? [];

                    return (object) [
                        'game_id'     => $game['id'] ?? null,
                        'game_slug'   => $game['slug'] ?? '',
                        'result_date' => $result['result_date'] ?? null,
                        'result'      => $result['result'] ?? null,
                        'status'      => $result['status'] ?? 'waiting',
                    ];
                })->values()
            );
        }
    }

     $seo = SeoPage::where('page_key', 'home')->first();
      $advertisements = Advertisement::where('is_active', true)->get();
    return view('front.home.index', compact(
        'games',
        'chartGames',
        'dates',
        'monthlyResults',
        'seo',
        'advertisements'
    ));
}

public function chart()
{
    try {
        $response = Http::timeout(10)->get($this->apiBaseUrl . '/api/chart-games');

        $games = $response->successful()
            ? collect($response->json('games', []))->map(function ($game) {
                $chartYears = collect($game['chartYears'] ?? [])
                    ->map(function ($year) {
                        return (object) [
                            'year' => $year['year'] ?? null,
                        ];
                    })
                    ->filter(fn ($year) => !empty($year->year))
                    ->sortByDesc('year')
                    ->values();

                if ($chartYears->isEmpty()) {
                    $chartYears = collect([
                        (object) ['year' => now('Asia/Kolkata')->year],
                        (object) ['year' => now('Asia/Kolkata')->copy()->subYear()->year],
                        (object) ['year' => now('Asia/Kolkata')->copy()->subYears(2)->year],
                    ]);
                }

                return (object) [
                    'id'          => $game['id'] ?? null,
                    'name'        => $game['name'] ?? '',
                    'slug'        => $game['slug'] ?? '',
                    'result_time' => $game['result_time'] ?? '',
                    'sort_order'  => $game['sort_order'] ?? 0,
                    'chartYears'  => $chartYears,
                ];
            })
            ->filter(fn ($game) => !empty($game->slug))
            ->sortBy('sort_order')
            ->values()
            : collect();

    } catch (\Throwable $e) {
        \Log::error('Chart API Error', [
            'url' => $this->apiBaseUrl . '/api/chart-games',
            'error' => $e->getMessage(),
        ]);

        $games = collect();
    }

   $seo = SeoPage::where('page_key', 'chart')->first();

    return view('front.chart.index', compact('games', 'seo'));
}

public function gameRecord(string $slug)
{
    return $this->yearRecord($slug, now('Asia/Kolkata')->year);
}

public function yearRecord(string $slug, int $year)
{
    try {
        $response = Http::timeout(10)->get($this->apiBaseUrl . "/api/game-year-record/{$slug}/{$year}");

        if ($response->successful()) {
            $apiData = $response->json();

            $gameData = $apiData['game'] ?? [];

            $game = (object) [
                'id'          => $gameData['id'] ?? null,
                'name'        => $gameData['name'] ?? ucwords(str_replace('-', ' ', $slug)),
                'slug'        => $gameData['slug'] ?? $slug,
                'result_time' => $gameData['result_time'] ?? null,
            ];

            $results = collect($apiData['results'] ?? [])
                ->map(function ($result) {
                    return (object) [
                        'result_date' => $result['result_date'] ?? null,
                        'result'      => $result['result'] ?? null,
                        'status'      => $result['status'] ?? 'waiting',
                    ];
                })
                ->filter(fn ($result) => !empty($result->result_date))
                ->values();
        } else {
            $game = (object) [
                'id'          => null,
                'name'        => ucwords(str_replace('-', ' ', $slug)),
                'slug'        => $slug,
                'result_time' => null,
            ];

            $results = collect();
        }
    } catch (\Throwable $e) {
        \Log::error('Game Year Record API Error', [
            'url'   => $this->apiBaseUrl . "/api/game-year-record/{$slug}/{$year}",
            'error' => $e->getMessage(),
        ]);

        $game = (object) [
            'id'          => null,
            'name'        => ucwords(str_replace('-', ' ', $slug)),
            'slug'        => $slug,
            'result_time' => null,
        ];

        $results = collect();
    }

     $seo = SeoPage::where('page_key', 'game-year-record')->first();

    return view('front.game.year_record', compact('game', 'results', 'year', 'seo'));
}
    

    public function contactUs()
    {
         $seo = SeoPage::where('page_key', 'contact-us')->first();
        return view('front.contact-us.index', compact('seo'));
    }

    public function privacyPolicy()
    {
        $seo = SeoPage::where('page_key', 'privacy-policy')->first();
        return view('front.privacy-policy.index', compact('seo'));
    }

    public function termsConditions()
    {
         $seo = SeoPage::where('page_key', 'terms-conditions')->first();
        return view('front.terms-conditions.index', compact('seo'));
    }
}