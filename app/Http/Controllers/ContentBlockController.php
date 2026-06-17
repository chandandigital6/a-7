<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ContentBlockController extends Controller
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
                ->map(fn ($game) => (object) [
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
                ])
                ->filter(fn ($game) => !empty($game->slug))
                ->sortBy('sort_order')
                ->values();

        } catch (\Throwable $e) {
            \Log::error('Content Block Game API Error', [
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    public function index()
    {
        $contentBlocks = ContentBlock::latest()->paginate(20);

        return view('content_block.index', compact('contentBlocks'));
    }

    public function create()
    {
        $games = $this->getApiGames();

        return view('content_block.form', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'         => ['nullable', 'string', 'max:255', 'unique:content_blocks,key'],
            'title'       => ['nullable', 'string', 'max:255'],
            'content'     => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
            'game_api_id' => ['nullable', 'string', 'max:255'],
            'game_name'   => ['nullable', 'string', 'max:255'],
            'game_slug'   => ['nullable', 'string', 'max:255'],
            'year'        => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $data['key'] = $data['key'] ?: Str::slug($data['title'] ?? 'content-block') . '-' . time();
        $data['is_active'] = $request->boolean('is_active');

        ContentBlock::create($data);

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block created successfully.');
    }

    public function edit(ContentBlock $contentBlock)
    {
        $games = $this->getApiGames();

        return view('content_block.form', compact('contentBlock', 'games'));
    }

    public function update(Request $request, ContentBlock $contentBlock)
    {
        $data = $request->validate([
            'key' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('content_blocks', 'key')->ignore($contentBlock->id),
            ],
            'title'       => ['nullable', 'string', 'max:255'],
            'content'     => ['nullable', 'string'],
            'is_active'   => ['nullable', 'boolean'],
            'game_api_id' => ['nullable', 'string', 'max:255'],
            'game_name'   => ['nullable', 'string', 'max:255'],
            'game_slug'   => ['nullable', 'string', 'max:255'],
            'year'        => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $data['key'] = $data['key'] ?: Str::slug($data['title'] ?? 'content-block') . '-' . $contentBlock->id;
        $data['is_active'] = $request->boolean('is_active');

        $contentBlock->update($data);

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block updated successfully.');
    }

    public function destroy(ContentBlock $contentBlock)
    {
        $contentBlock->delete();

        return redirect()->route('content-blocks.index')
            ->with('success', 'Content block deleted successfully.');
    }
}