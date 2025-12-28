<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        return Inertia::render('Categories/Index', ['categories' => $categories]);
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa membuat kategori');
        }

        return Inertia::render('categories/Create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa membuat kategori');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:categories',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa edit kategori');
        }

        return Inertia::render('categories/Edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa edit kategori');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category berhasil diedit.');
    }

    public function destroy(Category $category)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa hapus kategori');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
