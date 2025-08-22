<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use App\Models\SiteCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SitesController extends Controller
{
    public function index(): Response
    {
        $sites = Site::query()
            ->orderBy('created_at', 'desc')
            ->with('siteCategory')
            ->latest()
            ->paginate(15)
            ->through(function (Site $site) {
                return [
                    'id' => $site->id,
                    'name' => $site->name,
                    'description' => $site->description,
                    'category' => $site->siteCategory?->name,
                ];
            });

        return Inertia::render('sites/index', [
            'sites' => $sites,
            'actions' => [
                'create_url' => route('sites.create'),
            ],
        ]);
    }

    public function store(SiteRequest $request): RedirectResponse
    {
        $site = Site::create($request->validated());

        return redirect()->route('sites.edit', $site)->with('success', __('messages.sites.create.subtitle'));
    }

    public function create(): Response
    {
        return Inertia::render('sites/create', [
            'categories' => SiteCategory::query()->orderBy('name')->get(['id', 'name'])->map->only(['id', 'name']),
        ]);
    }

    public function edit(Site $site): Response
    {
        return Inertia::render('sites/edit', [
            'site' => [
                'id' => $site->id,
                'name' => $site->name,
                'description' => $site->description,
                'site_category_id' => $site->site_category_id,
            ],
            'categories' => SiteCategory::query()->orderBy('name')->get(['id', 'name'])->map->only(['id', 'name']),
        ]);
    }

    public function update(SiteRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());

        return redirect()->route('sites.edit', $site)->with('success', __('messages.sites.edit.subtitle'));
    }
}
