<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = Category::query()
            ->withCount('events')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new Category(),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Category::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => $validated['icon'] ?? null,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->slug === 'umum') {
            return back()->with('error', 'Kategori "Umum" adalah kategori default sistem dan tidak dapat dihapus.');
        }

        if ($category->events()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih dipakai oleh event yang ada.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil dihapus.');
    }
}
