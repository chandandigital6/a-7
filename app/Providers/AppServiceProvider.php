<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Game;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    $this->configureDefaults();

    View::composer('front.layouts.header', function ($view) {

        $apiBaseUrl = rtrim(config('services.main_api.url'), '/');

        $headerGames = collect();

        try {
            if (blank($apiBaseUrl)) {
                throw new \Exception('MAIN_API_URL empty hai');
            }

            $apiUrl = $apiBaseUrl . '/api/home-live-results';

            $response = Http::timeout(10)->get($apiUrl, [
                'limit' => 4,
            ]);

            \Log::info('Header home-live-results API response', [
                'url' => $apiUrl,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful() && $response->json('success') == true) {
                $headerGames = collect($response->json('games', []))
                    ->map(function ($game) {

                        $result = $game['result'] ?? [];

                        return (object) [
                            'id'          => $game['id'] ?? null,
                            'name'        => $game['name'] ?? '',
                            'slug'        => $game['slug'] ?? '',
                            'result_time' => $game['result_time'] ?? null,
                            'sort_order'  => $game['sort_order'] ?? 0,

                            'todayResult' => (object) [
                                'id'           => $result['id'] ?? null,
                                'result_date'  => $result['result_date'] ?? null,
                                'result'       => $result['result'] ?? null,
                                'status'       => $result['status'] ?? 'waiting',
                                'show_minutes' => !empty($result['show_minutes'])
                                    ? (int) $result['show_minutes']
                                    : 10,
                                'updated_at'   => $result['updated_at'] ?? null,
                                'is_live'      => $result['is_live'] ?? false,
                            ],
                        ];
                    })
                    ->values();
            }

        } catch (\Throwable $e) {
            \Log::error('Header home-live-results API Error', [
                'url' => ($apiBaseUrl ?? '') . '/api/home-live-results',
                'error' => $e->getMessage(),
            ]);

            $headerGames = collect();
        }

        $view->with('headerGames', $headerGames);
    });
}

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
