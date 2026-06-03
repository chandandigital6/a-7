<?php

namespace App\Http\Controllers;

use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SeoPageController extends Controller
{
    private string $apiBaseUrl;

   public function __construct()
{
    $this->apiBaseUrl = rtrim(config('services.main_api.url'), '/');
}

   private function getApiGames()
{
    try {
        $response = Http::timeout(10)->get($this->apiBaseUrl . '/api/chart-games');

        if (!$response->successful()) {
            return collect();
        }

        return collect($response->json('games', []))
            ->map(function ($game) {
                return (object) [
                    'id' => $game['id'] ?? null,
                    'name' => $game['name'] ?? '',
                    'slug' => $game['slug'] ?? '',
                    'sort_order' => $game['sort_order'] ?? 0,
                    'chartYears' => collect($game['chartYears'] ?? [])
                        ->map(fn ($year) => (object) [
                            'year' => $year['year'] ?? null,
                        ])
                        ->filter(fn ($year) => !empty($year->year))
                        ->values(),
                ];
            })
            ->filter(fn ($game) => !empty($game->slug))
            ->sortBy('sort_order')
            ->values();

    } catch (\Throwable $e) {
        \Log::error('SEO Game API Error', [
            'url' => $this->apiBaseUrl . '/api/chart-games',
            'error' => $e->getMessage(),
        ]);

        return collect();
    }
}

    public function index()
    {
        $seoPages = SeoPage::latest()->paginate(20);
        return view('seo_page.index', compact('seoPages'));
    }

    public function create()
    {
        $games = $this->getApiGames();
        //    dd($games);
        return view('seo_page.form', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'page_key' => ['nullable', 'string', 'max:255', 'unique:seo_pages,page_key'],
            'game_api_id' => ['nullable', 'string', 'max:255'],
            'game_name' => ['nullable', 'string', 'max:255'],
            'game_slug' => ['nullable', 'string', 'max:255'],
            'year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('seo_pages')->where(function ($query) use ($request) {
                    return $query->where('game_slug', $request->game_slug)
                        ->where('year', $request->year);
                }),
            ],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'url'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'schema_markup' => ['nullable', 'string'],
        ]);

        // if (!empty($data['game_slug']) && !empty($data['year'])) {
        //     $data['page_key'] = 'game-year-record-' . $data['game_slug'] . '-' . $data['year'];
        // }

        if (!empty($data['game_slug']) && !empty($data['year'])) {
    $data['page_key'] = 'game-year-record-' . $data['game_slug'] . '-' . $data['year'];
} elseif (!empty($data['game_slug'])) {
    $data['page_key'] = 'game-record-' . $data['game_slug'];
}

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        SeoPage::create($data);

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page created successfully.');
    }

    public function edit(SeoPage $seoPage)
    {
        $games = $this->getApiGames();
        return view('seo_page.form', compact('seoPage', 'games'));
    }

    public function update(Request $request, SeoPage $seoPage)
    {
        $data = $request->validate([
            'page_key' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('seo_pages', 'page_key')->ignore($seoPage->id),
            ],
            'game_api_id' => ['nullable', 'string', 'max:255'],
            'game_name' => ['nullable', 'string', 'max:255'],
            'game_slug' => ['nullable', 'string', 'max:255'],
            'year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('seo_pages')->where(function ($query) use ($request) {
                    return $query->where('game_slug', $request->game_slug)
                        ->where('year', $request->year);
                })->ignore($seoPage->id),
            ],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'canonical_url' => ['nullable', 'url'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'schema_markup' => ['nullable', 'string'],
        ]);

        // if (!empty($data['game_slug']) && !empty($data['year'])) {
        //     $data['page_key'] = 'game-year-record-' . $data['game_slug'] . '-' . $data['year'];
        // }

         if (!empty($data['game_slug']) && !empty($data['year'])) {
    $data['page_key'] = 'game-year-record-' . $data['game_slug'] . '-' . $data['year'];
} elseif (!empty($data['game_slug'])) {
    $data['page_key'] = 'game-record-' . $data['game_slug'];
}

        if ($request->hasFile('og_image')) {
            if ($seoPage->og_image) {
                Storage::disk('public')->delete($seoPage->og_image);
            }

            $data['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        $seoPage->update($data);

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page updated successfully.');
    }

    public function destroy(SeoPage $seoPage)
    {
        if ($seoPage->og_image) {
            Storage::disk('public')->delete($seoPage->og_image);
        }

        $seoPage->delete();

        return redirect()->route('seo-pages.index')
            ->with('success', 'SEO page deleted successfully.');
    }
}