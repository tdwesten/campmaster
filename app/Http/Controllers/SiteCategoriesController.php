<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteCategoryRequest;
use App\Models\SiteCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteCategoriesController extends Controller
{
    public function index(): Response
    {
        $categories = SiteCategory::query()
            ->withCount('sites')
            ->latest()
            ->paginate(15)
            ->through(function (SiteCategory $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'slug' => $category->slug,
                    'sites_count' => $category->sites_count,
                    'created_at' => $category->created_at?->toISOString(),
                ];
            });

        return Inertia::render('site-categories/index', [
            'categories' => $categories,
            'actions' => [
                'create_url' => route('site-categories.create'),
            ],
        ]);
    }

    public function store(SiteCategoryRequest $request): RedirectResponse
    {
        $category = SiteCategory::create($request->validated());

        return redirect()->route('site-categories.edit', $category)->with('success', __('messages.site_categories.create.subtitle'));
    }

    public function create(): Response
    {
        return Inertia::render('site-categories/create');
    }

    public function edit(SiteCategory $site_category): Response
    {
        return Inertia::render('site-categories/edit', [
            'category' => [
                'id' => $site_category->id,
                'name' => $site_category->name,
                'description' => $site_category->description,
                'slug' => $site_category->slug,
            ],
        ]);
    }

    public function update(SiteCategoryRequest $request, SiteCategory $site_category): RedirectResponse
    {
        $site_category->update($request->validated());

        return redirect()->route('site-categories.edit', $site_category)->with('success', __('messages.site_categories.edit.subtitle'));
    }
}
